<!-- Ligne Narthex (séparateur) -->
<?php if($double ?? false): ?>
    <!-- Double ligne : 1px gris + 4px rouge -->
    <div class="border-t border-border-light">
        <div class="border-t-4 border-cardinal"></div>
    </div>
<?php else: ?>
    <!-- Simple : 4px rouge pleine largeur -->
    <div class="border-t-4 border-cardinal w-full"></div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/components/narthex-line.blade.php ENDPATH**/ ?>