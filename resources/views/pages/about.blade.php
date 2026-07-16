@extends('layouts.app')

@section('title', 'À propos — APACC-M')

@section('content')

{{-- Hero éditorial --}}
<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">L'association</span>
        <h1 style="font-size:2.2rem;margin-bottom:0.3rem;">À propos de l'APACC-M</h1>
        <p style="color:var(--text-secondary);font-size:1.05rem;margin:0;">Association pour la Promotion de l'Art et la Culture Chrétienne</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;">

    {{-- Notre histoire --}}
    <div class="row g-5 mb-5 align-items-center">
        <div class="col-md-7">
            <span class="section-label">Notre histoire</span>
            <h2 style="font-size:1.75rem;margin-bottom:1.25rem;">Missions &amp; engagements</h2>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                L'Association pour la Promotion de l'Art et la Culture Chrétienne (APACC-M) est née d'une volonté
                profonde de faire rayonner l'identité chrétienne au cœur de la culture martiniquaise.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Notre plateforme numérique prolonge cette mission et diffuse notre revue phare,
                <em>Transandans</em>, ainsi qu'une sélection d'ouvrages consacrés à l'art sacré, à l'histoire
                religieuse et au patrimoine culturel créole.
            </p>
        </div>
        <div class="col-md-5">
            <blockquote style="background:var(--cream);border-left:4px solid var(--cardinal);padding:1.75rem;border-radius:0 var(--radius-md) var(--radius-md) 0;font-family:var(--font-serif,Georgia,serif);font-style:italic;font-size:1.05rem;line-height:1.7;color:var(--text-primary);margin:0;">
                « L'APACC-M est née d'une volonté profonde de faire rayonner l'identité chrétienne au cœur de la culture martiniquaise. »
            </blockquote>
        </div>
    </div>

    <div class="narthex-line"></div>

    {{-- Mot du Président --}}
    <div class="mb-5" style="max-width:820px;margin-left:auto;margin-right:auto;">
        <div style="text-align:center;margin-bottom:1.75rem;">
            <span class="section-label">Mot du Président</span>
            <h2 style="font-size:1.7rem;margin:0.35rem 0 0;">Pourquoi l'APACC-M ?</h2>
        </div>
        <div style="line-height:1.9;color:var(--text-secondary);font-size:1.02rem;">
            <p style="margin-bottom:1rem;">
                Parce que tout art a une culture. Le transcendant est déjà présent en l'homme. Il y a quelque chose
                qui est inscrit dans l'homme et qui l'amène à un dépassement. Et l'artiste peut exprimer cette réalité,
                inscrite en l'homme et en toutes choses ; ce qui nous amène finalement à nous élever au-dessus de la
                simple matérialité des choses. Et c'est là que se trouve aussi l'universalisme, l'universalité de
                l'art : une Parole est dite, par l'œuvre artistique, pour le monde et pour la culture locale, la
                culture créole, et qui permet de dépasser les drames de l'humanité, comme celui de l'esclavage, vers
                quelque chose de transcendant. L'Art est en quelque sorte Résilience, parce qu'il permet et amène à
                ce dépassement-là.
            </p>
            <p style="margin-bottom:1rem;">
                Finalement, l'artiste est tel un prophète, un élu, qui dit ce qu'on a du mal à entendre, révèle ce
                qu'on a du mal à voir, exprime la beauté déjà présente mais insuffisamment connue. L'artiste nous
                donne accès à une dimension de la réalité trop souvent insoupçonnée.
            </p>
            <p style="text-align:right;font-weight:700;color:var(--cardinal);margin:1.25rem 0 0;">— Le Président de l'APACC-M</p>
        </div>
    </div>

    <div class="narthex-line"></div>

    {{-- Vision & valeurs --}}
    <div class="row g-5 my-5">
        <div class="col-md-6">
            <div style="display:flex;align-items:baseline;gap:0.75rem;margin-bottom:0.75rem;">
                <span style="font-family:var(--font-serif,Georgia,serif);font-size:2rem;font-weight:700;color:var(--cardinal);line-height:1;">01.</span>
                <h2 style="font-size:1.4rem;margin:0;">Notre vision</h2>
            </div>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Promouvoir et valoriser l'art et la culture chrétienne martiniquaise, à travers notamment sa culture
                créole et son patrimoine. Nous soutenons les activités artistiques par des événements, des
                expositions et des formations.
            </p>
        </div>
        <div class="col-md-6">
            <div style="display:flex;align-items:baseline;gap:0.75rem;margin-bottom:0.75rem;">
                <span style="font-family:var(--font-serif,Georgia,serif);font-size:2rem;font-weight:700;color:var(--cardinal);line-height:1;">02.</span>
                <h2 style="font-size:1.4rem;margin:0;">Nos valeurs</h2>
            </div>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Dignité humaine, éthique, bienveillance et solidarité. L'association se veut un lieu de rencontre et
                de partage, un espace de dialogue pour la transmission des richesses de la culture créole.
            </p>
        </div>
    </div>

    <div class="narthex-line"></div>

    {{-- Qui sommes-nous --}}
    <div class="row g-5 my-5">
        <div class="col-md-7">
            <span class="section-label">Qui sommes-nous ?</span>
            <h2 style="font-size:1.6rem;margin-bottom:1rem;">Un espace ouvert à tous</h2>
            <p style="line-height:1.85;color:var(--text-secondary);margin-bottom:1rem;">
                L'Association pour la Promotion de l'Art et la Culture Chrétienne (APACC-M) s'adresse à tous :
                artistes confirmés, amateurs d'art, membres de la communauté chrétienne ou simples curieux.
            </p>
            <p style="line-height:1.85;color:var(--text-secondary);">
                Nous croyons que l'art est un vecteur universel de médiation et de paix. En documentant les œuvres
                inspirées par la foi, nous préservons une part essentielle de l'âme martiniquaise.
            </p>
        </div>
        <div class="col-md-5">
            <div style="background:var(--cream);border-left:4px solid var(--cardinal);padding:1.75rem;border-radius:0 var(--radius-md) var(--radius-md) 0;">
                <h3 style="font-size:0.95rem;text-transform:uppercase;letter-spacing:0.18em;color:var(--cardinal);margin-bottom:1rem;">Objet de l'association</h3>
                <ul class="mission-values">
                    <li>Favoriser et soutenir toutes activités et initiatives culturelles et artistiques chrétiennes de Martinique.</li>
                    <li>Assurer le développement et la valorisation des arts et de la culture chrétienne créole sous toutes ses formes — art sacré, littéraire, linguistique, musical, pictural, patrimonial — et en assurer la production, la diffusion et la transmission.</li>
                    <li>Promouvoir le rapprochement entre arts et culture chrétienne et les autres formes d'expression artistique et culturelle en Martinique.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="narthex-line"></div>

    {{-- Informations légales --}}
    <div class="my-5">
        <div style="text-align:center;margin-bottom:1.75rem;">
            <span class="section-label">Informations légales</span>
            <h2 style="font-size:1.6rem;margin:0.35rem 0 0;">Cadre institutionnel</h2>
        </div>
        <div class="row g-4">
            @php
                $legal = [
                    ['Statut juridique', 'Association loi 1901'],
                    ['RNA', 'W9M1011611'],
                    ['SIRET', '924 433 808 00012'],
                    ['Siège social', '11 Avenue Frantz Fanon, 97200 Fort-de-France'],
                ];
            @endphp
            @foreach($legal as [$label, $value])
                <div class="col-6 col-md-3">
                    <div style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:1.25rem;height:100%;">
                        <p style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.15em;color:var(--cardinal);margin:0 0 0.4rem;font-weight:700;">{{ $label }}</p>
                        <p style="margin:0;color:var(--text-primary);font-size:0.92rem;line-height:1.5;">{{ $value }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <p style="text-align:center;color:var(--text-muted);font-size:0.82rem;margin-top:1rem;">Association enregistrée en Préfecture.</p>
    </div>

    {{-- Citation de clôture --}}
    <blockquote style="text-align:center;font-family:var(--font-serif,Georgia,serif);font-style:italic;font-size:1.4rem;color:var(--cardinal);max-width:640px;margin:3rem auto 0;line-height:1.6;">
        « L'art est le reflet de l'âme d'un peuple. »
    </blockquote>

    {{-- CTA --}}
    <div style="text-align:center;padding:2.5rem 0 0;">
        <div class="cta-group" style="justify-content:center;">
            <a href="{{ route('ebooks.index') }}" class="btn-primary">Parcourir le catalogue</a>
            <a href="{{ route('contact') }}" class="btn-secondary">Nous contacter</a>
        </div>
    </div>

</div>

@endsection
