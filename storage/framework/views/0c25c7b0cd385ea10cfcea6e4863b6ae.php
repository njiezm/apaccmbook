<?php $__env->startSection('title', 'Contact — APACC-M'); ?>

<?php $__env->startSection('content'); ?>

<section style="background:var(--white);border-bottom:1px solid var(--border-light);padding:2.5rem 0 2rem;">
    <div class="container-custom">
        <span class="section-label">Nous joindre</span>
        <h1 style="font-size:2.2rem;margin-bottom:0.3rem;">Contact</h1>
        <p style="color:var(--text-secondary);font-size:1.05rem;margin:0;">Une question, une suggestion ? Notre équipe vous répond sous 24 h.</p>
    </div>
</section>

<div class="container-custom" style="padding-top:3rem;padding-bottom:5rem;">
    <div class="row g-5">

        
        <div class="col-md-7">
            <?php if(session('success')): ?>
                <div class="flash-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('contact.store')); ?>" style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:2rem;box-shadow:var(--shadow-soft);">
                <?php echo csrf_field(); ?>

                <div class="contact-form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Votre nom">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="error-text"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="contact-form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="votre@email.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="error-text"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="contact-form-group">
                    <label for="subject">Sujet</label>
                    <input type="text" id="subject" name="subject" value="<?php echo e(old('subject')); ?>" required placeholder="Objet de votre message">
                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="error-text"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="contact-form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required placeholder="Votre message…"><?php echo e(old('message')); ?></textarea>
                    <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="error-text"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn-primary">Envoyer le message</button>
            </form>
        </div>

        
        <div class="col-md-5">
            <div style="background:var(--cream);border-left:4px solid var(--cardinal);padding:2rem;border-radius:0 var(--radius-md) var(--radius-md) 0;margin-bottom:1.5rem;">
                <h3 style="font-size:1rem;text-transform:uppercase;letter-spacing:0.2em;color:var(--cardinal);margin-bottom:1.25rem;">Informations</h3>
                <p style="font-size:0.92rem;color:var(--text-secondary);line-height:1.7;margin-bottom:0.75rem;">
                    <strong style="color:var(--text-primary);">APACC-M</strong><br>
                    Association de Promotion et d'Animation<br>
                    de la Culture Catholique en Martinique
                </p>
                <p style="font-size:0.92rem;color:var(--text-secondary);line-height:1.7;margin:0;">
                    Nous répondons à toutes les demandes sous <strong>24 heures ouvrées</strong>, du lundi au vendredi.
                </p>
            </div>

            <div style="background:var(--white);border:1px solid var(--border-light);border-radius:var(--radius-md);padding:1.5rem;">
                <h3 style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.2em;color:var(--text-muted);margin-bottom:1rem;">Questions fréquentes</h3>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.75rem;">
                    <li style="font-size:0.9rem;color:var(--text-secondary);padding-bottom:0.75rem;border-bottom:1px solid var(--border-light);">
                        <strong style="display:block;color:var(--text-primary);margin-bottom:0.2rem;">Mon accès n'est pas encore actif</strong>
                        La validation s'effectue sous 12 à 24 h ouvrées après votre signalement de paiement.
                    </li>
                    <li style="font-size:0.9rem;color:var(--text-secondary);padding-bottom:0.75rem;border-bottom:1px solid var(--border-light);">
                        <strong style="display:block;color:var(--text-primary);margin-bottom:0.2rem;">Je ne vois pas mon eBook</strong>
                        Vérifiez votre section "Mes eBooks" après connexion. Si le problème persiste, contactez-nous.
                    </li>
                    <li style="font-size:0.9rem;color:var(--text-secondary);">
                        <strong style="display:block;color:var(--text-primary);margin-bottom:0.2rem;">Comment signaler un paiement ?</strong>
                        Sur la fiche de l'eBook, cliquez sur "J'ai effectué mon paiement" après avoir réglé via HelloAsso.
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/pages/contact.blade.php ENDPATH**/ ?>