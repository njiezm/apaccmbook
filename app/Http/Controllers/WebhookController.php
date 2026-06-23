<?php

namespace App\Http\Controllers;

use App\Http\Concerns\GrantsPurchaseAccess;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook as StripeWebhook;

class WebhookController extends Controller
{
    use GrantsPurchaseAccess;

    // ── Stripe ────────────────────────────────────────────────────────────────

    public function stripe(Request $request)
    {
        $settings = $this->paymentSettings();
        $secret   = $settings['stripe_webhook_secret'] ?? '';

        if (!$secret) {
            Log::error('Stripe webhook : secret non configuré');
            return response('Webhook secret missing', 400);
        }

        try {
            Stripe::setApiKey($settings['stripe_secret_key'] ?? '');
            $event = StripeWebhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $secret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook : signature invalide', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            if ($session->payment_status === 'paid') {
                // Résolution primaire par purchase_id stocké dans les métadonnées
                $purchase = Purchase::with('user', 'ebook')
                    ->find($session->metadata->purchase_id ?? 0);

                // Fallback : email + ebook_id
                if (!$purchase && $session->customer_email) {
                    $user = User::where('email', $session->customer_email)->first();
                    if ($user) {
                        $purchase = Purchase::with('user', 'ebook')
                            ->where('user_id', $user->id)
                            ->where('ebook_id', $session->metadata->ebook_id ?? 0)
                            ->latest()
                            ->first();
                    }
                }

                if ($purchase) {
                    $this->grantAccess($purchase);
                }
            }
        }

        return response('OK');
    }

    // ── HelloAsso ─────────────────────────────────────────────────────────────

    public function helloasso(Request $request)
    {
        $settings = $this->paymentSettings();

        // Vérification optionnelle de la clé secrète HelloAsso
        $secret = $settings['helloasso_api_secret'] ?? '';
        if ($secret) {
            $received = $request->header('X-HelloAsso-Signature')
                     ?? ltrim($request->header('Authorization') ?? '', 'Bearer ');
            if (!hash_equals($secret, $received)) {
                Log::error('HelloAsso webhook : signature invalide');
                return response('Invalid signature', 401);
            }
        }

        $payload   = $request->json()->all();
        $eventType = $payload['eventType'] ?? null;

        if (!in_array($eventType, ['Payment', 'Order'], true)) {
            return response('OK');
        }

        $data        = $payload['data'] ?? [];
        $payer       = $data['payer'] ?? [];
        $payerEmail  = strtolower(trim($payer['email'] ?? ''));
        $amountCents = (int) ($data['amount'] ?? 0);
        $amount      = $amountCents / 100;

        if (!$payerEmail) {
            Log::warning('HelloAsso webhook : email payeur absent', ['payload' => $payload]);
            return response('OK');
        }

        $user = User::where('email', $payerEmail)->first();

        if (!$user) {
            Log::info("HelloAsso webhook : aucun compte trouvé pour {$payerEmail}");
            return response('OK');
        }

        // Priorité : achat pending dont le prix de l'ebook correspond (±0,50 €)
        $purchase = Purchase::where('user_id', $user->id)
            ->where('payment_status', Purchase::STATUS_PENDING)
            ->whereHas('ebook', fn ($q) => $q
                ->where('price', '>=', $amount - 0.5)
                ->where('price', '<=', $amount + 0.5)
            )
            ->with('user', 'ebook')
            ->latest()
            ->first();

        // Fallback : dernier achat pending de cet utilisateur
        if (!$purchase) {
            $purchase = Purchase::where('user_id', $user->id)
                ->where('payment_status', Purchase::STATUS_PENDING)
                ->with('user', 'ebook')
                ->latest()
                ->first();
        }

        if ($purchase) {
            $this->grantAccess($purchase);
        }

        return response('OK');
    }

    // ── PayPal ────────────────────────────────────────────────────────────────

    public function paypal(Request $request)
    {
        $settings = $this->paymentSettings();
        $mode     = $settings['paypal_mode'] ?? 'sandbox';
        $base     = $mode === 'live' ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';

        // Obtenir un token pour la vérification de signature
        $tokenR = Http::withBasicAuth(
            $settings['paypal_client_id']     ?? '',
            $settings['paypal_client_secret'] ?? ''
        )->asForm()->post("{$base}/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        if (!$tokenR->successful()) {
            Log::error('PayPal webhook : impossible d\'obtenir un token');
            return response('OK'); // Retourner 200 pour éviter les retries infinis
        }

        $token     = $tokenR->json('access_token');
        $webhookId = $settings['paypal_webhook_id'] ?? '';

        // Vérification de signature si webhook_id configuré
        if ($webhookId) {
            $verifyR = Http::withToken($token)
                ->post("{$base}/v1/notifications/verify-webhook-signature", [
                    'auth_algo'         => $request->header('PAYPAL-AUTH-ALGO'),
                    'cert_url'          => $request->header('PAYPAL-CERT-URL'),
                    'transmission_id'   => $request->header('PAYPAL-TRANSMISSION-ID'),
                    'transmission_sig'  => $request->header('PAYPAL-TRANSMISSION-SIG'),
                    'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
                    'webhook_id'        => $webhookId,
                    'webhook_event'     => $request->json()->all(),
                ]);

            if ($verifyR->json('verification_status') !== 'SUCCESS') {
                Log::error('PayPal webhook : signature invalide');
                return response('OK');
            }
        }

        $event = $request->json()->all();

        if (($event['event_type'] ?? null) === 'PAYMENT.CAPTURE.COMPLETED') {
            $resource = $event['resource'] ?? [];
            $customId = $resource['custom_id'] ?? null;

            // Résolution primaire : custom_id = purchase->id
            $purchase = $customId
                ? Purchase::with('user', 'ebook')->find((int) $customId)
                : null;

            // Fallback : transaction_id = PayPal order ID
            if (!$purchase) {
                $orderId  = $resource['supplementary_data']['related_ids']['order_id'] ?? null;
                $purchase = $orderId
                    ? Purchase::where('transaction_id', $orderId)->with('user', 'ebook')->first()
                    : null;
            }

            if ($purchase) {
                $this->grantAccess($purchase);
            }
        }

        return response('OK');
    }

    // ── SumUp ─────────────────────────────────────────────────────────────────

    public function sumup(Request $request)
    {
        $settings      = $this->paymentSettings();
        $webhookSecret = $settings['sumup_webhook_secret'] ?? '';

        // Vérification HMAC-SHA256 si configuré
        if ($webhookSecret) {
            $signature = $request->header('X-Payload-Signature') ?? '';
            $expected  = hash_hmac('sha256', $request->getContent(), $webhookSecret);

            if (!hash_equals($expected, $signature)) {
                Log::error('SumUp webhook : signature invalide');
                return response('Invalid signature', 400);
            }
        }

        $event    = $request->json()->all();
        $type     = $event['type'] ?? null;
        $checkout = $event['payload']['checkout'] ?? $event['payload'] ?? [];

        if ($type === 'checkout.status.changed' && ($checkout['status'] ?? null) === 'PAID') {
            $ref      = $checkout['checkout_reference'] ?? null;
            $purchase = $ref
                ? Purchase::where('transaction_id', $ref)->with('user', 'ebook')->first()
                : null;

            if ($purchase) {
                $this->grantAccess($purchase);
            }
        }

        return response('OK');
    }
}
