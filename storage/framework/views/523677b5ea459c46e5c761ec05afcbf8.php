<!-- Navigation Principale -->
<nav class="bg-white border-b border-border-light sticky top-0 z-50 shadow-soft">
    <?php if (isset($component)) { $__componentOriginala766c2d312d6f7864fe218e2500d2bba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala766c2d312d6f7864fe218e2500d2bba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.container','data' => ['class' => 'flex justify-between items-center py-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'flex justify-between items-center py-4']); ?>
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2">
            <span class="font-serif font-bold text-2xl text-cardinal">APACC-M</span>
        </a>

        <!-- Links -->
        <div class="hidden md:flex gap-8">
            <a href="/" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Accueil
            </a>
            <a href="/ebooks" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Catalogue
            </a>
            <a href="/about" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                À Propos
            </a>
            <a href="/contact" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Contact
            </a>
        </div>

        <!-- Auth -->
        <div class="flex gap-4 items-center">
            <?php if(auth()->guard()->check()): ?>
                <div class="hidden sm:flex items-center gap-4">
                    <a href="/dashboard" class="text-text-secondary hover:text-cardinal transition-colors text-sm font-600">
                        <?php echo e(Auth::user()->name); ?>

                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ebooks')): ?>
                        <a href="/admin" class="text-text-secondary hover:text-cardinal transition-colors text-sm font-600">
                            Admin
                        </a>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="text-sm text-text-secondary hover:text-cardinal transition-colors font-600">
                        Déconnexion
                    </button>
                </form>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginal3b9eae2fda1979ebeecf9420d156d189 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9eae2fda1979ebeecf9420d156d189 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-secondary','data' => ['href' => '/login','class' => 'text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/login','class' => 'text-sm']); ?>Connexion <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b9eae2fda1979ebeecf9420d156d189)): ?>
<?php $attributes = $__attributesOriginal3b9eae2fda1979ebeecf9420d156d189; ?>
<?php unset($__attributesOriginal3b9eae2fda1979ebeecf9420d156d189); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b9eae2fda1979ebeecf9420d156d189)): ?>
<?php $component = $__componentOriginal3b9eae2fda1979ebeecf9420d156d189; ?>
<?php unset($__componentOriginal3b9eae2fda1979ebeecf9420d156d189); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala766c2d312d6f7864fe218e2500d2bba)): ?>
<?php $attributes = $__attributesOriginala766c2d312d6f7864fe218e2500d2bba; ?>
<?php unset($__attributesOriginala766c2d312d6f7864fe218e2500d2bba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala766c2d312d6f7864fe218e2500d2bba)): ?>
<?php $component = $__componentOriginala766c2d312d6f7864fe218e2500d2bba; ?>
<?php unset($__componentOriginala766c2d312d6f7864fe218e2500d2bba); ?>
<?php endif; ?>

    <!-- Ligne Narthex Double -->
    <?php if (isset($component)) { $__componentOriginal722fc204526a4c1785097575a67956eb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal722fc204526a4c1785097575a67956eb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.narthex-line','data' => ['double' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('narthex-line'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['double' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal722fc204526a4c1785097575a67956eb)): ?>
<?php $attributes = $__attributesOriginal722fc204526a4c1785097575a67956eb; ?>
<?php unset($__attributesOriginal722fc204526a4c1785097575a67956eb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal722fc204526a4c1785097575a67956eb)): ?>
<?php $component = $__componentOriginal722fc204526a4c1785097575a67956eb; ?>
<?php unset($__componentOriginal722fc204526a4c1785097575a67956eb); ?>
<?php endif; ?>
</nav>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/navbar.blade.php ENDPATH**/ ?>