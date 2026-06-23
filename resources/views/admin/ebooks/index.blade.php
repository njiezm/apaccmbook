@extends('layouts.app')

@php
    use App\Models\Purchase;
@endphp

@section('content')
<div class="admin-shell">
    <div class="section-header">
        <p class="text-muted letter-spacing-2">Espace administrateur</p>
        <h2>Publier, valider et sécuriser</h2>
        <p class="text-muted">Déposez les ebooks, pilotez les validations et gérez les comptes depuis un tableau de bord compartimenté.</p>
    </div>
    @if(session('status'))
        <div class="flash-message">
            {{ session('status') }}
        </div>
    @endif

    <div class="admin-panel" x-data="{
        section: 'catalog',
        showAdd: false,
        editEbook: null,
        editAction: '',
        openEdit(payload) {
            this.editEbook = payload;
            this.editAction = `/admin/ebooks/${payload.id}`;
        },
        closeModals() {
            this.showAdd = false;
            this.editEbook = null;
            this.editAction = '';
        }
    }">
        <div class="admin-menu">
            <button type="button" class="admin-tab" :class="{ 'active-tab': section === 'catalog' }" @click="section = 'catalog'">Catalogue publié</button>
            <button type="button" class="admin-tab" :class="{ 'active-tab': section === 'validation' }" @click="section = 'validation'">Validation des paiements</button>
            <button type="button" class="admin-tab" :class="{ 'active-tab': section === 'accounts' }" @click="section = 'accounts'">Gestion des comptes</button>
        </div>

        <div class="admin-section" x-show="section === 'catalog'" x-cloak>
            <div class="admin-card admin-card--header">
                <h3>Catalogue publié</h3>
                <div>
                    <p class="text-muted">Ajoutez des parutions, éditez les prix et mettez à jour les liens HelloAsso en un clic.</p>
                    <button type="button" class="btn-primary" @click="showAdd = true">Ajouter un ebook</button>
                </div>
            </div>
            <div class="catalog-grid">
                @forelse($ebooks as $ebook)
                    @php
                        $payload = json_encode([
                            'id' => $ebook->id,
                            'title' => $ebook->title,
                            'price' => number_format($ebook->price, 2, '.', ''),
                            'helloasso_url' => $ebook->helloasso_url,
                            'description' => $ebook->description,
                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
                    @endphp
                    <article class="product-card reveal">
                        <img src="{{ $ebook->cover_image ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $ebook->title }}" class="product-image">
                        <div>
                            <h4>{{ $ebook->title }}</h4>
                            <p class="text-muted">{{ \Illuminate\Support\Str::limit($ebook->description, 120) }}</p>
                        </div>
                        <div class="product-footer">
                            <span class="product-price">{{ number_format($ebook->price, 2, ',', ' ') }} €</span>
                            <div class="cta-group">
                                <button type="button" class="btn-secondary" @click="openEdit({{ $payload }})">Modifier</button>
                                <form method="POST" action="{{ route('admin.ebooks.destroy', $ebook) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-secondary">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-muted">Aucun ebook publié.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-section" x-show="section === 'validation'" x-cloak>
            <div class="admin-card admin-card--header">
                <h3>Validation des paiements</h3>
                <p class="text-muted">Les demandes issues d'HelloAsso doivent être vérifiées manuellement (12 à 24 h).</p>
            </div>
            <div class="table-responsive">
                <table class="table" id="payment-table">
                    <thead>
                        <tr>
                            <th>Acheteur</th>
                            <th>Ebook</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->user->name }} ({{ $purchase->user->email }})</td>
                                <td>{{ $purchase->ebook->title }}</td>
                                <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="status-pill {{ $purchase->payment_status }}">{{ ucfirst($purchase->payment_status) }}</span></td>
                                <td>
                                    @if($purchase->payment_status === Purchase::STATUS_PENDING)
                                        <form method="POST" action="{{ route('purchases.status.update', $purchase) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-primary">Valider</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Validé</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id="payment-pagination" class="table-pagination"></div>
        </div>

        <div class="admin-section" x-show="section === 'accounts'" x-cloak>
            <div class="admin-card admin-card--header">
                <h3>Comptes & rôles</h3>
                <p class="text-muted">Attribuez ou retirez un rôle d'administrateur en toute sécurité.</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge {{ $user->is_admin ? 'admin' : 'user' }}">{{ $user->is_admin ? 'Administrateur' : 'Utilisateur' }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-secondary">{{ $user->is_admin ? 'Retirer' : 'Attribuer' }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="showAdd" class="modal" x-cloak @click.self="closeModals()">
            <div class="modal-panel">
                <div class="modal-header">
                    <h3>Ajouter un ebook</h3>
                    <button type="button" class="btn-close" @click="closeModals()">×</button>
                </div>
                <form method="POST" action="{{ route('admin.ebooks.store') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <label class="text-muted" for="modal_title">Titre</label>
                    <input id="modal_title" name="title" type="text" required placeholder="Titre">
                    <label class="text-muted" for="modal_description">Résumé</label>
                    <textarea id="modal_description" name="description" rows="3" required placeholder="Résumé"></textarea>
                    <label class="text-muted" for="modal_price">Prix (€)</label>
                    <input id="modal_price" name="price" type="number" step="0.01" min="0" required>
                    <label class="text-muted" for="modal_link">Lien HelloAsso (optionnel)</label>
                    <input id="modal_link" name="helloasso_url" type="url">
                    <label class="text-muted" for="modal_pdf">PDF sécurisé (max 60 Mo)</label>
                    <input id="modal_pdf" name="pdf" type="file" accept="application/pdf" required>
                    <label class="text-muted" for="modal_cover">Couverture</label>
                    <input id="modal_cover" name="cover" type="file" accept="image/*">
                    <button type="submit" class="btn-primary">Publier</button>
                </form>
            </div>
        </div>

        <div x-show="editEbook" class="modal" x-cloak @click.self="closeModals()">
            <div class="modal-panel">
                <div class="modal-header">
                    <h3 x-text="editEbook ? 'Modifier – ' + editEbook.title : 'Modifier'">Modifier</h3>
                    <button type="button" class="btn-close" @click="closeModals()">×</button>
                </div>
                <form :action="editAction" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <label class="text-muted" for="modal_title_edit">Titre</label>
                    <input id="modal_title_edit" name="title" type="text" :value="editEbook ? editEbook.title : ''" required>
                    <label class="text-muted" for="modal_desc_edit">Résumé</label>
                    <textarea id="modal_desc_edit" name="description" rows="3" required x-effect="$el.value = editEbook ? editEbook.description : ''"></textarea>
                    <label class="text-muted" for="modal_price_edit">Prix (€)</label>
                    <input id="modal_price_edit" name="price" type="number" step="0.01" min="0" :value="editEbook ? editEbook.price : ''" required>
                    <label class="text-muted" for="modal_link_edit">Lien HelloAsso (optionnel)</label>
                    <input id="modal_link_edit" name="helloasso_url" type="url" :value="editEbook ? editEbook.helloasso_url : ''">
                    <label class="text-muted" for="modal_pdf_edit">PDF (optionnel, max 60 Mo)</label>
                    <input id="modal_pdf_edit" name="pdf" type="file" accept="application/pdf">
                    <label class="text-muted" for="modal_cover_edit">Couverture (optionnelle)</label>
                    <input id="modal_cover_edit" name="cover" type="file" accept="image/*">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
