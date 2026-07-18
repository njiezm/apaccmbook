@extends('layouts.app')

@section('title', 'Administration — APACC-M e-Livre')

@section('styles')
<style>
.site-footer, .mobile-bottom-nav { display: none !important; }
body { padding-bottom: 0 !important; }
main > .container-custom:first-child { padding-top: 0 !important; }
</style>
@endsection

@section('content')
@php use App\Models\Purchase; @endphp

<div class="admin-page" x-data="{
    tab: 'catalog',
    showAdd: false,
    editEbook: null,
    editAction: '',
    deleteUser: null,
    deleteUserAction: '',
    adminMenu: false,
    openEdit(p) { this.editEbook = p; this.editAction = p.update_url; },
    openDeleteUser(u) { this.deleteUser = u; this.deleteUserAction = u.destroy_url; },
    closeModals() { this.showAdd = false; this.editEbook = null; this.editAction = ''; this.deleteUser = null; this.deleteUserAction = ''; }
}">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="admin-sidebar">
        <div class="admin-sidebar-title">Tableau de bord</div>

        <nav class="admin-sidebar-nav">
            <button class="admin-sidebar-item" :class="{ active: tab === 'catalog' }" @click="tab = 'catalog'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Catalogue
                <span class="admin-sidebar-badge">{{ $ebooks->count() }}</span>
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'validation' }" @click="tab = 'validation'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Validations
                @php $pendingCount = $purchases->where('payment_status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="admin-sidebar-badge">{{ $pendingCount }}</span>
                @endif
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'accounts' }" @click="tab = 'accounts'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Comptes
                <span class="admin-sidebar-badge" style="background:var(--text-muted);">{{ $users->count() }}</span>
            </button>

            <div class="admin-sidebar-divider"></div>

            <button class="admin-sidebar-item" :class="{ active: tab === 'payment_cfg' }" @click="tab = 'payment_cfg'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Paiements
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'coupons' }" @click="tab = 'coupons'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12a2 2 0 0 1 2-2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3a2 2 0 0 1-2-2z"/><line x1="13" y1="5" x2="13" y2="19"/></svg>
                Coupons
                <span class="admin-sidebar-badge" style="background:var(--text-muted);">{{ $coupons->count() }}</span>
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'subscribers' }" @click="tab = 'subscribers'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                Abonnés
                <span class="admin-sidebar-badge" style="background:var(--text-muted);">{{ $subscribers->count() }}</span>
            </button>

            <div class="admin-sidebar-divider"></div>

            <a href="{{ route('admin.kit-communication') }}" target="_blank" rel="noopener" class="admin-sidebar-item" style="text-decoration:none;">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                Kit de communication
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto;opacity:.5;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
        </nav>

        <div style="margin-top:auto;">
            <div class="admin-sidebar-divider"></div>
            <div style="padding:0.75rem 1.25rem;">
                <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:0.6rem;font-size:0.8rem;color:var(--text-muted);text-decoration:none;padding:0.5rem 0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Retour au site
                </a>
            </div>
        </div>
    </aside>

    {{-- ═══ CONTENU ═══ --}}
    <div class="admin-content">

        @if(session('status'))
            <div class="flash-success" style="margin-bottom:1.75rem;">{{ session('status') }}</div>
        @endif

        {{-- Stats --}}
        <div class="admin-stats-grid">
            <div class="admin-stat">
                <div class="admin-stat-icon red"><i class="fa-solid fa-book"></i></div>
                <div class="admin-stat-body">
                    <span class="stat-label">Publications</span>
                    <span class="stat-value">{{ $stats['total_ebooks'] ?? 0 }}</span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon blue"><i class="fa-solid fa-users"></i></div>
                <div class="admin-stat-body">
                    <span class="stat-label">Membres</span>
                    <span class="stat-value">{{ $stats['total_users'] ?? 0 }}</span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
                <div class="admin-stat-body">
                    <span class="stat-label">Ventes</span>
                    <span class="stat-value">{{ $stats['total_sales'] ?? 0 }}</span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon amber"><i class="fa-solid fa-coins"></i></div>
                <div class="admin-stat-body">
                    <span class="stat-label">Revenues (théorique)</span>
                    <span class="stat-value" style="font-size:1.55rem;">{{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} €</span>
                </div>
            </div>
        </div>

        {{-- ══ CATALOGUE ══ --}}
        <div x-show="tab === 'catalog'" x-cloak>
            <div class="admin-section-header">
                <div>
                    <h2>Catalogue</h2>
                    <p>Gérez les publications disponibles sur la plateforme.</p>
                </div>
                <button type="button" class="btn-primary" @click="showAdd = true">+ Ajouter un e-Livre</button>
            </div>

            <div class="admin-ebook-grid">
                @forelse($ebooks as $ebook)
                    @php
                        $p = json_encode([
                            'id'            => $ebook->id,
                            'title'         => $ebook->title,
                            'price'         => number_format($ebook->price, 2, '.', ''),
                            'is_free'       => (bool) $ebook->is_free,
                            'category_id'   => $ebook->category_id,
                            'helloasso_url' => $ebook->helloasso_url,
                            'sumup_url'     => $ebook->sumup_url,
                            'description'   => $ebook->description,
                            'is_transandans' => (bool) $ebook->is_transandans,
                            'status'         => $ebook->status,
                            'published_date' => $ebook->published_date?->format('Y-m-d'),
                            'sommaire_items' => $ebook->sommaireEntries()
                                ->map(fn ($i) => ['title' => $i['title'], 'subtitle' => $i['subtitle'], 'page' => $i['page'] ?? ''])
                                ->values(),
                            'update_url'    => route('admin.ebooks.update', $ebook),
                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
                    @endphp
                    <article class="admin-ebook-card">
                        <div class="admin-ebook-cover">
                            @if($ebook->cover_image)
                                <img src="{{ $ebook->thumbUrl() }}" alt="Couverture — {{ $ebook->title }}" loading="lazy" decoding="async">
                            @else
                                <div class="admin-ebook-cover-placeholder">📖</div>
                            @endif
                        </div>
                        <div class="admin-ebook-body">
                            @php
                                $state = $ebook->publicationState();
                                $stateColors = ['publié' => '#166534', 'brouillon' => '#92600a', 'programmé' => '#1e40af', 'archivé' => '#6b7280'];
                                $stateBg     = ['publié' => '#dcfce7', 'brouillon' => '#fef3c7', 'programmé' => '#dbeafe', 'archivé' => '#f3f4f6'];
                            @endphp
                            <span style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.03em;padding:0.15rem 0.5rem;border-radius:99px;color:{{ $stateColors[$state] }};background:{{ $stateBg[$state] }};margin-bottom:0.35rem;">
                                {{ $state }}@if($state === 'programmé') · {{ $ebook->published_date?->format('d/m/Y') }}@endif
                            </span>
                            <p class="admin-ebook-title">{{ $ebook->title }}</p>
                            <p class="admin-ebook-desc">{{ Str::limit($ebook->description, 75) }}</p>
                            <div class="admin-ebook-footer">
                                @if($ebook->is_free)
                                    <span class="admin-ebook-price" style="color:var(--cardinal);">Gratuit</span>
                                @else
                                    <span class="admin-ebook-price">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                                @endif
                                <div style="display:flex;gap:0.4rem;">
                                    <button type="button" class="btn-ghost btn-xs" @click="openEdit({{ $p }})">Modifier</button>
                                    <form method="POST" action="{{ route('admin.ebooks.destroy', $ebook) }}" onsubmit="return confirm('Supprimer ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-ghost btn-xs" style="color:var(--cardinal);border-color:var(--cardinal);">Suppr.</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="admin-empty">
                        <span style="font-size:3rem;display:block;margin-bottom:0.75rem;">📚</span>
                        <p>Aucun e-Livre publié. Cliquez sur « Ajouter » pour commencer.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ══ VALIDATION ══ --}}
        <div x-show="tab === 'validation'" x-cloak>
            <div class="admin-section-header">
                <div>
                    <h2>Validations des paiements</h2>
                    <p>Vérifiez chaque paiement sur HelloAsso avant de valider l'accès.</p>
                </div>
            </div>
            <div class="admin-box">
                <div class="admin-box-header">
                    <h3>Toutes les commandes</h3>
                    <span style="font-size:0.82rem;color:var(--text-muted);">{{ $purchases->count() }} commande(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Date</th><th>Acheteur</th><th>Publication</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                            <tr>
                                <td style="font-size:0.82rem;color:var(--text-muted);white-space:nowrap;">
                                    {{ $purchase->created_at->format('d/m/Y') }}<br>
                                    <span style="font-size:0.72rem;">{{ $purchase->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <strong style="font-size:0.88rem;">{{ $purchase->user->name }}</strong><br>
                                    <span style="font-size:0.76rem;color:var(--text-muted);">{{ $purchase->user->email }}</span>
                                </td>
                                <td style="font-size:0.88rem;max-width:160px;">{{ Str::limit($purchase->ebook->title, 35) }}</td>
                                <td><strong style="color:var(--cardinal);">{{ number_format($purchase->ebook->price, 2, ',', ' ') }} €</strong></td>
                                <td>
                                    <span class="status-pill {{ $purchase->payment_status }}">
                                        {{ $purchase->payment_status === 'paid' ? 'Validé' : 'En attente' }}
                                    </span>
                                </td>
                                <td>
                                    @if($purchase->payment_status === Purchase::STATUS_PENDING)
                                        <form method="POST" action="{{ route('purchases.status.update', $purchase) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-primary btn-xs">Valider</button>
                                        </form>
                                    @else
                                        <span style="color:#16a34a;font-size:0.82rem;font-weight:700;">✓ Actif</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted);">Aucune commande.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ COMPTES ══ --}}
        <div x-show="tab === 'accounts'" x-cloak>
            <div class="admin-section-header">
                <div><h2>Comptes membres</h2><p>Gérez les rôles et permissions.</p></div>
            </div>
            <div class="admin-box">
                <div class="admin-box-header">
                    <h3>Tous les membres</h3>
                    <span style="font-size:0.82rem;color:var(--text-muted);">{{ $users->count() }} membre(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Membre</th><th>Email</th><th>Rôle</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.65rem;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:{{ $user->is_admin ? 'var(--cardinal)' : 'var(--cream)' }};display:flex;align-items:center;justify-content:center;font-weight:700;color:{{ $user->is_admin ? 'white' : 'var(--text-secondary)' }};flex-shrink:0;border:1px solid var(--border-light);">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        <strong style="font-size:0.9rem;">{{ $user->name }}</strong>
                                    </div>
                                </td>
                                <td style="font-size:0.85rem;color:var(--text-secondary);">
                                    {{ $user->email }}
                                    @if($user->email_verified_at)
                                        <span title="Email vérifié le {{ $user->email_verified_at->format('d/m/Y') }}" style="display:inline-flex;align-items:center;gap:0.25rem;margin-top:0.25rem;font-size:0.72rem;font-weight:600;color:#166534;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                            Email vérifié
                                        </span>
                                    @else
                                        <span title="Cet utilisateur ne peut pas lire tant que son email n'est pas vérifié" style="display:inline-flex;align-items:center;gap:0.25rem;margin-top:0.25rem;font-size:0.72rem;font-weight:600;color:var(--cardinal);">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                            Email NON vérifié
                                        </span>
                                    @endif
                                </td>
                                <td><span class="badge {{ $user->is_admin ? 'admin' : 'user' }}">{{ $user->is_admin ? 'Admin' : 'Membre' }}</span></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.4rem;flex-wrap:wrap;">
                                        @unless($user->email_verified_at)
                                            <form method="POST" action="{{ route('admin.users.verify-email', $user) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-primary btn-xs" title="Débloquer l'accès à la lecture en validant l'email">✓ Valider l'email</button>
                                            </form>
                                        @endunless
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-ghost btn-xs">{{ $user->is_admin ? '↓ Retirer Admin' : '↑ Nommer Admin' }}</button>
                                        </form>
                                        @if($user->id !== auth()->id())
                                            <button type="button" class="btn-ghost btn-xs" style="color:var(--cardinal);border-color:var(--cardinal);padding:0.3rem 0.45rem;"
                                                    title="Supprimer le compte"
                                                    @click="openDeleteUser({{ json_encode(['name' => $user->name, 'email' => $user->email, 'destroy_url' => route('admin.users.destroy', $user)], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG) }})">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ COUPONS ══ --}}
        <div x-show="tab === 'coupons'" x-cloak>
            <div class="admin-section-header">
                <div><h2>Coupons de réduction</h2><p>Créez des codes promo ciblés (par livre) ou globaux.</p></div>
            </div>

            <div class="admin-box" style="margin-bottom:1.5rem;">
                <div class="admin-box-header">
                    <h3><i class="fa-solid fa-ticket" style="color:var(--cardinal);margin-right:0.5rem;"></i>Nouveau coupon</h3>
                </div>
                <form method="POST" action="{{ route('admin.coupons.store') }}"
                      class="coupon-form"
                      x-data="{ type: 'percent', value: '', code: '' }">
                    @csrf

                    {{-- ── Réduction ── --}}
                    <p class="coupon-form-legend">Réduction</p>
                    <div class="admin-form-row coupon-form-row-3">
                        <div class="admin-field">
                            <label>Code <span class="req">*</span></label>
                            <input name="code" type="text" required placeholder="Ex. NOEL2026"
                                   maxlength="50" autocomplete="off"
                                   class="coupon-code-input"
                                   x-model="code"
                                   @input="code = code.toUpperCase()">
                            <span class="admin-hint">Ce que le client saisit au moment du paiement.</span>
                        </div>
                        <div class="admin-field">
                            <label>Type <span class="req">*</span></label>
                            <select name="discount_type" required x-model="type">
                                <option value="percent">Pourcentage (%)</option>
                                <option value="amount">Montant fixe (€)</option>
                            </select>
                        </div>
                        <div class="admin-field">
                            <label>Valeur <span class="req">*</span></label>
                            <div class="coupon-value-group">
                                <input name="discount_value" type="number" step="0.01" min="0" required
                                       placeholder="20" x-model="value">
                                <span class="coupon-value-suffix" x-text="type === 'percent' ? '%' : '€'">%</span>
                            </div>
                            <span class="admin-hint"
                                  x-text="value ? ('Prix réduit ' + (type === 'percent' ? 'de ' + value + ' %' : 'de ' + value + ' €')) : 'Montant de la remise appliquée.'"></span>
                        </div>
                    </div>

                    {{-- ── Ciblage & limites ── --}}
                    <p class="coupon-form-legend">Ciblage &amp; limites</p>
                    <div class="admin-form-row">
                        <div class="admin-field">
                            <label>Ouvrage concerné</label>
                            <select name="ebook_id">
                                <option value="">🌐 Tous les ouvrages (global)</option>
                                @foreach($ebooks as $eb)
                                    <option value="{{ $eb->id }}">{{ $eb->title }}</option>
                                @endforeach
                            </select>
                            <span class="admin-hint">Laisser sur « global » pour appliquer à tout le catalogue.</span>
                        </div>
                        <div class="admin-field">
                            <label>Limite d'usage</label>
                            <input name="usage_limit" type="number" min="1" placeholder="Illimité si vide">
                            <span class="admin-hint">Nombre maximum d'utilisations, tous clients confondus.</span>
                        </div>
                    </div>

                    {{-- ── Période de validité ── --}}
                    <p class="coupon-form-legend">Période de validité <span style="font-weight:400;text-transform:none;letter-spacing:0;color:var(--text-muted);">— optionnel</span></p>
                    <div class="admin-form-row">
                        <div class="admin-field"><label>Valide du</label><input name="valid_from" type="date"></div>
                        <div class="admin-field"><label>Valide jusqu'au</label><input name="valid_until" type="date"></div>
                    </div>

                    <div class="coupon-form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-plus" style="margin-right:0.4rem;"></i>Créer le coupon
                        </button>
                    </div>
                </form>
            </div>

            <div class="admin-box">
                <div class="admin-box-header"><h3>Coupons existants</h3><span style="font-size:0.82rem;color:var(--text-muted);">{{ $coupons->count() }}</span></div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Code</th><th>Réduction</th><th>Cible</th><th>Validité</th><th>Usage</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>{{ $coupon->discount_percent ? $coupon->discount_percent.' %' : number_format($coupon->discount_amount, 2, ',', ' ').' €' }}</td>
                                    <td style="font-size:0.85rem;">{{ $coupon->ebook?->title ?? 'Global' }}</td>
                                    <td style="font-size:0.82rem;color:var(--text-secondary);">
                                        {{ optional($coupon->valid_from)->format('d/m/y') }}
                                        @if($coupon->valid_until) → {{ $coupon->valid_until->format('d/m/y') }} @endif
                                    </td>
                                    <td style="font-size:0.85rem;">{{ $coupon->used_count }}{{ $coupon->usage_limit ? '/'.$coupon->usage_limit : '' }}</td>
                                    <td><span class="status-pill {{ $coupon->is_active ? 'paid' : 'pending' }}">{{ $coupon->is_active ? 'Actif' : 'Inactif' }}</span></td>
                                    <td>
                                        <div style="display:flex;gap:0.4rem;">
                                            <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">@csrf @method('PATCH')<button class="btn-ghost btn-xs" type="submit">{{ $coupon->is_active ? 'Désactiver' : 'Activer' }}</button></form>
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Supprimer ce coupon ?')">@csrf @method('DELETE')<button class="btn-ghost btn-xs" type="submit" style="color:var(--cardinal);border-color:var(--cardinal);">Suppr.</button></form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Aucun coupon créé.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ ABONNÉS NEWSLETTER ══ --}}
        <div x-show="tab === 'subscribers'" x-cloak>
            @php
                $activeSubs  = $subscribers->where('is_active', true);
                $pendingSubs = $subscribers->where('is_active', false);
            @endphp

            <div class="admin-section-header">
                <div>
                    <h2>Abonnés à la newsletter</h2>
                    <p>Les personnes ayant confirmé leur inscription reçoivent les notifications de publication.</p>
                </div>
                @if($subscribers->isNotEmpty())
                    <a href="{{ route('admin.subscribers.export') }}" class="btn-secondary" style="display:inline-flex;align-items:center;gap:0.45rem;">
                        <i class="fa-solid fa-file-csv"></i> Exporter (CSV)
                    </a>
                @endif
            </div>

            {{-- Petites stats --}}
            <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
                <div class="admin-box" style="flex:1;min-width:150px;margin:0;padding:1rem 1.25rem;">
                    <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);font-weight:700;">Confirmés</div>
                    <div style="font-size:1.6rem;font-weight:800;color:var(--cardinal);">{{ $activeSubs->count() }}</div>
                </div>
                <div class="admin-box" style="flex:1;min-width:150px;margin:0;padding:1rem 1.25rem;">
                    <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);font-weight:700;">En attente</div>
                    <div style="font-size:1.6rem;font-weight:800;color:var(--text-secondary);">{{ $pendingSubs->count() }}</div>
                </div>
                <div class="admin-box" style="flex:1;min-width:150px;margin:0;padding:1rem 1.25rem;">
                    <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);font-weight:700;">Total</div>
                    <div style="font-size:1.6rem;font-weight:800;color:var(--text-primary);">{{ $subscribers->count() }}</div>
                </div>
            </div>

            <div class="admin-box">
                <div class="admin-box-header">
                    <h3><i class="fa-solid fa-envelope" style="color:var(--cardinal);margin-right:0.5rem;"></i>Liste des abonnés</h3>
                    <span style="font-size:0.82rem;color:var(--text-muted);">{{ $subscribers->count() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Email</th><th>Statut</th><th>Inscrit le</th><th style="text-align:right;">Action</th></tr></thead>
                        <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr>
                                    <td><strong>{{ $subscriber->email }}</strong></td>
                                    <td>
                                        <span class="status-pill {{ $subscriber->is_active ? 'paid' : 'pending' }}">
                                            {{ $subscriber->is_active ? 'Confirmé' : 'En attente' }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.85rem;color:var(--text-secondary);">{{ optional($subscriber->created_at)->format('d/m/Y') }}</td>
                                    <td style="text-align:right;">
                                        <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('Supprimer cet abonné ?')" style="margin:0;">
                                            @csrf @method('DELETE')
                                            <button class="btn-ghost btn-xs" type="submit" style="color:var(--cardinal);border-color:var(--cardinal);">Suppr.</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Aucun abonné pour le moment.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ══ PAIEMENTS CONFIG ══ --}}
        <div x-show="tab === 'payment_cfg'" x-cloak>
            <div class="admin-section-header">
                <div><h2>Méthodes de paiement</h2><p>Activez les moyens acceptés et configurez leurs paramètres.</p></div>
            </div>

            @php
                $enabled = $paymentSettings['enabled_methods'] ?? ['helloasso'];
                $ps      = $paymentSettings;
            @endphp

            <form method="POST" action="{{ route('admin.settings.save') }}">
                @csrf
                <div style="display:flex;flex-direction:column;gap:0.75rem;">

                    @foreach([
                        ['helloasso', '#f47930', 'H', 'HelloAsso', 'Solution associative · 0 % de frais'],
                        ['stripe',    '#635bff', 'S', 'Stripe',    'Carte · Apple Pay · Google Pay'],
                        ['paypal',    '#003087', 'P', 'PayPal',    'Compte PayPal · Carte bancaire'],
                        ['sumup',     '#1a1a2e', 'S', 'SumUp',     'Terminal mobile · TPE'],
                        ['virement',  '#b91c1c', '€', 'Virement bancaire', 'SEPA · Gratuit'],
                        ['cheque',    '#6b7280', '✉', 'Chèque',    'Courrier postal'],
                    ] as [$id, $color, $letter, $name, $desc])
                    <div class="pm-card{{ in_array($id, $enabled) ? ' pm-card--active' : '' }}" id="pm-{{ $id }}">
                        <div class="pm-header">
                            <label class="pm-toggle">
                                <input type="checkbox" name="enabled_methods[]" value="{{ $id }}"
                                       {{ in_array($id, $enabled) ? 'checked' : '' }}
                                       onchange="togglePm('{{ $id }}', this.checked)">
                                <span class="pm-logo" style="background:{{ $color }};">{{ $letter }}</span>
                                <div class="pm-info">
                                    <span class="pm-name">{{ $name }}</span>
                                    <span class="pm-desc">{{ $desc }}</span>
                                </div>
                                <span class="pm-status-badge{{ in_array($id, $enabled) ? ' active' : '' }}" id="st-{{ $id }}">
                                    {{ in_array($id, $enabled) ? 'Actif' : 'Inactif' }}
                                </span>
                            </label>
                        </div>
                        <div class="pm-config" id="cfg-{{ $id }}" style="{{ !in_array($id, $enabled) ? 'display:none' : '' }}">
                            @if($id === 'helloasso')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>URL de collecte</label><input type="url" name="helloasso_url" placeholder="https://www.helloasso.com/…" value="{{ $ps['helloasso_url'] ?? '' }}"></div>
                                    <div class="admin-field"><label>Nom organisation</label><input type="text" name="helloasso_org" placeholder="apacc-m" value="{{ $ps['helloasso_org'] ?? '' }}"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook <small style="color:var(--text-muted);font-weight:400;">(optionnel — pour auto-validation)</small></label>
                                    <input type="password" name="helloasso_api_secret" placeholder="Clé secrète de notification HelloAsso" value="{{ $ps['helloasso_api_secret'] ?? '' }}" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur HelloAsso : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;">{{ url('/webhook/helloasso') }}</code></p>
                                </div>
                            @elseif($id === 'stripe')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Clé publique</label><input type="text" name="stripe_publishable_key" placeholder="pk_live_…" value="{{ $ps['stripe_publishable_key'] ?? '' }}"></div>
                                    <div class="admin-field"><label>Clé secrète</label><input type="password" name="stripe_secret_key" placeholder="sk_live_…" value="{{ $ps['stripe_secret_key'] ?? '' }}" autocomplete="off"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook</label>
                                    <input type="password" name="stripe_webhook_secret" placeholder="whsec_…" value="{{ $ps['stripe_webhook_secret'] ?? '' }}" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur Stripe : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;">{{ url('/webhook/stripe') }}</code></p>
                                </div>
                            @elseif($id === 'paypal')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Client ID</label><input type="text" name="paypal_client_id" placeholder="AXxx…" value="{{ $ps['paypal_client_id'] ?? '' }}"></div>
                                    <div class="admin-field"><label>Client Secret</label><input type="password" name="paypal_client_secret" placeholder="EXxx…" value="{{ $ps['paypal_client_secret'] ?? '' }}" autocomplete="off"></div>
                                </div>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Mode</label><select name="paypal_mode"><option value="sandbox" {{ ($ps['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (test)</option><option value="live" {{ ($ps['paypal_mode'] ?? '') === 'live' ? 'selected' : '' }}>Production</option></select></div>
                                    <div class="admin-field"><label>Webhook ID <small style="color:var(--text-muted);font-weight:400;">(optionnel)</small></label><input type="text" name="paypal_webhook_id" placeholder="ID du webhook PayPal" value="{{ $ps['paypal_webhook_id'] ?? '' }}"></div>
                                </div>
                                <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur PayPal Developer : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;">{{ url('/webhook/paypal') }}</code></p>
                            @elseif($id === 'sumup')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Clé API</label><input type="password" name="sumup_api_key" placeholder="sup_sk_…" value="{{ $ps['sumup_api_key'] ?? '' }}" autocomplete="off"></div>
                                    <div class="admin-field"><label>Code marchand</label><input type="text" name="sumup_merchant_code" placeholder="MC_XXXXXXXX" value="{{ $ps['sumup_merchant_code'] ?? '' }}"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook <small style="color:var(--text-muted);font-weight:400;">(optionnel)</small></label>
                                    <input type="password" name="sumup_webhook_secret" placeholder="Secret HMAC SumUp" value="{{ $ps['sumup_webhook_secret'] ?? '' }}" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur SumUp : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;">{{ url('/webhook/sumup') }}</code></p>
                                </div>
                            @elseif($id === 'virement')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>IBAN</label><input type="text" name="virement_iban" placeholder="FR76 XXXX…" value="{{ $ps['virement_iban'] ?? '' }}"></div>
                                    <div class="admin-field"><label>BIC / SWIFT</label><input type="text" name="virement_bic" placeholder="XXXXXXXX" value="{{ $ps['virement_bic'] ?? '' }}"></div>
                                </div>
                                <div class="admin-field"><label>Titulaire</label><input type="text" name="virement_titulaire" placeholder="APACC-M" value="{{ $ps['virement_titulaire'] ?? '' }}"></div>
                            @elseif($id === 'cheque')
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>À l'ordre de</label><input type="text" name="cheque_ordre" placeholder="APACC-M" value="{{ $ps['cheque_ordre'] ?? '' }}"></div>
                                    <div class="admin-field"><label>Adresse postale</label><textarea name="cheque_adresse" rows="2" placeholder="Adresse…">{{ $ps['cheque_adresse'] ?? '' }}</textarea></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <span style="font-size:0.8rem;color:var(--text-muted);">Stocké localement sur le serveur.</span>
                </div>
            </form>
        </div>

    </div>{{-- /admin-content --}}

    {{-- MODAL AJOUT --}}
    <div x-show="showAdd" class="modal" x-cloak @click.self="closeModals()">
        <div class="modal-panel">
            <div class="modal-header">
                <h3>Ajouter un e-Livre</h3>
                <button type="button" class="btn-close" @click="closeModals()">×</button>
            </div>
            <form method="POST" action="{{ route('admin.ebooks.store') }}" enctype="multipart/form-data" x-data="{ free: false, dragFrom: null, sommaire: [{title:'',subtitle:'',page:''}] }">
                @csrf
                <div class="admin-field"><label>Titre *</label><input name="title" type="text" required placeholder="Titre de la publication"></div>
                <div class="admin-field"><label>Résumé *</label><textarea name="description" rows="3" required placeholder="Description…"></textarea></div>
                <div class="admin-field">
                    <label>Sommaire <small style="color:var(--text-muted);font-weight:400;">(optionnel — un titre, un sous-titre facultatif et le n° de page pour chaque entrée)</small></label>
                    @include('admin.partials.sommaire-fields')
                </div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="add_is_transandans" name="is_transandans" type="checkbox" value="1" style="width:auto;margin:0;">
                    <label for="add_is_transandans" style="margin:0;cursor:pointer;">Numéro de la <strong>Revue Transandans</strong> <small style="color:var(--text-muted);font-weight:400;">(ajoute le filtre dans le catalogue)</small></label>
                </div>
                <div class="admin-form-row">
                    <div class="admin-field">
                        <label>Statut</label>
                        <select name="status">
                            <option value="published">Publié</option>
                            <option value="draft">Brouillon</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                    <div class="admin-field">
                        <label>Date de publication <small style="color:var(--text-muted);font-weight:400;">(vide = immédiat ; date future = programmé)</small></label>
                        <input type="date" name="published_date">
                    </div>
                </div>
                <div class="admin-field">
                    <label>Thème <small style="color:var(--text-muted);font-weight:400;">(sert de filtre au catalogue)</small></label>
                    <select name="category_id">
                        <option value="">— Choisir un thème —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="add_is_free" name="is_free" type="checkbox" value="1" x-model="free" style="width:auto;margin:0;">
                    <label for="add_is_free" style="margin:0;cursor:pointer;">Livre gratuit (aucun paiement requis)</label>
                </div>
                <div class="admin-form-row" x-show="!free" x-cloak>
                    <div class="admin-field"><label>Prix (€) *</label><input name="price" type="number" step="0.01" min="0" :required="!free" placeholder="0.00"></div>
                    <div class="admin-field"><label>Lien HelloAsso</label><input name="helloasso_url" type="url" placeholder="https://…"></div>
                </div>
                <div class="admin-field" x-show="!free" x-cloak>
                    <label>Lien de paiement SumUp <small style="color:var(--text-muted);font-weight:400;">(repli si l'API SumUp n'est pas configurée — ouvre le paiement carte)</small></label>
                    <input name="sumup_url" type="url" placeholder="https://pay.sumup.com/b2c/…">
                </div>
                <div class="admin-form-row">
                    <div class="admin-field"><label>Fichier PDF *</label><input name="pdf" type="file" accept="application/pdf" required></div>
                    <div class="admin-field"><label>Couverture</label><input name="cover" type="file" accept="image/*"></div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;margin-top:0.5rem;">Publier l'e-Livre</button>
            </form>
        </div>
    </div>

    {{-- MODAL ÉDITION --}}
    <div x-show="editEbook" class="modal" x-cloak @click.self="closeModals()">
        <div class="modal-panel">
            <div class="modal-header">
                <h3 x-text="editEbook ? 'Modifier — ' + editEbook.title : 'Modifier'">Modifier</h3>
                <button type="button" class="btn-close" @click="closeModals()">×</button>
            </div>
            <form :action="editAction" method="POST" enctype="multipart/form-data" x-data="{ free: false, editCategory: '', dragFrom: null, sommaire: [{title:'',subtitle:'',page:''}] }" x-effect="free = !!(editEbook && editEbook.is_free); editCategory = editEbook && editEbook.category_id ? String(editEbook.category_id) : ''; sommaire = (editEbook && editEbook.sommaire_items && editEbook.sommaire_items.length) ? JSON.parse(JSON.stringify(editEbook.sommaire_items)) : [{title:'',subtitle:'',page:''}]">
                @csrf @method('PATCH')
                <div class="admin-field"><label>Titre *</label><input name="title" type="text" :value="editEbook ? editEbook.title : ''" required></div>
                <div class="admin-field"><label>Résumé *</label><textarea name="description" rows="3" required x-effect="$el.value = editEbook ? editEbook.description : ''"></textarea></div>
                <div class="admin-field">
                    <label>Sommaire <small style="color:var(--text-muted);font-weight:400;">(un titre, un sous-titre facultatif et le n° de page pour chaque entrée)</small></label>
                    @include('admin.partials.sommaire-fields')
                </div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="edit_is_transandans" name="is_transandans" type="checkbox" value="1" style="width:auto;margin:0;" :checked="editEbook && editEbook.is_transandans">
                    <label for="edit_is_transandans" style="margin:0;cursor:pointer;">Numéro de la <strong>Revue Transandans</strong></label>
                </div>
                <div class="admin-form-row">
                    <div class="admin-field">
                        <label>Statut</label>
                        <select name="status" x-effect="$el.value = editEbook ? (editEbook.status || 'published') : 'published'">
                            <option value="published">Publié</option>
                            <option value="draft">Brouillon</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                    <div class="admin-field">
                        <label>Date de publication <small style="color:var(--text-muted);font-weight:400;">(vide = immédiat ; date future = programmé)</small></label>
                        <input type="date" name="published_date" x-effect="$el.value = editEbook ? (editEbook.published_date || '') : ''">
                    </div>
                </div>
                <div class="admin-field">
                    <label>Thème <small style="color:var(--text-muted);font-weight:400;">(sert de filtre au catalogue)</small></label>
                    <select name="category_id" x-model="editCategory">
                        <option value="">— Choisir un thème —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="edit_is_free" name="is_free" type="checkbox" value="1" x-model="free" style="width:auto;margin:0;">
                    <label for="edit_is_free" style="margin:0;cursor:pointer;">Livre gratuit (aucun paiement requis)</label>
                </div>
                <div class="admin-form-row" x-show="!free" x-cloak>
                    <div class="admin-field"><label>Prix (€)</label><input name="price" type="number" step="0.01" min="0" :value="editEbook ? editEbook.price : ''" :required="!free"></div>
                    <div class="admin-field"><label>Lien HelloAsso</label><input name="helloasso_url" type="url" :value="editEbook ? editEbook.helloasso_url : ''"></div>
                </div>
                <div class="admin-field" x-show="!free" x-cloak>
                    <label>Lien de paiement SumUp <small style="color:var(--text-muted);font-weight:400;">(repli si l'API SumUp n'est pas configurée — ouvre le paiement carte)</small></label>
                    <input name="sumup_url" type="url" placeholder="https://pay.sumup.com/b2c/…" :value="editEbook ? (editEbook.sumup_url || '') : ''">
                </div>
                <div class="admin-form-row">
                    <div class="admin-field"><label>Nouveau PDF</label><input name="pdf" type="file" accept="application/pdf"></div>
                    <div class="admin-field"><label>Nouvelle couverture</label><input name="cover" type="file" accept="image/*"></div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;margin-top:0.5rem;">Enregistrer</button>
            </form>
        </div>
    </div>

    {{-- MODAL SUPPRESSION DE COMPTE --}}
    <div x-show="deleteUser" class="modal" x-cloak @click.self="closeModals()">
        <div class="modal-panel" style="max-width:440px;">
            <div class="modal-header">
                <h3>Supprimer ce compte ?</h3>
                <button type="button" class="btn-close" @click="closeModals()">×</button>
            </div>
            <p class="text-muted" style="font-size:0.9rem;line-height:1.6;">
                Vous êtes sur le point de supprimer définitivement le compte de
                <strong x-text="deleteUser ? deleteUser.name : ''"></strong>
                (<span x-text="deleteUser ? deleteUser.email : ''"></span>).
                Cette action est <strong>irréversible</strong>.
            </p>
            <div style="display:flex;gap:0.6rem;justify-content:flex-end;margin-top:1rem;">
                <button type="button" class="btn-ghost" @click="closeModals()">Annuler</button>
                <form :action="deleteUserAction" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-primary" style="background:var(--cardinal);border-color:var(--cardinal);">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ FAB MOBILE (navigation admin) ═══ --}}
    <button class="admin-fab" :class="{ 'admin-fab--open': adminMenu }"
            @click="adminMenu = true"
            aria-label="Navigation administration"
            :aria-expanded="adminMenu.toString()">
        <svg x-show="!adminMenu" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
        <svg x-cloak x-show="adminMenu" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
    </button>

    {{-- ═══ OVERLAY ═══ --}}
    <div class="admin-sheet-overlay" :class="{ open: adminMenu }" @click="adminMenu = false" aria-hidden="true"></div>

    {{-- ═══ BOTTOM SHEET ═══ --}}
    <div class="admin-sheet" :class="{ open: adminMenu }" role="dialog" aria-modal="true" aria-label="Navigation administration">
        <div class="admin-sheet-handle"></div>
        <div class="admin-sheet-title">Administration</div>
        <nav class="admin-sheet-nav">

            <button class="admin-sheet-item" :class="{ active: tab === 'catalog' }"
                    @click="tab = 'catalog'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Catalogue
                <span class="admin-sidebar-badge" style="margin-left:auto;">{{ $ebooks->count() }}</span>
            </button>

            <button class="admin-sheet-item" :class="{ active: tab === 'validation' }"
                    @click="tab = 'validation'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Validations
                @if($pendingCount > 0)
                    <span class="admin-sidebar-badge" style="margin-left:auto;">{{ $pendingCount }}</span>
                @endif
            </button>

            <button class="admin-sheet-item" :class="{ active: tab === 'accounts' }"
                    @click="tab = 'accounts'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Comptes
                <span class="admin-sidebar-badge" style="margin-left:auto;background:var(--text-muted);">{{ $users->count() }}</span>
            </button>

            <div class="admin-sheet-divider"></div>

            <button class="admin-sheet-item" :class="{ active: tab === 'payment_cfg' }"
                    @click="tab = 'payment_cfg'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Paiements
            </button>

            <div class="admin-sheet-divider"></div>

            <a href="{{ route('home') }}" class="admin-sheet-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Retour au site
            </a>

        </nav>
    </div>

</div>
@endsection

@section('scripts')
<script>
function togglePm(id, visible) {
    const cfg  = document.getElementById('cfg-' + id);
    const st   = document.getElementById('st-'  + id);
    const card = document.getElementById('pm-'  + id);
    if (cfg)  cfg.style.display = visible ? 'block' : 'none';
    if (st)   { st.textContent = visible ? 'Actif' : 'Inactif'; st.classList.toggle('active', visible); }
    if (card) card.classList.toggle('pm-card--active', visible);
}
</script>
@endsection
