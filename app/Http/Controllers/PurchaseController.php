<?php

namespace App\Http\Controllers;

use App\Mail\AccessGrantedMail;
use App\Mail\PaymentPendingMail;
use App\Models\Purchase;
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
        $existing = $request->user()->purchases()->where('ebook_id', $request->ebook_id)->first();

        if ($existing && $existing->payment_status === Purchase::STATUS_PAID) {
            return back()->with('status', 'Vous avez déjà accès à cet eBook.');
        }

        if (!$existing) {
            $purchase = $request->user()->purchases()->create([
                'ebook_id'       => $request->ebook_id,
                'payment_status' => Purchase::STATUS_PENDING,
                'payment_method' => $method,
            ]);
            Mail::to($purchase->user->email)->queue(new PaymentPendingMail($purchase->load('ebook')));
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
