<!-- Footer -->
<footer class="bg-bg-footer border-t border-border-light mt-12">
    <?php if (isset($component)) { $__componentOriginala766c2d312d6f7864fe218e2500d2bba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala766c2d312d6f7864fe218e2500d2bba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.container','data' => ['class' => 'py-12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-12']); ?>
        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Brand -->
            <div>
                <h3 class="font-serif font-bold text-lg text-text-primary mb-4">APACC-M</h3>
                <p class="text-text-secondary text-sm leading-relaxed">
                    Plateforme de lecture et diffusion d'e-books de qualité, inspirée par l'esprit éditorial de Narthex.
                </p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-sans font-600 text-text-primary mb-4 uppercase text-sm tracking-tracked">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/ebooks" class="text-text-secondary hover:text-cardinal transition-colors">Catalogue</a></li>
                    <li><a href="/about" class="text-text-secondary hover:text-cardinal transition-colors">À Propos</a></li>
                    <li><a href="/contact" class="text-text-secondary hover:text-cardinal transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="font-sans font-600 text-text-primary mb-4 uppercase text-sm tracking-tracked">Légal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/terms" class="text-text-secondary hover:text-cardinal transition-colors">CGU</a></li>
                    <li><a href="/privacy" class="text-text-secondary hover:text-cardinal transition-colors">Confidentialité</a></li>
                    <li><a href="/legal" class="text-text-secondary hover:text-cardinal transition-colors">Mentions légales</a></li>
                </ul>
            </div>
        </div>

        <!-- Narthex Line -->
        <?php if (isset($component)) { $__componentOriginal722fc204526a4c1785097575a67956eb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal722fc204526a4c1785097575a67956eb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.narthex-line','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('narthex-line'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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

        <!-- Copyright -->
        <div class="mt-8 text-center text-sm text-text-tertiary">
            <p>&copy; <?php echo e(date('Y')); ?> APACC-M. Tous droits réservés.</p>
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
</footer>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/footer.blade.php ENDPATH**/ ?>