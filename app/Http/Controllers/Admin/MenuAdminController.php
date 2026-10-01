<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Services\ImageOptimizer;

class MenuAdminController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = \App\Models\Category::with('parent')
            ->orderByRaw('COALESCE(parent_id, id)')->orderBy('parent_id')->orderBy('name')
            ->get();

        return view('admin.menus.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $parents = \App\Models\Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.menus.create', compact('parents'));
    }
    
    /**
     * Store a newly created category in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Save image to public/categories directory
            $uploadPath = public_path('categories');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $filename);
            $validated['image'] = basename(ImageOptimizer::optimize($uploadPath . '/' . $filename, 800)['path']);
        }

        $validated['parent_id'] = $validated['parent_id'] ?? null;
        // Sub-subcategories not supported — a child cannot become a parent
        if ($validated['parent_id']) {
            $p = \App\Models\Category::find($validated['parent_id']);
            if ($p && $p->parent_id) {
                return back()->withInput()->withErrors(['parent_id' => 'Sub-subcategories are not supported. Choose a top-level category.']);
            }
        }

        \App\Models\Category::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $menu)
    {
        // Note: parameter is named $menu for route compatibility
        // but it's actually a Category model instance
        $parents = \App\Models\Category::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('name')->get();
        return view('admin.menus.edit', compact('menu', 'parents'));
    }

    /**
     * Update the specified category in storage
     */
    public function update(Request $request, Category $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $menu->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                \Illuminate\Validation\Rule::notIn([$menu->id]),
            ],
            'description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image && file_exists($menu->image_path)) {
                unlink($menu->image_path);
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Save image to public/categories directory
            $uploadPath = public_path('categories');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $filename);
            $validated['image'] = basename(ImageOptimizer::optimize($uploadPath . '/' . $filename, 800)['path']);
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage
     */
    public function destroy(Category $menu)
    {
        // Note: parameter is named $menu for route compatibility
        // but it's actually a Category model instance
        
        // Delete category image if exists
        if ($menu->image && file_exists($menu->image_path)) {
            unlink($menu->image_path);
        }

        // Before deleting, set category_id to null for all products in this category
        \App\Models\Product::where('category_id', $menu->id)->update(['category_id' => null]);
        
        $menu->delete();
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Category deleted successfully. Products in this category have been set to "No Category".');
    }
}