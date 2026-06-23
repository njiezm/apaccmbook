<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true]
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Merci ! Vous êtes inscrit(e) à notre newsletter.']);
        }

        return back()->with('newsletter_success', 'Merci ! Vous êtes inscrit(e) à notre newsletter.');
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->query('email');

        if ($email) {
            Subscriber::where('email', $email)->update(['is_active' => false]);
        }

        return redirect()->route('home')->with('status', 'Vous avez été désabonné(e) de la newsletter.');
    }
}
