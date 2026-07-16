<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $topEbooks = Ebook::with('category')
            ->where('status', 'published')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::all();

        return view('home', compact('topEbooks', 'categories'));
    }
}
