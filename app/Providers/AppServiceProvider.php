<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('validate-purchase', fn ($user) => $user->is_admin);
        Gate::define('manage-ebooks', fn ($user) => $user->is_admin);

        // Pagination stylée Bootstrap 5 (chargé via CDN dans le layout)
        Paginator::useBootstrapFive();
    }
}
