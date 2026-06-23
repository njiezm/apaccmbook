<!-- Carte E-Book Individuelle -->
<?php if (isset($component)) { $__componentOriginale9bc6b36f4e6a04c3a23d63699361368 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale9bc6b36f4e6a04c3a23d63699361368 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.arch-card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('arch-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Cover Image -->
    <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
        <img
            src="<?php echo e(asset('storage/' . ($ebook->cover_image ?? 'ebooks/default-cover.jpg'))); ?>"
            alt="<?php echo e($ebook->title); ?>"
            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
        >

        <?php if($ebook->status === 'draft'): ?>
            <div class="absolute top-4 right-4 bg-cardinal text-white px-3 py-1 rounded font-sans text-sm font-600">
                Brouillon
            </div>
        <?php elseif($ebook->created_at > now()->subDays(7)): ?>
            <div class="absolute top-4 right-4 bg-cardinal text-white px-3 py-1 rounded font-sans text-sm font-600">
                Nouveau
            </div>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="p-6">
        <h3 class="font-serif font-bold text-lg text-text-primary line-clamp-2 mb-2">
            <?php echo e($ebook->title); ?>

        </h3>

        <p class="text-sm text-text-secondary mb-4">
            par <span class="font-600"><?php echo e($ebook->author->name ?? 'Auteur inconnu'); ?></span>
        </p>

        <!-- Rating -->
        <?php if($ebook->avg_rating ?? false): ?>
            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-cardinal">
                    <?php for($i = 0; $i < 5; $i++): ?>
                        <?php if($i < floor($ebook->avg_rating)): ?>
                            <span>⭐</span>
                        <?php elseif($i < $ebook->avg_rating): ?>
                            <span>✨</span>
                        <?php else: ?>
                            <span>☆</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <span class="text-xs text-text-tertiary">(<?php echo e($ebook->reviews_count ?? 0); ?>)</span>
            </div>
        <?php endif; ?>

        <!-- Price -->
        <div class="mb-6 text-lg font-bold text-cardinal">
            <?php echo e(number_format($ebook->price ?? 0, 2, ',', ' ')); ?> €
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <?php if (isset($component)) { $__componentOriginal3b9eae2fda1979ebeecf9420d156d189 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b9eae2fda1979ebeecf9420d156d189 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-secondary','data' => ['href' => '/ebooks/'.e($ebook->slug ?? $ebook->id).'','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/ebooks/'.e($ebook->slug ?? $ebook->id).'','class' => 'flex-1']); ?>
                Détails
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b9eae2fda1979ebeecf9420d156d189)): ?>
<?php $attributes = $__attributesOriginal3b9eae2fda1979ebeecf9420d156d189; ?>
<?php unset($__attributesOriginal3b9eae2fda1979ebeecf9420d156d189); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b9eae2fda1979ebeecf9420d156d189)): ?>
<?php $component = $__componentOriginal3b9eae2fda1979ebeecf9420d156d189; ?>
<?php unset($__componentOriginal3b9eae2fda1979ebeecf9420d156d189); ?>
<?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <form action="<?php echo e(route('purchases.store')); ?>" method="POST" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                    <button
                        type="submit"
                        class="w-full bg-cardinal text-white px-4 py-2 rounded hover:bg-cardinal-hover transition-colors font-sans font-600"
                    >
                        Acheter
                    </button>
                </form>
            <?php else: ?>
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['href' => '/login','class' => 'flex-1 text-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => '/login','class' => 'flex-1 text-sm']); ?>Acheter <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale9bc6b36f4e6a04c3a23d63699361368)): ?>
<?php $attributes = $__attributesOriginale9bc6b36f4e6a04c3a23d63699361368; ?>
<?php unset($__attributesOriginale9bc6b36f4e6a04c3a23d63699361368); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale9bc6b36f4e6a04c3a23d63699361368)): ?>
<?php $component = $__componentOriginale9bc6b36f4e6a04c3a23d63699361368; ?>
<?php unset($__componentOriginale9bc6b36f4e6a04c3a23d63699361368); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/ebook-card.blade.php ENDPATH**/ ?>