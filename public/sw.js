// APACC-M e-Livre — Service Worker
const CACHE_NAME = 'apacc-m-v1';

// Ressources à pré-cacher au premier chargement
const PRECACHE = [
    '/',
    '/ebooks',
    '/css/apacc-m.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap',
];

// ── Installation ──────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting())
    );
});

// ── Activation (nettoyage des anciens caches) ─────────────────────────────────
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Stratégie : Network-first pour les pages, Cache-first pour les assets ─────
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Ne jamais mettre en cache les endpoints sécurisés (PDF, admin, auth)
    if (
        url.pathname.startsWith('/admin') ||
        url.pathname.includes('/pdf') ||
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/register') ||
        url.pathname.startsWith('/logout') ||
        request.method !== 'GET'
    ) {
        return;
    }

    // Assets statiques (CSS, JS, fonts, images) → Cache-first
    if (
        url.pathname.startsWith('/css/') ||
        url.pathname.startsWith('/js/') ||
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/images/') ||
        url.hostname !== self.location.hostname
    ) {
        event.respondWith(
            caches.match(request).then(cached => cached || fetch(request).then(resp => {
                const clone = resp.clone();
                caches.open(CACHE_NAME).then(c => c.put(request, clone));
                return resp;
            }))
        );
        return;
    }

    // Pages HTML → Network-first avec fallback sur cache
    event.respondWith(
        fetch(request)
            .then(resp => {
                const clone = resp.clone();
                caches.open(CACHE_NAME).then(c => c.put(request, clone));
                return resp;
            })
            .catch(() => caches.match(request))
    );
});
