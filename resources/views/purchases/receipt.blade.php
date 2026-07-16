<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reçu — {{ $purchase->ebook->title }}</title>
<style>
    * { box-sizing: border-box; }
    body { margin:0; background:#f6f3ef; color:#1a1a1a; font-family:'Helvetica Neue',Arial,sans-serif; padding:2rem 1rem; }
    .sheet { max-width:640px; margin:0 auto; background:#fff; border-radius:10px; box-shadow:0 6px 24px rgba(0,0,0,0.08); overflow:hidden; }
    .head { background:#b91c1c; color:#fff; padding:1.75rem 2rem; }
    .head h1 { margin:0; font-size:1.25rem; letter-spacing:0.05em; }
    .head p { margin:0.3rem 0 0; font-size:0.85rem; opacity:0.85; }
    .body { padding:2rem; }
    .row { display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid #eee; font-size:0.95rem; }
    .row span:first-child { color:#666; }
    .row span:last-child { font-weight:600; text-align:right; }
    .total { margin-top:1rem; padding-top:1rem; border-top:2px solid #b91c1c; display:flex; justify-content:space-between; font-size:1.15rem; font-weight:700; }
    .total .amount { color:#b91c1c; }
    .meta { margin-top:1.5rem; font-size:0.8rem; color:#888; line-height:1.6; }
    .actions { max-width:640px; margin:1.25rem auto 0; display:flex; gap:0.75rem; justify-content:center; }
    .btn { padding:0.65rem 1.4rem; border-radius:6px; border:none; font-size:0.9rem; font-weight:700; cursor:pointer; text-decoration:none; display:inline-block; }
    .btn-print { background:#b91c1c; color:#fff; }
    .btn-back { background:#eee; color:#1a1a1a; }
    @media print {
        body { background:#fff; padding:0; }
        .sheet { box-shadow:none; border-radius:0; max-width:100%; }
        .actions { display:none !important; }
    }
</style>
</head>
<body>
    <div class="sheet">
        <div class="head">
            <h1>Reçu / Attestation d'accès</h1>
            <p>APACC-M e-Livre — Association pour la Promotion de l'Art et la Culture Chrétienne</p>
        </div>
        <div class="body">
            <div class="row"><span>N° de reçu</span><span>#{{ str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</span></div>
            <div class="row"><span>Date</span><span>{{ $purchase->created_at->format('d/m/Y à H\hi') }}</span></div>
            <div class="row"><span>Bénéficiaire</span><span>{{ $purchase->user->name }}</span></div>
            <div class="row"><span>Email</span><span>{{ $purchase->user->email }}</span></div>
            <div class="row"><span>e-Livre</span><span>{{ $purchase->ebook->title }}</span></div>
            <div class="row"><span>Moyen de paiement</span><span>{{ ucfirst($purchase->payment_method ?? '—') }}</span></div>
            @if($purchase->coupon_code)
                <div class="row"><span>Code promo</span><span>{{ $purchase->coupon_code }}</span></div>
            @endif
            <div class="row"><span>Statut</span><span>Payé / Accès validé</span></div>

            @php
                $amount = $purchase->final_price ?? $purchase->ebook->price;
            @endphp
            <div class="total">
                <span>Montant</span>
                <span class="amount">{{ number_format((float) $amount, 2, ',', ' ') }} €</span>
            </div>

            <p class="meta">
                Ce document atteste de l'accès accordé à l'ouvrage numérique ci-dessus sur la plateforme APACC-M e-Livre.
                Association loi 1901 — RNA W9M1011611 — SIRET 924 433 808 00012 — 11 Avenue Frantz Fanon, 97200 Fort-de-France.
            </p>
        </div>
    </div>

    <div class="actions">
        <button type="button" class="btn btn-print" onclick="window.print()">Imprimer / Enregistrer en PDF</button>
        <a href="{{ route('ebooks.mine') }}" class="btn btn-back">Retour à ma bibliothèque</a>
    </div>
</body>
</html>
