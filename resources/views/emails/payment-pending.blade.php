@extends('emails.layout')

@section('body')
<h1>Paiement en cours de vérification</h1>
<p>Bonjour <strong>{{ $purchase->user->name }}</strong>,</p>
<p>Nous avons bien reçu votre déclaration de paiement pour la publication suivante :</p>

<div class="highlight-box">
    <p><strong>Publication :</strong> {{ $purchase->ebook->title }}</p>
    <p style="margin-top:6px;"><strong>Montant :</strong> {{ number_format($purchase->ebook->price, 2, ',', ' ') }} €</p>
    <p style="margin-top:6px;"><strong>Statut :</strong> En attente de vérification</p>
</div>

<p>Notre équipe va vérifier votre paiement sur HelloAsso et vous enverra un email de confirmation dès que votre accès sera activé. Ce processus prend généralement entre <strong>12 et 24 heures</strong>.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('ebooks.mine') }}" class="btn">Voir mes e-Livres</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Si vous n'avez pas effectué ce paiement ou si vous avez une question, contactez-nous via <a href="{{ route('contact') }}" style="color:#b91c1c;">notre formulaire</a>.</p>
@endsection
