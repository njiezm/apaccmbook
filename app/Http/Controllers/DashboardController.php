<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Redirige selon le rôle (remplace la closure pour permettre route:cache).
     */
    public function __invoke(): RedirectResponse
    {
        return auth()->user()->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('ebooks.mine');
    }
}
