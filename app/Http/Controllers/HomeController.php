<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $homeCategories = Category::query()
            ->withCount('products')
            ->with(['products' => function ($query) {
                $query->select(['id', 'slug', 'name', 'category_id', 'image', 'price', 'original_price', 'stock'])
                    ->with(['sizes', 'colors'])
                    ->latest('id')
                    ->limit(6);
            }])
            ->orderBy('products_count', 'desc')
            ->get();

        $categoryProducts = [];
        foreach ($homeCategories as $category) {
            if ($category->products->isNotEmpty()) {
                $categoryProducts[$category->name] = $category->products;
            }
        }

        $banners = Banner::active()->get();

        return view('home', compact('categoryProducts', 'banners'));
    }
}
