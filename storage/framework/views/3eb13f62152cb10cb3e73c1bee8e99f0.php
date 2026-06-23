<?php $__env->startSection('title', $ebook->title . ' — APACC-M'); ?>

<?php $__env->startSection('content'); ?>


<div style="background:var(--white);border-bottom:1px solid var(--border-light);padding:0.75rem 0;">
    <div class="container-custom">
        <nav style="font-size:0.8rem;color:var(--text-muted);">
            <a href="<?php echo e(route('home')); ?>" style="color:var(--text-muted);">Accueil</a>
            <span style="margin:0 0.5rem;">›</span>
            <a href="<?php echo e(route('ebooks.index')); ?>" style="color:var(--text-muted);">Catalogue</a>
            <?php if($ebook->category): ?>
                <span style="margin:0 0.5rem;">›</span>
                <a href="<?php echo e(route('ebooks.index', ['category_id' => $ebook->category->id])); ?>" style="color:var(--text-muted);"><?php echo e($ebook->category->name); ?></a>
            <?php endif; ?>
            <span style="margin:0 0.5rem;">›</span>
            <span style="color:var(--text-primary);"><?php echo e($ebook->title); ?></span>
        </nav>
    </div>
</div>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    <div class="product-detail-grid">

        
        <div class="product-cover">
            <?php if($ebook->cover_image): ?>
                <img src="<?php echo e(asset('storage/' . $ebook->cover_image)); ?>" alt="<?php echo e($ebook->title); ?>">
            <?php else: ?>
                <div class="product-cover-placeholder">📖</div>
            <?php endif; ?>
        </div>

        
        <div class="product-info">

            <?php if($ebook->category): ?>
                <span class="section-label"><?php echo e($ebook->category->name); ?></span>
            <?php endif; ?>

            <h1><?php echo e($ebook->title); ?></h1>

            
            <div class="product-meta">
                <?php if($ebook->author): ?>
                    <span>✍ <?php echo e($ebook->author->name); ?></span>
                <?php endif; ?>
                <?php if($ebook->page_count): ?>
                    <span>📄 <?php echo e($ebook->page_count); ?> pages</span>
                <?php endif; ?>
                <?php if($ebook->published_date): ?>
                    <span>📅 <?php echo e(\Carbon\Carbon::parse($ebook->published_date)->format('Y')); ?></span>
                <?php endif; ?>
            </div>

            
            <div class="narthex-line" style="margin:0.5rem 0 1rem;"></div>

            
            <?php if($ebook->short_description): ?>
                <p style="font-size:1.05rem;line-height:1.75;color:var(--text-secondary);"><?php echo e($ebook->short_description); ?></p>
            <?php endif; ?>

            <?php if($ebook->description && $ebook->description !== $ebook->short_description): ?>
                <div style="font-size:0.95rem;line-height:1.75;color:var(--text-secondary);">
                    <?php echo nl2br(e($ebook->description)); ?>

                </div>
            <?php endif; ?>

            
            <div class="product-purchase-box">
                <div class="product-price-block" style="margin-bottom:1rem;">
                    <?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €
                </div>

                <?php $methods = $paymentSettings['enabled_methods'] ?? ['helloasso']; ?>

                <?php if($purchase): ?>
                    
                    <div style="margin-bottom:1rem;">
                        <span class="status-pill <?php echo e($purchase->payment_status); ?>" style="margin-bottom:0.5rem;display:inline-block;">
                            <?php echo e($purchase->payment_status === 'paid' ? 'Accès validé' : 'En attente de validation'); ?>

                        </span>
                        <p style="font-size:0.85rem;color:var(--text-secondary);margin:0.5rem 0 0;">
                            <?php if($purchase->payment_status === 'paid'): ?>
                                Votre accès est actif. Vous pouvez lire cet eBook dès maintenant.
                            <?php else: ?>
                                Votre paiement est en cours de validation (12 à 24 h). Vous recevrez une notification dès que l'accès sera ouvert.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if($purchase->payment_status === \App\Models\Purchase::STATUS_PAID): ?>
                        <a class="btn-primary" href="<?php echo e(route('ebooks.read', $ebook)); ?>" style="display:block;text-align:center;margin-bottom:0.75rem;">Lire maintenant</a>
                    <?php endif; ?>

                <?php else: ?>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <div style="display:flex;flex-direction:column;gap:0.6rem;">

                            
                            <?php if(in_array('stripe', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.stripe', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;gap:0.5rem;display:flex;align-items:center;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        Payer par carte — Stripe
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('paypal', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.paypal', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #003087;border-radius:var(--radius);background:#003087;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.826l-1.348 8.54h3.36c.524 0 .967-.382 1.05-.9l.978-6.195h2.18c4.298 0 6.797-2.078 7.647-6.55.128-.67.183-1.292.13-1.69z"/></svg>
                                        Payer via PayPal
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('sumup', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.sumup', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #1a1a2e;border-radius:var(--radius);background:#1a1a2e;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        Payer via SumUp
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('helloasso', $methods)): ?>
                                <?php $helloassoUrl = $paymentSettings['helloasso_url'] ?? ($ebook->helloasso_url ?? '#'); ?>
                                <a href="<?php echo e($helloassoUrl); ?>" target="_blank" rel="noopener"
                                   style="width:100%;padding:0.6rem 1.25rem;border:2px solid #f47930;border-radius:var(--radius);background:#f47930;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:0.5rem;box-sizing:border-box;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    HelloAsso — 0 % de frais
                                </a>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.45rem;">Après paiement sur HelloAsso, confirmez ici :</p>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="helloasso">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué mon paiement HelloAsso</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(in_array('virement', $methods) && ($paymentSettings['virement_iban'] ?? '')): ?>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Virement bancaire</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">IBAN : <code style="background:#fff;padding:0.1rem 0.35rem;border-radius:3px;border:1px solid var(--border-light);font-size:0.78rem;"><?php echo e($paymentSettings['virement_iban']); ?></code></p>
                                    <?php if($paymentSettings['virement_bic'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">BIC : <strong><?php echo e($paymentSettings['virement_bic']); ?></strong></p><?php endif; ?>
                                    <?php if($paymentSettings['virement_titulaire'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;">Titulaire : <strong><?php echo e($paymentSettings['virement_titulaire']); ?></strong></p><?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="virement">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué le virement</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(in_array('cheque', $methods) && ($paymentSettings['cheque_ordre'] ?? '')): ?>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Paiement par chèque</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">À l'ordre de : <strong><?php echo e($paymentSettings['cheque_ordre']); ?></strong></p>
                                    <?php if($paymentSettings['cheque_adresse'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;white-space:pre-line;"><?php echo e($paymentSettings['cheque_adresse']); ?></p><?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="cheque">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai envoyé mon chèque</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-secondary" style="display:block;text-align:center;">
                            Connectez-vous pour acheter
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('validate-purchase')): ?>
                    <?php if($purchase && $purchase->payment_status === \App\Models\Purchase::STATUS_PENDING): ?>
                        <div style="border-top:1px solid var(--border-light);padding-top:0.75rem;margin-top:0.75rem;">
                            <form method="POST" action="<?php echo e(route('purchases.status.update', $purchase)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button class="btn-primary" type="submit" style="background:var(--text-primary);border-color:var(--text-primary);width:100%;">
                                    Valider le paiement (Admin)
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if($recommendations->count()): ?>
        <div style="margin-top:4rem;">
            <div class="narthex-line" style="margin-bottom:2rem;"></div>
            <span class="section-label">Dans la même thématique</span>
            <h2 style="font-size:1.5rem;margin-bottom:2rem;">Vous pourriez aussi apprécier</h2>
            <div class="row g-4">
                <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo e(route('ebooks.show', $rec->slug)); ?>" class="text-decoration-none d-block h-100">
                            <article class="arch-card h-100">
                                <?php if($rec->cover_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $rec->cover_image)); ?>" alt="<?php echo e($rec->title); ?>" loading="lazy" style="height:180px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="arch-card__cover-placeholder" style="height:180px;font-size:2.5rem;">📖</div>
                                <?php endif; ?>
                                <div class="arch-card-body">
                                    <h5 style="font-size:0.95rem;"><?php echo e($rec->title); ?></h5>
                                    <span class="price" style="font-size:1rem;"><?php echo e(number_format($rec->price, 2, ',', ' ')); ?> €</span>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/ebooks/show.blade.php ENDPATH**/ ?>