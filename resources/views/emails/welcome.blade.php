@extends('emails.layout')

@section('body')
<h1>Bienvenue, {{ $user->name }} !</h1>
<p>Nous sommes ravis de vous accueillir sur <strong>APACC-M e-Livre</strong>, la bibliothèque numérique de l'association APACC-M dédiée au patrimoine culturel et religieux martiniquais.</p>
<p>Vous pouvez dès maintenant parcourir notre catalogue et acquérir vos premières publications.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('ebooks.index') }}" class="btn">Découvrir le catalogue</a>
</div>

<div class="highlight-box">
    <p><strong>Comment ça fonctionne ?</strong><br>
    1. Choisissez un e-Livre dans le catalogue<br>
    2. Effectuez votre paiement via HelloAsso<br>
    3. Cliquez sur « J'ai effectué mon paiement »<br>
    4. L'accès est activé sous 12 à 24 h après vérification</p>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Des questions ? Contactez-nous via <a href="{{ route('contact') }}" style="color:#b91c1c;">notre formulaire de contact</a>.</p>
@endsection
