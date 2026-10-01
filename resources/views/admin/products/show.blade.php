@extends('layouts.app')

@section('title', $product->name . ' - libasbd')

@section('content')
<div class="product-detail-page">
    <div class="container my-4">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>

        <!-- Product Banner -->
        <div class="product-banner">
            <h1>Product Details</h1>
            <p>View complete product information</p>
        </div>

        <!-- Product Details Section -->
        <div class="row product-section">
            {{-- Product Image --}}
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="product-image-wrapper">
                    @if($product->discount)
                        <div class="discount-badge">
                            <i class="fas fa-tag"></i> {{ $product->discount }}% OFF
                        </div>
                    @endif
                    
                    @if($product->image)
                        <img src="{{ asset('products-images/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="no-image">
                            <i class="fas fa-image"></i>
                            <p>No Image Available</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="col-lg-7 col-md-6">
                <div class="product-info">
                    <h2 class="product-name">{{ $product->name }}</h2>
                    
                    <div class="product-meta">
                        <span class="category-tag">
                            <i class="fas fa-folder"></i> {{ $product->category_name ?? 'Uncategorized' }}
                        </span>
                        @if($product->brand)
                            <span class="brand-tag">
                                <i class="fas fa-copyright"></i> {{ $product->brand }}
                            </span>
                        @endif
                    </div>

                    <div class="price-wrapper">
                        <span class="current-price">৳{{ number_format($product->price, 2) }}</span>
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="original-price">৳{{ number_format($product->original_price, 2) }}</span>
                            <span class="save-amount">Save ৳{{ number_format($product->original_price - $product->price, 2) }}</span>
                        @endif
                    </div>

                    <div class="stock-info">
                        @if($product->stock > 5)
                            <span class="in-stock"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} units)</span>
                        @elseif($product->stock > 0)
                            <span class="low-stock"><i class="fas fa-exclamation-circle"></i> Only {{ $product->stock }} left</span>
                        @else
                            <span class="out-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>

                    @if($product->description)
                        <div class="description">
                            <h5><i class="fas fa-info-circle"></i> Description</h5>
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="product-actions">
                        <button class="add-to-cart-btn" 
                                id="addToCartBtn"
                                data-product-id="{{ $product->id }}"
                                @if($product->stock == 0) disabled @endif>
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                        <button class="buy-now-btn"
                                @if($product->stock == 0) disabled @endif>
                            <i class="fas fa-bolt"></i> Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($product->category_id)
            @php
                $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->take(4)
                    ->get();
            @endphp

            @if($relatedProducts->count() > 0)
                <div class="related-products-section">
                    <h3 class="section-title">
                        <i class="fas fa-th-large"></i> Related Products
                    </h3>
                    <div class="related-products-grid">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="related-product-card">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="product-link">
                                    <div class="related-product-image">
                                        @if($relatedProduct->image)
                                            <img src="{{ asset('products-images/' . $relatedProduct->image) }}" 
                                                 alt="{{ $relatedProduct->name }}">
                                        @else
                                            <div class="no-image-small">
                                                <i class="fas fa-box"></i>
                                            </div>
                                        @endif
                                        
                                        @if($relatedProduct->discount)
                                            <div class="small-discount">{{ $relatedProduct->discount }}% OFF</div>
                                        @endif
                                    </div>
                                    
                                    <div class="related-product-info">
                                        <h4>{{ $relatedProduct->name }}</h4>
                                        <div class="related-price">
                                            <span class="price">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                            @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                                <span class="old-price">৳{{ number_format($relatedProduct->original_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="related-stock {{ $relatedProduct->stock > 0 ? 'available' : 'unavailable' }}">
                                            @if($relatedProduct->stock > 0)
                                                <i class="fas fa-check"></i> Available
                                            @else
                                                <i class="fas fa-times"></i> Out of Stock
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
<style>
    /* Global Styles */
    .product-detail-page {
        background: #f8f9fa !important;
        padding: 20px 0 !important;
        min-height: 60vh !important;
    }

    /* Back Button */
    .back-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 10px 20px !important;
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%) !important;
        color: white !important;
        text-decoration: none !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3) !important;
        border: none !important;
        margin-bottom: 20px !important;
    }

    .back-btn:hover {
        transform: translateX(-5px) !important;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
        color: white !important;
    }

    /* Product Banner */
    .product-banner {
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%) !important;
        border-radius: 12px !important;
        padding: 40px 30px !important;
        margin-bottom: 30px !important;
        text-align: center !important;
        color: white !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
    }

    .product-banner h1 {
        font-size: 32px !important;
        font-weight: 700 !important;
        margin-bottom: 8px !important;
        color: white !important;
    }

    .product-banner p {
        font-size: 16px !important;
        opacity: 0.9 !important;
        margin: 0 !important;
    }

    /* Product Section */
    .product-section {
        background: white !important;
        border-radius: 12px !important;
        padding: 30px !important;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08) !important;
        margin-bottom: 40px !important;
    }

    /* Product Image */
    .product-image-wrapper {
        position: relative !important;
        background: #f8f9fa !important;
        border-radius: 12px !important;
        padding: 20px !important;
        overflow: hidden !important;
    }

    .product-image {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        border-radius: 8px !important;
        transition: transform 0.3s ease !important;
        display: block !important;
    }

    .product-image-wrapper:hover .product-image {
        transform: scale(1.05) !important;
    }

    .no-image {
        width: 100% !important;
        height: 400px !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        background: #e9ecef !important;
        border-radius: 8px !important;
        color: #adb5bd !important;
    }

    .no-image i {
        font-size: 64px !important;
        margin-bottom: 10px !important;
    }

    .no-image p {
        font-size: 16px !important;
        margin: 0 !important;
    }

    .discount-badge {
        position: absolute !important;
        top: 30px !important;
        left: 30px !important;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        padding: 8px 12px !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: bold !important;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3) !important;
        z-index: 10 !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
    }

    /* Product Info */
    .product-info {
        padding: 0 !important;
    }

    .product-name {
        font-size: 28px !important;
        font-weight: 700 !important;
        color: #333 !important;
        margin-bottom: 15px !important;
        line-height: 1.3 !important;
    }

    .product-meta {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 10px !important;
        margin-bottom: 20px !important;
    }

    .category-tag,
    .brand-tag {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }

    .category-tag {
        background: #e0f2fe !important;
        color: #0369a1 !important;
    }

    .brand-tag {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    /* Price */
    .price-wrapper {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
        margin-bottom: 20px !important;
    }

    .current-price {
        font-size: 32px !important;
        font-weight: 800 !important;
        color: #10b981 !important;
    }

    .original-price {
        font-size: 20px !important;
        color: #999 !important;
        text-decoration: line-through !important;
    }

    .save-amount {
        background: #10b981 !important;
        color: white !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
    }

    /* Stock Info */
    .stock-info {
        margin-bottom: 20px !important;
    }

    .stock-info span {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        padding: 8px 14px !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }

    .in-stock {
        background: #d1fae5 !important;
        color: #065f46 !important;
    }

    .low-stock {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    .out-stock {
        background: #fee2e2 !important;
        color: #991b1b !important;
    }

    /* Description */
    .description {
        margin-bottom: 25px !important;
        padding: 20px !important;
        background: #f8f9fa !important;
        border-radius: 8px !important;
    }

    .description h5 {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #333 !important;
        margin-bottom: 10px !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .description p {
        font-size: 15px !important;
        color: #666 !important;
        line-height: 1.6 !important;
        margin: 0 !important;
    }

    /* Action Buttons */
    .product-actions {
        display: flex !important;
        gap: 12px !important;
        margin-top: 25px !important;
    }

    .add-to-cart-btn,
    .buy-now-btn {
        flex: 1 !important;
        padding: 14px 20px !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        border: none !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
    }

    .add-to-cart-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3) !important;
    }

    .add-to-cart-btn:hover:not(:disabled) {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4) !important;
    }

    .buy-now-btn {
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%) !important;
        color: white !important;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3) !important;
    }

    .buy-now-btn:hover:not(:disabled) {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
    }

    .add-to-cart-btn:disabled,
    .buy-now-btn:disabled {
        background: #e5e7eb !important;
        color: #9ca3af !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
    }

    /* Related Products Section */
    .related-products-section {
        margin-top: 40px !important;
    }

    .section-title {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #333 !important;
        margin-bottom: 25px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .section-title i {
        color: #d4b25f !important;
    }

    .related-products-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
        gap: 20px !important;
    }

    .related-product-card {
        background: white !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08) !important;
        transition: all 0.3s ease !important;
    }

    .related-product-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
    }

    .product-link {
        text-decoration: none !important;
        color: inherit !important;
        display: block !important;
    }

    .related-product-image {
        position: relative !important;
        height: 200px !important;
        background: #f8f9fa !important;
        overflow: hidden !important;
    }

    .related-product-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.3s ease !important;
    }

    .related-product-card:hover .related-product-image img {
        transform: scale(1.1) !important;
    }

    .no-image-small {
        width: 100% !important;
        height: 200px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #e9ecef !important;
        color: #ccc !important;
        font-size: 40px !important;
    }

    .small-discount {
        position: absolute !important;
        top: 10px !important;
        left: 10px !important;
        background: #ef4444 !important;
        color: white !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: bold !important;
    }

    .related-product-info {
        padding: 15px !important;
    }

    .related-product-info h4 {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #333 !important;
        margin-bottom: 10px !important;
        line-height: 1.4 !important;
        height: 42px !important;
        overflow: hidden !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
    }

    .related-price {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 8px !important;
    }

    .related-price .price {
        font-size: 18px !important;
        font-weight: 700 !important;
        color: #10b981 !important;
    }

    .related-price .old-price {
        font-size: 14px !important;
        color: #999 !important;
        text-decoration: line-through !important;
    }

    .related-stock {
        font-size: 12px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
    }

    .related-stock.available {
        background: #d1fae5 !important;
        color: #065f46 !important;
    }

    .related-stock.unavailable {
        background: #fee2e2 !important;
        color: #991b1b !important;
    }

    /* Notification Styles */
    .custom-notification {
        position: fixed !important;
        top: 20px !important;
        right: 20px !important;
        background: white !important;
        padding: 16px 20px !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        z-index: 9999 !important;
        transform: translateX(400px) !important;
        transition: transform 0.3s ease !important;
        min-width: 300px !important;
    }

    .custom-notification.show {
        transform: translateX(0) !important;
    }

    .custom-notification.success {
        border-left: 4px solid #10b981 !important;
    }

    .custom-notification.error {
        border-left: 4px solid #ef4444 !important;
    }

    .custom-notification.success i {
        color: #10b981 !important;
        font-size: 22px !important;
    }

    .custom-notification.error i {
        color: #ef4444 !important;
        font-size: 22px !important;
    }

    .custom-notification span {
        color: #333 !important;
        font-size: 14px !important;
        font-weight: 600 !important;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .product-banner h1 {
            font-size: 28px !important;
        }

        .product-name {
            font-size: 24px !important;
        }

        .current-price {
            font-size: 28px !important;
        }

        .product-image {
            height: 350px !important;
        }

        .no-image {
            height: 350px !important;
        }
    }

    @media (max-width: 768px) {
        .product-banner {
            padding: 30px 20px !important;
        }

        .product-banner h1 {
            font-size: 24px !important;
        }

        .product-section {
            padding: 20px !important;
        }

        .product-image {
            height: 300px !important;
        }

        .no-image {
            height: 300px !important;
        }

        .product-name {
            font-size: 22px !important;
        }

        .current-price {
            font-size: 26px !important;
        }

        .product-actions {
            flex-direction: column !important;
        }

        .add-to-cart-btn,
        .buy-now-btn {
            width: 100% !important;
        }

        .related-products-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 15px !important;
        }

        .section-title {
            font-size: 20px !important;
        }
    }

    @media (max-width: 576px) {
        .back-btn {
            font-size: 13px !important;
            padding: 8px 16px !important;
        }

        .product-banner {
            padding: 25px 15px !important;
        }

        .product-banner h1 {
            font-size: 20px !important;
        }

        .product-banner p {
            font-size: 14px !important;
        }

        .product-section {
            padding: 15px !important;
        }

        .product-image {
            height: 250px !important;
        }

        .no-image {
            height: 250px !important;
        }

        .no-image i {
            font-size: 48px !important;
        }

        .discount-badge {
            top: 20px !important;
            left: 20px !important;
            padding: 6px 10px !important;
            font-size: 11px !important;
        }

        .product-name {
            font-size: 20px !important;
        }

        .current-price {
            font-size: 24px !important;
        }

        .original-price {
            font-size: 16px !important;
        }

        .add-to-cart-btn,
        .buy-now-btn {
            padding: 12px 16px !important;
            font-size: 14px !important;
        }

        .related-products-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px !important;
        }

        .related-product-image {
            height: 150px !important;
        }

        .no-image-small {
            height: 150px !important;
            font-size: 32px !important;
        }

        .related-product-info {
            padding: 12px !important;
        }

        .related-product-info h4 {
            font-size: 14px !important;
            height: 38px !important;
        }

        .related-price .price {
            font-size: 16px !important;
        }

        .custom-notification {
            top: 10px !important;
            right: 10px !important;
            left: 10px !important;
            min-width: auto !important;
            padding: 12px 16px !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart Functionality
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.disabled) return;
            
            const productId = this.getAttribute('data-product-id');
            
            // Disable button temporarily
            this.disabled = true;
            const originalHTML = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Make AJAX call to add to cart
            fetch(`${window.LIBAS_BASE || ''}/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Success feedback
                    this.innerHTML = '<i class="fas fa-check-circle"></i> Added!';
                    this.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
                    
                    // Update cart count in header
                    updateCartCount(data.cartCount);
                    
                    // Show success notification
                    showNotification('Product added to cart successfully!', 'success');
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                } else {
                    // Error feedback
                    this.innerHTML = '<i class="fas fa-times-circle"></i> Failed';
                    this.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                    
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                    
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = '<i class="fas fa-times-circle"></i> Error';
                this.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                
                showNotification('An error occurred. Please try again.', 'error');
                
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.style.background = '';
                    this.disabled = false;
                }, 2000);
            });
        });
    }
    
    // Buy Now functionality (redirects to cart)
    const buyNowBtn = document.querySelector('.buy-now-btn');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            if (!this.disabled) {
                const productId = addToCartBtn.getAttribute('data-product-id');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                this.disabled = true;
                
                fetch(`${window.LIBAS_BASE || ''}/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: 1 })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        window.location.href = '/cart';
                    } else {
                        showNotification(data.message || 'Failed to add product to cart', 'error');
                        this.innerHTML = '<i class="fas fa-bolt"></i> Buy Now';
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    this.innerHTML = '<i class="fas fa-bolt"></i> Buy Now';
                    this.disabled = false;
                });
            }
        });
    }
    
    // Function to update cart count in header
    function updateCartCount(count) {
        let cartBadge = document.querySelector('.cart-count-badge');
        const cartLink = document.querySelector('.cart-link');
        
        if (count > 0) {
            if (!cartBadge) {
                cartBadge = document.createElement('span');
                cartBadge.className = 'cart-count-badge';
                if (cartLink) {
                    cartLink.appendChild(cartBadge);
                }
            }
            cartBadge.textContent = count;
        } else if (cartBadge) {
            cartBadge.remove();
        }
    }
    
    // Function to show notification
    function showNotification(message, type) {
        const existingNotification = document.querySelector('.custom-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `custom-notification ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
});
</script>