@extends('layouts.app')

@section('title', 'Mes envies — APACC-M')

@section('content')

<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2rem 0 1.75rem;">
    <div class="container-custom">
        <span class="section-label">Espace personnel</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Mes envies</h1>
        <p style="color:var(--text-secondary);margin:0;">Les ouvrages que vous souhaitez lire plus tard.</p>
    </div>
</section>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    @if($wishlists->count())
        <div class="catalog-grid">
            @foreach($wishlists as $item)
                @php $ebook = $item->ebook; @endphp
                <article class="ebook-card reveal">
                    <a href="{{ route('ebooks.show', $ebook) }}" style="display:block;">
                        @if($ebook->cover_image)
                            <img class="ebook-card__cover" src="{{ $ebook->thumbUrl() }}" alt="Couverture — {{ $ebook->title }}" loading="lazy" decoding="async">
                        @else
                            <div class="ebook-card__cover-placeholder">📖</div>
                        @endif
                    </a>
                    <div class="ebook-card__body">
                        @if($ebook->category)
                            <span class="ebook-card__category">{{ $ebook->category->name }}</span>
                        @endif
                        <h3 class="ebook-card__title">{{ $ebook->title }}</h3>
                        <div class="ebook-card__footer">
                            @if($ebook->is_free)
                                <span class="ebook-card__price" style="color:var(--cardinal);">Gratuit</span>
                            @else
                                <span class="ebook-card__price">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                            @endif
                            <div style="display:flex;gap:0.4rem;align-items:center;">
                                <a href="{{ route('ebooks.show', $ebook) }}" class="btn-primary" style="padding:0.42rem 0.9rem;font-size:0.76rem;">Voir</a>
                                <form method="POST" action="{{ route('wishlist.toggle', $ebook) }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-secondary" style="padding:0.42rem 0.6rem;font-size:0.76rem;" title="Retirer de mes envies">✕</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div style="text-align:center;padding:5rem 0;">
            <div style="font-size:4rem;margin-bottom:1.5rem;">❤️</div>
            <h3 style="font-size:1.4rem;margin-bottom:0.75rem;">Aucune envie pour l'instant</h3>
            <p style="color:var(--text-secondary);margin-bottom:1.75rem;max-width:420px;margin-left:auto;margin-right:auto;">Parcourez le catalogue et ajoutez les ouvrages qui vous intéressent avec le cœur ❤️.</p>
            <a href="{{ route('ebooks.index') }}" class="btn-primary">Découvrir le catalogue</a>
        </div>
    @endif
</div>

@endsection
