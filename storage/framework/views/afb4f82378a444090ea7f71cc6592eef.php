<!-- Grid de Cartes E-Books -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 <?php echo e($class ?? ''); ?>">
    <?php $__empty_1 = true; $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ebook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php if (isset($component)) { $__componentOriginald0bcc9b443897ed7e14d3e842788c9da = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0bcc9b443897ed7e14d3e842788c9da = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ebook-card','data' => ['ebook' => $ebook]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ebook-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['ebook' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ebook)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0bcc9b443897ed7e14d3e842788c9da)): ?>
<?php $attributes = $__attributesOriginald0bcc9b443897ed7e14d3e842788c9da; ?>
<?php unset($__attributesOriginald0bcc9b443897ed7e14d3e842788c9da); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0bcc9b443897ed7e14d3e842788c9da)): ?>
<?php $component = $__componentOriginald0bcc9b443897ed7e14d3e842788c9da; ?>
<?php unset($__componentOriginald0bcc9b443897ed7e14d3e842788c9da); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-12">
            <p class="text-text-secondary">Aucun eBook trouvé.</p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/ebook-grid.blade.php ENDPATH**/ ?>