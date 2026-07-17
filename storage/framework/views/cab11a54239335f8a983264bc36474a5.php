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
                <div style="position:relative;" x-data="{ show:false }">
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" style="padding-right:2.85rem;width:100%;">
                    <button type="button" @click="show = !show" :aria-label="show ? 'Masquer le mot de passe' : 'Afficher le mot de passe'" style="position:absolute;right:0.65rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);padding:0;display:flex;align-items:center;">
                        <svg x-show="!show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg x-show="show" x-cloak width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
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