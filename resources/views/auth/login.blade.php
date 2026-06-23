@extends('layouts.app')

@section('title', 'Connexion — APACC-M')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="space-y-1">
            <span class="letter-spacing-2">Connexion sécurisée</span>
            <h2>Accéder à ma bibliothèque</h2>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">Connectez-vous pour accéder à vos eBooks et suivre vos commandes.</p>
        </div>

        @if(session('status'))
            <div class="flash-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="links">
                <label class="links-checkbox" for="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Se souvenir de moi</span>
                </label>
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Connexion</button>
        </form>

        <p class="text-muted" style="text-align:center;font-size:0.9rem;margin:0;">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="font-weight:700;">Créer un compte</a>
        </p>
    </div>
</div>
@endsection
