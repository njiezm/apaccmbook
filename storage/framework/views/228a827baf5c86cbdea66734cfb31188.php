

<?php
    use App\Models\Purchase;
?>

<?php $__env->startSection('content'); ?>
<div class="admin-shell">
    <div class="section-header">
        <p class="text-muted letter-spacing-2">Espace administrateur</p>
        <h2>Publier, valider et sécuriser</h2>
        <p class="text-muted">Déposez les ebooks, pilotez les validations et gérez les comptes depuis un tableau de bord compartimenté.</p>
    </div>
    <?php if(session('status')): ?>
        <div class="flash-message">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ebook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $payload = json_encode([
                            'id' => $ebook->id,
                            'title' => $ebook->title,
                            'price' => number_format($ebook->price, 2, '.', ''),
                            'helloasso_url' => $ebook->helloasso_url,
                            'description' => $ebook->description,
                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
                    ?>
                    <article class="product-card reveal">
                        <img src="<?php echo e($ebook->cover_image ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo e($ebook->title); ?>" class="product-image">
                        <div>
                            <h4><?php echo e($ebook->title); ?></h4>
                            <p class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($ebook->description, 120)); ?></p>
                        </div>
                        <div class="product-footer">
                            <span class="product-price"><?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €</span>
                            <div class="cta-group">
                                <button type="button" class="btn-secondary" @click="openEdit(<?php echo e($payload); ?>)">Modifier</button>
                                <form method="POST" action="<?php echo e(route('admin.ebooks.destroy', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-secondary">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted">Aucun ebook publié.</p>
                <?php endif; ?>
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
                        <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($purchase->user->name); ?> (<?php echo e($purchase->user->email); ?>)</td>
                                <td><?php echo e($purchase->ebook->title); ?></td>
                                <td><?php echo e($purchase->created_at->format('d/m/Y H:i')); ?></td>
                                <td><span class="status-pill <?php echo e($purchase->payment_status); ?>"><?php echo e(ucfirst($purchase->payment_status)); ?></span></td>
                                <td>
                                    <?php if($purchase->payment_status === Purchase::STATUS_PENDING): ?>
                                        <form method="POST" action="<?php echo e(route('purchases.status.update', $purchase)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn-primary">Valider</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Validé</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><span class="badge <?php echo e($user->is_admin ? 'admin' : 'user'); ?>"><?php echo e($user->is_admin ? 'Administrateur' : 'Utilisateur'); ?></span></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.users.toggle-admin', $user)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn-secondary"><?php echo e($user->is_admin ? 'Retirer' : 'Attribuer'); ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <form method="POST" action="<?php echo e(route('admin.ebooks.store')); ?>" enctype="multipart/form-data" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <label class="text-muted" for="modal_title">Titre</label>
                    <input id="modal_title" name="title" type="text" required placeholder="Titre">
                    <label class="text-muted" for="modal_description">Résumé</label>
                    <textarea id="modal_description" name="description" rows="3" required placeholder="Résumé"></textarea>
                    <label class="text-muted" for="modal_price">Prix (€)</label>
                    <input id="modal_price" name="price" type="number" step="0.01" min="0" required>
                    <label class="text-muted" for="modal_link">Lien HelloAsso</label>
                    <input id="modal_link" name="helloasso_url" type="url" required>
                    <label class="text-muted" for="modal_pdf">PDF sécurisé</label>
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
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <label class="text-muted" for="modal_price_edit">Prix (€)</label>
                    <input id="modal_price_edit" name="price" type="number" step="0.01" min="0" :value="editEbook ? editEbook.price : ''" required>
                    <label class="text-muted" for="modal_link_edit">Lien HelloAsso</label>
                    <input id="modal_link_edit" name="helloasso_url" type="url" :value="editEbook ? editEbook.helloasso_url : ''" required>
                    <label class="text-muted" for="modal_pdf_edit">PDF (optionnel)</label>
                    <input id="modal_pdf_edit" name="pdf" type="file" accept="application/pdf">
                    <label class="text-muted" for="modal_cover_edit">Couverture (optionnelle)</label>
                    <input id="modal_cover_edit" name="cover" type="file" accept="image/*">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/admin/ebooks/index.blade.php ENDPATH**/ ?>