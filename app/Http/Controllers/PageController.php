<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        // Here you would send the email
        // Mail::send('emails.contact', $validated, function ($message) use ($validated) {
        //     $message->to('contact@apacc-m.fr')->subject($validated['subject']);
        // });

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
