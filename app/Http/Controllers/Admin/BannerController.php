<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of banners
     */
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'link' => 'nullable|url',
        'order' => 'integer|min:0',
        'is_active' => 'boolean'
    ]);

    $image = $request->file('image');

    // Generate unique filename
    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

    // Save image to public/banners directory
    $uploadPath = public_path('banners');

    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $image->move($uploadPath, $imageName);
    $imageName = basename(ImageOptimizer::optimize($uploadPath . '/' . $imageName, 1600)['path']);

    Banner::create([
        'title' => $request->title,
        'image' => $imageName,
        'link' => $request->link,
        'order' => $request->order ?? 0,
        'is_active' => $request->has('is_active')
    ]);

    return redirect()->route('admin.banners.index')
        ->with('success', 'Banner created successfully.');
}
    /**
     * Show the form for editing a banner
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'order' => $request->order ?? $banner->order,
            'is_active' => $request->has('is_active')
        ];

        // Handle image replacement
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Delete old image if exists
            if ($banner->image && file_exists($banner->image_path)) {
                unlink($banner->image_path);
            }

            // Save new image to public/banners directory
            $uploadPath = public_path('banners');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $image->move($uploadPath, $imageName);
            $data['image'] = basename(ImageOptimizer::optimize($uploadPath . '/' . $imageName, 1600)['path']);
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified banner
     */
    public function destroy(Banner $banner)
    {
        // Delete image file if exists
        if ($banner->image && file_exists($banner->image_path)) {
            unlink($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}