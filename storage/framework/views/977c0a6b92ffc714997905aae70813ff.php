<?php $__env->startSection('title', 'Administration — APACC-M e-Livre'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.site-footer, .mobile-bottom-nav { display: none !important; }
body { padding-bottom: 0 !important; }
main > .container-custom:first-child { padding-top: 0 !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php use App\Models\Purchase; ?>

<div class="admin-page" x-data="{
    tab: 'catalog',
    showAdd: false,
    editEbook: null,
    editAction: '',
    adminMenu: false,
    openEdit(p) { this.editEbook = p; this.editAction = p.update_url; },
    closeModals() { this.showAdd = false; this.editEbook = null; this.editAction = ''; }
}">

    
    <aside class="admin-sidebar">
        <div class="admin-sidebar-title">Tableau de bord</div>

        <nav class="admin-sidebar-nav">
            <button class="admin-sidebar-item" :class="{ active: tab === 'catalog' }" @click="tab = 'catalog'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Catalogue
                <span class="admin-sidebar-badge"><?php echo e($ebooks->count()); ?></span>
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'validation' }" @click="tab = 'validation'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Validations
                <?php $pendingCount = $purchases->where('payment_status', 'pending')->count(); ?>
                <?php if($pendingCount > 0): ?>
                    <span class="admin-sidebar-badge"><?php echo e($pendingCount); ?></span>
                <?php endif; ?>
            </button>

            <button class="admin-sidebar-item" :class="{ active: tab === 'accounts' }" @click="tab = 'accounts'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Comptes
                <span class="admin-sidebar-badge" style="background:var(--text-muted);"><?php echo e($users->count()); ?></span>
            </button>

            <div class="admin-sidebar-divider"></div>

            <button class="admin-sidebar-item" :class="{ active: tab === 'payment_cfg' }" @click="tab = 'payment_cfg'">
                <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Paiements
            </button>
        </nav>

        <div style="margin-top:auto;">
            <div class="admin-sidebar-divider"></div>
            <div style="padding:0.75rem 1.25rem;">
                <a href="<?php echo e(route('home')); ?>" style="display:flex;align-items:center;gap:0.6rem;font-size:0.8rem;color:var(--text-muted);text-decoration:none;padding:0.5rem 0;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Retour au site
                </a>
            </div>
        </div>
    </aside>

    
    <div class="admin-content">

        <?php if(session('status')): ?>
            <div class="flash-success" style="margin-bottom:1.75rem;"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        
        <div class="admin-stats-grid">
            <div class="admin-stat">
                <div class="admin-stat-icon red">📚</div>
                <div class="admin-stat-body">
                    <span class="stat-label">Publications</span>
                    <span class="stat-value"><?php echo e($stats['total_ebooks'] ?? 0); ?></span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon blue">👥</div>
                <div class="admin-stat-body">
                    <span class="stat-label">Membres</span>
                    <span class="stat-value"><?php echo e($stats['total_users'] ?? 0); ?></span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon green">✅</div>
                <div class="admin-stat-body">
                    <span class="stat-label">Ventes</span>
                    <span class="stat-value"><?php echo e($stats['total_sales'] ?? 0); ?></span>
                </div>
            </div>
            <div class="admin-stat">
                <div class="admin-stat-icon amber">💶</div>
                <div class="admin-stat-body">
                    <span class="stat-label">Revenus</span>
                    <span class="stat-value" style="font-size:1.55rem;"><?php echo e(number_format($stats['total_revenue'] ?? 0, 0, ',', ' ')); ?> €</span>
                </div>
            </div>
        </div>

        
        <div x-show="tab === 'catalog'" x-cloak>
            <div class="admin-section-header">
                <div>
                    <h2>Catalogue</h2>
                    <p>Gérez les publications disponibles sur la plateforme.</p>
                </div>
                <button type="button" class="btn-primary" @click="showAdd = true">+ Ajouter un e-Livre</button>
            </div>

            <div class="admin-ebook-grid">
                <?php $__empty_1 = true; $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ebook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $p = json_encode([
                            'id'            => $ebook->id,
                            'title'         => $ebook->title,
                            'price'         => number_format($ebook->price, 2, '.', ''),
                            'is_free'       => (bool) $ebook->is_free,
                            'helloasso_url' => $ebook->helloasso_url,
                            'description'   => $ebook->description,
                            'update_url'    => route('admin.ebooks.update', $ebook),
                        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG);
                    ?>
                    <article class="admin-ebook-card">
                        <div class="admin-ebook-cover">
                            <?php if($ebook->cover_image): ?>
                                <img src="<?php echo e(asset('storage/' . $ebook->cover_image)); ?>" alt="<?php echo e($ebook->title); ?>">
                            <?php else: ?>
                                <div class="admin-ebook-cover-placeholder">📖</div>
                            <?php endif; ?>
                        </div>
                        <div class="admin-ebook-body">
                            <p class="admin-ebook-title"><?php echo e($ebook->title); ?></p>
                            <p class="admin-ebook-desc"><?php echo e(Str::limit($ebook->description, 75)); ?></p>
                            <div class="admin-ebook-footer">
                                <span class="admin-ebook-price"><?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €</span>
                                <div style="display:flex;gap:0.4rem;">
                                    <button type="button" class="btn-ghost btn-xs" @click="openEdit(<?php echo e($p); ?>)">Modifier</button>
                                    <form method="POST" action="<?php echo e(route('admin.ebooks.destroy', $ebook)); ?>" onsubmit="return confirm('Supprimer ?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-ghost btn-xs" style="color:var(--cardinal);border-color:var(--cardinal);">Suppr.</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="admin-empty">
                        <span style="font-size:3rem;display:block;margin-bottom:0.75rem;">📚</span>
                        <p>Aucun e-Livre publié. Cliquez sur « Ajouter » pour commencer.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
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
                    <span style="font-size:0.82rem;color:var(--text-muted);"><?php echo e($purchases->count()); ?> commande(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr><th>Date</th><th>Acheteur</th><th>Publication</th><th>Montant</th><th>Statut</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="font-size:0.82rem;color:var(--text-muted);white-space:nowrap;">
                                    <?php echo e($purchase->created_at->format('d/m/Y')); ?><br>
                                    <span style="font-size:0.72rem;"><?php echo e($purchase->created_at->format('H:i')); ?></span>
                                </td>
                                <td>
                                    <strong style="font-size:0.88rem;"><?php echo e($purchase->user->name); ?></strong><br>
                                    <span style="font-size:0.76rem;color:var(--text-muted);"><?php echo e($purchase->user->email); ?></span>
                                </td>
                                <td style="font-size:0.88rem;max-width:160px;"><?php echo e(Str::limit($purchase->ebook->title, 35)); ?></td>
                                <td><strong style="color:var(--cardinal);"><?php echo e(number_format($purchase->ebook->price, 2, ',', ' ')); ?> €</strong></td>
                                <td>
                                    <span class="status-pill <?php echo e($purchase->payment_status); ?>">
                                        <?php echo e($purchase->payment_status === 'paid' ? 'Validé' : 'En attente'); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($purchase->payment_status === Purchase::STATUS_PENDING): ?>
                                        <form method="POST" action="<?php echo e(route('purchases.status.update', $purchase)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn-primary btn-xs">Valider</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:#16a34a;font-size:0.82rem;font-weight:700;">✓ Actif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-muted);">Aucune commande.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div x-show="tab === 'accounts'" x-cloak>
            <div class="admin-section-header">
                <div><h2>Comptes membres</h2><p>Gérez les rôles et permissions.</p></div>
            </div>
            <div class="admin-box">
                <div class="admin-box-header">
                    <h3>Tous les membres</h3>
                    <span style="font-size:0.82rem;color:var(--text-muted);"><?php echo e($users->count()); ?> membre(s)</span>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Membre</th><th>Email</th><th>Rôle</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:0.65rem;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:<?php echo e($user->is_admin ? 'var(--cardinal)' : 'var(--cream)'); ?>;display:flex;align-items:center;justify-content:center;font-weight:700;color:<?php echo e($user->is_admin ? 'white' : 'var(--text-secondary)'); ?>;flex-shrink:0;border:1px solid var(--border-light);"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                                        <strong style="font-size:0.9rem;"><?php echo e($user->name); ?></strong>
                                    </div>
                                </td>
                                <td style="font-size:0.85rem;color:var(--text-secondary);"><?php echo e($user->email); ?></td>
                                <td><span class="badge <?php echo e($user->is_admin ? 'admin' : 'user'); ?>"><?php echo e($user->is_admin ? 'Admin' : 'Membre'); ?></span></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.users.toggle-admin', $user)); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn-ghost btn-xs"><?php echo e($user->is_admin ? '↓ Retirer Admin' : '↑ Nommer Admin'); ?></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div x-show="tab === 'payment_cfg'" x-cloak>
            <div class="admin-section-header">
                <div><h2>Méthodes de paiement</h2><p>Activez les moyens acceptés et configurez leurs paramètres.</p></div>
            </div>

            <?php
                $enabled = $paymentSettings['enabled_methods'] ?? ['helloasso'];
                $ps      = $paymentSettings;
            ?>

            <form method="POST" action="<?php echo e(route('admin.settings.save')); ?>">
                <?php echo csrf_field(); ?>
                <div style="display:flex;flex-direction:column;gap:0.75rem;">

                    <?php $__currentLoopData = [
                        ['helloasso', '#f47930', 'H', 'HelloAsso', 'Solution associative · 0 % de frais'],
                        ['stripe',    '#635bff', 'S', 'Stripe',    'Carte · Apple Pay · Google Pay'],
                        ['paypal',    '#003087', 'P', 'PayPal',    'Compte PayPal · Carte bancaire'],
                        ['sumup',     '#1a1a2e', 'S', 'SumUp',     'Terminal mobile · TPE'],
                        ['virement',  '#b91c1c', '€', 'Virement bancaire', 'SEPA · Gratuit'],
                        ['cheque',    '#6b7280', '✉', 'Chèque',    'Courrier postal'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$id, $color, $letter, $name, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pm-card<?php echo e(in_array($id, $enabled) ? ' pm-card--active' : ''); ?>" id="pm-<?php echo e($id); ?>">
                        <div class="pm-header">
                            <label class="pm-toggle">
                                <input type="checkbox" name="enabled_methods[]" value="<?php echo e($id); ?>"
                                       <?php echo e(in_array($id, $enabled) ? 'checked' : ''); ?>

                                       onchange="togglePm('<?php echo e($id); ?>', this.checked)">
                                <span class="pm-logo" style="background:<?php echo e($color); ?>;"><?php echo e($letter); ?></span>
                                <div class="pm-info">
                                    <span class="pm-name"><?php echo e($name); ?></span>
                                    <span class="pm-desc"><?php echo e($desc); ?></span>
                                </div>
                                <span class="pm-status-badge<?php echo e(in_array($id, $enabled) ? ' active' : ''); ?>" id="st-<?php echo e($id); ?>">
                                    <?php echo e(in_array($id, $enabled) ? 'Actif' : 'Inactif'); ?>

                                </span>
                            </label>
                        </div>
                        <div class="pm-config" id="cfg-<?php echo e($id); ?>" style="<?php echo e(!in_array($id, $enabled) ? 'display:none' : ''); ?>">
                            <?php if($id === 'helloasso'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>URL de collecte</label><input type="url" name="helloasso_url" placeholder="https://www.helloasso.com/…" value="<?php echo e($ps['helloasso_url'] ?? ''); ?>"></div>
                                    <div class="admin-field"><label>Nom organisation</label><input type="text" name="helloasso_org" placeholder="apacc-m" value="<?php echo e($ps['helloasso_org'] ?? ''); ?>"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook <small style="color:var(--text-muted);font-weight:400;">(optionnel — pour auto-validation)</small></label>
                                    <input type="password" name="helloasso_api_secret" placeholder="Clé secrète de notification HelloAsso" value="<?php echo e($ps['helloasso_api_secret'] ?? ''); ?>" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur HelloAsso : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;"><?php echo e(url('/webhook/helloasso')); ?></code></p>
                                </div>
                            <?php elseif($id === 'stripe'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Clé publique</label><input type="text" name="stripe_publishable_key" placeholder="pk_live_…" value="<?php echo e($ps['stripe_publishable_key'] ?? ''); ?>"></div>
                                    <div class="admin-field"><label>Clé secrète</label><input type="password" name="stripe_secret_key" placeholder="sk_live_…" value="<?php echo e($ps['stripe_secret_key'] ?? ''); ?>" autocomplete="off"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook</label>
                                    <input type="password" name="stripe_webhook_secret" placeholder="whsec_…" value="<?php echo e($ps['stripe_webhook_secret'] ?? ''); ?>" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur Stripe : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;"><?php echo e(url('/webhook/stripe')); ?></code></p>
                                </div>
                            <?php elseif($id === 'paypal'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Client ID</label><input type="text" name="paypal_client_id" placeholder="AXxx…" value="<?php echo e($ps['paypal_client_id'] ?? ''); ?>"></div>
                                    <div class="admin-field"><label>Client Secret</label><input type="password" name="paypal_client_secret" placeholder="EXxx…" value="<?php echo e($ps['paypal_client_secret'] ?? ''); ?>" autocomplete="off"></div>
                                </div>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Mode</label><select name="paypal_mode"><option value="sandbox" <?php echo e(($ps['paypal_mode'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''); ?>>Sandbox (test)</option><option value="live" <?php echo e(($ps['paypal_mode'] ?? '') === 'live' ? 'selected' : ''); ?>>Production</option></select></div>
                                    <div class="admin-field"><label>Webhook ID <small style="color:var(--text-muted);font-weight:400;">(optionnel)</small></label><input type="text" name="paypal_webhook_id" placeholder="ID du webhook PayPal" value="<?php echo e($ps['paypal_webhook_id'] ?? ''); ?>"></div>
                                </div>
                                <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur PayPal Developer : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;"><?php echo e(url('/webhook/paypal')); ?></code></p>
                            <?php elseif($id === 'sumup'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>Clé API</label><input type="password" name="sumup_api_key" placeholder="sup_sk_…" value="<?php echo e($ps['sumup_api_key'] ?? ''); ?>" autocomplete="off"></div>
                                    <div class="admin-field"><label>Code marchand</label><input type="text" name="sumup_merchant_code" placeholder="MC_XXXXXXXX" value="<?php echo e($ps['sumup_merchant_code'] ?? ''); ?>"></div>
                                </div>
                                <div class="admin-field">
                                    <label>Secret webhook <small style="color:var(--text-muted);font-weight:400;">(optionnel)</small></label>
                                    <input type="password" name="sumup_webhook_secret" placeholder="Secret HMAC SumUp" value="<?php echo e($ps['sumup_webhook_secret'] ?? ''); ?>" autocomplete="off">
                                    <p style="font-size:0.76rem;color:var(--text-muted);margin:0.3rem 0 0;">URL webhook à configurer sur SumUp : <code style="background:var(--bg-subtle,#f3f3f0);padding:0.1rem 0.35rem;border-radius:3px;"><?php echo e(url('/webhook/sumup')); ?></code></p>
                                </div>
                            <?php elseif($id === 'virement'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>IBAN</label><input type="text" name="virement_iban" placeholder="FR76 XXXX…" value="<?php echo e($ps['virement_iban'] ?? ''); ?>"></div>
                                    <div class="admin-field"><label>BIC / SWIFT</label><input type="text" name="virement_bic" placeholder="XXXXXXXX" value="<?php echo e($ps['virement_bic'] ?? ''); ?>"></div>
                                </div>
                                <div class="admin-field"><label>Titulaire</label><input type="text" name="virement_titulaire" placeholder="APACC-M" value="<?php echo e($ps['virement_titulaire'] ?? ''); ?>"></div>
                            <?php elseif($id === 'cheque'): ?>
                                <div class="admin-form-row">
                                    <div class="admin-field"><label>À l'ordre de</label><input type="text" name="cheque_ordre" placeholder="APACC-M" value="<?php echo e($ps['cheque_ordre'] ?? ''); ?>"></div>
                                    <div class="admin-field"><label>Adresse postale</label><textarea name="cheque_adresse" rows="2" placeholder="Adresse…"><?php echo e($ps['cheque_adresse'] ?? ''); ?></textarea></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="btn-primary">Enregistrer</button>
                    <span style="font-size:0.8rem;color:var(--text-muted);">Stocké localement sur le serveur.</span>
                </div>
            </form>
        </div>

    </div>

    
    <div x-show="showAdd" class="modal" x-cloak @click.self="closeModals()">
        <div class="modal-panel">
            <div class="modal-header">
                <h3>Ajouter un e-Livre</h3>
                <button type="button" class="btn-close" @click="closeModals()">×</button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.ebooks.store')); ?>" enctype="multipart/form-data" x-data="{ free: false }">
                <?php echo csrf_field(); ?>
                <div class="admin-field"><label>Titre *</label><input name="title" type="text" required placeholder="Titre de la publication"></div>
                <div class="admin-field"><label>Résumé *</label><textarea name="description" rows="3" required placeholder="Description…"></textarea></div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="add_is_free" name="is_free" type="checkbox" value="1" x-model="free" style="width:auto;margin:0;">
                    <label for="add_is_free" style="margin:0;cursor:pointer;">Livre gratuit (aucun paiement requis)</label>
                </div>
                <div class="admin-form-row" x-show="!free" x-cloak>
                    <div class="admin-field"><label>Prix (€) *</label><input name="price" type="number" step="0.01" min="0" :required="!free" placeholder="0.00"></div>
                    <div class="admin-field"><label>Lien HelloAsso</label><input name="helloasso_url" type="url" placeholder="https://…"></div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-field"><label>Fichier PDF *</label><input name="pdf" type="file" accept="application/pdf" required></div>
                    <div class="admin-field"><label>Couverture</label><input name="cover" type="file" accept="image/*"></div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;margin-top:0.5rem;">Publier l'e-Livre</button>
            </form>
        </div>
    </div>

    
    <div x-show="editEbook" class="modal" x-cloak @click.self="closeModals()">
        <div class="modal-panel">
            <div class="modal-header">
                <h3 x-text="editEbook ? 'Modifier — ' + editEbook.title : 'Modifier'">Modifier</h3>
                <button type="button" class="btn-close" @click="closeModals()">×</button>
            </div>
            <form :action="editAction" method="POST" enctype="multipart/form-data" x-data="{ free: false }" x-effect="free = !!(editEbook && editEbook.is_free)">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="admin-field"><label>Titre *</label><input name="title" type="text" :value="editEbook ? editEbook.title : ''" required></div>
                <div class="admin-field"><label>Résumé *</label><textarea name="description" rows="3" required x-effect="$el.value = editEbook ? editEbook.description : ''"></textarea></div>
                <div class="admin-field" style="display:flex;align-items:center;gap:0.5rem;">
                    <input id="edit_is_free" name="is_free" type="checkbox" value="1" x-model="free" style="width:auto;margin:0;">
                    <label for="edit_is_free" style="margin:0;cursor:pointer;">Livre gratuit (aucun paiement requis)</label>
                </div>
                <div class="admin-form-row" x-show="!free" x-cloak>
                    <div class="admin-field"><label>Prix (€)</label><input name="price" type="number" step="0.01" min="0" :value="editEbook ? editEbook.price : ''" :required="!free"></div>
                    <div class="admin-field"><label>Lien HelloAsso</label><input name="helloasso_url" type="url" :value="editEbook ? editEbook.helloasso_url : ''"></div>
                </div>
                <div class="admin-form-row">
                    <div class="admin-field"><label>Nouveau PDF</label><input name="pdf" type="file" accept="application/pdf"></div>
                    <div class="admin-field"><label>Nouvelle couverture</label><input name="cover" type="file" accept="image/*"></div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;margin-top:0.5rem;">Enregistrer</button>
            </form>
        </div>
    </div>

    
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

    
    <div class="admin-sheet-overlay" :class="{ open: adminMenu }" @click="adminMenu = false" aria-hidden="true"></div>

    
    <div class="admin-sheet" :class="{ open: adminMenu }" role="dialog" aria-modal="true" aria-label="Navigation administration">
        <div class="admin-sheet-handle"></div>
        <div class="admin-sheet-title">Administration</div>
        <nav class="admin-sheet-nav">

            <button class="admin-sheet-item" :class="{ active: tab === 'catalog' }"
                    @click="tab = 'catalog'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                Catalogue
                <span class="admin-sidebar-badge" style="margin-left:auto;"><?php echo e($ebooks->count()); ?></span>
            </button>

            <button class="admin-sheet-item" :class="{ active: tab === 'validation' }"
                    @click="tab = 'validation'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Validations
                <?php if($pendingCount > 0): ?>
                    <span class="admin-sidebar-badge" style="margin-left:auto;"><?php echo e($pendingCount); ?></span>
                <?php endif; ?>
            </button>

            <button class="admin-sheet-item" :class="{ active: tab === 'accounts' }"
                    @click="tab = 'accounts'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Comptes
                <span class="admin-sidebar-badge" style="margin-left:auto;background:var(--text-muted);"><?php echo e($users->count()); ?></span>
            </button>

            <div class="admin-sheet-divider"></div>

            <button class="admin-sheet-item" :class="{ active: tab === 'payment_cfg' }"
                    @click="tab = 'payment_cfg'; adminMenu = false">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Paiements
            </button>

            <div class="admin-sheet-divider"></div>

            <a href="<?php echo e(route('home')); ?>" class="admin-sheet-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Retour au site
            </a>

        </nav>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>