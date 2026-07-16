@extends('layouts.app')

@section('title', 'Vérifiez votre email — APACC-M')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="space-y-1">
            <span class="letter-spacing-2">Activation du compte</span>
            <h2>Vérifiez votre adresse email</h2>
        </div>

        <p class="text-muted" style="font-size:0.9rem;line-height:1.6;">
            Un email d'activation vous a été envoyé. Cliquez sur le lien qu'il contient pour activer votre compte.
            Pensez à vérifier votre dossier <strong>courrier indésirable / spam</strong> si vous ne le trouvez pas.
        </p>

        <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
            <form method="POST" action="{{ route('verification.send') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-primary" style="justify-content:center;">Renvoyer l'email d'activation</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-ghost btn-sm">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>
@endsection
