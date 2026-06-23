<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::orderByDesc('created_at')->get();
        return view('ebooks.index', compact('ebooks'));
    }

    public function show(Ebook $ebook)
    {
        $purchase = auth()->user() ? auth()->user()
            ->purchases()
            ->where('ebook_id', $ebook->id)
            ->latest()
            ->first() : null;

        return view('ebooks.show', compact('ebook', 'purchase'));
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

    public function read(Ebook $ebook)
    {
        $this->abortUnlessPaid($ebook);

        $disk = Storage::disk('private');
        $content = $disk->get($ebook->file_path);
        $pdfData = 'data:application/pdf;base64,' . base64_encode($content);

        return view('ebooks.read', compact('ebook', 'pdfData'));
    }

    protected function abortUnlessPaid(Ebook $ebook): Purchase
    {
        $purchase = auth()->user()->purchases()
            ->where('ebook_id', $ebook->id)
            ->where('payment_status', Purchase::STATUS_PAID)
            ->first();

        abort_if(!$purchase, 403, 'Accès interdit');

        return $purchase;
    }
}
