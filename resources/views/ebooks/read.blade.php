@extends('layouts.app')

@section('title', 'Lecture — ' . $ebook->title)

@section('styles')
<style>
/* Reader override — canvas contrôle sa propre hauteur */
.ebook-reader {
    aspect-ratio: unset !important;
    min-height: 400px;
    overflow: auto;
    background: #111;
}
.ebook-reader canvas {
    display: block;
    /* La largeur/hauteur est pilotée par le JS via style="" */
}

/* ── Plein écran : canvas avec ombre, pas de scroll ── */
.reader-shell.fullscreen .ebook-reader {
    overflow: hidden !important;
    background: transparent !important;
}
.reader-shell.fullscreen .ebook-reader canvas {
    width: auto !important;
    height: auto !important;
    border-radius: 3px;
    box-shadow: 0 4px 60px rgba(0, 0, 0, 0.8);
}

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
    <div class="container-custom">
        <div class="reader-toolbar">
            <span>{{ $ebook->title }}</span>
            <span>Lecture sécurisée · téléchargement désactivé</span>
        </div>

        <div class="reader-controls">
            <div class="reader-nav">
                <button type="button" id="prev-page" class="btn-secondary">‹ Prec.</button>
                <div class="slider-wrapper">
                    <input id="page-slider" type="range" min="1" value="1">
                    <span id="page-indicator" style="min-width:70px;text-align:center;">— / —</span>
                </div>
                <button type="button" id="next-page" class="btn-secondary">Suiv. ›</button>
            </div>
            <div class="reader-actions">
                <button type="button" id="zoom-out" class="btn-secondary">A−</button>
                <span id="zoom-level" style="min-width:52px;text-align:center;">100%</span>
                <button type="button" id="zoom-in" class="btn-secondary">A+</button>
                <button type="button" id="fullscreen-btn" class="btn-primary">Plein écran</button>
            </div>
        </div>
    </div>

    <div id="reader-wrapper" style="width:min(100%,860px);margin:0 auto;padding:0 1rem;">
        <div class="ebook-reader" id="reader-box" oncontextmenu="return false;">
            {{-- État chargement --}}
            <div id="pdf-loading">
                <div class="pdf-spinner"></div>
                <span>Chargement du document…</span>
            </div>

            {{-- État erreur --}}
            <div id="pdf-error" style="display:none;position:absolute;inset:0;align-items:center;justify-content:center;flex-direction:column;background:#111;color:rgba(255,255,255,0.8);gap:0.75rem;text-align:center;padding:2rem;z-index:11;">
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

    <div class="reader-float" id="reader-float">
        <button type="button" id="float-prev"       class="btn-secondary" style="padding:0.3rem 0.65rem;">‹</button>
        <button type="button" id="float-next"       class="btn-secondary" style="padding:0.3rem 0.65rem;">›</button>
        <button type="button" id="float-zoom-out"   class="btn-secondary" style="padding:0.3rem 0.65rem;">A−</button>
        <span id="float-zoom-level" style="color:#fff;font-size:0.75rem;min-width:44px;text-align:center;">100%</span>
        <button type="button" id="float-zoom-in"    class="btn-secondary" style="padding:0.3rem 0.65rem;">A+</button>
        <button type="button" id="float-fullscreen" class="btn-primary"   style="padding:0.3rem 0.85rem;">⛶</button>
    </div>
</section>

{{-- PDF.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Éléments ──────────────────────────────────────────────
    const canvas        = document.getElementById('pdf-canvas');
    const ctx           = canvas.getContext('2d');
    const loadingEl     = document.getElementById('pdf-loading');
    const errorEl       = document.getElementById('pdf-error');
    const errorMsgEl    = document.getElementById('pdf-error-msg');
    const overlay       = document.getElementById('reader-overlay');
    const pageSlider    = document.getElementById('page-slider');
    const pageIndicator = document.getElementById('page-indicator');
    const prevBtn       = document.getElementById('prev-page');
    const nextBtn       = document.getElementById('next-page');
    const zoomOutBtn    = document.getElementById('zoom-out');
    const zoomInBtn     = document.getElementById('zoom-in');
    const zoomLevelEl   = document.getElementById('zoom-level');
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const readerShell   = document.querySelector('.reader-shell');
    const readerBox     = document.getElementById('reader-box');

    const floatPrev      = document.getElementById('float-prev');
    const floatNext      = document.getElementById('float-next');
    const floatZoomOut   = document.getElementById('float-zoom-out');
    const floatZoomIn    = document.getElementById('float-zoom-in');
    const floatFullscreen= document.getElementById('float-fullscreen');
    const floatZoomLevel = document.getElementById('float-zoom-level');

    // ── État ──────────────────────────────────────────────────
    let pdfDoc          = null;
    let pageNum         = 1;
    let pageRendering   = false;
    let pageNumPending  = null;
    let scaleRatio      = 1;
    const MIN_RATIO     = 0.4;
    const MAX_RATIO     = 3;
    const ZOOM_STEP     = 0.2;

    // ── Helpers ───────────────────────────────────────────────
    const showError = (msg) => {
        loadingEl.style.display = 'none';
        if (errorMsgEl) errorMsgEl.textContent = msg;
        errorEl.style.display = 'flex';
    };

    const updateZoom = () => {
        const label = Math.round(scaleRatio * 100) + '%';
        zoomLevelEl.textContent = label;
        if (floatZoomLevel) floatZoomLevel.textContent = label;
    };

    const setIndicator = () => {
        if (pdfDoc) pageIndicator.textContent = pageNum + ' / ' + pdfDoc.numPages;
    };

    // ── Rendu ─────────────────────────────────────────────────
    const renderPage = (num) => {
        pageRendering = true;

        pdfDoc.getPage(num).then(page => {
            const naturalVP = page.getViewport({ scale: 1 });

            let baseScale;
            if (readerShell.classList.contains('fullscreen')) {
                // Plein écran applicatif : tenir dans le viewport avec marges
                // PC : barre contrôles ≈ 56px + padding wrapper 2×2rem ≈ 64px → 120px
                // Mobile : barre flottante coin + padding ≈ 120px
                const maxW = window.innerWidth  - 64;
                const maxH = window.innerHeight - 120;
                baseScale = Math.min(maxW / naturalVP.width, maxH / naturalVP.height);
            } else {
                const containerW = readerBox.clientWidth || readerBox.offsetWidth || 600;
                baseScale = containerW / naturalVP.width;
            }

            const scale = baseScale * scaleRatio;
            const vp    = page.getViewport({ scale });

            // DPR pour écrans Retina (optionnel — améliore la netteté)
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

            // Rendre la canvas visible
            canvas.style.display = 'block';
            loadingEl.style.display = 'none';
            overlay.classList.add('hidden');

            setIndicator();
            pageSlider.value = pageNum;

            if (pageNumPending !== null) {
                const next = pageNumPending;
                pageNumPending = null;
                renderPage(next);
            }
        })
        .catch(err => {
            pageRendering = false;
            console.error('Rendu page PDF :', err);
            showError('Erreur lors du rendu de la page ' + num + '.');
        });
    };

    const queueRender = (num) => {
        if (pageRendering) { pageNumPending = num; } else { renderPage(num); }
    };

    const showPage = (num) => {
        if (!pdfDoc || num < 1 || num > pdfDoc.numPages) return;
        pageNum = num;
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
        if (fullscreenBtn) fullscreenBtn.textContent = entering ? 'Quitter' : 'Plein écran';
        if (floatFullscreen) floatFullscreen.textContent = entering ? '✕' : '⛶';
        if (pdfDoc) queueRender(pageNum);
    };

    // ── Événements ────────────────────────────────────────────
    prevBtn?.addEventListener('click',       () => showPage(pageNum - 1));
    nextBtn?.addEventListener('click',       () => showPage(pageNum + 1));
    pageSlider?.addEventListener('input',    e  => showPage(Number(e.target.value)));
    zoomOutBtn?.addEventListener('click',    () => applyZoom(-1));
    zoomInBtn?.addEventListener('click',     () => applyZoom(1));
    fullscreenBtn?.addEventListener('click', toggleFullscreen);
    floatPrev?.addEventListener('click',     () => showPage(pageNum - 1));
    floatNext?.addEventListener('click',     () => showPage(pageNum + 1));
    floatZoomOut?.addEventListener('click',  () => applyZoom(-1));
    floatZoomIn?.addEventListener('click',   () => applyZoom(1));
    floatFullscreen?.addEventListener('click', toggleFullscreen);

    window.addEventListener('resize', () => {
        if (pdfDoc) queueRender(pageNum);
    });

    // Clavier
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') showPage(pageNum + 1);
        if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   showPage(pageNum - 1);
        if (e.key === '+') applyZoom(1);
        if (e.key === '-') applyZoom(-1);
        if (e.key === 'f') toggleFullscreen();
        if (e.key === 'Escape' && readerShell.classList.contains('fullscreen')) toggleFullscreen();
    });

    // ── Chargement PDF via fetch + ArrayBuffer ─────────────────
    const pdfjsLib = window['pdfjs-dist/build/pdf'] || window.pdfjsLib;

    if (!pdfjsLib) {
        showError('PDF.js non disponible. Vérifiez votre connexion.');
        return;
    }

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.8.162/pdf.worker.min.js';

    const pdfUrl = "{{ route('ebooks.pdf', $ebook) }}";

    // On utilise fetch() + ArrayBuffer pour éviter les problèmes CORS du worker
    fetch(pdfUrl, { credentials: 'same-origin' })
        .then(resp => {
            if (!resp.ok) {
                throw new Error('Accès refusé (HTTP ' + resp.status + ').');
            }
            return resp.arrayBuffer();
        })
        .then(buffer => pdfjsLib.getDocument({ data: buffer }).promise)
        .then(pdf => {
            pdfDoc = pdf;
            pageSlider.max = pdf.numPages;
            updateZoom();
            setIndicator();
            renderPage(pageNum);
        })
        .catch(err => {
            console.error('Chargement PDF :', err);
            showError('Impossible de charger le document. ' + err.message);
        });
}());
</script>
@endsection
