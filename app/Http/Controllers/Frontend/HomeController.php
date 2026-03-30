<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();
        
        $categories = Category::withCount('products')->where('slug', '!=', 'clothing')->get();
        
        return view('frontend.home', compact('featuredProducts', 'categories'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
