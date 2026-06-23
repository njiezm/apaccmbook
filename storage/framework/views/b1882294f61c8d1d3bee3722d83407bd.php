<?php $__env->startSection('title', 'À propos — APACC-M'); ?>

<?php $__env->startSection('content'); ?>


<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">L'association</span>
        <h1 style="font-size:2.2rem;margin-bottom:0.3rem;">À propos de l'APACC-M</h1>
        <p style="color:var(--text-secondary);font-size:1.05rem;margin:0;">Association de Promotion et d'Animation de la Culture Catholique en Martinique</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;">

    
    <div class="row g-5 mb-5">
        <div class="col-md-7">
            <span class="section-label">Notre identité</span>
            <h2 style="font-size:1.75rem;margin-bottom:1.25rem;">Qui sommes-nous ?</h2>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                L'APACC-M est une association martiniquaise dont la vocation est de promouvoir, préserver et diffuser la culture catholique et le patrimoine religieux des Antilles françaises. Fondée dans l'esprit éditorial rigoureux de la revue <em>Narthex</em>, elle s'engage à produire des contenus de qualité à destination d'un large public.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                Notre plateforme numérique constitue le prolongement naturel de cette mission : rendre accessible, sous forme d'eBooks sécurisés, une sélection d'ouvrages consacrés à la théologie, à l'histoire religieuse et au patrimoine culturel martiniquais.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Chaque publication est le fruit d'un travail de recherche approfondi, soumis à une exigence éditoriale constante, dans le respect des auteurs et de leurs œuvres.
            </p>
        </div>
        <div class="col-md-5">
            <div style="background:var(--cream);border-left:4px solid var(--cardinal);padding:2rem;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                <h3 style="font-size:1rem;text-transform:uppercase;letter-spacing:0.2em;color:var(--cardinal);margin-bottom:1.25rem;">Nos valeurs</h3>
                <ul class="mission-values">
                    <li>Exigence éditoriale et rigueur documentaire</li>
                    <li>Valorisation du patrimoine religieux martiniquais</li>
                    <li>Accessibilité numérique pour tous</li>
                    <li>Respect des auteurs et des droits intellectuels</li>
                    <li>Engagement dans la transmission culturelle</li>
                    <li>Innovation au service de la sobriété éditoriale</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="narthex-line"></div>

    
    <div class="row g-5 mb-5">
        <div class="col-md-6">
            <span class="section-label">Notre mission</span>
            <h2 style="font-size:1.6rem;margin-bottom:1rem;">Diffuser, préserver, transmettre</h2>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                Dans un contexte de mutation numérique rapide, l'APACC-M fait le choix d'une présence digitale maîtrisée et sobre. La plateforme eBooks s'inscrit dans cette logique : proposer un accès sécurisé à des œuvres de qualité, sans sacrifier la rigueur au profit de la facilité.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Nos publications s'adressent aussi bien aux fidèles qu'aux chercheurs, aux étudiants qu'aux curieux — à tous ceux qui souhaitent approfondir leur connaissance du catholicisme en Martinique et dans les Antilles.
            </p>
        </div>
        <div class="col-md-6">
            <span class="section-label">La plateforme</span>
            <h2 style="font-size:1.6rem;margin-bottom:1rem;">Comment ça marche</h2>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                Les eBooks sont accessibles exclusivement en ligne, dans un lecteur sécurisé intégré à la plateforme. Aucun téléchargement, aucune impression n'est possible — une garantie pour les auteurs et un gage de qualité pour les lecteurs.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Le paiement s'effectue via HelloAsso, plateforme associative de confiance. Après validation par notre équipe (12 à 24 h), l'accès est ouvert directement dans votre espace personnel.
            </p>
        </div>
    </div>

    <div class="narthex-line"></div>

    
    <div style="text-align:center;padding:2rem 0;">
        <span class="section-label" style="display:block;margin-bottom:0.75rem;">Rejoignez-nous</span>
        <h2 style="font-size:1.6rem;margin-bottom:1rem;">Prêt à explorer notre catalogue ?</h2>
        <p style="color:var(--text-secondary);max-width:480px;margin:0 auto 2rem;">Découvrez nos publications et participez à la diffusion du patrimoine catholique martiniquais.</p>
        <div class="cta-group" style="justify-content:center;">
            <a href="<?php echo e(route('ebooks.index')); ?>" class="btn-primary">Parcourir le catalogue</a>
            <a href="<?php echo e(route('contact')); ?>" class="btn-secondary">Nous contacter</a>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/pages/about.blade.php ENDPATH**/ ?>