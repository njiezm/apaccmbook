<?php $__env->startSection('title', 'Mes eBooks — APACC-M'); ?>

<?php $__env->startSection('content'); ?>


<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2rem 0 1.75rem;">
    <div class="container-custom">
        <span class="section-label">Espace personnel</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Ma bibliothèque</h1>
        <p style="color:var(--text-secondary);margin:0;">Retrouvez ici tous vos eBooks acquis et leur statut de validation.</p>
    </div>
</section>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    <div class="grid-scroll">
        <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="purchase-card reveal">
                <div class="purchase-header">
                    <div style="flex:1;min-width:0;">
                        <h3 style="font-size:1.1rem;margin:0 0 0.3rem;"><?php echo e($purchase->ebook->title); ?></h3>
                        <?php if($purchase->ebook->category): ?>
                            <span style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:var(--cardinal);"><?php echo e($purchase->ebook->category->name); ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="status-pill <?php echo e($purchase->payment_status); ?>">
                        <?php echo e($purchase->payment_status === 'paid' ? 'Validé' : 'En attente'); ?>

                    </span>
                </div>

                <?php if($purchase->ebook->short_description): ?>
                    <p style="font-size:0.875rem;color:var(--text-secondary);line-height:1.55;margin:0;"><?php echo e(\Illuminate\Support\Str::limit($purchase->ebook->short_description, 120)); ?></p>
                <?php endif; ?>

                <p style="font-size:0.8rem;color:var(--text-muted);margin:0;">
                    Achat enregistré le <?php echo e($purchase->created_at->format('d/m/Y à H\hi')); ?>

                </p>

                <div style="margin-top:0.5rem;padding-top:0.75rem;border-top:1px solid var(--border-light);">
                    <?php if($purchase->payment_status === \App\Models\Purchase::STATUS_PAID): ?>
                        <a class="btn-primary" href="<?php echo e(route('ebooks.read', $purchase->ebook)); ?>" style="display:inline-block;">Lire maintenant</a>
                    <?php else: ?>
                        <p style="font-size:0.82rem;color:var(--text-muted);margin:0;">Validation en cours — un administrateur confirme votre paiement sous 12 à 24 h.</p>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('validate-purchase')): ?>
                        <?php if($purchase->payment_status === \App\Models\Purchase::STATUS_PENDING): ?>
                            <form method="POST" action="<?php echo e(route('purchases.status.update', $purchase)); ?>" style="margin-top:0.75rem;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn-secondary" style="font-size:0.78rem;padding:0.4rem 0.9rem;">Valider (Admin)</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="grid-column:1/-1;text-align:center;padding:5rem 0;">
                <div style="font-size:4rem;margin-bottom:1.5rem;">📚</div>
                <h3 style="font-size:1.4rem;margin-bottom:0.75rem;">Votre bibliothèque est vide</h3>
                <p style="color:var(--text-secondary);margin-bottom:1.75rem;max-width:420px;margin-left:auto;margin-right:auto;">Parcourez notre catalogue et acquérez votre premier eBook pour l'ajouter ici.</p>
                <a href="<?php echo e(route('ebooks.index')); ?>" class="btn-primary">Découvrir le catalogue</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/ebooks/mine.blade.php ENDPATH**/ ?>