<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmMail;
use App\Mail\NewsletterWelcomeMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Anti-spam : champ piège (honeypot)
        if ($request->filled('website')) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Merci !'])
                : back()->with('newsletter_success', 'Merci !');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => false, 'confirmation_token' => Str::random(40)]
        );

        if (!$subscriber->is_active) {
            // Double opt-in : (re)envoi du lien de confirmation
            if (!$subscriber->confirmation_token) {
                $subscriber->update(['confirmation_token' => Str::random(40)]);
            }
            Mail::to($subscriber->email)->send(new NewsletterConfirmMail($subscriber->email, $subscriber->confirmation_token));
            $message = 'Un email de confirmation vous a été envoyé. Cliquez sur le lien qu\'il contient pour valider votre inscription.';
        } else {
            $message = 'Vous êtes déjà inscrit(e) à notre newsletter.';
        }

        return $request->wantsJson()
            ? response()->json(['message' => $message])
            : back()->with('newsletter_success', $message);
    }

    public function confirm(Request $request)
    {
        $token = $request->query('token');
        $subscriber = $token ? Subscriber::where('confirmation_token', $token)->first() : null;

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Lien de confirmation invalide ou déjà utilisé.');
        }

        $subscriber->update(['is_active' => true, 'confirmation_token' => null]);
        Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber->email));

        return redirect()->route('home')->with('status', 'Votre inscription à la newsletter est confirmée. Merci ! 🎉');
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
