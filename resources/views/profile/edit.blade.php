@extends('layouts.app')

@section('title', 'Mon profil — APACC-M e-Livre')

@section('content')
<div class="profile-shell">
    <div class="container-custom">

        {{-- Header --}}
        <div style="margin-bottom:2rem;">
            <span class="section-label">Mon espace</span>
            <h1 style="font-size:1.75rem;margin:0.25rem 0 0;">Mon profil</h1>
        </div>

        <div class="profile-grid">

            {{-- Carte gauche --}}
            <aside class="profile-card">
                <div class="profile-avatar-wrap">
                    <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                </div>
                <p class="profile-name">{{ $user->name }}</p>
                <p class="profile-email">{{ $user->email }}</p>

                @php
                    $purchaseCount  = $user->purchases()->where('payment_status', 'paid')->count();
                    $pendingCount   = $user->purchases()->where('payment_status', 'pending')->count();
                @endphp

                <div class="profile-stats">
                    <div class="profile-stat-item">
                        <strong>{{ $purchaseCount }}</strong>
                        <span>e-Livre(s)</span>
                    </div>
                    <div class="profile-stat-item">
                        <strong>{{ $pendingCount }}</strong>
                        <span>En attente</span>
                    </div>
                </div>

                <a href="{{ route('ebooks.mine') }}" class="btn-primary" style="width:100%;text-align:center;display:block;">
                    Ma bibliothèque
                </a>
                <a href="{{ route('ebooks.index') }}" class="btn-ghost" style="width:100%;text-align:center;display:block;margin-top:0.6rem;">
                    Parcourir le catalogue
                </a>

                @if($user->is_admin)
                <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border-light);">
                    <a href="{{ route('admin.dashboard') }}" style="display:flex;align-items:center;justify-content:center;gap:0.5rem;font-size:0.8rem;color:var(--cardinal);font-weight:700;text-decoration:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        Tableau de bord admin
                    </a>
                </div>
                @endif
            </aside>

            {{-- Sections droite --}}
            <div>

                @if(session('status') === 'profile-updated')
                    <div class="flash-success">Profil mis à jour avec succès.</div>
                @endif
                @if(session('status') === 'password-updated')
                    <div class="flash-success">Mot de passe mis à jour.</div>
                @endif

                {{-- Informations --}}
                <div class="profile-section">
                    <div class="profile-section-header">
                        <h3>Informations personnelles</h3>
                    </div>
                    <div class="profile-section-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="profile-field">
                                <label for="name">Nom complet</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="email">Adresse email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div style="display:flex;align-items:center;gap:1rem;margin-top:0.5rem;">
                                <button type="submit" class="btn-primary">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div class="profile-section">
                    <div class="profile-section-header">
                        <h3>Changer le mot de passe</h3>
                    </div>
                    <div class="profile-section-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="profile-field">
                                <label for="current_password">Mot de passe actuel</label>
                                <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                                @error('current_password', 'updatePassword')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="password">Nouveau mot de passe</label>
                                <input id="password" name="password" type="password" autocomplete="new-password">
                                @error('password', 'updatePassword')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div class="profile-field">
                                <label for="password_confirmation">Confirmer le mot de passe</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn-primary" style="margin-top:0.5rem;">Mettre à jour</button>
                        </form>
                    </div>
                </div>

                {{-- Suppression de compte --}}
                <div class="profile-danger-zone">
                    <div>
                        <p style="font-weight:700;margin-bottom:0.25rem;">Supprimer mon compte</p>
                        <p style="font-size:0.82rem;">Cette action est irréversible. Vos achats seront perdus.</p>
                    </div>
                    <button type="button" class="btn-ghost" style="border-color:var(--cardinal);color:var(--cardinal);white-space:nowrap;"
                        onclick="document.getElementById('delete-modal').style.display='flex'">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal suppression --}}
<div id="delete-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:var(--white);border-radius:var(--radius-lg);padding:2rem;width:min(440px,100%);border-top:4px solid var(--cardinal);">
        <h3 style="margin:0 0 0.75rem;color:var(--cardinal);">Supprimer mon compte</h3>
        <p style="font-size:0.9rem;color:var(--text-secondary);margin-bottom:1.5rem;">
            Confirmez votre mot de passe pour supprimer définitivement votre compte et toutes vos données.
        </p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="profile-field" style="margin-bottom:1rem;">
                <label for="del_password">Mot de passe</label>
                <input id="del_password" name="password" type="password" required autocomplete="current-password">
                @error('password', 'userDeletion')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn-primary" style="background:var(--cardinal);">Confirmer la suppression</button>
                <button type="button" class="btn-ghost" onclick="document.getElementById('delete-modal').style.display='none'">Annuler</button>
            </div>
        </form>
    </div>
</div>
@endsection
