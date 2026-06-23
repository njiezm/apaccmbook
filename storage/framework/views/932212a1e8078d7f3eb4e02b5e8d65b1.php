<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#b91c1c">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="APACC-M e-Livre">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="description" content="Bibliothèque numérique de l'APACC-M — patrimoine culturel et religieux martiniquais">
    <title><?php echo $__env->yieldContent('title', 'APACC-M e-Livre'); ?></title>

    <link rel="icon" href="<?php echo e(asset('icons/icon.svg')); ?>" type="image/svg+xml">
    <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/apacc-m.css')); ?>">

    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>

<!-- ═══════════════════════ NAVBAR DESKTOP ═══════════════════════ -->
<nav class="site-navbar" role="navigation" aria-label="Navigation principale">
    <div class="navbar-inner">

        <a class="navbar-brand" href="<?php echo e(route('home')); ?>" aria-label="APACC-M e-Livre — Accueil">
            APACC-M<span class="brand-suffix">e-Livre</span>
        </a>

        <!-- Desktop links -->
        <ul class="nav-links" role="list">
            <li><a class="nav-link<?php echo e(request()->routeIs('home') ? ' active' : ''); ?>" href="<?php echo e(route('home')); ?>">Accueil</a></li>
            <li><a class="nav-link<?php echo e(request()->routeIs('ebooks.index') ? ' active' : ''); ?>" href="<?php echo e(route('ebooks.index')); ?>">Catalogue</a></li>
            <li><a class="nav-link<?php echo e(request()->routeIs('about') ? ' active' : ''); ?>" href="<?php echo e(route('about')); ?>">À propos</a></li>
            <li><a class="nav-link<?php echo e(request()->routeIs('contact') ? ' active' : ''); ?>" href="<?php echo e(route('contact')); ?>">Contact</a></li>
            <?php if(auth()->guard()->check()): ?>
            <li><a class="nav-link<?php echo e(request()->routeIs('ebooks.mine') ? ' active' : ''); ?>" href="<?php echo e(route('ebooks.mine')); ?>">Mes e-Livres</a></li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ebooks')): ?>
            <li><a class="nav-link nav-link--admin<?php echo e(request()->routeIs('admin.*') ? ' active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">Admin</a></li>
            <?php endif; ?>
        </ul>

        <div class="nav-actions">
            <?php if(auth()->guard()->check()): ?>
                <span class="nav-user"><?php echo e(Auth::user()->name); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-ghost btn-sm">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn-ghost btn-sm">Connexion</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-primary btn-sm">S'inscrire</a>
            <?php endif; ?>
        </div>

        <!-- Hamburger (mobile only) -->
        <button class="hamburger" id="hamburger-btn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-drawer">
            <span class="ham-bar"></span>
            <span class="ham-bar"></span>
            <span class="ham-bar"></span>
        </button>
    </div>
</nav>

<!-- ═══════════════════════ MOBILE DRAWER ═══════════════════════ -->
<div class="drawer-overlay" id="drawer-overlay" aria-hidden="true"></div>

<aside class="mobile-drawer" id="mobile-drawer" role="dialog" aria-modal="true" aria-label="Menu de navigation">
    <div class="drawer-header">
        <span class="drawer-brand">APACC-M <em>e-Livre</em></span>
        <button class="drawer-close" id="drawer-close" aria-label="Fermer le menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <?php if(auth()->guard()->check()): ?>
    <div class="drawer-user">
        <div class="drawer-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></div>
        <div>
            <div class="drawer-user-name"><?php echo e(Auth::user()->name); ?></div>
            <div class="drawer-user-email"><?php echo e(Auth::user()->email); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <nav class="drawer-nav" aria-label="Navigation mobile">
        <a href="<?php echo e(route('home')); ?>" class="drawer-link<?php echo e(request()->routeIs('home') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Accueil
        </a>
        <a href="<?php echo e(route('ebooks.index')); ?>" class="drawer-link<?php echo e(request()->routeIs('ebooks.index') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            Catalogue
        </a>
        <a href="<?php echo e(route('about')); ?>" class="drawer-link<?php echo e(request()->routeIs('about') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            À propos
        </a>
        <a href="<?php echo e(route('contact')); ?>" class="drawer-link<?php echo e(request()->routeIs('contact') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Contact
        </a>

        <?php if(auth()->guard()->check()): ?>
        <div class="drawer-separator"></div>
        <a href="<?php echo e(route('ebooks.mine')); ?>" class="drawer-link<?php echo e(request()->routeIs('ebooks.mine') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Mes e-Livres
        </a>
        <a href="<?php echo e(route('profile.edit')); ?>" class="drawer-link<?php echo e(request()->routeIs('profile.edit') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Mon profil
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ebooks')): ?>
        <div class="drawer-separator"></div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="drawer-link drawer-link--admin<?php echo e(request()->routeIs('admin.*') ? ' drawer-link--active' : ''); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Administration
        </a>
        <?php endif; ?>
    </nav>

    <div class="drawer-footer">
        <?php if(auth()->guard()->check()): ?>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="drawer-logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Se déconnecter
            </button>
        </form>
        <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn-primary" style="width:100%;text-align:center;display:block;">Connexion</a>
        <a href="<?php echo e(route('register')); ?>" class="btn-ghost" style="width:100%;text-align:center;display:block;margin-top:0.5rem;">Créer un compte</a>
        <?php endif; ?>
        <p class="drawer-site-link"><a href="https://apacc-martinique.fr" target="_blank" rel="noopener">↗ apacc-martinique.fr</a></p>
    </div>
</aside>

<!-- ═══════════════════════════ MAIN ═══════════════════════════ -->
<main id="main-content">
    <div class="container-custom" style="padding-top:0.5rem;">
        <?php if(session('status') || session('success')): ?>
            <div class="flash-success mt-3"><?php echo e(session('status') ?? session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="flash-error mt-3"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert-danger mt-3">
                <ul class="mb-0 ps-3">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php echo $__env->yieldContent('content'); ?>
</main>

<!-- ═══════════════════════════ FOOTER ═══════════════════════════ -->
<footer class="site-footer">
    <div class="container-custom">
        <div class="row g-4">
            <div class="col-md-4">
                <span class="footer-brand">APACC-M e-Livre</span>
                <p>Bibliothèque numérique de l'APACC-M — association dédiée à la promotion du patrimoine culturel et religieux martiniquais.</p>
                <a href="https://apacc-martinique.fr" target="_blank" rel="noopener" class="footer-ext-link">↗ apacc-martinique.fr</a>
            </div>
            <div class="col-md-2 col-6">
                <h6>Navigation</h6>
                <ul>
                    <li><a href="<?php echo e(route('home')); ?>">Accueil</a></li>
                    <li><a href="<?php echo e(route('ebooks.index')); ?>">Catalogue</a></li>
                    <li><a href="<?php echo e(route('about')); ?>">À propos</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-2 col-6">
                <h6>Mon compte</h6>
                <ul>
                    <?php if(auth()->guard()->check()): ?>
                        <li><a href="<?php echo e(route('ebooks.mine')); ?>">Mes e-Livres</a></li>
                        <li><a href="<?php echo e(route('profile.edit')); ?>">Mon profil</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo e(route('login')); ?>">Connexion</a></li>
                        <li><a href="<?php echo e(route('register')); ?>">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h6>Informations légales</h6>
                <ul>
                    <li><a href="<?php echo e(route('terms')); ?>">Conditions générales</a></li>
                    <li><a href="<?php echo e(route('privacy')); ?>">Confidentialité</a></li>
                    <li><a href="<?php echo e(route('legal')); ?>">Mentions légales</a></li>
                </ul>
            </div>
        </div>
        <hr class="footer-divider">
        <p class="footer-copyright">&copy; <?php echo e(date('Y')); ?> APACC-M · Martinique · Tous droits réservés</p>
    </div>
</footer>

<!-- ═══════════════════ MOBILE BOTTOM NAV ═══════════════════ -->
<nav class="mobile-bottom-nav" aria-label="Navigation rapide">
    <a href="<?php echo e(route('home')); ?>" class="mobile-nav-item<?php echo e(request()->routeIs('home') ? ' active' : ''); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Accueil</span>
    </a>
    <a href="<?php echo e(route('ebooks.index')); ?>" class="mobile-nav-item<?php echo e(request()->routeIs('ebooks.index') ? ' active' : ''); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        <span>Catalogue</span>
    </a>
    <?php if(auth()->guard()->check()): ?>
    <a href="<?php echo e(route('ebooks.mine')); ?>" class="mobile-nav-item<?php echo e(request()->routeIs('ebooks.mine') ? ' active' : ''); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Mes livres</span>
    </a>
    <?php else: ?>
    <a href="<?php echo e(route('login')); ?>" class="mobile-nav-item<?php echo e(request()->routeIs('login') ? ' active' : ''); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        <span>Connexion</span>
    </a>
    <?php endif; ?>
    <button class="mobile-nav-item mobile-nav-menu" id="mobile-menu-btn" aria-label="Menu" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        <span>Menu</span>
    </button>
</nav>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
(function () {
    const drawer  = document.getElementById('mobile-drawer');
    const overlay = document.getElementById('drawer-overlay');
    const hamBtn  = document.getElementById('hamburger-btn');
    const menuBtn = document.getElementById('mobile-menu-btn');
    const closeBtn= document.getElementById('drawer-close');

    function openDrawer() {
        drawer.classList.add('open');
        overlay.classList.add('open');
        document.body.classList.add('drawer-open');
        if (hamBtn)  hamBtn.setAttribute('aria-expanded', 'true');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
        drawer.querySelector('a, button').focus();
    }

    function closeDrawer() {
        drawer.classList.remove('open');
        overlay.classList.remove('open');
        document.body.classList.remove('drawer-open');
        if (hamBtn)  hamBtn.setAttribute('aria-expanded', 'false');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
    }

    hamBtn?.addEventListener('click', openDrawer);
    menuBtn?.addEventListener('click', openDrawer);
    closeBtn?.addEventListener('click', closeDrawer);
    overlay?.addEventListener('click', closeDrawer);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Fermer le drawer quand on clique sur un lien
    drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
})();

// Reveal on scroll
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
});

// Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .catch(() => {});
    });
}
</script>

<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/layouts/app.blade.php ENDPATH**/ ?>