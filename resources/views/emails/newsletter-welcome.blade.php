@extends('emails.layout')

@section('body')
<h1>Inscription confirmée !</h1>
<p>Merci de vous être inscrit(e) à la newsletter d'<strong>APACC-M e-Livre</strong>. Vous serez informé(e) en avant-première de chaque nouvelle parution de notre bibliothèque numérique.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('ebooks.index') }}" class="btn">Découvrir le catalogue</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Pour vous désinscrire à tout moment, <a href="{{ route('newsletter.unsubscribe', ['email' => $email]) }}" style="color:#b91c1c;">cliquez ici</a>.</p>
@endsection
