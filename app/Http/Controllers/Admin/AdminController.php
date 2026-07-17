<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Ebook;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-ebooks']);
    }

    public function dashboard()
    {
        $stats = [
            'total_ebooks'  => Ebook::where('status', 'published')->count(),
            'total_users'   => User::count(),
            'total_sales'   => Purchase::where('payment_status', Purchase::STATUS_PAID)->count(),
            'total_revenue' => Purchase::where('payment_status', Purchase::STATUS_PAID)
                                ->join('ebooks', 'purchases.ebook_id', '=', 'ebooks.id')
                                ->sum('ebooks.price'),
        ];

        $recentPurchases = Purchase::with(['user', 'ebook'])
            ->latest()
            ->limit(10)
            ->get();

        $ebooks   = Ebook::with('category')->latest()->get();
        $purchases = Purchase::with(['user', 'ebook'])->latest()->get();
        $users    = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $coupons  = Coupon::with('ebook')->latest()->get();

        $subscribers = Subscriber::latest()->get();
        $stats['total_subscribers'] = $subscribers->where('is_active', true)->count();

        $paymentSettings = $this->loadPaymentSettings();

        return view('admin.dashboard', compact(
            'stats', 'recentPurchases', 'ebooks', 'purchases', 'users', 'categories', 'coupons', 'subscribers', 'paymentSettings'
        ));
    }

    public function saveSettings(Request $request)
    {
        $data = $request->only([
            'enabled_methods',
            // HelloAsso
            'helloasso_url',
            'helloasso_org',
            'helloasso_api_secret',
            // Stripe
            'stripe_publishable_key',
            'stripe_secret_key',
            'stripe_webhook_secret',
            // PayPal
            'paypal_client_id',
            'paypal_client_secret',
            'paypal_mode',
            'paypal_webhook_id',
            // SumUp
            'sumup_api_key',
            'sumup_merchant_code',
            'sumup_webhook_secret',
            // Virement
            'virement_iban',
            'virement_bic',
            'virement_titulaire',
            // Chèque
            'cheque_ordre',
            'cheque_adresse',
        ]);

        // enabled_methods est un tableau de cases cochées
        $data['enabled_methods'] = $request->input('enabled_methods', []);

        file_put_contents(
            storage_path('app/payment-settings.json'),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return back()->with('status', 'Paramètres de paiement enregistrés avec succès.');
    }

    private function loadPaymentSettings(): array
    {
        $path = storage_path('app/payment-settings.json');

        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? [];
        }

        return [
            'enabled_methods' => ['helloasso'],
            'helloasso_url'   => '',
        ];
    }
}
