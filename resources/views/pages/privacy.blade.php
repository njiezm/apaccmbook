@extends('layouts.app')

@section('title', 'Confidentialité — APACC-M')

@section('content')

<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">Légal</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Politique de confidentialité</h1>
        <p style="color:var(--text-muted);font-size:0.85rem;margin:0;">Dernière mise à jour : juin 2025</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;max-width:800px;">
    <div style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:2.5rem;box-shadow:var(--shadow-soft);">

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">1. Données collectées</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Nous collectons uniquement les données nécessaires au bon fonctionnement du service : nom, adresse email et historique d'achats. Ces données sont collectées avec votre consentement lors de la création de votre compte.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">2. Utilisation des données</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Vos données personnelles sont utilisées exclusivement pour : traiter et valider vos commandes, gérer votre accès aux eBooks, et vous informer des nouvelles publications (avec votre accord).</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">3. Sécurité</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">La plateforme est sécurisée par le protocole HTTPS. Les fichiers eBooks sont stockés dans un espace privé inaccessible au public. Nous respectons les exigences du RGPD.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">4. Vos droits (RGPD)</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:1.75rem;">Conformément au RGPD, vous disposez d'un droit d'accès, de rectification, de suppression et de portabilité de vos données. Pour exercer ces droits, supprimez votre compte depuis votre profil ou contactez-nous.</p>

        <div class="narthex-line" style="margin:1.5rem 0;"></div>

        <h2 style="font-size:1.25rem;margin-bottom:1rem;">5. Contact</h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:0;">Pour toute question relative à vos données personnelles, contactez-nous via le <a href="{{ route('contact') }}" style="color:var(--cardinal);">formulaire de contact</a>.</p>
    </div>
</div>

@endsection
