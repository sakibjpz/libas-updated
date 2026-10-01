<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
            'customer_name' => 'required_if:user_id,null|string|max:255',
            'customer_email' => 'nullable|email|max:255',
        ]);

        $product = Product::findOrFail($productId);

        // Check if user already reviewed this product
        if (Auth::check()) {
            $existingReview = ProductReview::where('product_id', $productId)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReview) {
                return back()->with('error', 'You have already reviewed this product.');
            }
        }

        $review = ProductReview::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'customer_name' => Auth::check() ? null : $request->customer_name,
            'customer_email' => $request->customer_email,
            'rating' => $request->rating,
            'review' => $request->review,
            'approved' => false, // Requires admin approval
        ]);

        return back()->with('success', 'Thank you for your review! It will be visible after approval.');
    }
}
