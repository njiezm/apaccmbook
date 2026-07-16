@extends('layouts.app')

@section('title', $ebook->title . ' — APACC-M')

@php
    $ogImage = $ebook->cover_image
        ? asset('storage/' . $ebook->cover_image)
        : asset('icons/icon.svg');
    $ogDesc = \Illuminate\Support\Str::limit(strip_tags($ebook->description ?? ''), 200);
@endphp

@section('meta')
    <meta property="og:type" content="book">
    <meta property="og:site_name" content="APACC-M e-Livre">
    <meta property="og:title" content="{{ $ebook->title }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="Couverture — {{ $ebook->title }}">
    <meta property="og:url" content="{{ route('ebooks.show', $ebook) }}">
    <meta property="og:locale" content="fr_FR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ebook->title }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
@endsection

@section('content')

{{-- Fil d'Ariane --}}
<div style="background:var(--white);border-bottom:1px solid var(--border-light);padding:0.75rem 0;">
    <div class="container-custom">
        <nav style="font-size:0.8rem;color:var(--text-muted);">
            <a href="{{ route('home') }}" style="color:var(--text-muted);">Accueil</a>
            <span style="margin:0 0.5rem;">›</span>
            <a href="{{ route('ebooks.index') }}" style="color:var(--text-muted);">Catalogue</a>
            @if($ebook->category)
                <span style="margin:0 0.5rem;">›</span>
                <a href="{{ route('ebooks.index', ['category_id' => $ebook->category->id]) }}" style="color:var(--text-muted);">{{ $ebook->category->name }}</a>
            @endif
            <span style="margin:0 0.5rem;">›</span>
            <span style="color:var(--text-primary);">{{ $ebook->title }}</span>
        </nav>
    </div>
</div>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    <div class="product-detail-grid">

        {{-- ══════ COUVERTURE ══════ --}}
        <div class="product-cover">
            @if($ebook->cover_image)
                <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}">
            @else
                <div class="product-cover-placeholder">📖</div>
            @endif
        </div>

        {{-- ══════ INFORMATIONS ══════ --}}
        <div class="product-info">

            @if($ebook->category)
                <span class="section-label">{{ $ebook->category->name }}</span>
            @endif

            <h1>{{ $ebook->title }}</h1>

            {{-- Métadonnées --}}
            <div class="product-meta">
                @if($ebook->author)
                    <span>✍ {{ $ebook->author->name }}</span>
                @endif
                @if($ebook->page_count)
                    <span>📄 {{ $ebook->page_count }} pages</span>
                @endif
                @if($ebook->published_date)
                    <span>📅 {{ \Carbon\Carbon::parse($ebook->published_date)->format('Y') }}</span>
                @endif
            </div>

            {{-- Narthex line --}}
            <div class="narthex-line" style="margin:0.5rem 0 1rem;"></div>

            {{-- Description --}}
            @if($ebook->short_description)
                <p style="font-size:1.05rem;line-height:1.75;color:var(--text-secondary);">{{ $ebook->short_description }}</p>
            @endif

            @if($ebook->description && $ebook->description !== $ebook->short_description)
                <div style="font-size:0.95rem;line-height:1.75;color:var(--text-secondary);">
                    {!! nl2br(e($ebook->description)) !!}
                </div>
            @endif

            {{-- Bloc achat --}}
            <div class="product-purchase-box">
                <div class="product-price-block" style="margin-bottom:1rem;">
                    @if($ebook->is_free)
                        <span style="color:var(--cardinal,#b91c1c);">Gratuit</span>
                    @else
                        {{ number_format($ebook->price, 2, ',', ' ') }} €
                    @endif
                </div>

                @php $methods = $paymentSettings['enabled_methods'] ?? ['helloasso']; @endphp

                @if($ebook->is_free && (!$purchase || $purchase->payment_status !== \App\Models\Purchase::STATUS_PAID))
                    {{-- ── Livre gratuit ── --}}
                    @auth
                        <a class="btn-primary" href="{{ route('ebooks.read', $ebook) }}" style="display:block;text-align:center;">Lire gratuitement</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary" style="display:block;text-align:center;">Connectez-vous pour lire gratuitement</a>
                    @endauth

                @elseif($purchase)
                    {{-- ── Achat existant ── --}}
                    <div style="margin-bottom:1rem;">
                        <span class="status-pill {{ $purchase->payment_status }}" style="margin-bottom:0.5rem;display:inline-block;">
                            {{ $purchase->payment_status === 'paid' ? 'Accès validé' : 'En attente de validation' }}
                        </span>
                        <p style="font-size:0.85rem;color:var(--text-secondary);margin:0.5rem 0 0;">
                            @if($purchase->payment_status === 'paid')
                                Votre accès est actif. Vous pouvez lire cet eBook dès maintenant.
                            @else
                                Votre paiement est en cours de validation (12 à 24 h). Vous recevrez une notification dès que l'accès sera ouvert.
                            @endif
                        </p>
                    </div>
                    @if($purchase->payment_status === \App\Models\Purchase::STATUS_PAID)
                        <a class="btn-primary" href="{{ route('ebooks.read', $ebook) }}" style="display:block;text-align:center;margin-bottom:0.75rem;">Lire maintenant</a>
                    @endif

                @else
                    {{-- ── Méthodes de paiement disponibles ── --}}
                    @auth
                        <div style="display:flex;flex-direction:column;gap:0.6rem;">

                            {{-- Stripe --}}
                            @if(in_array('stripe', $methods))
                                <form method="POST" action="{{ route('checkout.stripe', $ebook) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;gap:0.5rem;display:flex;align-items:center;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        Payer par carte — Stripe
                                    </button>
                                </form>
                            @endif

                            {{-- PayPal --}}
                            @if(in_array('paypal', $methods))
                                <form method="POST" action="{{ route('checkout.paypal', $ebook) }}">
                                    @csrf
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #003087;border-radius:var(--radius);background:#003087;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.826l-1.348 8.54h3.36c.524 0 .967-.382 1.05-.9l.978-6.195h2.18c4.298 0 6.797-2.078 7.647-6.55.128-.67.183-1.292.13-1.69z"/></svg>
                                        Payer via PayPal
                                    </button>
                                </form>
                            @endif

                            {{-- SumUp --}}
                            @if(in_array('sumup', $methods))
                                <form method="POST" action="{{ route('checkout.sumup', $ebook) }}">
                                    @csrf
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #1a1a2e;border-radius:var(--radius);background:#1a1a2e;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        Payer via SumUp
                                    </button>
                                </form>
                            @endif

                            {{-- HelloAsso --}}
                            @if(in_array('helloasso', $methods))
                                @php $helloassoUrl = $paymentSettings['helloasso_url'] ?? ($ebook->helloasso_url ?? '#'); @endphp
                                <a href="{{ $helloassoUrl }}" target="_blank" rel="noopener"
                                   style="width:100%;padding:0.6rem 1.25rem;border:2px solid #f47930;border-radius:var(--radius);background:#f47930;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:0.5rem;box-sizing:border-box;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    HelloAsso — 0 % de frais
                                </a>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.45rem;">Après paiement sur HelloAsso, confirmez ici :</p>
                                    <form method="POST" action="{{ route('purchases.store') }}">
                                        @csrf
                                        <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                                        <input type="hidden" name="payment_method" value="helloasso">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué mon paiement HelloAsso</button>
                                    </form>
                                </div>
                            @endif

                            {{-- Virement bancaire --}}
                            @if(in_array('virement', $methods) && ($paymentSettings['virement_iban'] ?? ''))
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Virement bancaire</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">IBAN : <code style="background:#fff;padding:0.1rem 0.35rem;border-radius:3px;border:1px solid var(--border-light);font-size:0.78rem;">{{ $paymentSettings['virement_iban'] }}</code></p>
                                    @if($paymentSettings['virement_bic'] ?? '')<p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">BIC : <strong>{{ $paymentSettings['virement_bic'] }}</strong></p>@endif
                                    @if($paymentSettings['virement_titulaire'] ?? '')<p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;">Titulaire : <strong>{{ $paymentSettings['virement_titulaire'] }}</strong></p>@endif
                                    <form method="POST" action="{{ route('purchases.store') }}">
                                        @csrf
                                        <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                                        <input type="hidden" name="payment_method" value="virement">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué le virement</button>
                                    </form>
                                </div>
                            @endif

                            {{-- Chèque --}}
                            @if(in_array('cheque', $methods) && ($paymentSettings['cheque_ordre'] ?? ''))
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Paiement par chèque</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">À l'ordre de : <strong>{{ $paymentSettings['cheque_ordre'] }}</strong></p>
                                    @if($paymentSettings['cheque_adresse'] ?? '')<p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;white-space:pre-line;">{{ $paymentSettings['cheque_adresse'] }}</p>@endif
                                    <form method="POST" action="{{ route('purchases.store') }}">
                                        @csrf
                                        <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                                        <input type="hidden" name="payment_method" value="cheque">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai envoyé mon chèque</button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary" style="display:block;text-align:center;">
                            Connectez-vous pour acheter
                        </a>
                    @endauth
                @endif

                @can('validate-purchase')
                    @if($purchase && $purchase->payment_status === \App\Models\Purchase::STATUS_PENDING)
                        <div style="border-top:1px solid var(--border-light);padding-top:0.75rem;margin-top:0.75rem;">
                            <form method="POST" action="{{ route('purchases.status.update', $purchase) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn-primary" type="submit" style="background:var(--text-primary);border-color:var(--text-primary);width:100%;">
                                    Valider le paiement (Admin)
                                </button>
                            </form>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    {{-- ══════ RECOMMANDATIONS ══════ --}}
    @if($recommendations->count())
        <div style="margin-top:4rem;">
            <div class="narthex-line" style="margin-bottom:2rem;"></div>
            <span class="section-label">Dans la même thématique</span>
            <h2 style="font-size:1.5rem;margin-bottom:2rem;">Vous pourriez aussi apprécier</h2>
            <div class="row g-4">
                @foreach($recommendations as $rec)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('ebooks.show', $rec->slug) }}" class="text-decoration-none d-block h-100">
                            <article class="arch-card h-100">
                                @if($rec->cover_image)
                                    <img src="{{ asset('storage/' . $rec->cover_image) }}" alt="{{ $rec->title }}" loading="lazy" style="height:180px;object-fit:cover;">
                                @else
                                    <div class="arch-card__cover-placeholder" style="height:180px;font-size:2.5rem;">📖</div>
                                @endif
                                <div class="arch-card-body">
                                    <h5 style="font-size:0.95rem;">{{ $rec->title }}</h5>
                                    <span class="price" style="font-size:1rem;">{{ number_format($rec->price, 2, ',', ' ') }} €</span>
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endsection
