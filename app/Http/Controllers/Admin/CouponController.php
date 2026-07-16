<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-ebooks']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'ebook_id'         => ['nullable', 'exists:ebooks,id'],
            'discount_type'    => ['required', 'in:percent,amount'],
            'discount_value'   => ['required', 'numeric', 'min:0'],
            'valid_from'       => ['nullable', 'date'],
            'valid_until'      => ['nullable', 'date', 'after_or_equal:valid_from'],
            'usage_limit'      => ['nullable', 'integer', 'min:1'],
        ]);

        if ($data['discount_type'] === 'percent' && $data['discount_value'] > 100) {
            throw ValidationException::withMessages(['discount_value' => 'Le pourcentage ne peut pas dépasser 100 %.']);
        }

        Coupon::create([
            'code'             => strtoupper(trim($data['code'])),
            'ebook_id'         => $data['ebook_id'] ?? null,
            'discount_percent' => $data['discount_type'] === 'percent' ? (int) $data['discount_value'] : null,
            'discount_amount'  => $data['discount_type'] === 'amount' ? $data['discount_value'] : null,
            'valid_from'       => $data['valid_from'] ?? now(),
            'valid_until'      => $data['valid_until'] ?? null,
            'usage_limit'      => $data['usage_limit'] ?? null,
            'used_count'       => 0,
            'is_active'        => true,
        ]);

        return back()->with('status', 'Coupon créé avec succès.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('status', 'Statut du coupon mis à jour.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return back()->with('status', 'Coupon supprimé.');
    }
}
