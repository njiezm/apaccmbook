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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ebooks', [CatalogController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{ebook}', [CatalogController::class, 'show'])->name('ebooks.show');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/my-ebooks', [CatalogController::class, 'mine'])->name('ebooks.mine');
    Route::get('/ebook/{ebook}/read', [CatalogController::class, 'read'])->name('ebooks.read');
    Route::get('/ebook/{ebook}/pdf', [CatalogController::class, 'servePdf'])->name('ebooks.pdf');

    // Achat manuel (HelloAsso confirm, virement, chèque)
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::patch('/purchases/{purchase}/status', [PurchaseController::class, 'updateStatus'])->name('purchases.status.update');

    // Checkout automatisé (Stripe, PayPal, SumUp)
    Route::post('/checkout/{ebook}/stripe', [CheckoutController::class, 'stripe'])->name('checkout.stripe');
    Route::post('/checkout/{ebook}/paypal', [CheckoutController::class, 'paypal'])->name('checkout.paypal');
    Route::post('/checkout/{ebook}/sumup',  [CheckoutController::class, 'sumup'])->name('checkout.sumup');
    Route::get('/checkout/paypal/capture',  [CheckoutController::class, 'paypalCapture'])->name('checkout.paypal.capture');
    Route::get('/checkout/{ebook}/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/{ebook}/cancel',  [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save');
    Route::get('/ebooks', [AdminEbookController::class, 'index'])->name('ebooks.index');
    Route::post('/ebooks', [AdminEbookController::class, 'store'])->name('ebooks.store');
    Route::patch('/ebooks/{ebook:id}', [AdminEbookController::class, 'update'])->name('ebooks.update');
    Route::delete('/ebooks/{ebook:id}', [AdminEbookController::class, 'destroy'])->name('ebooks.destroy');
    Route::patch('/users/{user:id}/toggle-admin', [AdminEbookController::class, 'toggleAdmin'])->name('users.toggle-admin');
});
