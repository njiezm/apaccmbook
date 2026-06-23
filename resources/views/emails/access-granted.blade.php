@extends('emails.layout')

@section('body')
<h1 style="color:#16a34a;">✅ Votre accès est activé !</h1>
<p>Bonjour <strong>{{ $purchase->user->name }}</strong>,</p>
<p>Bonne nouvelle ! Votre paiement a été vérifié et votre accès à la publication suivante est maintenant actif :</p>

<div class="highlight-box">
    <p><strong>Publication :</strong> {{ $purchase->ebook->title }}</p>
    <p style="margin-top:6px;"><strong>Accès :</strong> Lecture en ligne sécurisée, disponible immédiatement</p>
</div>

<p>Vous pouvez dès maintenant lire votre e-Livre directement depuis notre plateforme. La lecture est sécurisée et disponible sur tous vos appareils.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('ebooks.read', $purchase->ebook) }}" class="btn">Lire maintenant</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Retrouvez tous vos e-Livres dans <a href="{{ route('ebooks.mine') }}" style="color:#b91c1c;">votre bibliothèque</a>. Merci de faire confiance à l'APACC-M !</p>
@endsection
