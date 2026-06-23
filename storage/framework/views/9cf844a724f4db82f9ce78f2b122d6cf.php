<!-- Header Titre de Page -->
<div class="bg-white border-b border-border-light">
    <?php if (isset($component)) { $__componentOriginala766c2d312d6f7864fe218e2500d2bba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala766c2d312d6f7864fe218e2500d2bba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.container','data' => ['class' => 'py-8 lg:py-12']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('container'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-8 lg:py-12']); ?>
        <h1 class="text-3xl lg:text-5xl font-serif font-bold text-text-primary mb-4">
            <?php echo e($title); ?>

        </h1>
        <?php if($subtitle ?? false): ?>
            <p class="text-lg text-text-secondary"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
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
</div>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/page-header.blade.php ENDPATH**/ ?>