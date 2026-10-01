<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Services\ImageOptimizer;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $products = Product::with('categoryRelation', 'sizes', 'colors')
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only('search'));

        return view('admin.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('sort_order')->get();
        return view('admin.products.create', compact('sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|url|max:255',
            'brand' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'landing_theme' => 'nullable|in:' . implode(',', array_keys(\App\Http\Controllers\ProductLandingController::THEMES)),
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        $product->landing_theme = $request->landing_theme;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->original_price = $request->original_price;
        $product->discount = $request->discount;
        $product->brand = $request->brand;
        $product->stock = $request->stock;
        $product->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('products-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $product->image = basename(ImageOptimizer::optimize($uploadPath . '/' . $imageName, 1200)['path']);
        }

        $product->youtube_url = $request->youtube_url;

        // Handle gallery image uploads
        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            $uploadPath = public_path('products-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move($uploadPath, $galleryName);
                $gallery[] = basename(ImageOptimizer::optimize($uploadPath . '/' . $galleryName, 1200)['path']);
            }
            $product->gallery = $gallery;
        }

        $product->save();

        // Attach sizes and colors
        if ($request->has('sizes')) {
            $sizesData = [];
            foreach ($request->sizes as $sizeId) {
                $sizesData[$sizeId] = [
                    'stock' => $request->input("size_stock_{$sizeId}"),
                    'price_adjustment' => $request->input("size_price_{$sizeId}", 0)
                ];
            }
            $product->sizes()->attach($sizesData);
        }

        if ($request->has('colors')) {
            $colorsData = [];
            foreach ($request->colors as $colorId) {
                $colorsData[$colorId] = [
                    'stock' => $request->input("color_stock_{$colorId}"),
                    'price_adjustment' => $request->input("color_price_{$colorId}", 0)
                ];
            }
            $product->colors()->attach($colorsData);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $sizes = Size::orderBy('sort_order')->get();
        $colors = Color::orderBy('sort_order')->get();
        $product->load('sizes', 'colors');
        return view('admin.products.edit', compact('product', 'sizes', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'youtube_url' => 'nullable|url|max:255',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'string',
            'brand' => 'nullable|string|max:255',
            'stock' => 'nullable|integer',
            'description' => 'nullable|string',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'landing_theme' => 'nullable|in:' . implode(',', array_keys(\App\Http\Controllers\ProductLandingController::THEMES)),
        ]);

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        $product->landing_theme = $request->landing_theme;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->original_price = $request->original_price;
        $product->discount = $request->discount;
        $product->brand = $request->brand;
        $product->stock = $request->stock;
        $product->description = $request->description;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldImagePath = public_path('products-images/' . $product->image);
            if ($product->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $uploadPath = public_path('products-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $product->image = basename(ImageOptimizer::optimize($uploadPath . '/' . $imageName, 1200)['path']);
        }

        $product->youtube_url = $request->youtube_url;

        // Remove gallery images marked for deletion
        $gallery = collect($product->gallery ?? []);
        if ($request->filled('remove_gallery')) {
            foreach ($request->remove_gallery as $removedFile) {
                $filePath = public_path('products-images/' . $removedFile);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $gallery = $gallery->reject(fn($f) => $f === $removedFile);
            }
        }

        // Append newly uploaded gallery images
        if ($request->hasFile('gallery_images')) {
            $uploadPath = public_path('products-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            foreach ($request->file('gallery_images') as $galleryImage) {
                $galleryName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move($uploadPath, $galleryName);
                $gallery->push(basename(ImageOptimizer::optimize($uploadPath . '/' . $galleryName, 1200)['path']));
            }
        }

        $product->gallery = $gallery->values()->all();

        $product->save();

        // Sync sizes
        if ($request->has('sizes')) {
            $sizesData = [];
            foreach ($request->sizes as $sizeId) {
                $sizesData[$sizeId] = [
                    'stock' => $request->input("size_stock_{$sizeId}"),
                    'price_adjustment' => $request->input("size_price_{$sizeId}", 0)
                ];
            }
            $product->sizes()->sync($sizesData);
        } else {
            $product->sizes()->detach();
        }

        // Sync colors
        if ($request->has('colors')) {
            $colorsData = [];
            foreach ($request->colors as $colorId) {
                $colorsData[$colorId] = [
                    'stock' => $request->input("color_stock_{$colorId}"),
                    'price_adjustment' => $request->input("color_price_{$colorId}", 0)
                ];
            }
            $product->colors()->sync($colorsData);
        } else {
            $product->colors()->detach();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete main image
        $imagePath = public_path('products-images/' . $product->image);
        if ($product->image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete gallery images
        foreach ($product->gallery ?? [] as $galleryFile) {
            $galleryPath = public_path('products-images/' . $galleryFile);
            if (file_exists($galleryPath)) {
                unlink($galleryPath);
            }
        }

        // Detach sizes and colors
        $product->sizes()->detach();
        $product->colors()->detach();

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Update product stock via AJAX
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product->stock = $request->stock;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'stock' => $product->stock
        ]);
    }
}