@extends('layouts.app')

@section('title', 'Reçu — ' . $purchase->ebook->title)

@section('styles')
<style>
    .receipt-sheet { max-width:640px; margin:2rem auto; background:var(--white); border:1px solid var(--border-light); border-radius:10px; box-shadow:var(--shadow-soft); overflow:hidden; }
    .receipt-head { background:#b91c1c; color:#fff; padding:1.75rem 2rem; }
    .receipt-head h1 { margin:0; font-size:1.25rem; letter-spacing:0.05em; }
    .receipt-head p { margin:0.3rem 0 0; font-size:0.85rem; opacity:0.85; }
    .receipt-body { padding:2rem; }
    .receipt-row { display:flex; justify-content:space-between; gap:1rem; padding:0.6rem 0; border-bottom:1px solid var(--border-light); font-size:0.95rem; }
    .receipt-row span:first-child { color:var(--text-secondary); }
    .receipt-row span:last-child { font-weight:600; text-align:right; }
    .receipt-total { margin-top:1rem; padding-top:1rem; border-top:2px solid #b91c1c; display:flex; justify-content:space-between; font-size:1.15rem; font-weight:700; }
    .receipt-total .amount { color:#b91c1c; }
    .receipt-meta { margin-top:1.5rem; font-size:0.8rem; color:var(--text-muted); line-height:1.6; }
    .receipt-actions { max-width:640px; margin:0 auto 3rem; display:flex; gap:0.75rem; justify-content:center; flex-wrap:wrap; }

    /* Impression : ne garder que le reçu, cacher navigation et boutons */
    @media print {
        .site-navbar, .mobile-drawer, .drawer-overlay, .mobile-bottom-nav,
        .site-footer, .cookie-banner, .receipt-actions { display: none !important; }
        body { padding:0 !important; background:#fff !important; }
        .receipt-sheet { box-shadow:none; border:none; margin:0; max-width:100%; }
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="receipt-sheet">
        <div class="receipt-head">
            <h1>Reçu / Attestation d'accès</h1>
            <p>APACC-M e-Livre — Association pour la Promotion de l'Art et la Culture Chrétienne</p>
        </div>
        <div class="receipt-body">
            <div class="receipt-row"><span>N° de reçu</span><span>#{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</span></div>
            <div class="receipt-row"><span>Date</span><span>{{ $purchase->created_at->format('d/m/Y à H\hi') }}</span></div>
            <div class="receipt-row"><span>Bénéficiaire</span><span>{{ $purchase->user->name }}</span></div>
            <div class="receipt-row"><span>Email</span><span>{{ $purchase->user->email }}</span></div>
            <div class="receipt-row"><span>e-Livre</span><span>{{ $purchase->ebook->title }}</span></div>
            <div class="receipt-row"><span>Moyen de paiement</span><span>{{ ucfirst($purchase->payment_method ?? '—') }}</span></div>
            @if($purchase->coupon_code)
                <div class="receipt-row"><span>Code promo</span><span>{{ $purchase->coupon_code }}</span></div>
            @endif
            <div class="receipt-row"><span>Statut</span><span>Payé / Accès validé</span></div>

            @php $amount = $purchase->final_price ?? $purchase->ebook->price; @endphp
            <div class="receipt-total">
                <span>Montant</span>
                <span class="amount">{{ number_format((float) $amount, 2, ',', ' ') }} €</span>
            </div>

            <p class="receipt-meta">
                Ce document atteste de l'accès accordé à l'ouvrage numérique ci-dessus sur la plateforme APACC-M e-Livre.
                Association loi 1901 — RNA W9M1011611 — SIRET 924 433 808 00012 — 11 Avenue Frantz Fanon, 97200 Fort-de-France.
            </p>
        </div>
    </div>

    <div class="receipt-actions">
        <button type="button" class="btn-primary" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
        <a href="{{ route('ebooks.mine') }}" class="btn-ghost">Retour à ma bibliothèque</a>
    </div>
</div>
@endsection
