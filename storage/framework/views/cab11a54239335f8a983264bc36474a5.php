<?php $__env->startSection('title', 'Connexion — APACC-M'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-shell">
    <div class="auth-card">
        <div class="space-y-1">
            <span class="letter-spacing-2">Connexion sécurisée</span>
            <h2>Accéder à ma bibliothèque</h2>
            <p class="text-muted" style="margin:0;font-size:0.9rem;">Connectez-vous pour accéder à vos eBooks et suivre vos commandes.</p>
        </div>

        <?php if(session('status')): ?>
            <div class="flash-success"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div class="space-y-1">
                <label for="email">Adresse email</label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username">
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
                <input id="password" type="password" name="password" required autocomplete="current-password">
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

            <div class="links">
                <label class="links-checkbox" for="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Se souvenir de moi</span>
                </label>
                <a href="<?php echo e(route('password.request')); ?>">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Connexion</button>
        </form>

        <p class="text-muted" style="text-align:center;font-size:0.9rem;margin:0;">
            Pas encore de compte ?
            <a href="<?php echo e(route('register')); ?>" style="font-weight:700;">Créer un compte</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/auth/login.blade.php ENDPATH**/ ?>