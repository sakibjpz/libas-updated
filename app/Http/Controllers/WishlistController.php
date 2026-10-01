<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist.');
        }

        $wishlistItems = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to add to wishlist.'], 401);
        }

        $product = Product::findOrFail($productId);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Product already in wishlist.']);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        return response()->json(['success' => true, 'message' => 'Added to wishlist.']);
    }

    public function remove(Request $request, $productId)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login.'], 401);
            }
            return redirect()->route('login');
        }

        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist.']);
        }
        return back()->with('success', 'Removed from wishlist.');
    }
}
