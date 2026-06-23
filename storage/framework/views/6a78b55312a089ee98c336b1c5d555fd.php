<?php $__env->startSection('title', 'Inscription — APACC-M'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-shell">
    <div class="auth-card">
        <div class="space-y-1">
            <span class="letter-spacing-2">Créer un compte</span>
            <h2>Rejoindre la bibliothèque</h2>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">Créez votre espace personnel pour accéder aux eBooks et suivre vos achats.</p>
        </div>

        <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div class="space-y-1">
                <label for="name">Nom complet</label>
                <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus autocomplete="name" placeholder="Prénom Nom">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error-text"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username" placeholder="votre@email.com">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error-text"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="8 caractères minimum">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error-text"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="space-y-1">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Créer mon compte</button>
        </form>

        <p class="text-muted" style="text-align:center;font-size:0.9rem;margin:0;">
            Déjà inscrit ?
            <a href="<?php echo e(route('login')); ?>" style="font-weight:700;">Se connecter</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/auth/register.blade.php ENDPATH**/ ?>