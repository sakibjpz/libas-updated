<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with('product', 'user')
            ->latest()
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(ProductReview $review)
    {
        $review->update(['approved' => !$review->approved]);

        return back()->with('success', $review->approved ? 'Review approved.' : 'Review hidden.');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
