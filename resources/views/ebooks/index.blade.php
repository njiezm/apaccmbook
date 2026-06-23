@extends('layouts.app')

@section('title', 'Catalogue — APACC-M eBooks')

@section('content')

{{-- Header éditorial --}}
<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2rem 0 1.75rem;">
    <div class="container-custom">
        <span class="section-label">Bibliothèque numérique</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Catalogue</h1>
        <p style="color:var(--text-secondary);margin:0;">{{ $ebooks->total() }} publication{{ $ebooks->total() > 1 ? 's' : '' }} disponible{{ $ebooks->total() > 1 ? 's' : '' }}</p>
    </div>
</section>

@php
    $hasActiveFilters = request()->hasAny(['search','category_id','min_price','max_price'])
        || (request('sort') && request('sort') !== 'latest');
    $activeCount = collect(['search','category_id','min_price','max_price'])
        ->filter(fn($k) => request($k))->count()
        + ($hasActiveFilters && request('sort') && request('sort') !== 'latest' ? 1 : 0);
@endphp

<div class="container-custom" style="padding-top:2rem;padding-bottom:4rem;">
    <div class="catalog-shell" x-data="{ showFilters: {{ $hasActiveFilters ? 'true' : 'false' }} }">

        {{-- ══════ BARRE TOGGLE FILTRES (mobile/tablette) ══════ --}}
        <button class="catalog-filter-toggle" @click="showFilters = !showFilters" type="button"
                :aria-expanded="showFilters.toString()" aria-controls="catalog-filters-panel">
            <span style="display:flex;align-items:center;gap:0.65rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                Filtres
                @if($hasActiveFilters)
                    <span class="filter-active-badge">{{ $activeCount }} actif{{ $activeCount > 1 ? 's' : '' }}</span>
                @endif
            </span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :style="showFilters ? 'transform:rotate(180deg);transition:transform .25s' : 'transition:transform .25s'">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        {{-- ══════ SIDEBAR FILTRES ══════ --}}
        <aside class="catalog-filters" id="catalog-filters-panel"
               :class="{ 'catalog-filters--open': showFilters }">
            <h3>Filtres</h3>
            <form method="GET" action="{{ route('ebooks.index') }}">

                <div class="filter-group">
                    <label for="search">Recherche</label>
                    <input type="search" id="search" name="search" placeholder="Titre, description…" value="{{ request('search') }}">
                </div>

                @if($categories->count())
                <div class="filter-group">
                    <label for="category_id">Catégorie</label>
                    <select id="category_id" name="category_id">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="filter-group">
                    <label>Prix (€)</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" min="0" step="0.01" style="width:50%;">
                        <span style="color:var(--text-muted);font-size:0.85rem;">—</span>
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" min="0" step="0.01" style="width:50%;">
                    </div>
                </div>

                <div class="filter-group">
                    <label for="sort">Trier par</label>
                    <select id="sort" name="sort">
                        <option value="latest"     {{ request('sort','latest') === 'latest'     ? 'selected' : '' }}>Plus récents</option>
                        <option value="oldest"     {{ request('sort') === 'oldest'     ? 'selected' : '' }}>Plus anciens</option>
                        <option value="price_low"  {{ request('sort') === 'price_low'  ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Appliquer</button>

                @if($hasActiveFilters)
                    <a href="{{ route('ebooks.index') }}" class="btn-ghost mt-2" style="width:100%;text-align:center;display:block;">Réinitialiser</a>
                @endif
            </form>
        </aside>

        {{-- ══════ GRILLE EBOOKS ══════ --}}
        <div>
            @if(request('search') || request('category_id'))
                <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1.25rem;">
                    Résultats pour
                    @if(request('search'))<strong>« {{ request('search') }} »</strong>@endif
                    @if(request('category_id') && $categories->find(request('category_id')))<em> dans {{ $categories->find(request('category_id'))->name }}</em>@endif
                </p>
            @endif

            <div class="catalog-grid">
                @forelse($ebooks as $ebook)
                    <article class="ebook-card reveal">
                        @if($ebook->cover_image)
                            <img class="ebook-card__cover" src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" loading="lazy">
                        @else
                            <div class="ebook-card__cover-placeholder">📖</div>
                        @endif

                        <div class="ebook-card__body">
                            @if($ebook->category)
                                <span class="ebook-card__category">{{ $ebook->category->name }}</span>
                            @endif
                            <h3 class="ebook-card__title">{{ $ebook->title }}</h3>
                            @if($ebook->short_description)
                                <p class="ebook-card__description">{{ \Illuminate\Support\Str::limit($ebook->short_description, 110) }}</p>
                            @else
                                <p class="ebook-card__description">{{ \Illuminate\Support\Str::limit($ebook->description, 110) }}</p>
                            @endif

                            <div class="ebook-card__footer">
                                <span class="ebook-card__price">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                                <a href="{{ route('ebooks.show', $ebook->slug) }}" class="btn-primary" style="padding:0.42rem 0.9rem;font-size:0.76rem;">Voir le détail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div style="grid-column:1/-1;text-align:center;padding:4rem 0;">
                        <p style="color:var(--text-secondary);font-size:1.05rem;">Aucun eBook ne correspond à votre recherche.</p>
                        <a href="{{ route('ebooks.index') }}" class="btn-secondary mt-3">Réinitialiser les filtres</a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($ebooks->hasPages())
                <div style="margin-top:2.5rem;">
                    {{ $ebooks->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
