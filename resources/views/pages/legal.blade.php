@extends('layouts.app')

@section('title', 'Mentions légales — APACC-M')

@section('content')

<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">Légal</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Mentions légales</h1>
        <p style="color:var(--text-muted);font-size:0.85rem;margin:0;">Conformément aux articles 6-III et 19 de la loi n° 2004-575</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;max-width:800px;">
    <div style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:2.5rem;box-shadow:var(--shadow-soft);">

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">Éditeur du site</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">
            <strong style="color:var(--text-primary);">APACC-M</strong><br>
            Association de Promotion et d'Animation de la Culture Catholique en Martinique<br>
            Martinique, France<br>
            Email : <a href="{{ route('contact') }}" style="color:var(--cardinal);">via notre formulaire de contact</a>
        </p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">Directeur de la publication</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Le représentant légal de l'association APACC-M.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">Hébergement</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Ce site est hébergé sur une infrastructure dédiée. Les coordonnées de l'hébergeur sont disponibles sur demande.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">Propriété intellectuelle</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">L'ensemble des contenus publiés sur ce site (textes, images, eBooks, mises en page) sont protégés par le droit d'auteur français et international. Toute reproduction, même partielle, est interdite sans autorisation écrite préalable de l'APACC-M.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">Paiement</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:0;">Les transactions financières sont traitées par la plateforme HelloAsso, dont les conditions générales sont accessibles sur <a href="https://www.helloasso.com" target="_blank" rel="noopener" style="color:var(--cardinal);">helloasso.com</a>. L'APACC-M n'est pas responsable des opérations réalisées sur cette plateforme tierce.</p>
    </div>
</div>

@endsection
