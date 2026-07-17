<?php $__env->startSection('title', $ebook->title . ' — APACC-M'); ?>

<?php
    use App\Support\OgImage;
    use Illuminate\Support\Facades\Storage;

    $hasCover = (bool) $ebook->cover_image;

    // Carte de partage 1200×630 (ratio paysage exigé par WhatsApp/Facebook/Twitter).
    // Générée à la volée si absente, pour les couvertures déjà en base.
    $ogImage = asset('icons/icon-512x512.png');
    $ogImageW = 512; $ogImageH = 512;
    $ogImageType = 'image/png';

    if ($hasCover) {
        $ogPath = OgImage::pathFor($ebook->cover_image);
        if (!Storage::disk('public')->exists($ogPath)) {
            OgImage::generate($ebook->cover_image);
        }
        if (Storage::disk('public')->exists($ogPath)) {
            // Cache-bust sur updated_at : quand la couverture change, l'URL change,
            // ce qui force WhatsApp/Facebook à re-télécharger l'aperçu (fini le cache figé).
            $ogImage = asset('storage/' . $ogPath) . '?v=' . $ebook->updated_at?->timestamp;
            $ogImageW = OgImage::W; $ogImageH = OgImage::H;
            $ogImageType = 'image/jpeg';
        } else {
            // Repli : couverture brute (mieux que rien si GD indisponible).
            $ogImage = asset('storage/' . $ebook->cover_image) . '?v=' . $ebook->updated_at?->timestamp;
            $ogImageW = $ogImageH = null;
            $ogImageType = 'image/jpeg';
        }
    }

    // Description propre : sans HTML ni retours à la ligne
    $ogDesc = \Illuminate\Support\Str::limit(
        trim(preg_replace('/\s+/', ' ', strip_tags($ebook->description ?? ''))),
        200
    );
?>

<?php $__env->startSection('meta'); ?>
    <meta property="og:type" content="book">
    <meta property="og:site_name" content="APACC-M e-Livre">
    <meta property="og:title" content="<?php echo e($ebook->title); ?>">
    <meta property="og:description" content="<?php echo e($ogDesc); ?>">
    <meta property="og:image" content="<?php echo e($ogImage); ?>">
    <meta property="og:image:secure_url" content="<?php echo e($ogImage); ?>">
    <meta property="og:image:type" content="<?php echo e($ogImageType); ?>">
    <?php if($ogImageW && $ogImageH): ?>
        <meta property="og:image:width" content="<?php echo e($ogImageW); ?>">
        <meta property="og:image:height" content="<?php echo e($ogImageH); ?>">
    <?php endif; ?>
    <meta property="og:image:alt" content="Couverture — <?php echo e($ebook->title); ?>">
    <meta property="og:url" content="<?php echo e(route('ebooks.show', $ebook)); ?>">
    <meta property="og:locale" content="fr_FR">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($ebook->title); ?>">
    <meta name="twitter:description" content="<?php echo e($ogDesc); ?>">
    <meta name="twitter:image" content="<?php echo e($ogImage); ?>">

    
    <script type="application/ld+json">
    <?php echo json_encode([
        '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
        '@type' => 'Book',
        'name' => $ebook->title,
        'description' => $ogDesc,
        'image' => $ogImage,
        'url' => route('ebooks.show', $ebook),
        'inLanguage' => 'fr',
        'bookFormat' => 'https://schema.org/EBook',
        'genre' => $ebook->category?->name,
        'publisher' => ['@type' => 'Organization', 'name' => 'APACC-M'],
        'offers' => [
            '@type' => 'Offer',
            'price' => $ebook->is_free ? '0' : number_format($ebook->price, 2, '.', ''),
            'priceCurrency' => 'EUR',
            'availability' => 'https://schema.org/InStock',
            'url' => route('ebooks.show', $ebook),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div style="background:var(--white);border-bottom:1px solid var(--border-light);padding:0.75rem 0;">
    <div class="container-custom">
        <nav style="font-size:0.8rem;color:var(--text-muted);">
            <a href="<?php echo e(route('home')); ?>" style="color:var(--text-muted);">Accueil</a>
            <span style="margin:0 0.5rem;">›</span>
            <a href="<?php echo e(route('ebooks.index')); ?>" style="color:var(--text-muted);">Catalogue</a>
            <?php if($ebook->category): ?>
                <span style="margin:0 0.5rem;">›</span>
                <a href="<?php echo e(route('ebooks.index', ['category_id' => $ebook->category->id])); ?>" style="color:var(--text-muted);"><?php echo e($ebook->category->name); ?></a>
            <?php endif; ?>
            <span style="margin:0 0.5rem;">›</span>
            <span style="color:var(--text-primary);"><?php echo e($ebook->title); ?></span>
        </nav>
    </div>
</div>

<div class="container-custom" style="padding-top:2.5rem;padding-bottom:5rem;">
    <div class="product-detail-grid">

        
        <div class="product-cover">
            <?php if($ebook->cover_image): ?>
                <img src="<?php echo e(asset('storage/' . $ebook->cover_image)); ?>" alt="<?php echo e($ebook->title); ?>">
            <?php else: ?>
                <div class="product-cover-placeholder">📖</div>
            <?php endif; ?>
        </div>

        
        <div class="product-info">

            <?php if($ebook->category): ?>
                <span class="section-label"><?php echo e($ebook->category->name); ?></span>
            <?php endif; ?>

            <h1><?php echo e($ebook->title); ?></h1>

            
            <?php $avg = round($ebook->avg_rating, 1); $rc = $ebook->reviews_count; ?>
            <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin:0.35rem 0 0.5rem;">
                <?php if($rc > 0): ?>
                    <a href="#avis" style="text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;color:var(--text-secondary);">
                        <span style="color:#f5a623;letter-spacing:1px;">
                            <?php for($i = 1; $i <= 5; $i++): ?><?php echo e($i <= round($avg) ? '★' : '☆'); ?><?php endfor; ?>
                        </span>
                        <span style="font-size:0.85rem;"><?php echo e(number_format($avg, 1, ',', '')); ?> · <?php echo e($rc); ?> avis</span>
                    </a>
                <?php else: ?>
                    <span style="font-size:0.85rem;color:var(--text-muted);">Pas encore d'avis</span>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>
                    <?php $inWishlist = auth()->user()->wishlists->contains('ebook_id', $ebook->id); ?>
                    <form method="POST" action="<?php echo e(route('wishlist.toggle', $ebook)); ?>" style="margin:0;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="background:none;border:1px solid var(--border-light);border-radius:999px;padding:0.3rem 0.8rem;cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;color:<?php echo e($inWishlist ? 'var(--cardinal,#b91c1c)' : 'var(--text-secondary)'); ?>;font-size:0.82rem;">
                            <i class="fa-<?php echo e($inWishlist ? 'solid' : 'regular'); ?> fa-heart"></i>
                            <?php echo e($inWishlist ? 'Dans mes envies' : 'Ajouter à mes envies'); ?>

                        </button>
                    </form>
                <?php endif; ?>
            </div>

            
            <?php
                $shareUrl     = route('ebooks.show', $ebook);
                $shareAuthor  = $ebook->author?->name ? ' — ' . $ebook->author->name : '';
                $shareTeaser  = \Illuminate\Support\Str::limit(
                    trim(preg_replace('/\s+/', ' ', strip_tags($ebook->description ?? ''))), 140
                );

                // Message soigné (WhatsApp affiche *…* en gras). Le lien seul en dernière
                // ligne déclenche l'aperçu (carte Open Graph) dans WhatsApp/Messenger.
                $shareMsg = "📖 *{$ebook->title}*{$shareAuthor}";
                if ($shareTeaser) {
                    $shareMsg .= "\n\n« {$shareTeaser} »";
                }
                $shareMsg .= "\n\n📚 À découvrir sur la bibliothèque numérique de l'APACC-M :";

                $waHref      = 'https://wa.me/?text=' . rawurlencode($shareMsg . "\n" . $shareUrl);
                $fbHref      = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($shareUrl);
                $mailHref    = 'mailto:?subject=' . rawurlencode($ebook->title . ' — APACC-M e-Livre')
                             . '&body=' . rawurlencode($shareMsg . "\n\n" . $shareUrl);

                // Données pour le partage natif mobile (navigator.share)
                $nativeShare = [
                    'title' => $ebook->title . ' — APACC-M e-Livre',
                    'text'  => $shareMsg,
                    'url'   => $shareUrl,
                ];
            ?>
            <div style="display:flex;align-items:center;gap:0.5rem;margin:0.25rem 0 0.5rem;flex-wrap:wrap;"
                 x-data="{ copied:false, canShare: !!(navigator.share) }">
                <span style="font-size:0.8rem;color:var(--text-muted);">Partager :</span>

                
                <button type="button" x-cloak x-show="canShare" title="Partager"
                        @click='navigator.share(<?php echo json_encode($nativeShare, 15, 512) ?>).catch(()=>{})'
                        style="width:32px;height:32px;border-radius:50%;background:var(--cardinal);color:#fff;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-share-nodes"></i>
                </button>

                <a href="<?php echo e($waHref); ?>" target="_blank" rel="noopener" title="WhatsApp" style="width:32px;height:32px;border-radius:50%;background:#25D366;color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="<?php echo e($fbHref); ?>" target="_blank" rel="noopener" title="Facebook" style="width:32px;height:32px;border-radius:50%;background:#1877F2;color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="<?php echo e($mailHref); ?>" title="Email" style="width:32px;height:32px;border-radius:50%;background:var(--text-secondary);color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">
                    <i class="fa-solid fa-envelope"></i>
                </a>
                <button type="button" title="Copier le lien"
                        @click="navigator.clipboard.writeText('<?php echo e($shareUrl); ?>').then(()=>{copied=true;setTimeout(()=>copied=false,1800)})"
                        style="width:32px;height:32px;border-radius:50%;background:var(--border-medium);color:var(--text-primary);border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-link"></i>
                </button>
                <span x-cloak x-show="copied" style="font-size:0.78rem;color:var(--cardinal);">Lien copié ✓</span>
            </div>

            
            <div class="product-meta">
                <?php if($ebook->author): ?>
                    <span>✍ <?php echo e($ebook->author->name); ?></span>
                <?php endif; ?>
                <?php if($ebook->page_count): ?>
                    <span>📄 <?php echo e($ebook->page_count); ?> pages</span>
                <?php endif; ?>
                <?php if($ebook->published_date): ?>
                    <span>📅 <?php echo e(\Carbon\Carbon::parse($ebook->published_date)->format('Y')); ?></span>
                <?php endif; ?>
            </div>

            
            <div class="narthex-line" style="margin:0.5rem 0 1rem;"></div>

            
            <?php if($ebook->short_description): ?>
                <p style="font-size:1.05rem;line-height:1.75;color:var(--text-secondary);"><?php echo e($ebook->short_description); ?></p>
            <?php endif; ?>

            <?php if($ebook->description && $ebook->description !== $ebook->short_description): ?>
                <div style="font-size:0.95rem;line-height:1.75;color:var(--text-secondary);">
                    <?php echo nl2br(e($ebook->description)); ?>

                </div>
            <?php endif; ?>

            
            <?php
                $sommaireLines = collect(preg_split('/\r\n|\r|\n/', (string) $ebook->sommaire))
                    ->map(fn ($l) => trim($l))->filter()->values();
            ?>
            <?php if($sommaireLines->isNotEmpty()): ?>
                <div x-data="{ open: false }" style="margin:1rem 0;">
                    <button type="button" @click="open = true" class="btn-secondary" style="display:inline-flex;align-items:center;gap:0.5rem;">
                        <i class="fa-solid fa-list-ul"></i> Voir le sommaire
                    </button>

                    <div x-cloak x-show="open" @click.self="open = false"
                         style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;">
                        <div style="background:var(--white);border-radius:var(--radius-lg);max-width:520px;width:100%;max-height:80vh;overflow:auto;border-top:4px solid var(--cardinal);">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-light);">
                                <h3 style="margin:0;font-size:1.15rem;">Sommaire</h3>
                                <button type="button" @click="open = false" style="background:none;border:none;font-size:1.5rem;line-height:1;cursor:pointer;color:var(--text-muted);">×</button>
                            </div>
                            <ul style="list-style:none;margin:0;padding:0.5rem 0;">
                                <?php $__currentLoopData = $sommaireLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php preg_match('/^(.*?)[\s.\-–—]*(\d+)\s*$/u', $line, $m); ?>
                                    <li style="display:flex;justify-content:space-between;gap:1rem;padding:0.55rem 1.5rem;border-bottom:1px solid var(--border-light);font-size:0.92rem;">
                                        <span><?php echo e($m[1] ?? $line); ?></span>
                                        <?php if(!empty($m[2])): ?><span style="color:var(--text-muted);flex-shrink:0;">p. <?php echo e($m[2]); ?></span><?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php
                // Coupon éventuellement appliqué (session)
                $appliedCoupon = null;
                if (!$ebook->is_free && ($sc = session('coupon_' . $ebook->id))) {
                    $c = \App\Models\Coupon::where('code', $sc)->first();
                    if ($c && $c->isValidForEbook($ebook)) {
                        $appliedCoupon = $c;
                    }
                }
                $displayPrice = $appliedCoupon ? $appliedCoupon->finalPrice((float) $ebook->price) : (float) $ebook->price;
            ?>
            <div class="product-purchase-box" id="achat">
                <div class="product-price-block" style="margin-bottom:1rem;">
                    <?php if($ebook->is_free): ?>
                        <span style="color:var(--cardinal,#b91c1c);">Gratuit</span>
                    <?php elseif($appliedCoupon): ?>
                        <span style="text-decoration:line-through;color:var(--text-muted);font-size:0.7em;"><?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €</span>
                        <span style="color:var(--cardinal,#b91c1c);"><?php echo e(number_format($displayPrice, 2, ',', ' ')); ?> €</span>
                    <?php else: ?>
                        <?php echo e(number_format($ebook->price, 2, ',', ' ')); ?> €
                    <?php endif; ?>
                </div>

                <?php if(session('coupon_error')): ?>
                    <p style="color:var(--cardinal,#b91c1c);font-size:0.82rem;margin:0 0 0.75rem;"><?php echo e(session('coupon_error')); ?></p>
                <?php endif; ?>

                
                <?php if(auth()->guard()->check()): ?>
                    <?php if(!$ebook->is_free && (!$purchase || $purchase->payment_status !== \App\Models\Purchase::STATUS_PAID)): ?>
                        <?php if($appliedCoupon): ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;background:#ecfdf5;border:1px solid #10b981;border-radius:var(--radius);padding:0.5rem 0.75rem;margin-bottom:0.75rem;">
                                <span style="font-size:0.82rem;color:#065f46;">Code <strong><?php echo e($appliedCoupon->code); ?></strong> appliqué</span>
                                <form method="POST" action="<?php echo e(route('coupon.remove', $ebook)); ?>" style="margin:0;">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" style="background:none;border:none;color:#065f46;cursor:pointer;font-size:0.82rem;text-decoration:underline;">Retirer</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="<?php echo e(route('coupon.apply', $ebook)); ?>" style="display:flex;gap:0.5rem;margin-bottom:0.75rem;">
                                <?php echo csrf_field(); ?>
                                <input type="text" name="code" placeholder="Code promo" style="flex:1;text-transform:uppercase;" maxlength="50">
                                <button type="submit" class="btn-secondary" style="font-size:0.82rem;white-space:nowrap;">Appliquer</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php $methods = $paymentSettings['enabled_methods'] ?? ['helloasso']; ?>

                <?php if($ebook->is_free && (!$purchase || $purchase->payment_status !== \App\Models\Purchase::STATUS_PAID)): ?>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <a class="btn-primary" href="<?php echo e(route('ebooks.read', $ebook)); ?>" style="display:block;text-align:center;">Lire gratuitement</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-secondary" style="display:block;text-align:center;">Connectez-vous pour lire gratuitement</a>
                    <?php endif; ?>

                <?php elseif($purchase): ?>
                    
                    <div style="margin-bottom:1rem;">
                        <span class="status-pill <?php echo e($purchase->payment_status); ?>" style="margin-bottom:0.5rem;display:inline-block;">
                            <?php echo e($purchase->payment_status === 'paid' ? 'Accès validé' : 'En attente de validation'); ?>

                        </span>
                        <p style="font-size:0.85rem;color:var(--text-secondary);margin:0.5rem 0 0;">
                            <?php if($purchase->payment_status === 'paid'): ?>
                                Votre accès est actif. Vous pouvez lire cet eBook dès maintenant.
                            <?php else: ?>
                                Votre paiement est en cours de validation (12 à 24 h). Vous recevrez une notification dès que l'accès sera ouvert.
                            <?php endif; ?>
                        </p>
                    </div>
                    <?php if($purchase->payment_status === \App\Models\Purchase::STATUS_PAID): ?>
                        <a class="btn-primary" href="<?php echo e(route('ebooks.read', $ebook)); ?>" style="display:block;text-align:center;margin-bottom:0.75rem;">Lire maintenant</a>
                    <?php endif; ?>

                <?php else: ?>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <div style="display:flex;flex-direction:column;gap:0.6rem;">

                            
                            <?php if(in_array('stripe', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.stripe', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;gap:0.5rem;display:flex;align-items:center;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        Payer par carte — Stripe
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('paypal', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.paypal', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #003087;border-radius:var(--radius);background:#003087;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.826l-1.348 8.54h3.36c.524 0 .967-.382 1.05-.9l.978-6.195h2.18c4.298 0 6.797-2.078 7.647-6.55.128-.67.183-1.292.13-1.69z"/></svg>
                                        Payer via PayPal
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('sumup', $methods)): ?>
                                <form method="POST" action="<?php echo e(route('checkout.sumup', $ebook)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" style="width:100%;padding:0.6rem 1.25rem;border:2px solid #1a1a2e;border-radius:var(--radius);background:#1a1a2e;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                                        Payer via SumUp
                                    </button>
                                </form>
                            <?php endif; ?>

                            
                            <?php if(in_array('helloasso', $methods)): ?>
                                <?php $helloassoUrl = $paymentSettings['helloasso_url'] ?? ($ebook->helloasso_url ?? '#'); ?>
                                <a href="<?php echo e($helloassoUrl); ?>" target="_blank" rel="noopener"
                                   style="width:100%;padding:0.6rem 1.25rem;border:2px solid #f47930;border-radius:var(--radius);background:#f47930;color:#fff;font-family:var(--font-sans);font-size:0.875rem;font-weight:600;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:0.5rem;box-sizing:border-box;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    HelloAsso — 0 % de frais
                                </a>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.45rem;">Après paiement sur HelloAsso, confirmez ici :</p>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="helloasso">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué mon paiement HelloAsso</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(in_array('virement', $methods) && ($paymentSettings['virement_iban'] ?? '')): ?>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Virement bancaire</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">IBAN : <code style="background:var(--white);padding:0.1rem 0.35rem;border-radius:3px;border:1px solid var(--border-light);font-size:0.78rem;color:var(--text-primary);"><?php echo e($paymentSettings['virement_iban']); ?></code></p>
                                    <?php if($paymentSettings['virement_bic'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">BIC : <strong><?php echo e($paymentSettings['virement_bic']); ?></strong></p><?php endif; ?>
                                    <?php if($paymentSettings['virement_titulaire'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;">Titulaire : <strong><?php echo e($paymentSettings['virement_titulaire']); ?></strong></p><?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="virement">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai effectué le virement</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            
                            <?php if(in_array('cheque', $methods) && ($paymentSettings['cheque_ordre'] ?? '')): ?>
                                <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:0.7rem 0.85rem;">
                                    <p style="font-size:0.82rem;font-weight:700;margin:0 0 0.4rem;color:var(--text-primary);">Paiement par chèque</p>
                                    <p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.2rem;">À l'ordre de : <strong><?php echo e($paymentSettings['cheque_ordre']); ?></strong></p>
                                    <?php if($paymentSettings['cheque_adresse'] ?? ''): ?><p style="font-size:0.79rem;color:var(--text-secondary);margin:0 0 0.5rem;white-space:pre-line;"><?php echo e($paymentSettings['cheque_adresse']); ?></p><?php endif; ?>
                                    <form method="POST" action="<?php echo e(route('purchases.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="ebook_id" value="<?php echo e($ebook->id); ?>">
                                        <input type="hidden" name="payment_method" value="cheque">
                                        <button type="submit" class="btn-secondary" style="width:100%;font-size:0.82rem;">J'ai envoyé mon chèque</button>
                                    </form>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn-secondary" style="display:block;text-align:center;">
                            Connectez-vous pour acheter
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('validate-purchase')): ?>
                    <?php if($purchase && $purchase->payment_status === \App\Models\Purchase::STATUS_PENDING): ?>
                        <div style="border-top:1px solid var(--border-light);padding-top:0.75rem;margin-top:0.75rem;">
                            <form method="POST" action="<?php echo e(route('purchases.status.update', $purchase)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button class="btn-primary" type="submit" style="background:var(--text-primary);border-color:var(--text-primary);width:100%;">
                                    Valider le paiement (Admin)
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php
        $reviews = $ebook->reviews()->where('status', 'approved')->with('user')->latest()->get();
        $myReview = auth()->check() ? $reviews->firstWhere('user_id', auth()->id()) : null;
    ?>
    <div id="avis" style="margin-top:4rem;">
        <div class="narthex-line" style="margin-bottom:2rem;"></div>
        <span class="section-label">Avis des lecteurs</span>
        <h2 style="font-size:1.5rem;margin-bottom:0.5rem;">
            <?php echo e($reviews->count()); ?> avis
            <?php if($reviews->count()): ?>
                — <span style="color:#f5a623;"><?php echo e(number_format(round($ebook->avg_rating, 1), 1, ',', '')); ?>/5</span>
            <?php endif; ?>
        </h2>

        
        <?php if(auth()->guard()->check()): ?>
            <div style="background:var(--cream,#f8f7f4);border:1px solid var(--border-light);border-radius:var(--radius);padding:1.25rem;margin:1.25rem 0 2rem;max-width:640px;">
                <p style="font-weight:700;margin:0 0 0.75rem;"><?php echo e($myReview ? 'Modifier mon avis' : 'Laisser un avis'); ?></p>
                <form method="POST" action="<?php echo e(route('reviews.store', $ebook)); ?>">
                    <?php echo csrf_field(); ?>
                    <div style="display:flex;flex-direction:column;gap:0.6rem;">
                        <label style="font-size:0.85rem;">
                            Note
                            <select name="rating" required style="width:100%;margin-top:0.25rem;">
                                <?php for($i = 5; $i >= 1; $i--): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e($myReview && $myReview->rating == $i ? 'selected' : ''); ?>><?php echo e(str_repeat('★', $i)); ?> (<?php echo e($i); ?>/5)</option>
                                <?php endfor; ?>
                            </select>
                        </label>
                        <input type="text" name="title" maxlength="255" placeholder="Titre (optionnel)" value="<?php echo e($myReview->title ?? ''); ?>">
                        <textarea name="content" rows="3" maxlength="2000" placeholder="Votre commentaire (optionnel)"><?php echo e($myReview->content ?? ''); ?></textarea>
                        <div style="display:flex;gap:0.6rem;">
                            <button type="submit" class="btn-primary" style="font-size:0.85rem;"><?php echo e($myReview ? 'Mettre à jour' : 'Publier mon avis'); ?></button>
                            <?php if($myReview): ?>
                                <button type="submit" form="delete-review" class="btn-secondary" style="font-size:0.85rem;">Supprimer</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                <?php if($myReview): ?>
                    <form method="POST" action="<?php echo e(route('reviews.destroy', $ebook)); ?>" id="delete-review"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p style="color:var(--text-muted);font-size:0.9rem;margin:1rem 0 2rem;"><a href="<?php echo e(route('login')); ?>" style="color:var(--cardinal);font-weight:700;">Connectez-vous</a> pour laisser un avis.</p>
        <?php endif; ?>

        
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="border-bottom:1px solid var(--border-light);padding:1rem 0;max-width:760px;">
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.3rem;">
                    <strong style="font-size:0.92rem;"><?php echo e($review->user->name ?? 'Membre'); ?></strong>
                    <span style="color:#f5a623;font-size:0.9rem;"><?php for($i = 1; $i <= 5; $i++): ?><?php echo e($i <= $review->rating ? '★' : '☆'); ?><?php endfor; ?></span>
                    <span style="color:var(--text-muted);font-size:0.78rem;"><?php echo e($review->created_at->format('d/m/Y')); ?></span>
                    <?php if(auth()->id() === $review->user_id): ?><span style="font-size:0.72rem;color:var(--cardinal);">(vous)</span><?php endif; ?>
                </div>
                <?php if($review->title): ?><p style="font-weight:600;margin:0 0 0.2rem;"><?php echo e($review->title); ?></p><?php endif; ?>
                <?php if($review->content): ?><p style="margin:0;color:var(--text-secondary);font-size:0.92rem;line-height:1.6;"><?php echo e($review->content); ?></p><?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-ebooks')): ?>
                    <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" onsubmit="return confirm('Supprimer cet avis ?')" style="margin:0.4rem 0 0;">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" style="background:none;border:none;color:var(--cardinal);cursor:pointer;font-size:0.78rem;text-decoration:underline;padding:0;">Supprimer (modération)</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="color:var(--text-muted);">Soyez le premier à donner votre avis sur cet ouvrage.</p>
        <?php endif; ?>
    </div>

    
    <?php if($recommendations->count()): ?>
        <div style="margin-top:4rem;">
            <div class="narthex-line" style="margin-bottom:2rem;"></div>
            <span class="section-label">Dans la même thématique</span>
            <h2 style="font-size:1.5rem;margin-bottom:2rem;">Vous pourriez aussi apprécier</h2>
            <div class="row g-4">
                <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo e(route('ebooks.show', $rec->slug)); ?>" class="text-decoration-none d-block h-100">
                            <article class="arch-card h-100">
                                <?php if($rec->cover_image): ?>
                                    <img src="<?php echo e($rec->thumbUrl()); ?>" alt="Couverture — <?php echo e($rec->title); ?>" loading="lazy" decoding="async" style="height:180px;object-fit:cover;">
                                <?php else: ?>
                                    <div class="arch-card__cover-placeholder" style="height:180px;font-size:2.5rem;">📖</div>
                                <?php endif; ?>
                                <div class="arch-card-body">
                                    <h5 style="font-size:0.95rem;"><?php echo e($rec->title); ?></h5>
                                    <span class="price" style="font-size:1rem;"><?php echo e(number_format($rec->price, 2, ',', ' ')); ?> €</span>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\apacc-m-ebook\resources\views/ebooks/show.blade.php ENDPATH**/ ?>