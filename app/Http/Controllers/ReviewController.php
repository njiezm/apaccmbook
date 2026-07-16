<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Ebook $ebook)
    {
        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'title'   => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'max:2000'],
        ]);

        // Un seul avis par membre et par livre (création ou mise à jour)
        $ebook->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating'  => $data['rating'],
                'title'   => $data['title'] ?? null,
                'content' => $data['content'] ?? null,
                'status'  => 'approved', // publié immédiatement
            ]
        );

        return back()->with('status', 'Merci ! Votre avis a été publié.')->withFragment('avis');
    }

    public function destroy(Ebook $ebook)
    {
        $ebook->reviews()->where('user_id', auth()->id())->delete();

        return back()->with('status', 'Votre avis a été supprimé.')->withFragment('avis');
    }
}
