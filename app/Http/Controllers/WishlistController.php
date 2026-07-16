<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()
            ->wishlists()
            ->with('ebook.category')
            ->latest()
            ->get()
            ->filter(fn ($w) => $w->ebook !== null);

        return view('ebooks.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request, Ebook $ebook)
    {
        $existing = auth()->user()->wishlists()->where('ebook_id', $ebook->id)->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'Retiré de vos envies.';
        } else {
            auth()->user()->wishlists()->create(['ebook_id' => $ebook->id]);
            $added = true;
            $message = 'Ajouté à vos envies ❤️';
        }

        if ($request->wantsJson()) {
            return response()->json(['added' => $added, 'message' => $message]);
        }

        return back()->with('status', $message);
    }
}
