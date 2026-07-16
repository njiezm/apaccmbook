@extends('layouts.app')

@section('title', 'Lecture — ' . $ebook->title)

@section('styles')
<style>
/* ── Reader base ── */
.ebook-reader {
    aspect-ratio: unset !important;
    min-height: 300px;
    overflow: visible;
    background: #111;
}
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
        </div>
    </div>

    {{-- ── Barre flottante mobile (et fullscreen mobile) ── --}}
    <div class="reader-float" id="reader-float">
        <button type="button" id="float-prev"       class="btn-secondary" style="padding:0.3rem 0.6rem;">‹</button>
        <span id="float-page-indicator"             style="color:#fff;font-size:0.72rem;min-width:48px;text-align:center;">— / —</span>
        <button type="button" id="float-next"       class="btn-secondary" style="padding:0.3rem 0.6rem;">›</button>
        <button type="button" id="float-zoom-out"   class="btn-secondary" style="padding:0.3rem 0.6rem;">A−</button>
        <span id="float-zoom-level"                 style="color:#fff;font-size:0.72rem;min-width:40px;text-align:center;">100%</span>
        <button type="button" id="float-zoom-in"    class="btn-secondary" style="padding:0.3rem 0.6rem;">A+</button>
        <button type="button" id="float-fullscreen" class="btn-primary"   style="padding:0.3rem 0.75rem;font-size:0.78rem;">⛶</button>
    </div>

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
    let pageNum        = 1;
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
                baseScale = Math.min(w / naturalVP.width, h / naturalVP.height);
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

    const showPage = (num) => {
        if (!pdfDoc || num < 1 || num > pdfDoc.numPages) return;
        pageNum = num;
        // En plein écran scrollable : revenir en haut à chaque changement de page
        if (readerShell.classList.contains('fullscreen') && readerWrapper) {
            readerWrapper.scrollTop = 0;
            readerWrapper.scrollLeft = 0;
        }
        queueRender(pageNum);
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
        if (pdfDoc) queueRender(pageNum);
    };

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
            if (pageSlider) pageSlider.max = pdf.numPages;
            updateZoom();
            setIndicator();
            renderPage(pageNum);
        })
        .catch(err => showError('Impossible de charger le document. ' + err.message));
}());
</script>
@endsection
