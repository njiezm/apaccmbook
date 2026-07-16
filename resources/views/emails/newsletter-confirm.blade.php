@extends('emails.layout')

@section('body')
<h1>Confirmez votre inscription</h1>
<p>Vous avez demandé à recevoir la newsletter d'<strong>APACC-M e-Livre</strong>. Pour finaliser votre inscription, merci de confirmer votre adresse email en cliquant sur le bouton ci-dessous.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="{{ route('newsletter.confirm', ['token' => $token]) }}" class="btn">Confirmer mon inscription</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email : aucune inscription ne sera enregistrée.</p>
@endsection
