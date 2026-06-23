@extends('layouts.app')

@section('title', 'Conditions générales — APACC-M')

@section('content')

<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">Légal</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Conditions générales d'utilisation</h1>
        <p style="color:var(--text-muted);font-size:0.85rem;margin:0;">Dernière mise à jour : juin 2025</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;max-width:800px;">
    <div style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:2.5rem;box-shadow:var(--shadow-soft);">

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">1. Objet</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Les présentes conditions générales régissent l'utilisation de la plateforme APACC-M eBooks. En accédant au site, l'utilisateur accepte sans réserve les présentes conditions.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">2. Accès au site</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">L'accès au catalogue est libre et gratuit. La consultation des eBooks requiert une inscription ainsi que la validation d'un paiement effectué via HelloAsso.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">3. Propriété intellectuelle</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Tous les contenus publiés sur la plateforme (textes, images, eBooks) sont protégés par le droit d'auteur. Toute reproduction, même partielle, sans autorisation écrite préalable est strictement interdite.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">4. Responsabilité</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">L'APACC-M décline toute responsabilité quant aux erreurs, interruptions ou omissions pouvant affecter le site. L'utilisation se fait sous la seule responsabilité de l'utilisateur.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">5. Modifications</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:0;">Les présentes conditions peuvent être modifiées à tout moment. Les modifications entrent en vigueur dès leur publication sur le site.</p>
    </div>

    <div style="margin-top:2rem;text-align:center;">
        <a href="{{ route('contact') }}" class="btn-secondary">Une question ? Contactez-nous</a>
    </div>
</div>

@endsection
