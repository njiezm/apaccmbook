<?php $__env->startSection('title', 'Catalogue — APACC-M eBooks'); ?>

<?php $__env->startSection('content'); ?>


<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2rem 0 1.75rem;">
    <div class="container-custom">
        <span class="section-label">Bibliothèque numérique</span>
        <h1 style="font-size:2rem;margin-bottom:0.25rem;">Catalogue</h1>
        <p style="color:var(--text-secondary);margin:0;"><?php echo e($ebooks->total()); ?> publication<?php echo e($ebooks->total() > 1 ? 's' : ''); ?> disponible<?php echo e($ebooks->total() > 1 ? 's' : ''); ?></p>
    </div>
</section>

<?php
    $hasActiveFilters = request()->hasAny(['search','category_id','min_price','max_price'])
        || (request('sort') && request('sort') !== 'latest');
    $activeCount = collect(['search','category_id','min_price','max_price'])
        ->filter(fn($k) => request($k))->count()
        + ($hasActiveFilters && request('sort') && request('sort') !== 'latest' ? 1 : 0);
?>

<div class="container-custom" style="padding-top:2rem;padding-bottom:4rem;">
    <div class="catalog-shell" x-data="{ showFilters: <?php echo e($hasActiveFilters ? 'true' : 'false'); ?> }">

        
        <button class="catalog-filter-toggle" @click="showFilters = !showFilters" type="button"
                :aria-expanded="showFilters.toString()" aria-controls="catalog-filters-panel">
            <span style="display:flex;align-items:center;gap:0.65rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/></svg>
                Filtres
                <?php if($hasActiveFilters): ?>
                    <span class="filter-active-badge"><?php echo e($activeCount); ?> actif<?php echo e($activeCount > 1 ? 's' : ''); ?></span>
                <?php endif; ?>
            </span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 :style="showFilters ? 'transform:rotate(180deg);transition:transform .25s' : 'transition:transform .25s'">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        
        <aside class="catalog-filters" id="catalog-filters-panel"
               :class="{ 'catalog-filters--open': showFilters }">
            <h3>Filtres</h3>
            <form method="GET" action="<?php echo e(route('ebooks.index')); ?>">

                <div class="filter-group">
                    <label for="search">Recherche</label>
                    <input type="search" id="search" name="search" placeholder="Titre, description…" value="<?php echo e(request('search')); ?>">
                </div>

                <?php if($categories->count()): ?>
                <div class="filter-group">
                    <label for="category_id">Thème</label>
                    <select id="category_id" name="category_id">
                        <option value="">Tous les thèmes</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="filter-group">
                    <label>Prix (€)</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <input type="number" name="min_price" placeholder="Min" value="<?php echo e(request('min_price')); ?>" min="0" step="0.01" style="width:50%;">
                        <span style="color:var(--text-muted);font-size:0.85rem;">—</span>
                        <input type="number" name="max_price" placeholder="Max" value="<?php echo e(request('max_price')); ?>" min="0" step="0.01" style="width:50%;">
                    </div>
                </div>

                <div class="filter-group">
                    <label for="sort">Trier par</label>
                    <select id="sort" name="sort">
                        <option value="latest"     <?php echo e(request('sort','latest') === 'latest'     ? 'selected' : ''); ?>>Plus récents</option>
                        <option value="oldest"     <?php echo e(request('sort') === 'oldest'     ? 'selected' : ''); ?>>Plus anciens</option>
                        <option value="price_low"  <?php echo e(request('sort') === 'price_low'  ? 'selected' : ''); ?>>Prix croissant</option>
                        <option value="price_high" <?php echo e(request('sort') === 'price_high' ? 'selected' : ''); ?>>Prix décroissant</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Appliquer</button>

                <?php if($hasActiveFilters): ?>
                    <a href="<?php echo e(route('ebooks.index')); ?>" class="btn-ghost mt-2" style="width:100%;text-align:center;display:block;">Réinitialiser</a>
                <?php endif; ?>
            </form>
        </aside>

        
        <div>
            <?php if(request('search') || request('category_id')): ?>
                <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1.25rem;">
                    Résultats pour
                    <?php if(request('search')): ?><strong>« <?php echo e(request('search')); ?> »</strong><?php endif; ?>
                    <?php if(request('category_id') && $categories->find(request('category_id'))): ?><em> dans <?php echo e($categories->find(request('category_id'))->name); ?></em><?php endif; ?>
                </p>
            <?php endif; ?>

            <div class="catalog-grid">
                <?php $__empty_1 = true; $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ebook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="ebook-card reveal">
                        <div style="position:relative;">
                            <?php if($ebook->cover_image): ?>
                                <img class="ebook-card__cover" src="<?php echo e(asset('storage/' . $ebook->cover_image)); ?>" alt="<?php echo e($ebook->title); ?>" loading="lazy">
                            <?php else: ?>
                                <div class="ebook-card__cover-placeholder">📖</div>
                            <?php endif; ?>
                            <?php if(auth()->guard()->check()): ?>
                                <?php $inWishlist = auth()->user()->wishlists->contains('ebook_id', $ebook->id); ?>
                                <form method="POST" action="<?php echo e(route('wishlist.toggle', $ebook)); ?>" style="position:absolute;top:0.5rem;right:0.5rem;margin:0;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" title="<?php echo e($inWishlist ? 'Retirer de mes envies' : 'Ajouter à mes envies'); ?>"
                                            style="width:36px;height:36px;border-radius:50%;border:none;background:rgba(255,255,255,0.92);box-shadow:0 2px 8px rgba(0,0,0,0.15);cursor:pointer;display:flex;align-items:center;justify-content:center;color:<?php echo e($inWishlist ? 'var(--cardinal,#b91c1c)' : '#888'); ?>;">
                                        <i class="fa-<?php echo e($inWishlist ? 'solid' : 'regular'); ?> fa-heart"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" title="Connectez-vous pour ajouter à vos envies"
                                   style="position:absolute;top:0.5rem;right:0.5rem;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.92);box-shadow:0 2px 8px rgba(0,0,0,0.15);display:flex;align-items:center;justify-content:center;color:#888;text-decoration:none;">
                                    <i class="fa-regular fa-heart"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="ebook-card__body">
                            <?php if($ebook->category): ?>
                                <span class="ebook-card__category"><?php echo e($ebook->category->name); ?></span>
                            <?php endif; ?>
                            <h3 class="ebook-card__title"><?php echo e($ebook->title); ?></h3>
                            <?php if($ebook->short_description): ?>
                                <p class="ebook-card__description"><?php echo e(\Illuminate\Support\Str::limit($ebook->short_description, 110)); ?></p>
                            <?php else: ?>
                                <p class="ebook-card__description"><?php echo e(\Illuminate\Support\Str::limit($ebook->description, 110)); ?></p>
                            <?php endif; ?>

                            <div class="ebook-card__footer">
                                <?php if($ebook->is_free): ?>
                                    <span class="ebook-card__price" style="color:var(--cardinal,#b91c1c);">Gratuit</span>
                                <?php else: ?>
                                    <span class="ebook-card__price"><?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €</span>
                                <?php endif; ?>
                                <a href="<?php echo e(route('ebooks.show', $ebook->slug)); ?>" class="btn-primary" style="padding:0.42rem 0.9rem;font-size:0.76rem;">Voir le détail</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="grid-column:1/-1;text-align:center;padding:4rem 0;">
                        <p style="color:var(--text-secondary);font-size:1.05rem;">Aucun eBook ne correspond à votre recherche.</p>
                        <a href="<?php echo e(route('ebooks.index')); ?>" class="btn-secondary mt-3">Réinitialiser les filtres</a>
                    </div>
                <?php endif; ?>
            </div>

            
            <?php if($ebooks->hasPages()): ?>
                <div style="margin-top:2.5rem;">
                    <?php echo e($ebooks->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/ebooks/index.blade.php ENDPATH**/ ?>