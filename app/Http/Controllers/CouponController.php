<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Ebook;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request, Ebook $ebook)
    {
        $request->validate(['code' => ['required', 'string', 'max:50']]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon || !$coupon->isValidForEbook($ebook)) {
            return back()->with('coupon_error', 'Code promo invalide ou expiré pour cet ouvrage.')->withFragment('achat');
        }

        // Mémorise le coupon appliqué pour cet ebook
        session(['coupon_' . $ebook->id => $coupon->code]);

        $final = number_format($coupon->finalPrice((float) $ebook->price), 2, ',', ' ');

        return back()->with('status', "Code promo appliqué ! Nouveau prix : {$final} €")->withFragment('achat');
    }

    public function remove(Ebook $ebook)
    {
        session()->forget('coupon_' . $ebook->id);

        return back()->with('status', 'Code promo retiré.')->withFragment('achat');
    }
}
