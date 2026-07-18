<?php

use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ebooks', [CatalogController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{ebook}', [CatalogController::class, 'show'])->name('ebooks.show');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:6,1')->name('newsletter.subscribe');
Route::get('/newsletter/confirm', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->middleware('throttle:6,1')->name('contact.store');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/my-ebooks', [CatalogController::class, 'mine'])->name('ebooks.mine');
    Route::get('/ebook/{ebook}/read', [CatalogController::class, 'read'])->middleware('verified')->name('ebooks.read');
    Route::get('/ebook/{ebook}/pdf', [CatalogController::class, 'servePdf'])->middleware('verified')->name('ebooks.pdf');
    Route::post('/ebook/{ebook}/progress', [CatalogController::class, 'saveProgress'])->middleware('verified')->name('ebooks.progress');

    // Liste d'envies
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{ebook}', [\App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Avis / notes
    Route::post('/ebooks/{ebook}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->middleware('throttle:10,1')->name('reviews.store');
    Route::delete('/ebooks/{ebook}/reviews', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Codes promo (application sur la fiche)
    Route::post('/ebooks/{ebook}/coupon', [\App\Http\Controllers\CouponController::class, 'apply'])->name('coupon.apply');
    Route::delete('/ebooks/{ebook}/coupon', [\App\Http\Controllers\CouponController::class, 'remove'])->name('coupon.remove');

    // Achat manuel (HelloAsso confirm, virement, chèque)
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::patch('/purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.status.update');
    Route::get('/purchases/{purchase}/receipt', [PurchaseController::class, 'receipt'])->name('purchases.receipt');

    // Checkout automatisé (Stripe, PayPal, SumUp)
    Route::post('/checkout/{ebook}/stripe', [CheckoutController::class, 'stripe'])->name('checkout.stripe');
    Route::post('/checkout/{ebook}/paypal', [CheckoutController::class, 'paypal'])->name('checkout.paypal');
    Route::post('/checkout/{ebook}/sumup',  [CheckoutController::class, 'sumup'])->name('checkout.sumup');
    Route::post('/checkout/{ebook}/sumup/init',   [CheckoutController::class, 'sumupInit'])->name('checkout.sumup.init');
    Route::post('/checkout/{ebook}/sumup/verify', [CheckoutController::class, 'sumupVerify'])->name('checkout.sumup.verify');
    Route::get('/checkout/paypal/capture',  [CheckoutController::class, 'paypalCapture'])->name('checkout.paypal.capture');
    Route::get('/checkout/{ebook}/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/{ebook}/cancel',  [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');
});

require __DIR__.'/auth.php';

// Webhooks des passerelles (CSRF exempté dans bootstrap/app.php)
Route::post('/webhook/stripe',    [WebhookController::class, 'stripe'])->name('webhook.stripe');
Route::post('/webhook/helloasso', [WebhookController::class, 'helloasso'])->name('webhook.helloasso');
Route::post('/webhook/paypal',    [WebhookController::class, 'paypal'])->name('webhook.paypal');
Route::post('/webhook/sumup',     [WebhookController::class, 'sumup'])->name('webhook.sumup');

// Admin routes
Route::middleware(['auth', 'can:manage-ebooks'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kit-communication', [AdminController::class, 'kitCommunication'])->name('kit-communication');
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save');
    Route::get('/ebooks', [AdminEbookController::class, 'index'])->name('ebooks.index');
    Route::post('/ebooks', [AdminEbookController::class, 'store'])->name('ebooks.store');
    Route::patch('/ebooks/{ebook:id}', [AdminEbookController::class, 'update'])->name('ebooks.update');
    Route::delete('/ebooks/{ebook:id}', [AdminEbookController::class, 'destroy'])->name('ebooks.destroy');
    Route::patch('/users/{user:id}/toggle-admin', [AdminEbookController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('/users/{user:id}/verify-email', [AdminEbookController::class, 'verifyEmail'])->name('users.verify-email');
    Route::delete('/users/{user:id}', [AdminEbookController::class, 'destroyUser'])->name('users.destroy');

    // Modération des avis
    Route::delete('/reviews/{review}', [AdminEbookController::class, 'destroyReview'])->name('reviews.destroy');

    // Coupons de réduction
    Route::post('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store');
    Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy');

    // Abonnés newsletter
    Route::get('/subscribers/export', [\App\Http\Controllers\Admin\SubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('/subscribers/{subscriber}', [\App\Http\Controllers\Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');
});
