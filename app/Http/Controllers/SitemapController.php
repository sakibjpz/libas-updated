<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'slug', 'name', 'image', 'updated_at')
            ->latest('updated_at')
            ->get();

        $categories = Category::whereHas('products')
            ->select('id', 'slug', 'name', 'updated_at')
            ->get();

        return response()
            ->view('sitemap', compact('products', 'categories'))
            ->header('Content-Type', 'application/xml');
    }
}
