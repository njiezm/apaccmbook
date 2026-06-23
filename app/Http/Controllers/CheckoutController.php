<?php

namespace App\Http\Controllers;

use App\Http\Concerns\GrantsPurchaseAccess;
use App\Mail\PaymentPendingMail;
use App\Models\Ebook;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    use GrantsPurchaseAccess;

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function resolvePurchase(Request $request, Ebook $ebook, string $method): Purchase
    {
        $user = $request->user();
        $purchase = $user->purchases()->where('ebook_id', $ebook->id)->first();

        if (!$purchase) {
            $purchase = $user->purchases()->create([
                'ebook_id'       => $ebook->id,
                'payment_status' => Purchase::STATUS_PENDING,
                'payment_method' => $method,
            ]);
        } else {
            $purchase->update(['payment_method' => $method]);
        }

        return $purchase;
    }

    // ── Stripe ────────────────────────────────────────────────────────────────

    public function stripe(Request $request, Ebook $ebook)
    {
        $settings = $this->paymentSettings();

        if (!($settings['stripe_secret_key'] ?? '')) {
            return back()->with('error', 'Stripe n\'est pas configuré.');
        }

        $purchase = $this->resolvePurchase($request, $ebook, 'stripe');

        if ($purchase->payment_status === Purchase::STATUS_PAID) {
            return redirect()->route('ebooks.read', $ebook)
                ->with('status', 'Vous avez déjà accès à cet eBook.');
        }

        Stripe::setApiKey($settings['stripe_secret_key']);

        $session = StripeSession::create([
            'mode'                 => 'payment',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => (int) round($ebook->price * 100),
                    'product_data' => ['name' => $ebook->title],
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $request->user()->email,
            'metadata'       => [
                'purchase_id' => $purchase->id,
                'ebook_id'    => $ebook->id,
                'user_id'     => $request->user()->id,
            ],
            'success_url' => route('checkout.success', $ebook) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel', $ebook),
        ]);

        $purchase->update(['transaction_id' => $session->id]);

        return redirect($session->url);
    }

    // ── PayPal ────────────────────────────────────────────────────────────────

    private function paypalBase(array $settings): string
    {
        return ($settings['paypal_mode'] ?? 'sandbox') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function paypalToken(array $settings): ?string
    {
        $r = Http::withBasicAuth(
            $settings['paypal_client_id']     ?? '',
            $settings['paypal_client_secret'] ?? ''
        )->asForm()->post($this->paypalBase($settings) . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $r->successful() ? $r->json('access_token') : null;
    }

    public function paypal(Request $request, Ebook $ebook)
    {
        $settings = $this->paymentSettings();

        if (!($settings['paypal_client_id'] ?? '') || !($settings['paypal_client_secret'] ?? '')) {
            return back()->with('error', 'PayPal n\'est pas configuré.');
        }

        $purchase = $this->resolvePurchase($request, $ebook, 'paypal');

        if ($purchase->payment_status === Purchase::STATUS_PAID) {
            return redirect()->route('ebooks.read', $ebook)
                ->with('status', 'Vous avez déjà accès à cet eBook.');
        }

        $token = $this->paypalToken($settings);

        if (!$token) {
            return back()->with('error', 'Impossible de se connecter à PayPal.');
        }

        $r = Http::withToken($token)->post($this->paypalBase($settings) . '/v2/checkout/orders', [
            'intent'            => 'CAPTURE',
            'purchase_units'    => [[
                'amount'      => ['currency_code' => 'EUR', 'value' => number_format($ebook->price, 2, '.', '')],
                'description' => $ebook->title,
                'custom_id'   => (string) $purchase->id,
            ]],
            'application_context' => [
                'return_url' => route('checkout.paypal.capture'),
                'cancel_url' => route('checkout.cancel', $ebook),
                'brand_name' => config('app.name', 'APACC-M'),
                'user_action' => 'PAY_NOW',
            ],
        ]);

        if (!$r->successful()) {
            return back()->with('error', 'Erreur lors de la création de la commande PayPal.');
        }

        $order = $r->json();
        $purchase->update(['transaction_id' => $order['id']]);

        $approveUrl = collect($order['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;

        return $approveUrl
            ? redirect($approveUrl)
            : back()->with('error', 'Lien d\'approbation PayPal introuvable.');
    }

    public function paypalCapture(Request $request)
    {
        $settings  = $this->paymentSettings();
        $orderId   = $request->query('token');
        $purchase  = Purchase::where('transaction_id', $orderId)->with('user', 'ebook')->first();

        if (!$purchase) {
            return redirect()->route('ebooks.mine')->with('error', 'Commande introuvable.');
        }

        $token = $this->paypalToken($settings);

        if (!$token) {
            return redirect()->route('ebooks.show', $purchase->ebook->slug)
                ->with('error', 'Erreur de connexion PayPal.');
        }

        $r = Http::withToken($token)
            ->post($this->paypalBase($settings) . "/v2/checkout/orders/{$orderId}/capture");

        if ($r->successful() && $r->json('status') === 'COMPLETED') {
            $this->grantAccess($purchase);
            return redirect()->route('ebooks.read', $purchase->ebook)
                ->with('status', 'Paiement PayPal confirmé ! Bonne lecture.');
        }

        return redirect()->route('ebooks.show', $purchase->ebook->slug)
            ->with('error', 'Le paiement PayPal n\'a pas pu être capturé.');
    }

    // ── SumUp ─────────────────────────────────────────────────────────────────

    public function sumup(Request $request, Ebook $ebook)
    {
        $settings = $this->paymentSettings();

        if (!($settings['sumup_api_key'] ?? '')) {
            return back()->with('error', 'SumUp n\'est pas configuré.');
        }

        $purchase = $this->resolvePurchase($request, $ebook, 'sumup');

        if ($purchase->payment_status === Purchase::STATUS_PAID) {
            return redirect()->route('ebooks.read', $ebook)
                ->with('status', 'Vous avez déjà accès à cet eBook.');
        }

        $ref = 'APACC-' . $purchase->id . '-' . time();

        $r = Http::withToken($settings['sumup_api_key'])
            ->post('https://api.sumup.com/v0.1/checkouts', [
                'checkout_reference' => $ref,
                'amount'             => round($ebook->price, 2),
                'currency'           => 'EUR',
                'description'        => $ebook->title,
                'return_url'         => route('checkout.success', $ebook) . '?ref=' . urlencode($ref),
                'merchant_code'      => $settings['sumup_merchant_code'] ?? '',
            ]);

        if (!$r->successful()) {
            return back()->with('error', 'SumUp : ' . ($r->json('message') ?? 'erreur inconnue'));
        }

        $purchase->update(['transaction_id' => $ref]);

        return redirect('https://checkout.sumup.com/pay/' . $r->json('id'));
    }

    // ── Success / Cancel ──────────────────────────────────────────────────────

    public function success(Request $request, Ebook $ebook)
    {
        // Stripe : session_id dans la query string
        if ($sessionId = $request->query('session_id')) {
            $settings = $this->paymentSettings();
            try {
                Stripe::setApiKey($settings['stripe_secret_key'] ?? '');
                $session = StripeSession::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $purchase = Purchase::with('user', 'ebook')
                        ->find($session->metadata->purchase_id ?? 0);
                    if ($purchase) {
                        $this->grantAccess($purchase);
                    }
                }
            } catch (\Throwable) {
                // Le webhook Stripe prendra le relais si nécessaire
            }

            return redirect()->route('ebooks.read', $ebook)
                ->with('status', 'Paiement confirmé ! Bonne lecture.');
        }

        // SumUp : ref dans la query string
        if ($ref = $request->query('ref')) {
            $purchase = Purchase::where('transaction_id', $ref)->with('user', 'ebook')->first();

            if ($purchase) {
                $settings = $this->paymentSettings();
                $r = Http::withToken($settings['sumup_api_key'] ?? '')
                    ->get('https://api.sumup.com/v0.1/checkouts/' . urlencode($ref));

                if ($r->successful() && $r->json('status') === 'PAID') {
                    $this->grantAccess($purchase);
                    return redirect()->route('ebooks.read', $ebook)
                        ->with('status', 'Paiement SumUp confirmé ! Bonne lecture.');
                }
            }
        }

        return redirect()->route('ebooks.show', $ebook->slug)
            ->with('status', 'Paiement reçu. Votre accès sera activé sous quelques instants.');
    }

    public function cancel(Ebook $ebook)
    {
        return redirect()->route('ebooks.show', $ebook->slug)
            ->with('error', 'Paiement annulé. Vous pouvez réessayer à tout moment.');
    }
}
