<?php

namespace App\Http\Controllers;

use App\Mail\AccessGrantedMail;
use App\Mail\NewSaleAdminMail;
use App\Mail\PaymentPendingMail;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ebook_id'       => ['required', 'integer', 'exists:ebooks,id'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        $method   = $request->input('payment_method', 'helloasso');
        $ebook    = \App\Models\Ebook::findOrFail($request->ebook_id);
        $existing = $request->user()->purchases()->where('ebook_id', $request->ebook_id)->first();

        if ($existing && $existing->payment_status === Purchase::STATUS_PAID) {
            return back()->with('status', 'Vous avez déjà accès à cet eBook.');
        }

        // Coupon éventuellement appliqué (stocké en session sur la fiche)
        $couponCode = null;
        $finalPrice = (float) $ebook->price;
        $coupon = null;
        if ($sessionCode = session('coupon_' . $ebook->id)) {
            $coupon = \App\Models\Coupon::where('code', $sessionCode)->first();
            if ($coupon && $coupon->isValidForEbook($ebook)) {
                $couponCode = $coupon->code;
                $finalPrice = $coupon->finalPrice((float) $ebook->price);
            }
        }

        if (!$existing) {
            $purchase = $request->user()->purchases()->create([
                'ebook_id'       => $request->ebook_id,
                'payment_status' => Purchase::STATUS_PENDING,
                'payment_method' => $method,
                'coupon_code'    => $couponCode,
                'final_price'    => $finalPrice,
            ]);

            // Comptabilise l'usage du coupon et le retire de la session
            if ($coupon) {
                $coupon->increment('used_count');
                session()->forget('coupon_' . $ebook->id);
            }
            Mail::to($purchase->user->email)->queue(new PaymentPendingMail($purchase->load('ebook')));

            // Alerte les administrateurs qu'une vente est à valider
            $adminEmails = User::where('is_admin', true)->pluck('email');
            if ($adminEmails->isNotEmpty()) {
                Mail::to($adminEmails->all())->queue(new NewSaleAdminMail($purchase->load('user', 'ebook')));
            }

            $message = 'Votre demande a bien été enregistrée. Un email de confirmation vous a été envoyé. Un administrateur validera votre paiement sous 12 à 24 h.';
        } else {
            $existing->update(['payment_method' => $method]);
            $message = "Votre demande est déjà enregistrée. L'équipe administrative confirme les paiements sous 12 à 24 h.";
        }

        return back()->with('status', $message);
    }

    public function updateStatus(Purchase $purchase)
    {
        Gate::authorize('validate-purchase');

        $purchase->update([
            'payment_status' => Purchase::STATUS_PAID,
        ]);

        Mail::to($purchase->user->email)->queue(new AccessGrantedMail($purchase->load('user', 'ebook')));

        return back()->with('status', "Achat validé — {$purchase->user->name} a reçu un email d'activation.");
    }
}
