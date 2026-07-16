<?php $__env->startSection('body'); ?>
<h1>Inscription confirmée !</h1>
<p>Merci de vous être inscrit(e) à la newsletter d'<strong>APACC-M e-Livre</strong>. Vous serez informé(e) en avant-première de chaque nouvelle parution de notre bibliothèque numérique.</p>

<div style="text-align:center;margin:28px 0;">
    <a href="<?php echo e(route('ebooks.index')); ?>" class="btn">Découvrir le catalogue</a>
</div>

<hr class="divider">
<p style="font-size:13px;color:#888;">Pour vous désinscrire à tout moment, <a href="<?php echo e(route('newsletter.unsubscribe', ['email' => $email])); ?>" style="color:#b91c1c;">cliquez ici</a>.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/emails/newsletter-welcome.blade.php ENDPATH**/ ?>