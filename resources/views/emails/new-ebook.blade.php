@extends('emails.layout')

@section('body')
<h1>📚 Nouvelle publication disponible !</h1>
<p>L'APACC-M vient de mettre en ligne une nouvelle publication :</p>

<div class="highlight-box">
    <p><strong style="font-size:16px;">{{ $ebook->title }}</strong></p>
    @if($ebook->description)
    <p style="margin-top:8px;">{{ Str::limit($ebook->description, 200) }}</p>
    @endif
    <p style="margin-top:8px;"><strong>Prix :</strong> {{ number_format($ebook->price, 2, ',', ' ') }} €</p>
</div>

<p>Ne manquez pas cette nouvelle ressource sur le patrimoine culturel et religieux martiniquais.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('ebooks.show', $ebook) }}" class="btn">Découvrir cette publication</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Vous recevez cet email car vous êtes abonné(e) aux actualités d'APACC-M e-Livre. Pour vous désabonner, <a href="{{ url('/newsletter/unsubscribe?email=' . urlencode(request()->email ?? '')) }}" style="color:#b91c1c;">cliquez ici</a>.</p>
@endsection
