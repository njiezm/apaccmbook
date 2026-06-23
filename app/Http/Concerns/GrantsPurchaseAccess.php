<?php

namespace App\Http\Concerns;

use App\Mail\AccessGrantedMail;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait GrantsPurchaseAccess
{
    protected function grantAccess(Purchase $purchase): void
    {
        if ($purchase->payment_status === Purchase::STATUS_PAID) {
            return;
        }

        $purchase->update(['payment_status' => Purchase::STATUS_PAID]);

        Mail::to($purchase->user->email)
            ->queue(new AccessGrantedMail($purchase->loadMissing('user', 'ebook')));

        Log::info('Achat validé automatiquement', [
            'purchase_id' => $purchase->id,
            'user_id'     => $purchase->user_id,
            'ebook_id'    => $purchase->ebook_id,
            'method'      => $purchase->payment_method,
        ]);
    }

    protected function paymentSettings(): array
    {
        $path = storage_path('app/payment-settings.json');
        return file_exists($path)
            ? (json_decode(file_get_contents($path), true) ?? [])
            : ['enabled_methods' => ['helloasso']];
    }
}
