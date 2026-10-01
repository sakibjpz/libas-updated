@extends('layouts.app')

@section('title', $categorySlug ? ($products->first() && $products->first()->category ? $products->first()->category->name : ucfirst(str_replace('-', ' ', $categorySlug))) . ' - Buy Online | libasbd' : 'All Products - libasbd')
@section('canonical', rtrim(config('app.url'), '/') . '/products' . ($categorySlug ? '?category=' . urlencode($categorySlug) : ''))
@php
    $seoCatName = $categorySlug ? (($products->first() && $products->first()->category) ? $products->first()->category->name : ucfirst(str_replace('-', ' ', $categorySlug))) : null;
@endphp
@section('description', ($category ?? null) && $category->description
    ? $category->description
    : ($seoCatName
        ? 'Shop premium ' . $seoCatName . ' online at LibasBD — best prices in Bangladesh, 100% authentic products, cash on delivery & fast nationwide shipping.'
        : 'Shop premium burkha, abaya, 3 piece, cosmetics and more at LibasBD. Best prices, authentic products, fast nationwide delivery across Bangladesh.'))

@section('keywords', ($category ?? null) && $category->meta_keywords
    ? $category->meta_keywords
    : 'Buy Burkha Online BD, Abaya Online Bangladesh, Dubai Abaya, Saudi Burkha, Jilbab, Niqab, Hijab Online, Premium 3 Piece, Pakistani 3 Piece, Unstitched Three Piece, Salwar Kameez Bangladesh, Original Cosmetics BD, Skin Care, Women\'s Fashion Bangladesh, Online Shopping Bangladesh, Modest Fashion, Muslim Women Clothing, Eid Collection, Men Fashion BD, Kids Fashion Bangladesh, Kids Wear, Sherwani Premium, Sherwani Online BD, Panjabi Online, Boys Girls Dress, Cash on Delivery, বোরকা, আবায়া, থ্রি পিস, কসমেটিকস, শেরওয়ানি, পাঞ্জাবি, LibasBD products')

@section('content')
<div class="products-page">
    <div class="container">
        <!-- Go Back Button -->
        <div class="go-back-container">
            <a href="{{ url('/') }}">
                <i class="fas fa-arrow-left"></i>
                <span>Go Back</span>
            </a>
        </div>
        
        <h1>Products</h1>

        <!-- Banner Section -->
        <div class="products-banner mb-5">
            <div class="banner-content">
                <div class="banner-text">
                    <h2>Discover Amazing Products</h2>
                    <p>Shop from our wide collection of quality products at best prices</p>
                    <div class="banner-stats">
                        <div class="stat">
                            <span class="number">{{ \App\Models\Product::count() }}+</span>
                            <span class="label">Products</span>
                        </div>
                        <div class="stat">
                            <span class="number">{{ \App\Models\Category::count() }}+</span>
                            <span class="label">Categories</span>
                        </div>
                        <div class="stat">
                            <span class="number">100%</span>
                            <span class="label">Quality</span>
                        </div>
                    </div>
                </div>
                <div class="banner-image">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Shopping Banner" 
                         loading="lazy">
                </div>
            </div>
        </div>
        
        <!-- Simple Category Filter -->
        <div class="category-filter mb-4" id="categoryFilter">
            <div class="filter-header">
                <h3><i class="fas fa-filter"></i> Filter by Category</h3>
            </div>
            <div class="filter-options">
                <a href="{{ url('/products') }}" class="filter-option {{ !$categorySlug ? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    All Products
                    <span class="count">{{ \App\Models\Product::count() }}</span>
                </a>
                
                @foreach(\App\Models\Category::whereNull('parent_id')->with('children')->orderBy('name')->get() as $categoryItem)
                    @php
                        $subCategories = $categoryItem->children;
                        $categoryProductCount = \App\Models\Product::whereIn('category_id', $categoryItem->idsWithChildren())->count();
                    @endphp
                    <a href="{{ url('/products') }}?category={{ $categoryItem->slug }}" 
                       class="filter-option {{ ($categorySlug == $categoryItem->slug) ? 'active' : '' }}">
                        <i class="fas fa-tag"></i>
                        {{ $categoryItem->name }}
                        <span class="count">{{ $categoryProductCount }}</span>
                    </a>
                    @foreach($subCategories as $sub)
                        <a href="{{ url('/products') }}?category={{ $sub->slug }}"
                           class="filter-option filter-option--sub {{ ($categorySlug == $sub->slug) ? 'active' : '' }}">
                            <i class="fas fa-level-up-alt fa-rotate-90"></i>
                            {{ $sub->name }}
                            <span class="count">{{ \App\Models\Product::where('category_id', $sub->id)->count() }}</span>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </div>
        
        <!-- Products Section Start Marker -->
        <div id="productsSection"></div>
        
        @if($categorySlug && $products->count() > 0)
            <div class="current-category mb-4">
                @php
                    $categoryName = $products->first() ? $products->first()->category->name : urldecode($categorySlug);
                @endphp
                <div class="category-info">
                    <i class="fas fa-folder-open"></i>
                    <h2>{{ $categoryName }}</h2>
                    <span class="product-count">{{ $products->count() }} {{ $products->count() == 1 ? 'Product' : 'Products' }}</span>
                </div>
                <a href="{{ url('/products') }}" class="clear-filter">
                    <i class="fas fa-times-circle"></i> Clear Filter
                </a>
            </div>
        @endif
        
        <!-- Products — grouped by category/subcategory, or flat grid -->
        @if($products->count() > 0)
            @if($productGroups && $productGroups->count())
                @foreach($productGroups as $group)
                    <div class="cat-group">
                        <div class="cat-group__head">
                            <h2><i class="fas fa-folder-open"></i> {{ $group['name'] }}</h2>
                            @if($group['slug'])
                                <a href="{{ url('/products') }}?category={{ $group['slug'] }}" class="cat-group__more">View All <i class="fas fa-arrow-right"></i></a>
                            @endif
                        </div>

                        @if($group['subs']->count())
                            @if($group['own']->count())
                                <div class="products-grid">
                                    @foreach($group['own'] as $product)
                                        @include('products._card', ['product' => $product])
                                    @endforeach
                                </div>
                            @endif
                            @foreach($group['subs'] as $sub)
                                <h3 class="cat-group__sub">
                                    <a href="{{ url('/products') }}?category={{ $sub['slug'] }}">{{ $sub['name'] }}</a>
                                    <span class="cat-group__count">{{ $sub['items']->count() }}</span>
                                </h3>
                                <div class="products-grid">
                                    @foreach($sub['items'] as $product)
                                        @include('products._card', ['product' => $product])
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <div class="products-grid">
                                @foreach($group['items'] as $product)
                                    @include('products._card', ['product' => $product])
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="products-grid">
                    @foreach($products as $product)
                        @include('products._card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        @else
            <div class="no-products">
                <i class="fas fa-box-open"></i>
                <h3>No products found</h3>
                <p>Try selecting a different category</p>
                <a href="{{ url('/products') }}" class="btn">
                    <i class="fas fa-arrow-left"></i> View All Products
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .products-page {
        padding: 20px 0;
        min-height: 60vh;
    }
    
    .products-page h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 36px;
        font-weight: 700;
    }
    
    /* Go Back Button */
    .go-back-container {
        margin-bottom: 20px;
    }
    
    .go-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .go-back-btn:hover {
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .go-back-btn i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }
    
    .go-back-btn:hover i {
        transform: translateX(-3px);
    }
    
    /* Banner Styles */
    .products-banner {
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
        border-radius: 15px;
        overflow: hidden;
        color: white;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        position: relative;
    }
    
    .products-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,106.7C1248,96,1344,96,1392,96L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
        opacity: 0.3;
    }
    
    .banner-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        position: relative;
        z-index: 1;
    }
    
    .banner-text {
        flex: 1;
    }
    
    .banner-text h2 {
        font-size: 36px;
        margin-bottom: 15px;
        color: white;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .banner-text p {
        font-size: 18px;
        margin-bottom: 25px;
        opacity: 0.95;
        max-width: 500px;
        line-height: 1.6;
    }
    
    .banner-stats {
        display: flex;
        gap: 40px;
        margin-top: 30px;
    }
    
    .stat {
        text-align: center;
        padding: 15px 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    
    .stat:hover {
        transform: translateY(-5px);
    }
    
    .stat .number {
        display: block;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat .label {
        font-size: 14px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .banner-image {
        flex: 0 0 300px;
    }
    
    .banner-image img {
        width: 100%;
        border-radius: 10px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
    }
    
    .banner-image img:hover {
        transform: scale(1.05);
    }
    
    /* Category Filter */
    .category-filter {
        background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }
    
    .filter-header {
        margin-bottom: 20px;
    }
    
    .filter-header h3 {
        margin: 0;
        font-size: 20px;
        color: #333;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-header i {
        color: #d4b25f;
    }
    
    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .filter-option {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        padding: 10px 18px;
        text-decoration: none;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .filter-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .filter-option:hover::before {
        left: 100%;
    }
    
    .filter-option:hover {
        border-color: #d4b25f;
        color: #d4b25f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(201, 162, 75, 0.25);
    }
    
    .filter-option.active {
        background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
        color: white;
        border-color: #d4b25f;
        box-shadow: 0 4px 15px rgba(201, 162, 75, 0.3);
    }

    .filter-option--sub {
        padding: 7px 14px;
        font-size: 12.5px;
        border-style: dashed;
        background: #faf8f1;
        color: #7a6126;
    }
    
    .filter-option i {
        font-size: 12px;
    }

    /* Category / subcategory grouped sections */
    .cat-group { margin-bottom: 44px; }
    .cat-group__head {
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 2px solid #f0e8d0; padding-bottom: 10px; margin-bottom: 18px;
    }
    .cat-group__head h2 {
        font-size: 22px; font-weight: 700; color: #1d1912; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .cat-group__head h2 i { color: #c9a24b; font-size: 19px; }
    .cat-group__more {
        font-size: 13.5px; font-weight: 600; color: #9c7c33; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;
    }
    .cat-group__more:hover { color: #1d1912; }
    .cat-group__sub {
        font-size: 16px; font-weight: 600; color: #7a6126; margin: 22px 0 12px;
        display: flex; align-items: center; gap: 8px;
    }
    .cat-group__sub a { color: inherit; text-decoration: none; }
    .cat-group__sub a:hover { color: #9c7c33; text-decoration: underline; }
    .cat-group__count {
        font-size: 11.5px; font-weight: 600; background: #faf6ec; color: #a07f2a;
        border: 1px solid #f0e8d0; border-radius: 20px; padding: 2px 10px;
    }
    
    .filter-option .count {
        background: #f3f4f6;
        color: #6b7280;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        min-width: 24px;
        text-align: center;
    }
    
    .filter-option.active .count {
        background: rgba(255,255,255,0.25);
        color: white;
    }
    
    /* Current Category */
    .current-category {
        background: linear-gradient(135deg, #faf6ec 0%, #f3e8cd 100%);
        padding: 20px 25px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-left: 4px solid #c9a24b;
        box-shadow: 0 2px 10px rgba(201, 162, 75, 0.12);
    }
    
    .category-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .category-info i {
        font-size: 24px;
        color: #c9a24b;
    }
    
    .current-category h2 {
        margin: 0;
        font-size: 20px;
        color: #8a6d2f;
        font-weight: 600;
    }
    
    .product-count {
        background: #c9a24b;
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .clear-filter {
        color: #c9a24b;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .clear-filter:hover {
        background: rgba(201, 162, 75, 0.12);
        transform: translateX(-3px);
    }
    
    /* Products Grid — fixed 4 per row */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-top: 20px;
    }
    
    /* Product Card */
    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
        position: relative;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border-color: #d4b25f;
    }
    
    .product-link {
        text-decoration: none;
        color: inherit;
        flex: 1;
        display: block;
    }
    
    .product-image {
        height: auto;
        aspect-ratio: 4 / 5;
        width: 100%;
        position: relative;
        overflow: hidden;
        background: #f4f4f5;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.04);
    }
    
    .no-image {
        aspect-ratio: 4 / 5;
        background: linear-gradient(135deg, #f5f5f5 0%, #e9e9e9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ccc;
        font-size: 48px;
    }
    
    .discount {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .product-details {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .name {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #333;
        line-height: 1.4;
        min-height: auto;
        overflow: visible;
        display: block;
        word-wrap: break-word;
        word-break: break-word;
    }
    
    .category {
        font-size: 13px;
        color: #666;
        margin: 0 0 12px 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .category i {
        font-size: 11px;
    }
    
    .price {
        margin: 12px 0;
        display: flex;
        align-items: baseline;
        gap: 8px;
    }
    
    .current {
        font-size: 20px;
        font-weight: bold;
        color: #111;
    }
    
    .original {
        font-size: 14px;
        color: #999;
        text-decoration: line-through;
    }
    
    .stock {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
        font-weight: 600;
        width: fit-content;
    }
    
    .in-stock {
        background: #d1fae5;
        color: #065f46;
    }
    
    .low-stock {
        background: #fef3c7;
        color: #92400e;
    }
    
    .out-of-stock {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Wishlist heart */
    .wishlist-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 6;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #9ca3af;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 1px 4px rgba(0,0,0,.12);
        transition: all .2s ease;
    }
    .wishlist-heart:hover { color: #ef4444; transform: scale(1.08); }
    .wishlist-heart.active { color: #ef4444; }

    .add-to-cart {
        background: linear-gradient(135deg, #c9a24b 0%, #a07f2a 100%);
        color: white;
        border: none;
        padding: 14px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .add-to-cart:hover:not(:disabled) {
        background: linear-gradient(135deg, #1d1912 0%, #3a3125 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 25, 18, 0.35);
    }
    
    .add-to-cart:active:not(:disabled) {
        transform: translateY(0);
    }
    
    .add-to-cart:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* Variant chips (size + color preview) */
    .product-variants { display: flex; flex-direction: column; gap: 4px; margin: 4px 0; }
    .variant-line { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
    .variant-label { font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: .4px; }
    .variant-size { font-size: 11px; font-weight: 600; color: #4b5563; border: 1px solid #e5e7eb; border-radius: 4px; padding: 1px 7px; background: #f9fafb; cursor: pointer; transition: all .15s ease; }
    .variant-size:hover { border-color: #c9a24b; }
    .variant-size.sel { border-color: #a07f2a; background: #faf6ec; color: #a07f2a; }
    .variant-dot { width: 15px; height: 15px; border-radius: 50%; border: 1.5px solid #e5e7eb; display: inline-block; cursor: pointer; padding: 0; transition: all .15s ease; }
    .variant-dot:hover { transform: scale(1.15); }
    .variant-dot.sel { border-color: #a07f2a; box-shadow: 0 0 0 2px #fff inset, 0 0 0 2.5px #a07f2a; }

    /* Card action buttons */
    .card-actions { display: flex; gap: 8px; margin-top: auto; }
    .card-actions .add-to-cart, .card-actions .order-now {
        flex: 1; margin-top: 0; padding: 12px 8px; font-size: 12px;
    }
    .order-now {
        background: #1d1912;
        color: white;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .order-now:hover:not(:disabled) {
        background: linear-gradient(135deg, #c9a24b 0%, #a07f2a 100%);
        box-shadow: 0 4px 12px rgba(201, 162, 75, 0.35);
    }
    .order-now:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    
    /* No Products */
    .no-products {
        text-align: center;
        padding: 60px 20px;
        color: #666;
        background: #f9fafb;
        border-radius: 12px;
        margin-top: 20px;
    }
    
    .no-products i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #d1d5db;
    }
    
    .no-products h3 {
        margin-bottom: 10px;
        color: #374151;
        font-size: 24px;
    }
    
    .no-products p {
        margin-bottom: 20px;
        font-size: 16px;
    }
    
    .no-products .btn {
        background: linear-gradient(135deg, #c9a24b 0%, #a07f2a 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .no-products .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Responsive Banner */
    @media (max-width: 768px) {
        .products-banner {
            padding: 30px 20px;
            margin-bottom: 30px;
        }
        
        .banner-content {
            flex-direction: column;
            text-align: center;
            gap: 30px;
        }
        
        .banner-text h2 {
            font-size: 28px;
        }
        
        .banner-text p {
            font-size: 16px;
            margin: 0 auto 20px;
        }
        
        .banner-stats {
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .stat {
            padding: 12px 16px;
        }
        
        .banner-image {
            flex: 0 0 auto;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
    }
    
    @media (max-width: 480px) {
        .products-banner {
            padding: 25px 15px;
        }
        
        .banner-text h2 {
            font-size: 24px;
        }
        
        .banner-stats {
            gap: 10px;
        }
        
        .stat .number {
            font-size: 24px;
        }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .products-page h1 {
            font-size: 28px;
        }
        
        .go-back-btn {
            font-size: 13px;
            padding: 8px 16px;
        }
        
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .category-filter {
            padding: 20px 15px;
        }
        
        .filter-header h3 {
            font-size: 18px;
        }
        
        .filter-options {
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 10px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #d4b25f #f0f0f0;
        }
        
        .filter-options::-webkit-scrollbar {
            height: 6px;
        }
        
        .filter-options::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 10px;
        }
        
        .filter-options::-webkit-scrollbar-thumb {
            background: #d4b25f;
            border-radius: 10px;
        }
        
        .filter-option {
            white-space: nowrap;
            flex-shrink: 0;
            font-size: 13px;
            padding: 8px 14px;
        }
        
        .filter-option i {
            font-size: 11px;
        }
        
        .filter-option .count {
            font-size: 11px;
            padding: 2px 8px;
        }
        
        .current-category {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 15px 20px;
        }
        
        .category-info {
            flex-direction: column;
            gap: 8px;
        }
    }
    
    @media (max-width: 480px) {
        .category-filter {
            padding: 15px 12px;
            margin-bottom: 20px;
        }
        
        .filter-header h3 {
            font-size: 16px;
        }
        
        .filter-option {
            font-size: 12px;
            padding: 7px 12px;
        }
        
        .filter-option .count {
            font-size: 10px;
            padding: 2px 6px;
        }
        
        .current-category h2 {
            font-size: 16px;
        }
        
        .product-count {
            font-size: 11px;
            padding: 3px 10px;
        }
        
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .product-details {
            padding: 12px;
        }
        
        .name {
            font-size: 14px;
            min-height: auto;
        }
        
        .current {
            font-size: 18px;
        }
        
        .add-to-cart {
            padding: 12px;
            font-size: 13px;
        }

        .card-actions { flex-direction: column; gap: 6px; }
    }

    /* Tablet: 3 per row */
    @media (min-width: 769px) and (max-width: 1023px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to products when category is filtered
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    
    if (categoryParam) {
        // Small delay to ensure page is fully loaded
        setTimeout(() => {
            const productsSection = document.getElementById('productsSection');
            if (productsSection) {
                productsSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 100);
    }
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    // Card-level size/color chip selection
    document.querySelectorAll('.card-chip').forEach(function (chip) {
        chip.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var wasSel = this.classList.contains('sel');
            this.closest('.variant-line').querySelectorAll('.card-chip').forEach(function (c) { c.classList.remove('sel'); });
            if (!wasSel) this.classList.add('sel');
        });
    });

    function cardSelection(btn) {
        var card = btn.closest('.product-card');
        var wrap = card.querySelector('.product-variants');
        var sizeSel = card.querySelector('.card-chip[data-type="size"].sel');
        var colorSel = card.querySelector('.card-chip[data-type="color"].sel');
        return {
            hasSizes: !!(wrap && wrap.dataset.hasSizes === '1'),
            hasColors: !!(wrap && wrap.dataset.hasColors === '1'),
            sizeId: sizeSel ? parseInt(sizeSel.dataset.id) : null,
            colorId: colorSel ? parseInt(colorSel.dataset.id) : null
        };
    }

    function cardAction(btn, checkout) {
        var pid = btn.getAttribute('data-product-id');
        var sel = cardSelection(btn);
        if ((sel.hasSizes && !sel.sizeId) || (sel.hasColors && !sel.colorId)) {
            libasHandleAdd(pid, btn, checkout, { sizeId: sel.sizeId, colorId: sel.colorId });
        } else {
            libasCartAdd(pid, {
                quantity: 1,
                size_id: sel.sizeId,
                color_id: sel.colorId,
                redirect_to_checkout: checkout ? 1 : undefined
            }, btn);
        }
    }

    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            cardAction(this, false);
        });
    });

    // Order Now → same variant flow, then redirect to checkout
    document.querySelectorAll('.order-now').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            cardAction(this, true);
        });
    });

    // Wishlist heart — real add/remove via /wishlist routes
    document.querySelectorAll('.wishlist-heart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            libasWishlistToggle(this.getAttribute('data-product-id'), this);
        });
    });
    
    // Function to update cart count
    function updateCartCount(count) {
        let cartBadge = document.querySelector('.cart-count-badge');
        const cartLink = document.querySelector('.cart-link');
        
        if (count > 0) {
            if (!cartBadge) {
                cartBadge = document.createElement('span');
                cartBadge.className = 'cart-count-badge';
                cartLink.appendChild(cartBadge);
            }
            cartBadge.textContent = count;
        } else if (cartBadge) {
            cartBadge.remove();
        }
    }

    function showToast(message, type = 'info') {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);

            const style = document.createElement('style');
            style.textContent = `
                .toast-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                }
                .toast {
                    background: white;
                    padding: 12px 20px;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    animation: slideIn 0.3s ease;
                }
                .toast.success { border-left: 4px solid #10b981; }
                .toast.error { border-left: 4px solid #ef4444; }
                .toast.info { border-left: 4px solid #c9a24b; }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';

        toast.innerHTML = `<i class="fas fa-${icon}"></i><span>${message}</span>`;
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>
@endsection