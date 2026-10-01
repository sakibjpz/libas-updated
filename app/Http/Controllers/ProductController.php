<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; // Add this import

class ProductController extends Controller
{
    /**
     * Display all products with filters
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $query = $request->input('search');
        $categorySlug = $request->query('category');

        // Start query builder with category relationship
        $productsQuery = Product::with('categoryRelation', 'sizes', 'colors');

        // Filter by search keyword if provided
        if ($query) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhereHas('categoryRelation', function ($categoryQuery) use ($query) {
                      $categoryQuery->where('name', 'LIKE', "%{$query}%");
                  });
            });
        }

        // Filter by category if provided
        $category = null;
        if ($categorySlug) {
            // Find category by slug
            $category = Category::where('slug', $categorySlug)->first();
            
            if ($category) {
                // Include subcategory products on a parent category page
                $productsQuery->whereIn('category_id', $category->idsWithChildren());
            }
        }

        // Get products
        $products = $productsQuery->get();

        // Group products by category / subcategory for sectioned display
        $productGroups = null;
        $hasHierarchy = \Illuminate\Support\Facades\Schema::hasColumn('categories', 'parent_id');
        if (!$query && $hasHierarchy) {
            if ($category && $category->parent_id === null) {
                // Parent category page — sections per subcategory
                $productGroups = $this->groupedProducts($products, collect([$category]));
            } elseif (!$categorySlug) {
                // All products — one section per top-level category
                $productGroups = $this->groupedProducts(
                    $products,
                    Category::whereNull('parent_id')->with('children')->orderBy('name')->get()
                );
            }
        }

        return view('products.index', compact('products', 'categorySlug', 'query', 'productGroups', 'category'));
    }

    /**
     * Group products under top-level categories, with subcategory sub-sections.
     */
    private function groupedProducts($products, $roots)
    {
        $groups = collect();
        foreach ($roots as $root) {
            $rootItems = $products->whereIn('category_id', $root->idsWithChildren());
            if ($rootItems->isEmpty()) continue;

            $subs = $root->children
                ->map(fn ($sub) => [
                    'name' => $sub->name,
                    'slug' => $sub->slug,
                    'items' => $rootItems->where('category_id', $sub->id)->values(),
                ])
                ->filter(fn ($s) => $s['items']->isNotEmpty())
                ->values();

            $groups->push([
                'name' => $root->name,
                'slug' => $root->slug,
                'items' => $rootItems->values(),
                'own' => $rootItems->where('category_id', $root->id)->values(),
                'subs' => $subs,
            ]);
        }
        return $groups;
    }

    /**
     * Search suggestions (autocomplete) - returns JSON
     */
    public function suggestions(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with('categoryRelation')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhereHas('categoryRelation', function ($categoryQuery) use ($query) {
                $categoryQuery->where('name', 'LIKE', "%{$query}%");
            })
            ->latest('id')
            ->limit(8)
            ->get(['id', 'slug', 'name', 'price', 'original_price', 'image', 'category_id']);

        return response()->json($products->map(function ($product) {
            return [
                'name' => $product->name,
                'url' => route('products.show', $product),
                'image' => $product->image_url,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'category' => $product->category_name,
            ];
        }));
    }

    /**
     * Product size/color options for the add-to-cart variant picker - returns JSON
     */
    public function options($id)
    {
        $product = Product::with(['sizes', 'colors'])->findOrFail($id);

        return response()->json([
            'name' => $product->name,
            'sizes' => $product->sizes->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'price_adjustment' => $s->pivot->price_adjustment ?? 0,
            ])->values(),
            'colors' => $product->colors->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'hex_code' => $c->hex_code,
                'price_adjustment' => $c->pivot->price_adjustment ?? 0,
            ])->values(),
        ]);
    }

    /**
     * Display a single product for customers
     */
    public function show(Product $product)
    {
        // Load the category relationship, sizes, colors, and reviews
        $product->load('categoryRelation', 'sizes', 'colors', 'reviews');

        // Get related products
        $relatedProducts = $product->getRelatedProducts(4);

        // Prepare pixel event data for ViewContent
        $pixelViewData = [
            'content_name' => $product->name,
            'content_category' => $product->categoryRelation ? $product->categoryRelation->name : 'General',
            'content_ids' => [(string)$product->id],
            'content_type' => 'product',
            'value' => $product->price,
            'currency' => 'BDT'
        ];

        // Server-side ViewContent (Meta CAPI), deduped with the browser event
        $pixelEventId = \App\Services\MetaConversionsApi::eventId('vc');
        \App\Services\MetaConversionsApi::send('ViewContent', $pixelViewData, [], $pixelEventId);

        // Return frontend product detail view
        return view('frontend.products.show', compact('product', 'pixelViewData', 'pixelEventId', 'relatedProducts'));
    }
}