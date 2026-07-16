@extends('layouts.app')

@section('title', 'APACC-M — Bibliothèque numérique chrétienne martiniquaise')

@section('content')

{{-- ════════════════════ HERO ════════════════════ --}}
<section class="hero-section">
    <div style="position:relative;z-index:1;">
        <span class="letter-spacing-2" style="color:rgba(255,255,255,0.65);display:block;margin-bottom:1rem;">Bibliothèque numérique</span>
        <h1>Une lecture inspirée,<br>un patrimoine à découvrir</h1>
        <p>APACC-M vous propose une sélection d'eBooks dédiés à la culture, à la foi et au patrimoine martiniquais.</p>
        <div class="cta-group" style="justify-content:center;">
            <a href="{{ route('ebooks.index') }}" class="btn-primary" style="background:white;color:var(--cardinal)!important;border-color:white;font-size:0.88rem;">Parcourir le catalogue</a>
            <a href="{{ route('about') }}" class="btn-secondary" style="border-color:rgba(255,255,255,0.6);color:white!important;font-size:0.88rem;">En savoir plus</a>
        </div>
        <p class="hero-note">Paiement sécurisé via HelloAsso · Validation sous 12 à 24 h · Lecture en ligne protégée</p>
    </div>
</section>

{{-- ════════════════════ MISSION ════════════════════ --}}
<section class="mission-block">
    <div class="container-custom">
        <div class="row g-5 align-items-center">
            <div class="col-md-6">
                <span class="section-label">Notre mission</span>
                <h2 style="font-size:1.9rem;margin-bottom:1.25rem;">Diffuser la connaissance,<br>valoriser le patrimoine</h2>
                <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.25rem;">L'APACC-M est une association martiniquaise engagée dans la promotion de la culture chrétienne et du patrimoine religieux des Antilles. Nos publications numériques allient rigueur théologique, qualité éditoriale et accessibilité moderne.</p>
                <p style="color:var(--text-secondary);line-height:1.8;">Chaque eBook est le fruit d'un travail de recherche approfondi, présenté dans un format sécurisé et lisible sur tous les supports.</p>
                <a href="{{ route('about') }}" class="btn-secondary mt-3" style="display:inline-block;">Découvrir l'association</a>
            </div>
            <div class="col-md-6">
                <ul class="mission-values">
                    <li>Exigence éditoriale et rigueur documentaire</li>
                    <li>Valorisation du patrimoine religieux martiniquais</li>
                    <li>Accessibilité numérique pour tous les lecteurs</li>
                    <li>Respect des auteurs et des droits intellectuels</li>
                    <li>Engagement dans la transmission culturelle</li>
                    <li>Plateforme sécurisée sans téléchargement</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════ NARTHEX LINE ════════════════════ --}}
<div class="narthex-line" style="margin:0;"></div>

{{-- ════════════════════ SÉLECTIONS ════════════════════ --}}
<section style="padding:4rem 0 5rem;background:var(--cream);">
    <div class="container-custom">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="section-label">Sélections du moment</span>
                <h2 style="font-size:1.8rem;margin:0;">À lire maintenant</h2>
            </div>
            <a href="{{ route('ebooks.index') }}" class="btn-ghost d-none d-md-inline-block">Voir tout le catalogue</a>
        </div>

        <div class="row g-4">
            @forelse($topEbooks as $ebook)
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('ebooks.show', $ebook->slug) }}" class="text-decoration-none d-block h-100">
                        <article class="arch-card reveal h-100">
                            @if($ebook->cover_image)
                                <img src="{{ $ebook->thumbUrl() }}" alt="Couverture — {{ $ebook->title }}" loading="lazy" decoding="async">
                            @else
                                <div class="arch-card__cover-placeholder">📖</div>
                            @endif
                            <div class="arch-card-body">
                                @if($ebook->category)
                                    <span class="category-link">{{ $ebook->category->name }}</span>
                                @endif
                                <h5>{{ $ebook->title }}</h5>
                                @if($ebook->short_description)
                                    <p class="description">{{ \Illuminate\Support\Str::limit($ebook->short_description, 100) }}</p>
                                @endif
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:0.75rem;border-top:1px solid var(--border-light);">
                                    <span class="price">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                                    <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--cardinal);">Lire →</span>
                                </div>
                            </div>
                        </article>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p style="color:var(--text-secondary);text-align:center;padding:3rem 0;">Aucun eBook disponible pour le moment.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('ebooks.index') }}" class="btn-secondary">Voir tout le catalogue</a>
        </div>
    </div>
</section>

{{-- ════════════════════ NARTHEX LINE ════════════════════ --}}
<div class="narthex-line" style="margin:0;"></div>

{{-- ════════════════════ COMMENT ÇA MARCHE ════════════════════ --}}
<section style="padding:4rem 0;background:var(--white);">
    <div class="container-custom">
        <div class="text-center mb-4">
            <span class="section-label">Comment ça marche</span>
            <h2 style="font-size:1.8rem;">Simple, sécurisé, accessible</h2>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-4 text-center reveal">
                <div style="width:56px;height:56px;background:var(--cardinal);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-family:var(--font-serif);font-size:1.4rem;color:white;font-weight:700;">1</div>
                <h5 style="font-size:1rem;margin-bottom:0.5rem;">Choisissez votre eBook</h5>
                <p style="font-size:0.88rem;color:var(--text-secondary);line-height:1.6;">Parcourez notre catalogue et sélectionnez le titre qui vous intéresse.</p>
            </div>
            <div class="col-md-4 text-center reveal">
                <div style="width:56px;height:56px;background:var(--cardinal);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-family:var(--font-serif);font-size:1.4rem;color:white;font-weight:700;">2</div>
                <h5 style="font-size:1rem;margin-bottom:0.5rem;">Payez via HelloAsso</h5>
                <p style="font-size:0.88rem;color:var(--text-secondary);line-height:1.6;">Le paiement est sécurisé via HelloAsso. Signalez ensuite votre achat sur la plateforme.</p>
            </div>
            <div class="col-md-4 text-center reveal">
                <div style="width:56px;height:56px;background:var(--cardinal);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-family:var(--font-serif);font-size:1.4rem;color:white;font-weight:700;">3</div>
                <h5 style="font-size:1rem;margin-bottom:0.5rem;">Lisez en ligne</h5>
                <p style="font-size:0.88rem;color:var(--text-secondary);line-height:1.6;">Après validation sous 12 à 24 h, accédez à votre lecture sécurisée depuis n'importe quel appareil.</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════ CATEGORIES ════════════════════ --}}
@if($categories->count())
<section style="padding:3rem 0;background:var(--cream);border-top:1px solid var(--border-light);">
    <div class="container-custom">
        <span class="section-label" style="display:block;text-align:center;margin-bottom:1.25rem;">Thématiques</span>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;">
            @foreach($categories as $cat)
                <a href="{{ route('ebooks.index', ['category_id' => $cat->id]) }}"
                   style="display:inline-flex;align-items:center;gap:0.35rem;border:2px solid var(--border-medium);border-radius:999px;padding:0.4rem 1.1rem;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-secondary);transition:all 0.2s;text-decoration:none;"
                   onmouseover="this.style.borderColor='var(--cardinal)';this.style.color='var(--cardinal)'"
                   onmouseout="this.style.borderColor='var(--border-medium)';this.style.color='var(--text-secondary)'">
                    @if($cat->icon)<span>{{ $cat->icon }}</span>@endif {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════ NEWSLETTER ════════════════════ --}}
<section class="newsletter-section" x-data="{
    email: '',
    website: '',
    errorMsg: '',
    successMsg: '',
    loading: false,
    done: false,
    async submit() {
        this.errorMsg = '';
        this.loading = true;
        try {
            const res = await fetch('{{ route('newsletter.subscribe') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: this.email, website: this.website })
            });
            if (res.ok) {
                const data = await res.json();
                this.successMsg = data.message ?? 'Merci !';
                this.done = true;
                this.email = '';
            } else {
                const data = await res.json();
                this.errorMsg = data.errors?.email?.[0] ?? 'Une erreur est survenue.';
            }
        } catch {
            this.errorMsg = 'Erreur réseau. Veuillez réessayer.';
        }
        this.loading = false;
    }
}">
    <span class="section-label">Restez informé</span>
    <h3>Recevez nos nouvelles parutions</h3>
    <p>Inscrivez-vous pour être alerté des nouvelles publications et des actualités de l'APACC-M.</p>

    <div x-cloak x-show="done" class="flash-success" style="max-width:460px;margin:0 auto 1.25rem;" x-text="successMsg"></div>

    <div x-cloak x-show="errorMsg" class="flash-error" style="max-width:460px;margin:0 auto 1.25rem;" x-text="errorMsg"></div>

    <form class="newsletter-form" @submit.prevent="submit" x-show="!done">
        <input type="text" x-model="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">
        <input type="email" x-model="email" placeholder="Votre adresse email" required :disabled="loading">
        <button type="submit" class="btn-primary" :disabled="loading" style="display:inline-flex;align-items:center;gap:0.45rem;min-width:110px;justify-content:center;">
            <span x-cloak x-show="loading" style="width:14px;height:14px;border:2px solid rgba(255,255,255,0.35);border-top-color:white;border-radius:50%;animation:spin 0.65s linear infinite;flex-shrink:0;"></span>
            <span x-text="loading ? 'Envoi…' : 'S\'abonner'">S'abonner</span>
        </button>
    </form>
</section>

@endsection
