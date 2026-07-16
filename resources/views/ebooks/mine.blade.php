@extends('layouts.app')

@section('title', 'Mes eBooks — APACC-M')

@section('content')

{{-- Header --}}
<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2rem 0 1.75rem;">
    <div class="container-custom">
        <span class="section-label">Espace personnel</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Ma bibliothèque</h1>
        <p style="color:var(--text-secondary);margin:0;">Retrouvez ici tous vos eBooks acquis et leur statut de validation.</p>
    </div>
</section>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    <div class="grid-scroll">
        @forelse($purchases as $purchase)
            <article class="purchase-card reveal">
                <a href="{{ route('ebooks.show', $purchase->ebook) }}" style="display:block;margin:-0.25rem -0.25rem 0.9rem;border-radius:var(--radius,10px);overflow:hidden;aspect-ratio:3/4;background:var(--cream,#f8f7f4);">
                    @if($purchase->ebook->cover_image)
                        <img src="{{ asset('storage/' . $purchase->ebook->cover_image) }}" alt="{{ $purchase->ebook->title }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;">📖</div>
                    @endif
                </a>
                <div class="purchase-header">
                    <div style="flex:1;min-width:0;">
                        <h3 style="font-size:1.1rem;margin:0 0 0.3rem;">{{ $purchase->ebook->title }}</h3>
                        @if($purchase->ebook->category)
                            <span style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:var(--cardinal);">{{ $purchase->ebook->category->name }}</span>
                        @endif
                    </div>
                    <span class="status-pill {{ $purchase->payment_status }}">
                        {{ $purchase->payment_status === 'paid' ? 'Validé' : 'En attente' }}
                    </span>
                </div>

                @if($purchase->ebook->short_description)
                    <p style="font-size:0.875rem;color:var(--text-secondary);line-height:1.55;margin:0;">{{ \Illuminate\Support\Str::limit($purchase->ebook->short_description, 120) }}</p>
                @endif

                <p style="font-size:0.8rem;color:var(--text-muted);margin:0;">
                    Achat enregistré le {{ $purchase->created_at->format('d/m/Y à H\hi') }}
                </p>

                <div style="margin-top:0.5rem;padding-top:0.75rem;border-top:1px solid var(--border-light);">
                    @if($purchase->payment_status === \App\Models\Purchase::STATUS_PAID)
                        <a class="btn-primary" href="{{ route('ebooks.read', $purchase->ebook) }}" style="display:inline-block;">Lire maintenant</a>
                    @else
                        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">Validation en cours — un administrateur confirme votre paiement sous 12 à 24 h.</p>
                    @endif

                    @can('validate-purchase')
                        @if($purchase->payment_status === \App\Models\Purchase::STATUS_PENDING)
                            <form method="POST" action="{{ route('purchases.status.update', $purchase) }}" style="margin-top:0.75rem;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-secondary" style="font-size:0.78rem;padding:0.4rem 0.9rem;">Valider (Admin)</button>
                            </form>
                        @endif
                    @endcan
                </div>
            </article>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:5rem 0;">
                <div style="font-size:4rem;margin-bottom:1.5rem;">📚</div>
                <h3 style="font-size:1.4rem;margin-bottom:0.75rem;">Votre bibliothèque est vide</h3>
                <p style="color:var(--text-secondary);margin-bottom:1.75rem;max-width:420px;margin-left:auto;margin-right:auto;">Parcourez notre catalogue et acquérez votre premier eBook pour l'ajouter ici.</p>
                <a href="{{ route('ebooks.index') }}" class="btn-primary">Découvrir le catalogue</a>
            </div>
        @endforelse
    </div>
</div>

@endsection
