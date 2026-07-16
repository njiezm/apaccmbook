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
        // Le catalogue admin est géré depuis le tableau de bord unique (admin.dashboard).
        return redirect()->route('admin.dashboard');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'is_free'       => ['sometimes', 'boolean'],
            'price'         => ['required_without:is_free', 'nullable', 'numeric', 'min:0'],
            'helloasso_url' => ['nullable', 'url'],
            'pdf'           => ['required', 'file', 'mimes:pdf', 'max:512000'],
            'cover'         => ['nullable', 'image', 'max:4096'],
        ]);

        $isFree = $request->boolean('is_free');

        $pdf = $request->file('pdf');
        $pdfPath = $this->storePdf($pdf);

        $coverImage = null;
        if ($request->hasFile('cover')) {
            $coverImage = $request->file('cover')->store('covers', 'public');
        }

        $ebook = Ebook::create([
            'title'         => $data['title'],
            'description'   => $data['description'],
            'category_id'   => $data['category_id'] ?? null,
            'is_free'       => $isFree,
            'price'         => $isFree ? 0 : ($data['price'] ?? 0),
            'helloasso_url' => $isFree ? null : ($data['helloasso_url'] ?? null),
            'file_path'     => $pdfPath,
            'cover_image'   => $coverImage,
            'status'        => 'published',
        ]);

        // Notifier les abonnés actifs — APRÈS la réponse HTTP et par lots,
        // pour ne pas bloquer/timeout la requête de publication (envoi synchrone).
        $subscriberCount = Subscriber::where('is_active', true)->count();
        $ebookId = $ebook->id;
        dispatch(function () use ($ebookId) {
            $ebook = Ebook::find($ebookId);
            if (!$ebook) {
                return;
            }
            Subscriber::where('is_active', true)
                ->select('email')
                ->chunk(100, function ($chunk) use ($ebook) {
                    foreach ($chunk as $subscriber) {
                        Mail::to($subscriber->email)->send(new NewEbookMail($ebook));
                    }
                });
        })->afterResponse();

        return back()->with('status', "e-Livre « {$ebook->title} » publié. {$subscriberCount} abonné(s) seront notifiés par email.");
    }

    public function update(Request $request, Ebook $ebook)
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'is_free'       => ['sometimes', 'boolean'],
            'price'         => ['required_without:is_free', 'nullable', 'numeric', 'min:0'],
            'helloasso_url' => ['nullable', 'url'],
            'pdf'           => ['nullable', 'file', 'mimes:pdf', 'max:512000'],
            'cover'         => ['nullable', 'image', 'max:4096'],
        ]);

        $isFree = $request->boolean('is_free');

        $filePath = $ebook->file_path;
        if ($request->hasFile('pdf')) {
            Storage::disk('private')->delete($ebook->file_path);
            $filePath = $this->storePdf($request->file('pdf'));
        }

        $coverImage = $ebook->cover_image;
        if ($request->hasFile('cover')) {
            if ($ebook->cover_image) {
                Storage::disk('public')->delete($ebook->cover_image);
            }
            $coverImage = $request->file('cover')->store('covers', 'public');
        }

        $ebook->update([
            'title'         => $data['title'],
            'description'   => $data['description'],
            'category_id'   => $data['category_id'] ?? null,
            'is_free'       => $isFree,
            'price'         => $isFree ? 0 : ($data['price'] ?? 0),
            'helloasso_url' => $isFree ? null : ($data['helloasso_url'] ?? $ebook->helloasso_url),
            'file_path'     => $filePath,
            'cover_image'   => $coverImage,
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

    private function storePdf(\Illuminate\Http\UploadedFile $file): string
    {
        $destination = 'ebooks/' . \Illuminate\Support\Str::uuid() . '.pdf';
        $fullPath     = storage_path('app/private/' . $destination);

        // Try Ghostscript compression first (reduces size significantly)
        $gs = $this->ghostscriptPath();
        if ($gs && $file->getSize() > 5 * 1024 * 1024) {
            $tmpIn  = $file->getRealPath();
            $tmpOut = sys_get_temp_dir() . '/' . uniqid('gs_') . '.pdf';
            $cmd    = escapeshellcmd($gs)
                . ' -sDEVICE=pdfwrite -dCompatibilityLevel=1.4'
                . ' -dPDFSETTINGS=/ebook'
                . ' -dNOPAUSE -dQUIET -dBATCH'
                . ' -sOutputFile=' . escapeshellarg($tmpOut)
                . ' ' . escapeshellarg($tmpIn);

            @exec($cmd, output: $_, result_code: $code);

            if ($code === 0 && file_exists($tmpOut) && filesize($tmpOut) > 0) {
                @mkdir(dirname($fullPath), 0755, true);
                rename($tmpOut, $fullPath);
                @unlink($tmpIn);
                return $destination;
            }
            @unlink($tmpOut);
        }

        // Fallback: store as-is
        $file->storeAs('ebooks', basename($destination), 'private');
        return $destination;
    }

    private function ghostscriptPath(): ?string
    {
        foreach (['gs', 'gswin64c', 'gswin32c'] as $bin) {
            $path = trim((string) @shell_exec('which ' . escapeshellarg($bin) . ' 2>/dev/null'));
            if ($path && file_exists($path)) {
                return $path;
            }
        }
        return null;
    }

    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('status', "Le rôle administrateur de {$user->name} a été mis à jour.");
    }

    public function destroyReview(\App\Models\Review $review)
    {
        $review->delete();

        return back()->with('status', 'Avis supprimé.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Supprime les données liées pour éviter les contraintes de clé étrangère
        $user->purchases()->delete();
        $user->reviews()->delete();
        $user->wishlists()->delete();

        $name = $user->name;
        $user->delete();

        return back()->with('status', "Le compte de {$name} a été supprimé.");
    }
}
