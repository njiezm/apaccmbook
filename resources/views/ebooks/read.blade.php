@extends('layouts.app')

@section('title', 'Lecture — ' . $ebook->title)

@section('styles')
<style>
/* Page lecture : on garde le menu du site en bas sur mobile ; le footer n'est pas utile ici. */
.site-footer { display: none !important; }
.reader-float { z-index: 500; }

@media (max-width: 768px) {
    /* Commandes du lecteur juste AU-DESSUS du menu du site.
       Valeur de repli : le JS l'aligne précisément sur la hauteur réelle du menu. */
    .reader-shell:not(.fullscreen) .reader-float {
        bottom: calc(64px + env(safe-area-inset-bottom, 0));
    }
    /* Espace bas = menu du site + barre de commandes */
    .reader-shell:not(.fullscreen) { padding-bottom: 128px !important; }
}

/* ── Reader base ── */
.ebook-reader {
    aspect-ratio: unset !important;
    min-height: 300px;
    overflow: visible;
    background: #111;
    /* Anti-sélection / anti-copie du contenu */
    user-select: none;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
}
#pdf-canvas { pointer-events: auto; -webkit-user-drag: none; }

/* ── Protection : masque le rendu quand la fenêtre perd le focus ── */
#reader-guard {
    display: none;
    position: absolute;
    inset: 0;
    z-index: 50;
    background: #0a0a0a;
    color: rgba(255,255,255,0.85);
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
    line-height: 1.5;
}
.reader-guarded #reader-guard { display: flex; }
.reader-guarded #pdf-canvas { filter: blur(22px); }
.ebook-reader canvas {
    display: block;
}

/* ── Plein écran : canvas ombre ── */
.reader-shell.fullscreen .ebook-reader {
    overflow: visible !important;
    background: transparent !important;
    min-height: 0 !important;
}
.reader-shell.fullscreen .ebook-reader canvas {
    width: auto !important;
    height: auto !important;
    border-radius: 3px;
    box-shadow: 0 4px 60px rgba(0,0,0,0.8);
}

/* ── Spinner chargement ── */
#pdf-loading {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #111;
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    gap: 1rem;
    z-index: 10;
}
.pdf-spinner {
    width: 36px; height: 36px;
    border: 3px solid rgba(255,255,255,0.15);
    border-top-color: #b91c1c;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

#pdf-error {
    display: none;
    position: absolute;
    inset: 0;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background: #111;
    color: rgba(255,255,255,0.8);
    gap: 0.75rem;
    text-align: center;
    padding: 2rem;
    z-index: 11;
}
</style>
@endsection

@section('content')
@php
    $sommaireItems = $ebook->sommaireEntries();
@endphp
<section class="reader-shell">

    {{-- ── Barre titre + contrôles desktop (cachée sur mobile) ── --}}
    <div class="container-custom">
        <div class="reader-toolbar">
            <span>{{ $ebook->title }}</span>
            <span>Lecture sécurisée · téléchargement désactivé</span>
        </div>

        <div class="reader-controls">
            <div class="reader-nav">
                <button type="button" id="prev-page" class="btn-secondary">‹ Préc.</button>
                <div class="slider-wrapper">
                    <input id="page-slider" type="range" min="1" value="1">
                    <span id="page-indicator" style="min-width:64px;text-align:center;font-size:0.78rem;">— / —</span>
                </div>
                <button type="button" id="next-page" class="btn-secondary">Suiv. ›</button>
            </div>
            <div class="reader-actions">
                <button type="button" id="zoom-out" class="btn-secondary">A−</button>
                <span id="zoom-level" style="min-width:46px;text-align:center;font-size:0.78rem;">100%</span>
                <button type="button" id="zoom-in" class="btn-secondary">A+</button>
                @if($sommaireItems->isNotEmpty())
                    <button type="button" id="sommaire-btn" class="btn-secondary" title="Sommaire"><i class="fa-solid fa-list-ul"></i></button>
                @endif
                <button type="button" id="fullscreen-btn" class="btn-primary">Plein écran</button>
            </div>
        </div>
    </div>

    {{-- ── Zone PDF ── --}}
    <div id="reader-wrapper" style="width:min(100%,860px);margin:0 auto;padding:0 1rem;">
        <div class="ebook-reader" id="reader-box" oncontextmenu="return false;">
            <div id="pdf-loading">
                <div class="pdf-spinner"></div>
                <span>Chargement du document…</span>
            </div>
            <div id="pdf-error">
                <span style="font-size:2.5rem;">⚠</span>
                <p id="pdf-error-msg" style="margin:0;">Impossible de charger le document.</p>
                <a href="{{ route('ebooks.mine') }}" class="btn-secondary" style="border-color:rgba(255,255,255,0.5);color:white!important;">Retour à ma bibliothèque</a>
            </div>
            <canvas id="pdf-canvas" style="display:none;"></canvas>
            <div class="reader-overlay" id="reader-overlay">
                <p>Lecture sécurisée — le PDF ne peut pas être téléchargé.</p>
            </div>
            {{-- Protection : masque le contenu dès que la fenêtre perd le focus (capture/enregistrement) --}}
            <div id="reader-guard" aria-hidden="true">
                <span style="font-size:2rem;">🔒</span>
                <p style="margin:0.5rem 0 0;">Contenu protégé masqué.<br>Revenez sur la page pour reprendre la lecture.</p>
            </div>
        </div>
    </div>

    {{-- ── Barre flottante mobile (et fullscreen mobile) ── --}}
    <div class="reader-float" id="reader-float">
        <button type="button" id="float-prev"       class="btn-secondary" style="padding:0.3rem 0.6rem;" aria-label="Page précédente">‹</button>
        <span id="float-page-indicator"             style="color:#fff;font-size:0.72rem;min-width:48px;text-align:center;">— / —</span>
        <button type="button" id="float-next"       class="btn-secondary" style="padding:0.3rem 0.6rem;" aria-label="Page suivante">›</button>
        <button type="button" id="float-zoom-out"   class="btn-secondary" style="padding:0.3rem 0.6rem;" aria-label="Dézoomer">A−</button>
        <span id="float-zoom-level"                 style="color:#fff;font-size:0.72rem;min-width:40px;text-align:center;">100%</span>
        <button type="button" id="float-zoom-in"    class="btn-secondary" style="padding:0.3rem 0.6rem;" aria-label="Zoomer">A+</button>
        @if($sommaireItems->isNotEmpty())
            <button type="button" id="float-sommaire" class="btn-secondary" style="padding:0.3rem 0.6rem;" title="Sommaire"><i class="fa-solid fa-list-ul"></i></button>
        @endif
        <button type="button" id="float-fullscreen" class="btn-primary"   style="padding:0.3rem 0.75rem;font-size:0.78rem;" aria-label="Plein écran">⛶</button>
    </div>

    {{-- ── Modal Sommaire ── --}}
    @if($sommaireItems->isNotEmpty())
    <div id="sommaire-modal" style="display:none;position:fixed;inset:0;z-index:10001;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;padding:1rem;">
        <div style="background:#fff;color:#1a1a1a;border-radius:12px;max-width:520px;width:100%;max-height:80vh;overflow:auto;border-top:4px solid #b91c1c;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid #eee;position:sticky;top:0;background:#fff;">
                <h3 style="margin:0;font-size:1.1rem;">Sommaire</h3>
                <button type="button" id="sommaire-close" style="background:none;border:none;font-size:1.6rem;line-height:1;cursor:pointer;color:#888;">×</button>
            </div>
            <ul style="list-style:none;margin:0;padding:0.5rem 0;">
                @foreach($sommaireItems as $item)
                    <li>
                        @if($item['page'])
                            <button type="button" class="sommaire-jump" data-page="{{ $item['page'] }}"
                                    style="width:100%;text-align:left;background:none;border:none;border-bottom:1px solid #eee;padding:0.6rem 1.5rem;cursor:pointer;display:flex;justify-content:space-between;gap:1rem;font-size:0.92rem;color:#1a1a1a;">
                                <span style="min-width:0;">
                                    <span style="display:block;">{{ $item['title'] }}</span>
                                    @if(($item['subtitle'] ?? '') !== '')<span style="display:block;color:#888;font-size:0.82rem;margin-top:0.1rem;">{{ $item['subtitle'] }}</span>@endif
                                </span>
                                <span style="color:#b91c1c;flex-shrink:0;font-weight:600;">p. {{ $item['page'] }}</span>
                            </button>
                        @else
                            <div style="padding:0.6rem 1.5rem;border-bottom:1px solid #eee;font-size:0.92rem;">
                                <span style="display:block;">{{ $item['title'] }}</span>
                                @if(($item['subtitle'] ?? '') !== '')<span style="display:block;color:#888;font-size:0.82rem;margin-top:0.1rem;">{{ $item['subtitle'] }}</span>@endif
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Éléments ──────────────────────────────────────────────
    const canvas          = document.getElementById('pdf-canvas');
    const ctx             = canvas.getContext('2d');
    const loadingEl       = document.getElementById('pdf-loading');
    const errorEl         = document.getElementById('pdf-error');
    const errorMsgEl      = document.getElementById('pdf-error-msg');
    const overlay         = document.getElementById('reader-overlay');
    const pageSlider      = document.getElementById('page-slider');
    const pageIndicator   = document.getElementById('page-indicator');
    const prevBtn         = document.getElementById('prev-page');
    const nextBtn         = document.getElementById('next-page');
    const zoomOutBtn      = document.getElementById('zoom-out');
    const zoomInBtn       = document.getElementById('zoom-in');
    const zoomLevelEl     = document.getElementById('zoom-level');
    const fullscreenBtn   = document.getElementById('fullscreen-btn');
    const readerShell     = document.querySelector('.reader-shell');
    const readerBox       = document.getElementById('reader-box');
    const readerWrapper   = document.getElementById('reader-wrapper');

    const floatPrev       = document.getElementById('float-prev');
    const floatNext       = document.getElementById('float-next');
    const floatZoomOut    = document.getElementById('float-zoom-out');
    const floatZoomIn     = document.getElementById('float-zoom-in');
    const floatFullscreen = document.getElementById('float-fullscreen');
    const floatZoomLevel  = document.getElementById('float-zoom-level');
    const floatPageInd    = document.getElementById('float-page-indicator');

    // ── État ──────────────────────────────────────────────────
    let pdfDoc         = null;
    let pageNum        = {{ (int) ($startPage ?? 1) }};   // reprise de lecture
    const PROGRESS_URL = "{{ route('ebooks.progress', $ebook) }}";
    const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content;
    let pageRendering  = false;
    let pageNumPending = null;
    let scaleRatio     = 1;
    const MIN_RATIO    = 0.3;
    const MAX_RATIO    = 4;
    const ZOOM_STEP    = 0.25;

    // ── Helpers ───────────────────────────────────────────────
    const showError = (msg) => {
        loadingEl.style.display = 'none';
        if (errorMsgEl) errorMsgEl.textContent = msg;
        errorEl.style.display = 'flex';
    };

    const updateZoom = () => {
        const label = Math.round(scaleRatio * 100) + '%';
        if (zoomLevelEl)   zoomLevelEl.textContent  = label;
        if (floatZoomLevel) floatZoomLevel.textContent = label;
    };

    const setIndicator = () => {
        if (!pdfDoc) return;
        const txt = pageNum + ' / ' + pdfDoc.numPages;
        if (pageIndicator) pageIndicator.textContent = txt;
        if (floatPageInd)  floatPageInd.textContent  = txt;
        if (pageSlider)    pageSlider.value           = pageNum;
    };

    // ── Calcul de l'espace disponible en plein écran ──────────
    const fsMaxDims = () => {
        const isMobile = window.innerWidth < 1024;
        if (isMobile) {
            // Float bar bas ~52px + padding haut 12px
            return {
                w: window.innerWidth  - 24,
                h: window.innerHeight - 12 - 52,
            };
        } else {
            // Barre contrôles ~56px + padding wrapper 2×32px
            return {
                w: window.innerWidth  - 64,
                h: window.innerHeight - 120,
            };
        }
    };

    // ── Rendu ─────────────────────────────────────────────────
    const renderPage = (num) => {
        pageRendering = true;

        pdfDoc.getPage(num).then(page => {
            const naturalVP = page.getViewport({ scale: 1 });

            let baseScale;
            if (readerShell.classList.contains('fullscreen')) {
                const { w, h } = fsMaxDims();
                const isMobile = window.innerWidth < 1024;
                // Mobile : la page A4 remplit la largeur (échelle A4, défilement vertical).
                // Desktop : la page entière tient à l'écran.
                baseScale = isMobile
                    ? (w / naturalVP.width)
                    : Math.min(w / naturalVP.width, h / naturalVP.height);
            } else {
                const containerW = readerBox.clientWidth || readerBox.offsetWidth || 600;
                baseScale = containerW / naturalVP.width;
            }

            const scale = baseScale * scaleRatio;
            const vp    = page.getViewport({ scale });

            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            canvas.width  = Math.floor(vp.width  * dpr);
            canvas.height = Math.floor(vp.height * dpr);
            canvas.style.width  = vp.width  + 'px';
            canvas.style.height = vp.height + 'px';
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            return page.render({ canvasContext: ctx, viewport: vp }).promise;
        })
        .then(() => {
            pageRendering = false;
            canvas.style.display = 'block';
            loadingEl.style.display = 'none';
            overlay.classList.add('hidden');
            setIndicator();

            if (pageNumPending !== null) {
                const next = pageNumPending;
                pageNumPending = null;
                renderPage(next);
            }
        })
        .catch(err => {
            pageRendering = false;
            showError('Erreur lors du rendu de la page ' + num + '.');
        });
    };

    const queueRender = (num) => {
        if (pageRendering) { pageNumPending = num; } else { renderPage(num); }
    };

    // ── Reprise de lecture : sauvegarde débattue de la page courante ──
    let progressTimer = null;
    const saveProgress = () => {
        if (!CSRF_TOKEN) return;
        clearTimeout(progressTimer);
        progressTimer = setTimeout(() => {
            fetch(PROGRESS_URL, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ page: pageNum }),
            }).catch(() => {});
        }, 800);
    };

    const showPage = (num) => {
        if (!pdfDoc || num < 1 || num > pdfDoc.numPages) return;
        pageNum = num;
        // En plein écran scrollable : revenir en haut à chaque changement de page
        if (readerShell.classList.contains('fullscreen') && readerWrapper) {
            readerWrapper.scrollTop = 0;
            readerWrapper.scrollLeft = 0;
        }
        queueRender(pageNum);
        saveProgress();
    };

    const applyZoom = (dir) => {
        scaleRatio = Math.min(MAX_RATIO, Math.max(MIN_RATIO, scaleRatio + dir * ZOOM_STEP));
        updateZoom();
        queueRender(pageNum);
    };

    const toggleFullscreen = () => {
        const entering = !readerShell.classList.contains('fullscreen');
        readerShell.classList.toggle('fullscreen');

        // Réinitialise le zoom dans les deux sens pour un "fit to screen" propre
        // (à l'entrée ET à la sortie, pour que le document se réadapte à l'écran)
        scaleRatio = 1;
        updateZoom();

        if (fullscreenBtn) fullscreenBtn.textContent   = entering ? 'Quitter' : 'Plein écran';
        if (floatFullscreen) floatFullscreen.textContent = entering ? '✕' : '⛶';
        alignReaderFloat();
        if (pdfDoc) queueRender(pageNum);
    };

    // Aligne précisément la barre de commandes juste au-dessus du menu du site (mobile)
    const siteBottomNav = document.querySelector('.mobile-bottom-nav');
    const readerFloatEl = document.getElementById('reader-float');
    function alignReaderFloat() {
        if (!readerFloatEl) return;
        const mobile = window.matchMedia('(max-width: 768px)').matches;
        const fs = readerShell.classList.contains('fullscreen');
        if (mobile && !fs && siteBottomNav && getComputedStyle(siteBottomNav).display !== 'none') {
            readerFloatEl.style.setProperty('bottom', siteBottomNav.offsetHeight + 'px', 'important');
        } else {
            readerFloatEl.style.removeProperty('bottom');
        }
    }
    window.addEventListener('resize', alignReaderFloat);
    document.addEventListener('DOMContentLoaded', alignReaderFloat);

    // ── Glisser pour déplacer (pan) quand le document dépasse l'écran ──
    let isPanning = false, panStartX = 0, panStartY = 0, panLeft = 0, panTop = 0;
    readerWrapper?.addEventListener('pointerdown', e => {
        if (e.pointerType !== 'mouse') return; // le tactile garde le scroll natif
        const canScroll = readerWrapper.scrollWidth > readerWrapper.clientWidth ||
                          readerWrapper.scrollHeight > readerWrapper.clientHeight;
        if (!canScroll) return;
        isPanning = true;
        readerWrapper.classList.add('panning');
        panStartX = e.clientX; panStartY = e.clientY;
        panLeft = readerWrapper.scrollLeft; panTop = readerWrapper.scrollTop;
        try { readerWrapper.setPointerCapture(e.pointerId); } catch (_) {}
        e.preventDefault();
    });
    readerWrapper?.addEventListener('pointermove', e => {
        if (!isPanning) return;
        readerWrapper.scrollLeft = panLeft - (e.clientX - panStartX);
        readerWrapper.scrollTop  = panTop  - (e.clientY - panStartY);
    });
    const endPan = (e) => {
        if (!isPanning) return;
        isPanning = false;
        readerWrapper.classList.remove('panning');
        try { readerWrapper.releasePointerCapture(e.pointerId); } catch (_) {}
    };
    readerWrapper?.addEventListener('pointerup', endPan);
    readerWrapper?.addEventListener('pointercancel', endPan);

    // ── Événements ────────────────────────────────────────────
    prevBtn?.addEventListener('click',        () => showPage(pageNum - 1));
    nextBtn?.addEventListener('click',        () => showPage(pageNum + 1));
    pageSlider?.addEventListener('input',     e  => showPage(Number(e.target.value)));
    zoomOutBtn?.addEventListener('click',     () => applyZoom(-1));
    zoomInBtn?.addEventListener('click',      () => applyZoom(1));
    fullscreenBtn?.addEventListener('click',  toggleFullscreen);

    floatPrev?.addEventListener('click',      () => showPage(pageNum - 1));
    floatNext?.addEventListener('click',      () => showPage(pageNum + 1));
    floatZoomOut?.addEventListener('click',   () => applyZoom(-1));
    floatZoomIn?.addEventListener('click',    () => applyZoom(1));
    floatFullscreen?.addEventListener('click',toggleFullscreen);

    window.addEventListener('resize', () => { if (pdfDoc) queueRender(pageNum); });

    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') showPage(pageNum + 1);
        if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   showPage(pageNum - 1);
        if (e.key === '+') applyZoom(1);
        if (e.key === '-') applyZoom(-1);
        if (e.key === 'f') toggleFullscreen();
        if (e.key === 'Escape' && readerShell.classList.contains('fullscreen')) toggleFullscreen();
    });

    // ── Swipe tactile pour tourner les pages (quand le document n'est pas zoomé) ──
    let swipeX = 0, swipeY = 0, swipeT = 0, swipeActive = false;
    readerBox?.addEventListener('touchstart', e => {
        if (e.touches.length !== 1) { swipeActive = false; return; }
        swipeActive = true;
        swipeX = e.touches[0].clientX;
        swipeY = e.touches[0].clientY;
        swipeT = Date.now();
    }, { passive: true });
    readerBox?.addEventListener('touchend', e => {
        if (!swipeActive) return;
        swipeActive = false;
        // Si le document est zoomé, on laisse le défilement/déplacement natif
        if (scaleRatio > 1.05) return;
        const t  = e.changedTouches[0];
        const dx = t.clientX - swipeX;
        const dy = t.clientY - swipeY;
        const dt = Date.now() - swipeT;
        // Geste horizontal franc et rapide
        if (dt < 700 && Math.abs(dx) > 55 && Math.abs(dx) > Math.abs(dy) * 1.6) {
            if (dx < 0) showPage(pageNum + 1);   // swipe vers la gauche → page suivante
            else        showPage(pageNum - 1);   // swipe vers la droite → page précédente
        }
    }, { passive: true });

    // ── Protection anti-capture / anti-enregistrement (dissuasion) ──
    const guardOn  = () => readerShell.classList.add('reader-guarded');
    const guardOff = () => readerShell.classList.remove('reader-guarded');

    // Masque le contenu dès que la page perd le focus / est masquée
    document.addEventListener('visibilitychange', () => document.hidden ? guardOn() : guardOff());
    window.addEventListener('blur',  guardOn);
    window.addEventListener('focus', guardOff);

    // Touche « Impr. écran » : tentative d'effacement du presse-papier + masquage bref
    document.addEventListener('keyup', (e) => {
        if (e.key === 'PrintScreen') {
            try { navigator.clipboard.writeText(''); } catch (_) {}
            guardOn();
            setTimeout(guardOff, 1200);
        }
    });

    // Bloque impression / enregistrement / outils développeur
    document.addEventListener('keydown', (e) => {
        const k = (e.key || '').toLowerCase();
        if ((e.ctrlKey || e.metaKey) && (k === 'p' || k === 's')) e.preventDefault();
        if (e.ctrlKey && e.shiftKey && ['i', 'j', 'c', 's'].includes(k)) e.preventDefault();
        if (k === 'f12') e.preventDefault();
    });

    // ── Sommaire (modal + saut de page) ──
    const sommaireModal = document.getElementById('sommaire-modal');
    const openSommaire  = () => { if (sommaireModal) sommaireModal.style.display = 'flex'; };
    const closeSommaire = () => { if (sommaireModal) sommaireModal.style.display = 'none'; };
    document.getElementById('sommaire-btn')?.addEventListener('click', openSommaire);
    document.getElementById('float-sommaire')?.addEventListener('click', openSommaire);
    document.getElementById('sommaire-close')?.addEventListener('click', closeSommaire);
    sommaireModal?.addEventListener('click', (e) => { if (e.target === sommaireModal) closeSommaire(); });
    document.querySelectorAll('.sommaire-jump').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = parseInt(btn.dataset.page, 10);
            if (p) showPage(p);
            closeSommaire();
        });
    });

    // ── Chargement PDF ────────────────────────────────────────
    const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;
    if (!pdfjsLib) { showError('PDF.js non disponible.'); return; }

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.worker.min.js';

    fetch("{{ route('ebooks.pdf', $ebook) }}", { credentials: 'same-origin' })
        .then(r => {
            if (!r.ok) throw new Error('Accès refusé (HTTP ' + r.status + ').');
            return r.arrayBuffer();
        })
        .then(buf => pdfjsLib.getDocument({ data: buf }).promise)
        .then(pdf => {
            pdfDoc = pdf;
            // Borne la page de reprise au nombre réel de pages
            pageNum = Math.min(Math.max(1, pageNum), pdf.numPages);
            if (pageSlider) pageSlider.max = pdf.numPages;
            updateZoom();
            setIndicator();
            renderPage(pageNum);
        })
        .catch(err => showError('Impossible de charger le document. ' + err.message));
}());
</script>
@endsection
