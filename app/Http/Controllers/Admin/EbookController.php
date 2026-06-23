<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewEbookMail;
use App\Models\Ebook;
use App\Models\Purchase;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-ebooks']);
    }

    public function index()
    {
        $ebooks = Ebook::latest()->get();
        $users = User::orderBy('name')->get();
        $purchases = Purchase::with(['ebook', 'user'])->orderByDesc('created_at')->get();

        return view('admin.ebooks.index', compact('ebooks', 'users', 'purchases'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'helloasso_url' => ['required', 'url'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['file_path'] = $request->file('pdf')->store('ebooks', 'private');

        if ($request->hasFile('cover')) {
            $data['cover_image'] = $request->file('cover')->store('covers', 'public');
        }

        $ebook = Ebook::create([
            'title'         => $data['title'],
            'description'   => $data['description'],
            'price'         => $data['price'],
            'helloasso_url' => $data['helloasso_url'],
            'file_path'     => $data['file_path'],
            'cover_image'   => $data['cover_image'] ?? null,
            'status'        => 'published',
        ]);

        // Notifier les abonnés actifs
        $subscribers = Subscriber::where('is_active', true)->pluck('email');
        foreach ($subscribers as $email) {
            Mail::to($email)->queue(new NewEbookMail($ebook));
        }

        return back()->with('status', "e-Livre « {$ebook->title} » publié. {$subscribers->count()} abonné(s) notifié(s).");
    }

    public function update(Request $request, Ebook $ebook)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'helloasso_url' => ['required', 'url'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('private')->delete($ebook->file_path);
            $data['file_path'] = $request->file('pdf')->store('ebooks', 'private');
        }

        if ($request->hasFile('cover')) {
            if ($ebook->cover_image) {
                Storage::disk('public')->delete($ebook->cover_image);
            }
            $data['cover_image'] = $request->file('cover')->store('covers', 'public');
        }

        $ebook->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'helloasso_url' => $data['helloasso_url'],
            'file_path' => $data['file_path'] ?? $ebook->file_path,
            'cover_image' => $data['cover_image'] ?? $ebook->cover_image,
        ]);

        return back()->with('status', "Ebook « {$ebook->title} » mis à jour.");
    }

    public function destroy(Ebook $ebook)
    {
        Storage::disk('private')->delete($ebook->file_path);
        if ($ebook->cover_image) {
            Storage::disk('public')->delete($ebook->cover_image);
        }
        $ebook->delete();

        return back()->with('status', 'Ebook supprimé de la médiathèque.');
    }

    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('status', "Le rôle administrateur de {$user->name} a été mis à jour.");
    }
}
