<?php

namespace App\Http\Controllers;

use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Show images page
     */
    public function index()
{
    // Get images only from products folder
    $images = Storage::files('public/products');
    
    // Pass to view
    return view('admin.images.index', compact('images'));
}

    /**
     * Handle image upload
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Store in products folder by default
        $folder = 'products';
        $path = $request->file('image')->store($folder, 'public');
        $result = ImageOptimizer::optimize(Storage::disk('public')->path($path), 1200);
        if ($result['renamed']) {
            $path = $folder . '/' . basename($result['path']);
        }

        return back()->with('success', 'Image uploaded successfully!');
    }

    /**
     * Delete image
     */
    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        if (Storage::exists($request->path)) {
            Storage::delete($request->path);
            return back()->with('success', 'Image deleted successfully!');
        }

        return back()->with('error', 'Image not found.');
    }
}