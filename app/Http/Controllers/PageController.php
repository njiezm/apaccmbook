<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessageMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Destinataires : tous les administrateurs, sinon l'adresse d'expédition par défaut
        $recipients = User::where('is_admin', true)->pluck('email');
        if ($recipients->isEmpty()) {
            $recipients = collect([config('mail.from.address')]);
        }

        Mail::to($recipients->all())->queue(new ContactMessageMail(
            $validated['name'],
            $validated['email'],
            $validated['subject'],
            $validated['message'],
        ));

        return back()->with('success', 'Message envoyé avec succès !');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function legal()
    {
        return view('pages.legal');
    }
}
