<!-- Bouton Secondaire (Outline) -->
<?php if($href ?? false): ?>
    <a
        href="<?php echo e($href); ?>"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600
               border-2 border-cardinal text-cardinal hover:bg-cream transition-colors <?php echo e($class ?? ''); ?>"
    >
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button
        type="<?php echo e($type ?? 'button'); ?>"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600
               border-2 border-cardinal text-cardinal hover:bg-cream transition-colors <?php echo e($class ?? ''); ?>"
    >
        <?php echo e($slot); ?>

    </button>
<?php endif; ?>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/button-secondary.blade.php ENDPATH**/ ?>