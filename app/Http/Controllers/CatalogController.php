<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Ebook::where('status', 'published');

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%");
        }

        // Sort
        $sort = $request->sort ?? 'latest';
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $ebooks = $query->paginate(12);
        $categories = Category::all();

        return view('ebooks.index', compact('ebooks', 'categories'));
    }

    public function show(Ebook $ebook)
    {
        if ($ebook->status === 'draft') {
            abort(404);
        }

        $purchase = auth()->user()
            ? auth()->user()->purchases()->where('ebook_id', $ebook->id)->first()
            : null;

        $recommendations = Ebook::where('status', 'published')
            ->where('category_id', $ebook->category_id)
            ->where('id', '!=', $ebook->id)
            ->limit(4)
            ->get();

        $settingsPath    = storage_path('app/payment-settings.json');
        $paymentSettings = file_exists($settingsPath)
            ? (json_decode(file_get_contents($settingsPath), true) ?? [])
            : ['enabled_methods' => ['helloasso']];

        return view('ebooks.show', compact('ebook', 'purchase', 'recommendations', 'paymentSettings'));
    }

    public function read(Ebook $ebook)
    {
        $user = auth()->user();
        $purchase = $user
            ? $user->purchases()->where('ebook_id', $ebook->id)->where('payment_status', \App\Models\Purchase::STATUS_PAID)->first()
            : null;

        // Livre gratuit : accès sans paiement, on enregistre l'accès pour « Mes e-Livres »
        if (!$purchase && $user && $ebook->is_free) {
            $purchase = $user->purchases()->firstOrCreate(
                ['ebook_id' => $ebook->id],
                ['payment_method' => 'gratuit', 'payment_status' => \App\Models\Purchase::STATUS_PAID]
            );
            if ($purchase->payment_status !== \App\Models\Purchase::STATUS_PAID) {
                $purchase->update(['payment_status' => \App\Models\Purchase::STATUS_PAID]);
            }
        }

        if (!$purchase) {
            return redirect()->route('ebooks.show', $ebook)->with('error', 'Vous devez avoir un achat validé pour lire cet eBook.');
        }

        return view('ebooks.read', compact('ebook'));
    }

    public function servePdf(Ebook $ebook)
    {
        $user = auth()->user();
        $hasAccess = $user && (
            $ebook->is_free ||
            $user->purchases()->where('ebook_id', $ebook->id)->where('payment_status', \App\Models\Purchase::STATUS_PAID)->exists()
        );

        if (!$hasAccess) {
            abort(403, 'Accès non autorisé.');
        }

        $path = Storage::disk('private')->path($ebook->file_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        return response()->file($path, [
            'Content-Type'              => 'application/pdf',
            'Content-Disposition'       => 'inline; filename="ebook.pdf"',
            'X-Content-Type-Options'    => 'nosniff',
            'Cache-Control'             => 'private, no-cache, no-store',
            'X-Frame-Options'           => 'SAMEORIGIN',
        ]);
    }

    public function mine()
    {
        $purchases = auth()->user()
            ->purchases()
            ->with('ebook')
            ->orderByDesc('created_at')
            ->get();

        return view('ebooks.mine', compact('purchases'));
    }
}
