<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category; // Change from Product to Category

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'parent_id')) {
                    $categories = Category::withCount('products')
                        ->with(['children' => fn ($q) => $q->withCount('products')->orderBy('name')])
                        ->orderByRaw('COALESCE(parent_id, id)')->orderByRaw('parent_id IS NOT NULL')->orderBy('name')
                        ->get();
                } else {
                    // parent_id migration not run yet — flat list keeps the site up
                    $categories = Category::withCount('products')->orderBy('name')->get();
                }
            } catch (\Throwable $e) {
                $categories = collect();
            }

            try {
                $navMenus = \App\Http\Controllers\MenuController::getMenuHierarchy();
            } catch (\Throwable $e) {
                $navMenus = collect();
            }

            $view->with('categories', $categories);
            $view->with('navMenus', $navMenus);
        });
    }
}