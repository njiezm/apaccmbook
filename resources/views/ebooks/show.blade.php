@extends('layouts.app')

@section('title', $ebook->title . ' — APACC-M')

@php
    use App\Support\OgImage;
    use Illuminate\Support\Facades\Storage;

    $hasCover = (bool) $ebook->cover_image;

    // Carte de partage 1200×630 (ratio paysage exigé par WhatsApp/Facebook/Twitter).
    // Générée à la volée si absente, pour les couvertures déjà en base.
    $ogImage = asset('icons/icon-512x512.png');
    $ogImageW = 512; $ogImageH = 512;
    $ogImageType = 'image/png';

    if ($hasCover) {
        $ogPath = OgImage::pathFor($ebook->cover_image);
        if (!Storage::disk('public')->exists($ogPath)) {
            OgImage::generate($ebook->cover_image);
        }
        if (Storage::disk('public')->exists($ogPath)) {
            // Cache-bust sur updated_at : quand la couverture change, l'URL change,
            // ce qui force WhatsApp/Facebook à re-télécharger l'aperçu (fini le cache figé).
            $ogImage = asset('storage/' . $ogPath) . '?v=' . $ebook->updated_at?->timestamp;
            $ogImageW = OgImage::W; $ogImageH = OgImage::H;
            $ogImageType = 'image/jpeg';
        } else {
            // Repli : couverture brute (mieux que rien si GD indisponible).
            $ogImage = asset('storage/' . $ebook->cover_image) . '?v=' . $ebook->updated_at?->timestamp;
            $ogImageW = $ogImageH = null;
            $ogImageType = 'image/jpeg';
        }
    }

    // Description propre : sans HTML ni retours à la ligne
    $ogDesc = \Illuminate\Support\Str::limit(
        trim(preg_replace('/\s+/', ' ', strip_tags($ebook->description ?? ''))),
        200
    );
@endphp

@section('meta')
    <meta property="og:type" content="book">
    <meta property="og:site_name" content="APACC-M e-Livre">
    <meta property="og:title" content="{{ $ebook->title }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:type" content="{{ $ogImageType }}">
    @if($ogImageW && $ogImageH)
        <meta property="og:image:width" content="{{ $ogImageW }}">
        <meta property="og:image:height" content="{{ $ogImageH }}">
    @endif
    <meta property="og:image:alt" content="Couverture — {{ $ebook->title }}">
    <meta property="og:url" content="{{ route('ebooks.show', $ebook) }}">
    <meta property="og:locale" content="fr_FR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ebook->title }}">
    <meta name="twitter:description" content="{{ $ogDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Données structurées (SEO Google) --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Book',
        'name' => $ebook->title,
        'description' => $ogDesc,
        'image' => $ogImage,
        'url' => route('ebooks.show', $ebook),
        'inLanguage' => 'fr',
        'bookFormat' => 'https://schema.org/EBook',
        'genre' => $ebook->category?->name,
        'publisher' => ['@type' => 'Organization', 'name' => 'APACC-M'],
        'offers' => [
            '@type' => 'Offer',
            'price' => $ebook->is_free ? '0' : number_format($ebook->price, 2, '.', ''),
            'priceCurrency' => 'EUR',
            'availability' => 'https://schema.org/InStock',
            'url' => route('ebooks.show', $ebook),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
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

            @if($ebook->is_transandans)
                <a href="{{ route('ebooks.index', ['transandans' => 1]) }}"
                   style="display:inline-flex;align-items:center;gap:0.4rem;margin:0.3rem 0;padding:0.25rem 0.7rem;border:1px solid var(--cardinal);border-radius:99px;color:var(--cardinal);font-size:0.76rem;font-weight:700;text-decoration:none;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    Revue <em style="font-style:italic;">Transandans</em>
                </a>
            @endif

            <h1>{{ $ebook->title }}</h1>

            {{-- Note moyenne + liste d'envies --}}
            @php $avg = round($ebook->avg_rating, 1); $rc = $ebook->reviews_count; @endphp
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin:0.35rem 0 0.5rem;">
                @if($rc > 0)
                    <a href="#avis" style="text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;color:var(--text-secondary);">
                        <span style="color:#f5a623;letter-spacing:1px;">
                            @for($i = 1; $i <= 5; $i++){{ $i <= round($avg) ? '★' : '☆' }}@endfor
                        </span>
                        <span style="font-size:0.85rem;">{{ number_format($avg, 1, ',', '') }} · {{ $rc }} avis</span>
                    </a>
                @else
                    <span style="font-size:0.85rem;color:var(--text-muted);">Pas encore d'avis</span>
                @endif

                @auth
                    @php $inWishlist = auth()->user()->wishlists->contains('ebook_id', $ebook->id); @endphp
                    <form method="POST" action="{{ route('wishlist.toggle', $ebook) }}" style="margin:0;">
                        @csrf
                        <button type="submit" style="background:none;border:1px solid var(--border-light);border-radius:999px;padding:0.3rem 0.8rem;cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;color:{{ $inWishlist ? 'var(--cardinal,#b91c1c)' : 'var(--text-secondary)' }};font-size:0.82rem;">
                            <i class="fa-{{ $inWishlist ? 'solid' : 'regular' }} fa-heart"></i>
                            {{ $inWishlist ? 'Dans mes envies' : 'Ajouter à mes envies' }}
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Partage social --}}
            @php
                $shareUrl     = route('ebooks.show', $ebook);
                $shareAuthor  = $ebook->author?->name ? ' — ' . $ebook->author->name : '';
                $shareTeaser  = \Illuminate\Support\Str::limit(
                    trim(preg_replace('/\s+/', ' ', strip_tags($ebook->description ?? ''))), 140
                );

                // Message soigné (WhatsApp affiche *…* en gras). Le lien seul en dernière
                // ligne déclenche l'aperçu (carte Open Graph) dans WhatsApp/Messenger.
                $shareMsg = "📖 *{$ebook->title}*{$shareAuthor}";
                if ($shareTeaser) {
                    $shareMsg .= "\n\n« {$shareTeaser} »";
                }
                $shareMsg .= "\n\n📚 À découvrir sur la bibliothèque numérique de l'APACC-M :";

                $waHref      = 'https://wa.me/?text=' . rawurlencode($shareMsg . "\n" . $shareUrl);
                $fbHref      = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($shareUrl);
                $mailHref    = 'mailto:?subject=' . rawurlencode($ebook->title . ' — APACC-M e-Livre')
                             . '&body=' . rawurlencode($shareMsg . "\n\n" . $shareUrl);

                // Données pour le partage natif mobile (navigator.share)
                $nativeShare = [
                    'title' => $ebook->title . ' — APACC-M e-Livre',
                    'text'  => $shareMsg,
                    'url'   => $shareUrl,
                ];
            @endphp
            <div style="display:flex;align-items:center;gap:0.5rem;margin:0.25rem 0 0.5rem;flex-wrap:wrap;"
                 x-data="{ copied:false, canShare: !!(navigator.share) }">
                <span style="font-size:0.8rem;color:var(--text-muted);">Partager :</span>

                {{-- Partage natif (mobile) : ouvre WhatsApp/Instagram/SMS… au choix --}}
                <button type="button" x-cloak x-show="canShare" title="Partager"
                        @click='navigator.share(@json($nativeShare)).catch(()=>{})'
                        style="width:32px;height:32px;border-radius:50%;background:var(--cardinal);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>

                <a href="{{ $waHref }}" target="_blank" rel="noopener" title="WhatsApp" style="width:32px;height:32px;border-radius:50%;background:#25D366;color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="{{ $fbHref }}" target="_blank" rel="noopener" title="Facebook" style="width:32px;height:32px;border-radius:50%;background:#1877F2;color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="{{ $mailHref }}" title="Email" style="width:32px;height:32px;border-radius:50%;background:var(--text-secondary);color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-solid fa-envelope"></i>
                </a>
                <button type="button" title="Copier le lien"
                        @click="navigator.clipboard.writeText('{{ $shareUrl }}').then(()=>{copied=true;setTimeout(()=>copied=false,1800)})"
                        style="width:32px;height:32px;border-radius:50%;background:var(--border-medium);color:var(--text-primary);border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-link"></i>
                </button>
                <span x-cloak x-show="copied" style="font-size:0.78rem;color:var(--cardinal);">Lien copié ✓</span>
            </div>

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

            {{-- Sommaire (aperçu) --}}
            @php $sommaireEntries = $ebook->sommaireEntries(); @endphp
            @if($sommaireEntries->isNotEmpty())
                <div x-data="{ open: false }" style="margin:1rem 0;">
                    <button type="button" @click="open = true" class="btn-secondary" style="display:inline-flex;align-items:center;gap:0.5rem;">
                        <i class="fa-solid fa-list-ul"></i> Voir le sommaire
                    </button>

                    <div x-cloak x-show="open" @click.self="open = false"
                         style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;">
                        <div style="background:var(--white);border-radius:var(--radius-lg);max-width:520px;width:100%;max-height:80vh;overflow:auto;border-top:4px solid var(--cardinal);">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-light);">
                                <h3 style="margin:0;font-size:1.15rem;">Sommaire</h3>
                                <button type="button" @click="open = false" style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;color:var(--text-muted);">×</button>
                            </div>
                            <ul style="list-style:none;margin:0;padding:0.5rem 0;">
                                @foreach($sommaireEntries as $entry)
                                    <li style="display:flex;justify-content:space-between;gap:1rem;padding:0.65rem 1.5rem;border-bottom:1px solid var(--border-light);font-size:0.92rem;">
                                        <span style="min-width:0;">
                                            <span style="display:block;">{{ $entry['title'] }}</span>
                                            @if($entry['subtitle'] !== '')
                                                <span style="display:block;color:var(--text-muted);font-size:0.82rem;margin-top:0.1rem;">{{ $entry['subtitle'] }}</span>
                                            @endif
                                        </span>
                                        @if(!empty($entry['page']))<span style="color:var(--text-muted);flex-shrink:0;">p. {{ $entry['page'] }}</span>@endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Bloc achat --}}
            @php
                // Coupon éventuellement appliqué (session)
                $appliedCoupon = null;
                if (!$ebook->is_free && ($sc = session('coupon_' . $ebook->id))) {
                    $c = \App\Models\Coupon::where('code', $sc)->first();
                    if ($c && $c->isValidForEbook($ebook)) {
                        $appliedCoupon = $c;
                    }
                }
                $displayPrice = $appliedCoupon ? $appliedCoupon->finalPrice((float) $ebook->price) : (float) $ebook->price;
            @endphp
            <div class="product-purchase-box" id="achat">
                <div class="product-price-block" style="margin-bottom:1rem;">
                    @if($ebook->is_free)
                        <span style="color:var(--cardinal,#b91c1c);">Gratuit</span>
                    @elseif($appliedCoupon)
                        <span style="text-decoration:line-through;color:var(--text-muted);font-size:0.7em;">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                        <span style="color:var(--cardinal,#b91c1c);">{{ number_format($displayPrice, 2, ',', ' ') }} €</span>
                    @else
                        {{ number_format($ebook->price, 2, ',', ' ') }} €
                    @endif
                </div>

                @if(session('coupon_error'))
                    <p style="color:var(--cardinal,#b91c1c);font-size:0.82rem;margin:0 0 0.75rem;">{{ session('coupon_error') }}</p>
                @endif

                {{-- Code promo (pour un achat payant, non encore validé) --}}
                @auth
                    @if(!$ebook->is_free && (!$purchase || $purchase->payment_status !== \App\Models\Purchase::STATUS_PAID))
                        @if($appliedCoupon)
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;background:#ecfdf5;border:1px solid #10b981;border-radius:var(--radius);padding:0.5rem 0.75rem;margin-bottom:0.75rem;">
                                <span style="font-size:0.82rem;color:#065f46;">Code <strong>{{ $appliedCoupon->code }}</strong> appliqué</span>
                                <form method="POST" action="{{ route('coupon.remove', $ebook) }}" style="margin:0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:#065f46;cursor:pointer;font-size:0.82rem;text-decoration:underline;">Retirer</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('coupon.apply', $ebook) }}" class="coupon-apply-form">
                                @csrf
                                <input type="text" name="code" placeholder="Code promo" maxlength="50">
                                <button type="submit" class="coupon-apply-btn" title="Appliquer le code" aria-label="Appliquer le code promo">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth

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
                            @php $sumupApiReady = in_array('sumup', $methods) && ($paymentSettings['sumup_api_key'] ?? '') && ($paymentSettings['sumup_merchant_code'] ?? ''); @endphp
                            @if($sumupApiReady)
                                {{-- API SumUp configurée : formulaire carte bancaire intégré, accès auto --}}
                                <div id="sumup-pay" data-init="{{ route('checkout.sumup.init', $ebook) }}" data-verify="{{ route('checkout.sumup.verify', $ebook) }}">
                                    <button type="button" id="sumup-start" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #1a1a2e;border-radius:var(--radius);background:#1a1a2e;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        Payer par carte bancaire
                                    </button>
                                    <div id="sumup-card" style="margin-top:0.6rem;"></div>
                                    <p id="sumup-status" role="status" style="display:none;font-size:0.8rem;color:var(--text-secondary);margin:0.5rem 0 0;text-align:center;"></p>
                                </div>
                                <script src="https://gateway.sumup.com/gateway/ecom/card/v2/sdk.js"></script>
                                <script>
                                (function () {
                                    const wrap    = document.getElementById('sumup-pay');
                                    const startBtn = document.getElementById('sumup-start');
                                    const statusEl = document.getElementById('sumup-status');
                                    if (!wrap || !startBtn) return;

                                    const initUrl   = wrap.dataset.init;
                                    const verifyUrl = wrap.dataset.verify;
                                    const csrf      = document.querySelector('meta[name="csrf-token"]')?.content;
                                    let mounted = false;

                                    const setStatus = (msg) => {
                                        statusEl.style.display = msg ? 'block' : 'none';
                                        statusEl.textContent = msg || '';
                                    };

                                    const post = (url) => fetch(url, {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                                    }).then(r => r.json());

                                    startBtn.addEventListener('click', async () => {
                                        if (mounted) return;
                                        startBtn.disabled = true;
                                        setStatus('Préparation du paiement sécurisé…');
                                        try {
                                            const data = await post(initUrl);
                                            if (data.already_paid) { window.location = data.redirect; return; }
                                            if (!data.ok || !data.checkout_id) {
                                                setStatus(data.error || 'SumUp indisponible pour le moment.');
                                                startBtn.disabled = false;
                                                return;
                                            }
                                            if (typeof SumUpCard === 'undefined') {
                                                setStatus('Le module de paiement n\'a pas pu se charger.');
                                                startBtn.disabled = false;
                                                return;
                                            }
                                            mounted = true;
                                            startBtn.style.display = 'none';
                                            setStatus('');
                                            SumUpCard.mount({
                                                id: 'sumup-card',
                                                checkoutId: data.checkout_id,
                                                locale: 'fr-FR',
                                                onResponse: async function (type, body) {
                                                    if (type === 'success') {
                                                        setStatus('Paiement accepté, ouverture de la lecture…');
                                                        const v = await post(verifyUrl);
                                                        if (v.ok && v.redirect) { window.location = v.redirect; }
                                                        else { setStatus('Paiement reçu — vérification en cours, un instant…'); }
                                                    } else if (type === 'error') {
                                                        setStatus('Paiement refusé ou interrompu. Vous pouvez réessayer.');
                                                    }
                                                },
                                            });
                                        } catch (e) {
                                            setStatus('Erreur réseau. Réessayez dans un instant.');
                                            startBtn.disabled = false;
                                        }
                                    });
                                })();
                                </script>
                            @elseif($ebook->sumup_url)
                                {{-- Repli : lien de paiement carte SumUp propre à l'ouvrage --}}
                                <a href="{{ $ebook->sumup_url }}" target="_blank" rel="noopener"
                                   style="width:100%;padding:0.6rem 1.25rem;border:2px solid #1a1a2e;border-radius:var(--radius);background:#1a1a2e;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:0.5rem;box-sizing:border-box;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                    Payer par carte via SumUp
                                </a>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.45rem;">Après votre paiement par carte sur SumUp, confirmez ici :</p>
                                    <form method="POST" action="{{ route('purchases.store') }}">
                                        @csrf
                                        <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                                        <input type="hidden" name="payment_method" value="sumup">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai payé par carte (SumUp)</button>
                                    </form>
                                </div>
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
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">IBAN : <code style="background:var(--white);padding:0.1rem 0.35rem;border-radius:3px;border:1px solid var(--border-light);font-size:0.78rem;color:var(--text-primary);">{{ $paymentSettings['virement_iban'] }}</code></p>
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

    {{-- ══════ AVIS ══════ --}}
    @php
        $reviews = $ebook->reviews()->where('status', 'approved')->with('user')->latest()->get();
        $myReview = auth()->check() ? $reviews->firstWhere('user_id', auth()->id()) : null;
    @endphp
    <div id="avis" style="margin-top:4rem;">
        <div class="narthex-line" style="margin-bottom:2rem;"></div>
        <span class="section-label">Avis des lecteurs</span>
        <h2 style="font-size:1.5rem;margin-bottom:0.5rem;">
            {{ $reviews->count() }} avis
            @if($reviews->count())
                — <span style="color:#f5a623;">{{ number_format(round($ebook->avg_rating, 1), 1, ',', '') }}/5</span>
            @endif
        </h2>

        {{-- Formulaire d'avis (membre connecté) --}}
        @auth
            <div class="review-form-card" x-data="{ rating: {{ (int) ($myReview->rating ?? 0) }}, hover: 0 }">
                <div class="review-form-head">
                    <span class="review-form-head__icon"><i class="fa-solid fa-pen-nib"></i></span>
                    <div>
                        <h3>{{ $myReview ? 'Modifier mon avis' : 'Donnez votre avis' }}</h3>
                        <p>Votre retour aide les autres lecteurs.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('reviews.store', $ebook) }}">
                    @csrf

                    {{-- Note : étoiles cliquables --}}
                    <div class="review-field">
                        <label>Votre note</label>
                        <input type="hidden" name="rating" x-model="rating">
                        <div class="review-stars" @mouseleave="hover = 0">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="review-star"
                                        @click="rating = {{ $i }}" @mouseenter="hover = {{ $i }}"
                                        :class="{ 'is-on': (hover || rating) >= {{ $i }} }"
                                        aria-label="{{ $i }} étoile{{ $i > 1 ? 's' : '' }}">★</button>
                            @endfor
                            <span class="review-stars__hint"
                                  x-text="(hover || rating) ? ((hover || rating) + '/5') : 'Cliquez pour noter'"></span>
                        </div>
                    </div>

                    {{-- Titre --}}
                    <div class="review-field">
                        <label>Titre <span class="review-opt">(optionnel)</span></label>
                        <input type="text" name="title" maxlength="255" placeholder="Ex. Une lecture passionnante" value="{{ $myReview->title ?? '' }}">
                    </div>

                    {{-- Commentaire --}}
                    <div class="review-field">
                        <label>Commentaire <span class="review-opt">(optionnel)</span></label>
                        <textarea name="content" rows="4" maxlength="2000" placeholder="Partagez ce que vous avez pensé de cet ouvrage…">{{ $myReview->content ?? '' }}</textarea>
                    </div>

                    <div class="review-form-actions">
                        <button type="submit" class="btn-primary" :disabled="!rating" :style="!rating ? 'opacity:.5;cursor:not-allowed;' : ''">
                            <i class="fa-solid fa-paper-plane" style="margin-right:0.4rem;"></i>{{ $myReview ? 'Mettre à jour' : 'Publier mon avis' }}
                        </button>
                        @if($myReview)
                            <button type="submit" form="delete-review" class="btn-ghost" style="color:var(--cardinal);border-color:var(--cardinal);">Supprimer</button>
                        @endif
                    </div>
                </form>
                @if($myReview)
                    <form method="POST" action="{{ route('reviews.destroy', $ebook) }}" id="delete-review">@csrf @method('DELETE')</form>
                @endif
            </div>
        @else
            <p style="color:var(--text-muted);font-size:0.9rem;margin:1rem 0 2rem;"><a href="{{ route('login') }}" style="color:var(--cardinal);font-weight:700;">Connectez-vous</a> pour laisser un avis.</p>
        @endauth

        {{-- Liste des avis --}}
        @forelse($reviews as $review)
            <div style="border-bottom:1px solid var(--border-light);padding:1rem 0;max-width:760px;">
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.3rem;">
                    <strong style="font-size:0.92rem;">{{ $review->user->name ?? 'Membre' }}</strong>
                    <span style="color:#f5a623;font-size:0.9rem;">@for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor</span>
                    <span style="color:var(--text-muted);font-size:0.78rem;">{{ $review->created_at->format('d/m/Y') }}</span>
                    @if(auth()->id() === $review->user_id)<span style="font-size:0.72rem;color:var(--cardinal);">(vous)</span>@endif
                </div>
                @if($review->title)<p style="font-weight:600;margin:0 0 0.2rem;">{{ $review->title }}</p>@endif
                @if($review->content)<p style="margin:0;color:var(--text-secondary);font-size:0.92rem;line-height:1.6;">{{ $review->content }}</p>@endif
                @can('manage-ebooks')
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Supprimer cet avis ?')" style="margin:0.4rem 0 0;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:var(--cardinal);cursor:pointer;font-size:0.78rem;text-decoration:underline;padding:0;">Supprimer (modération)</button>
                    </form>
                @endcan
            </div>
        @empty
            <p style="color:var(--text-muted);">Soyez le premier à donner votre avis sur cet ouvrage.</p>
        @endforelse
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
                                    <img src="{{ $rec->thumbUrl() }}" alt="Couverture — {{ $rec->title }}" loading="lazy" decoding="async" style="height:180px;object-fit:cover;">
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
