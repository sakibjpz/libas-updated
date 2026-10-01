<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuAdminController;
use App\Http\Controllers\Admin\NavigationMenuController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\FraudController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SalesAnalyticsController;
use App\Http\Controllers\Admin\SiteSettingController;

// ====================
// PUBLIC ROUTES
// ====================

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Contact Page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Public parcel tracking (Steadfast)
Route::get('/track', [App\Http\Controllers\TrackingController::class, 'index'])->name('track');

// About Page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Legal Pages
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');
Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');
Route::get('/refund', function () {
    return view('legal.refund');
})->name('refund');
Route::get('/delivery', function () {
    return view('legal.delivery');
})->name('delivery');

// Contact Form Submission
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.submit');

// Password Reset
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');

// Frontend Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Search autocomplete suggestions
Route::get('/search/suggestions', [ProductController::class, 'suggestions'])->name('search.suggestions');

// Product variant options (size/color) for add-to-cart modal
Route::get('/products/{id}/options', [ProductController::class, 'options'])->whereNumber('id')->name('products.options');

// Legacy numeric product URLs -> 301 redirect to slug URL (SEO)
Route::get('/products/{id}', function ($id) {
    $product = App\Models\Product::findOrFail($id);
    return redirect()->route('products.show', $product, 301);
})->whereNumber('id')->name('products.legacy');

Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Product landing pages (themed, standalone)
Route::get('/lp/{product:slug}', [App\Http\Controllers\ProductLandingController::class, 'show'])->name('landing.show');

// SEO Sitemap
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
// Customer review removed as requested
Route::post('/products/{product}/review', [App\Http\Controllers\ProductReviewController::class, 'store'])->middleware('throttle:5,1')->name('products.review.store');

// Wishlist (Removed as requested)
Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add/{productId}', [App\Http\Controllers\WishlistController::class, 'add'])->name('wishlist.add');
Route::post('/wishlist/remove/{productId}', [App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');

// Cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/save-delivery-area', [CartController::class, 'saveDeliveryArea'])->name('cart.save-delivery-area');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-order', [CartController::class, 'placeOrder'])->middleware('throttle:10,1')->name('cart.placeOrder');
});

// ====================
// ADMIN ROUTES (Protected)
// ====================

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', AdminProductController::class)->except(['show']);
    // Stock update route
    Route::patch('/products/{product}/update-stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    
    // Orders Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/print', [OrderController::class, 'printInvoice'])->name('orders.print');
    Route::patch('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Image Management
    Route::prefix('images')->name('images.')->group(function () {
        Route::get('/', [ImageController::class, 'index'])->name('index');
        Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
        Route::delete('/delete', [ImageController::class, 'delete'])->name('delete');
    });


// Contact Messages Management
Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('index');
    Route::get('/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('show');
    Route::post('/{id}/status', [App\Http\Controllers\Admin\ContactMessageController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-action', [App\Http\Controllers\Admin\ContactMessageController::class, 'bulkAction'])->name('bulk-action');
});


    
    // Menu Management (New)
    Route::resource('menus', MenuAdminController::class)->except(['show']);

    // Navigation Menu Management (header menus + submenus)
    Route::resource('menu-items', NavigationMenuController::class)
        ->parameters(['menu-items' => 'menu'])
        ->except(['show']);

    // Banners Management
    Route::resource('banners', BannerController::class)->except(['show']);

    // Fraud Detection Management
    Route::prefix('fraud')->name('fraud.')->group(function () {
        Route::get('/', [FraudController::class, 'index'])->name('index');
        Route::get('/statistics', [FraudController::class, 'statistics'])->name('statistics');
        Route::post('/blacklist/add', [FraudController::class, 'addToBlacklist'])->name('add-blacklist');
        Route::post('/blacklist/remove', [FraudController::class, 'removeFromBlacklist'])->name('remove-blacklist');
        Route::get('/{id}', [FraudController::class, 'show'])->name('show');
        Route::post('/{id}/mark-safe', [FraudController::class, 'markAsSafe'])->name('mark-safe');
        Route::post('/{id}/mark-fraudulent', [FraudController::class, 'markAsFraudulent'])->name('mark-fraudulent');
    });

    // Coupon Management
    Route::resource('coupons', CouponController::class)->except(['show']);
    Route::post('/coupons/{coupon}/toggle', [CouponController::class, 'toggleStatus'])->name('coupons.toggle');

    // Product Reviews moderation
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/toggle', [App\Http\Controllers\Admin\ReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Sales Analytics
    Route::get('/analytics', [SalesAnalyticsController::class, 'index'])->name('analytics.index');

    // Site Settings
    Route::prefix('site-settings')->name('site-settings.')->group(function () {
        Route::get('/', [SiteSettingController::class, 'index'])->name('index');
        Route::put('/', [SiteSettingController::class, 'update'])->name('update');
    });


    // Add these inside your admin prefix route group
Route::prefix('steadfast')->name('steadfast.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SteadfastController::class, 'index'])->name('index');
    Route::get('/balance', [App\Http\Controllers\Admin\SteadfastController::class, 'checkBalance'])->name('balance');
    Route::post('/track', [App\Http\Controllers\Admin\SteadfastController::class, 'trackOrder'])->middleware('throttle:10,1')->name('track');
    Route::get('/returns', [App\Http\Controllers\Admin\SteadfastController::class, 'returns'])->name('returns');
    Route::get('/returns/{id}', [App\Http\Controllers\Admin\SteadfastController::class, 'showReturn'])->name('returns.show');
    Route::get('/payments', [App\Http\Controllers\Admin\SteadfastController::class, 'payments'])->name('payments');
    Route::get('/payments/{id}', [App\Http\Controllers\Admin\SteadfastController::class, 'showPayment'])->name('payments.show');
    Route::get('/police-stations', [App\Http\Controllers\Admin\SteadfastController::class, 'policeStations'])->name('police-stations');
    Route::post('/send-order/{orderId}', [App\Http\Controllers\Admin\SteadfastController::class, 'sendOrder'])->name('send-order');
});
});



// ====================
// CHATBOT ROUTES (DISABLED - Using Tawk.to instead)
// ====================
// Chatbot functionality now handled by Tawk.to widget