@extends('layouts.app')

@section('title', 'Inscription — APACC-M')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="space-y-1">
            <span class="letter-spacing-2">Créer un compte</span>
            <h2>Rejoindre la bibliothèque</h2>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">Créez votre espace personnel pour accéder aux eBooks et suivre vos achats.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="name">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Prénom Nom">
                @error('name')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="votre@email.com">
                @error('email')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="8 caractères minimum">
                @error('password')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Créer mon compte</button>
        </form>

        <p class="text-muted" style="text-align:center;font-size:0.9rem;margin:0;">
            Déjà inscrit ?
            <a href="{{ route('login') }}" style="font-weight:700;">Se connecter</a>
        </p>
    </div>
</div>
@endsection
