@extends('layouts.app')

@section('title', $product->meta_title ?: ($product->name . ' - libasbd'))

@section('description', $product->meta_description ?: ($product->name . ' - ' . ($product->description ? Str::limit(strip_tags($product->description), 150) : 'Buy quality products from Sultan Enterprise') . ' Shop online in Bangladesh'))

@php
    $categoryKeywords = '';
    $categoryName = strtolower($product->categoryRelation ? $product->categoryRelation->name : '');

    if (strpos($categoryName, 'burkha') !== false || strpos($categoryName, 'abaya') !== false) {
        $categoryKeywords = 'Premium Burkha, Luxury Burkha, Buy Burkha Online, Nida Burkha, Dubai Burkha, Premium Burkha Bangladesh, Stylish Burkha for Women, Luxury Burkha, Dubai Style Burkha, Elegant Burkha, Saudi Burkha style in bd, Black Burkha, Burkha price in bd, Stylish Abaya, Libas Burkha Collection, Burkha Shop Bangladesh, Islamic murkha, Nida Burkha, Comfortable Burkha';
    } elseif (strpos($categoryName, '3 piece') !== false || strpos($categoryName, 'three piece') !== false || strpos($categoryName, 'dress') !== false) {
        $categoryKeywords = 'Premium 3 Piece, Pakistani 3 Piece, Cotton 3 Piece, Designer 3 Piece, Premium 3 Piece prise in bd, 3 Piece Online bd, Pakistani 3 Piece, Luxury 3 Piece, Cotton 3 Piece, Lawn 3 Piece, Embroidered 3 Piece, Women\'s 3 Piece Collection, Party Wear 3 Piece, Casual 3 Piece, Trendy 3 Piece, Exclusive 3 Piece, Libas 3 piece collection, Women\'s Dress Bangladesh';
    } elseif (strpos($categoryName, 'cosmetic') !== false || strpos($categoryName, 'beauty') !== false || strpos($categoryName, 'makeup') !== false || strpos($categoryName, 'skin') !== false) {
        $categoryKeywords = 'Original Cosmetics, Korean Skincare, Makeup Products, Beauty Products, Original Cosmetics Bangladesh, Authentic Cosmetics BD, Original Makeup Bangladesh, Beauty Products Bangladesh, Libas Cosmetics, Buy Cosmetics Online Bangladesh, Skin Care Bangladesh, Trusted Cosmetics Store, Genuine Cosmetics, Korean Skincare Bangladesh';
    } else {
        $categoryKeywords = 'Premium Burkha Bangladesh, Buy Burkha Online, Premium Abaya, Luxury Burkha, Premium 3 Piece Bangladesh, Pakistani 3 Piece, Original Cosmetics Bangladesh, Authentic Cosmetics, Women\'s Fashion Bangladesh, Online Fashion Store Bangladesh, Modest Fashion Bangladesh, Luxury Women\'s Fashion, Libas Women\'s Product';
    }
@endphp

@section('keywords', $product->meta_keywords ?: ($product->name . ',' . $categoryKeywords . ',buy online bangladesh,cash on delivery,প্রিমিয়াম কালেকশন,online shopping,Bangladesh,LibasBD'))
@section('og_type', 'product')
@section('og_image', $product->image_url)

@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'image' => [$product->image_url],
    'description' => \Illuminate\Support\Str::limit(strip_tags($product->description ?? $product->name), 300),
    'brand' => ['@type' => 'Brand', 'name' => $product->brand ?: 'LibasBD'],
    'category' => $product->category_name,
    'sku' => (string) $product->id,
    'url' => url()->current(),
    'offers' => [
        '@type' => 'Offer',
        'url' => url()->current(),
        'priceCurrency' => 'BDT',
        'price' => $product->price,
        'availability' => ($product->stock ?? 0) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        'itemCondition' => 'https://schema.org/NewCondition',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => url('/products')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

<div class="pd-page">
    <div class="pd-container">

        {{-- ── Breadcrumbs ── --}}
        <nav class="pd-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ url('/products') }}">Products</a>
            <i class="fas fa-chevron-right"></i>
            <span>{{ $product->name }}</span>
        </nav>

        {{-- ── Main Layout ── --}}
        <div class="pd-layout">

            {{-- ── Gallery ── --}}
            <div class="pd-gallery">
                <div class="pd-image-frame">
                   @if($product->image)
    <img src="{{ $product->image_url }}"
         alt="{{ $product->name }}"
         id="mainProductImage">
                    @else
                        <div class="pd-no-image">
                            <i class="fas fa-image"></i>
                            <span>No image available</span>
                        </div>
                    @endif

                    @if($product->youtube_embed_url)
                        <div class="pd-video-embed" id="pdVideoEmbed" style="display:none;">
                            <iframe id="pdVideoIframe" data-src="{{ $product->youtube_embed_url }}?autoplay=1&rel=0"
                                    title="{{ $product->name }} — video" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @endif

                    {{-- Discount ribbon --}}
                    @if($product->original_price && $product->original_price > $product->price)
                        <div class="pd-ribbon">-{{ $product->discount }}%</div>
                    @endif
                </div>

                @if(count($product->gallery_urls) > 0 || $product->youtube_embed_url)
                <div class="pd-thumbs">
                    @if($product->image)
                    <button type="button" class="pd-thumb is-active" data-type="image" data-src="{{ $product->image_url }}" aria-label="Main image">
                        <img src="{{ $product->image_url }}" alt="" loading="lazy">
                    </button>
                    @endif
                    @foreach($product->gallery_urls as $galleryUrl)
                    <button type="button" class="pd-thumb" data-type="image" data-src="{{ $galleryUrl }}" aria-label="Gallery image">
                        <img src="{{ $galleryUrl }}" alt="" loading="lazy">
                    </button>
                    @endforeach
                    @if($product->youtube_embed_url)
                    <button type="button" class="pd-thumb pd-thumb--video" data-type="video" aria-label="Play video">
                        <i class="fas fa-play"></i>
                    </button>
                    @endif
                </div>
                @endif
            </div>

            {{-- ── Product Info ── --}}
            <div class="pd-info" style="display: flex !important; flex-direction: column !important; gap: 20px !important;">

                {{-- Brand --}}
                @if($product->brand)
                    <p class="pd-brand">{{ $product->brand }}</p>
                @endif

                {{-- Title --}}
                <h1 class="pd-title">{{ $product->name }}</h1>

                {{-- Pricing --}}
                <div class="pd-pricing">
                    <span class="pd-price-current" id="currentPrice">৳{{ number_format($product->price, 2) }}</span>
                    @if($product->original_price && $product->original_price > $product->price)
                        <div class="pd-price-meta">
                            <span class="pd-price-original">৳{{ number_format($product->original_price, 2) }}</span>
                            <span class="pd-savings">You save ৳{{ number_format($product->original_price - $product->price, 2) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Divider --}}
                <div class="pd-divider"></div>

                <form id="addToCartForm" method="POST" action="{{ route('cart.add', $product->id) }}">
                    @csrf

                {{-- ── Size Selection ── --}}
                @if($product->sizes && $product->sizes->count() > 0)
                <div class="pd-option">
                    <div class="pd-option__header">
                        <label class="pd-option__label">Size <span class="pd-required">*</span></label>
                        <span class="pd-option__selected-label" id="selectedSizeName">— not selected —</span>
                    </div>
                    <div class="pd-size-grid">
                        @foreach($product->sizes as $size)
                            <button type="button"
                                    class="pd-size-btn"
                                    data-size-id="{{ $size->id }}"
                                    data-size-name="{{ $size->name }}"
                                    data-price-adj="{{ $size->pivot->price_adjustment ?? 0 }}">
                                {{ $size->name }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="selected_size" id="selected_size" value="">
                    <p class="pd-error" id="size-error" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> Please select a size
                    </p>
                </div>
                @endif

                {{-- ── Color Selection ── --}}
                @if($product->colors && $product->colors->count() > 0)
                <div class="pd-option">
                    <div class="pd-option__header">
                        <label class="pd-option__label">Color <span class="pd-required">*</span></label>
                        <span class="pd-option__selected-label" id="selectedColorName">— not selected —</span>
                    </div>
                    <div class="pd-color-grid">
                        @foreach($product->colors as $color)
                            <button type="button"
                                    class="pd-color-btn"
                                    title="{{ $color->name }}"
                                    data-color-id="{{ $color->id }}"
                                    data-color-name="{{ $color->name }}"
                                    data-color-hex="{{ $color->hex_code }}"
                                    data-price-adj="{{ $color->pivot->price_adjustment ?? 0 }}"
                                    style="--swatch: {{ $color->hex_code }};">
                                <span class="pd-color-btn__check"><i class="fas fa-check"></i></span>
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="selected_color" id="selected_color" value="">
                    <p class="pd-error" id="color-error" style="display:none;">
                        <i class="fas fa-exclamation-circle"></i> Please select a color
                    </p>
                </div>
                @endif

                {{-- ── Quantity + Stock ── --}}
                <div class="pd-option pd-qty-row">
                    <div>
                        <label class="pd-option__label">Quantity</label>
                        <div class="pd-qty">
                            <button type="button" class="pd-qty__btn" id="qtyMinus"><i class="fas fa-minus"></i></button>
                            <input type="number" id="quantity" name="quantity" class="pd-qty__input" value="1" min="1" max="{{ $product->stock ?? 999 }}">
                            <button type="button" class="pd-qty__btn" id="qtyPlus"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="pd-stock-status">
                        @if(($product->stock && $product->stock > 0) || ($product->sizes && $product->sizes->count() > 0) || ($product->colors && $product->colors->count() > 0))
                            <span class="pd-stock pd-stock--in"><i class="fas fa-check-circle"></i> In Stock</span>
                        @else
                            <span class="pd-stock pd-stock--out"><i class="fas fa-times-circle"></i> Out of Stock</span>
                        @endif
                    </div>
                </div>

                {{-- ── Add to Cart + Order Now ── --}}
                <div class="pd-actions">
                    <button type="submit" id="addToCartBtn" class="pd-cart-btn">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Add to Cart</span>
                    </button>
                    <button type="button" id="buyNowBtn" class="pd-buy-btn">
                        <i class="fas fa-bolt"></i>
                        <span>Order Now</span>
                    </button>
                </div>

                </form>

                {{-- ── Description ── --}}
                <div class="pd-desc" style="margin-top: 20px !important; border: 1px solid #e0e0e0 !important; border-radius: 8px !important; overflow: hidden !important;">
                    <button class="pd-desc__toggle" id="descToggle" type="button" style="width: 100% !important; padding: 15px !important; background: #f5f5f5 !important; border: none !important; cursor: pointer !important; display: flex !important; justify-content: space-between !important; align-items: center !important; font-weight: bold !important; color: #333 !important;">
                        <span>Product Description</span>
                        <i class="fas fa-chevron-down pd-desc__toggle-icon"></i>
                    </button>
                    <div class="pd-desc__body" id="descBody" style="padding: 15px !important; display: block !important; max-height: none !important; overflow: visible !important;">
                        <div class="pd-desc__content" style="color: #333 !important; line-height: 1.6 !important;">
                            @if($product->description)
                                {!! nl2br(e($product->description)) !!}
                            @else
                                <p>No description available</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>{{-- /pd-info --}}
        </div>{{-- /pd-layout --}}

{{-- Reviews Section --}}
        <div class="pd-reviews" style="margin: 34px 0;">
            <h2 class="pd-related__title">Customer Reviews
                @if($product->review_count > 0)
                    <span style="font-size:14px;font-weight:500;color:#a07f2a;">
                        — {{ number_format($product->rating, 1) }}★ ({{ $product->review_count }})
                    </span>
                @endif
            </h2>

            @if(session('success'))<div class="alert-success" style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:14px;">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert-error" style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:8px;margin-bottom:14px;">{{ session('error') }}</div>@endif

            <div class="pd-reviews__grid" style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                {{-- Review list --}}
                <div>
                    @forelse($product->reviews->take(6) as $review)
                        <div style="background:#fff;border:1px solid #eee;border-radius:10px;padding:14px 16px;margin-bottom:12px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                <b>{{ $review->customer_name ?? ($review->user->name ?? 'Customer') }}</b>
                                <span style="color:#f59e0b;">@for($i=1;$i<=5;$i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor</span>
                            </div>
                            <p style="font-size:14px;color:#4b5563;margin:0;">{{ $review->review }}</p>
                        </div>
                    @empty
                        <p style="color:#9ca3af;">এখনো কোনো রিভিউ নেই — প্রথম রিভিউটি দিন!</p>
                    @endforelse
                </div>

                {{-- Submit form --}}
                <div style="background:#faf8f2;border:1px solid #efe8d8;border-radius:12px;padding:20px;">
                    <h3 style="font-size:16px;margin:0 0 12px;">রিভিউ লিখুন</h3>
                    <form method="POST" action="{{ route('products.review.store', $product->id) }}">
                        @csrf
                        @guest
                            <input type="text" name="customer_name" placeholder="আপনার নাম" required
                                   style="width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;">
                            <input type="email" name="customer_email" placeholder="Email (ঐচ্ছিক)"
                                   style="width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;">
                        @endguest
                        <div style="margin-bottom:10px;">
                            <label style="font-size:13px;color:#6b7280;display:block;margin-bottom:6px;">রেটিং</label>
                            <div class="star-input" style="display:flex;gap:4px;direction:rtl;">
                                @for($i=5;$i>=1;$i--)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" required style="display:none;">
                                    <label for="star{{ $i }}" style="font-size:24px;cursor:pointer;color:#d1d5db;" class="star-label">★</label>
                                @endfor
                            </div>
                        </div>
                        <textarea name="review" rows="4" placeholder="প্রোডাক্ট সম্পর্কে আপনার মতামত লিখুন..." required minlength="10"
                                  style="width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:12px;"></textarea>
                        <button type="submit" style="width:100%;background:linear-gradient(135deg,#d4b25f,#9c7c33);color:#fff;border:none;padding:12px;border-radius:8px;font-weight:700;cursor:pointer;">
                            রিভিউ জমা দিন
                        </button>
                        <p style="font-size:12px;color:#9ca3af;margin:8px 0 0;">রিভিউ admin অনুমোদনের পর দেখাবে।</p>
                    </form>
                </div>
            </div>
        </div>
        <style>
            .star-input input:checked ~ label, .star-input label:hover, .star-input label:hover ~ label { color: #f59e0b !important; }
            @media (max-width: 768px) { .pd-reviews__grid { grid-template-columns: 1fr !important; } }
        </style>

{{-- Related Products --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="pd-related">
                <h2 class="pd-related__title">Related Products</h2>
                <div class="pd-related__grid">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="product-card">
                            <a href="{{ route('products.show', $relatedProduct) }}" class="product-link">
                                <div class="product-image">
                                    @if($relatedProduct->image)
                                        <img src="{{ $relatedProduct->image_url }}" alt="{{ $relatedProduct->name }}" loading="lazy">
                                    @else
                                        <div class="no-image"><i class="fas fa-box"></i></div>
                                    @endif
                                    @if($relatedProduct->discount)
                                        <div class="discount"><i class="fas fa-tag"></i> {{ $relatedProduct->discount }}% OFF</div>
                                    @endif
                                </div>
                                <div class="product-details">
                                    <h3 class="name">{{ $relatedProduct->name }}</h3>
                                    @if($relatedProduct->categoryRelation)
                                        <p class="category"><i class="fas fa-folder"></i> {{ $relatedProduct->categoryRelation->name }}</p>
                                    @endif
                                    <div class="price">
                                        <span class="current">৳{{ number_format($relatedProduct->price, 2) }}</span>
                                        @if($relatedProduct->original_price && $relatedProduct->original_price > $relatedProduct->price)
                                            <span class="original">৳{{ number_format($relatedProduct->original_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="stock {{ $relatedProduct->stock > 5 ? 'in-stock' : ($relatedProduct->stock > 0 ? 'low-stock' : 'out-of-stock') }}">
                                        @if($relatedProduct->stock > 5)
                                            <i class="fas fa-check-circle"></i> In Stock
                                        @elseif($relatedProduct->stock > 0)
                                            <i class="fas fa-exclamation-circle"></i> Only {{ $relatedProduct->stock }} left
                                        @else
                                            <i class="fas fa-times-circle"></i> Out of Stock
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <button type="button" class="wishlist-heart" data-product-id="{{ $relatedProduct->id }}" aria-label="Add {{ $relatedProduct->name }} to wishlist"
                                    onclick="event.preventDefault(); event.stopPropagation(); libasWishlistToggle(this.dataset.productId, this);">
                                <i class="far fa-heart" aria-hidden="true"></i>
                            </button>
                            <div class="card-actions">
                                <button type="button" class="add-to-cart"
                                        data-product-id="{{ $relatedProduct->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation(); libasHandleAdd(this.dataset.productId, this, false);">
                                    <i class="fas fa-shopping-bag"></i> Add to Cart
                                </button>
                                <button type="button" class="buy-now"
                                        data-product-id="{{ $relatedProduct->id }}"
                                        onclick="event.preventDefault(); event.stopPropagation(); libasHandleAdd(this.dataset.productId, this, true);">
                                    <i class="fas fa-bolt"></i> Order Now
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>{{-- /pd-container --}}
</div>{{-- /pd-page --}}

<style>
/* ═══════════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════════ */
:root {
    --pd-bg:          #f2f3f7;
    --pd-surface:     #ffffff;
    --pd-border:      #e8eaf2;

    --pd-accent:      #e63950;
    --pd-accent-dk:   #c62840;
    --pd-accent-lt:   #fff0f2;

    --pd-text-h:      #15172b;
    --pd-text-b:      #4a4f6a;
    --pd-text-m:      #8c93b0;

    --pd-green:       #22c87a;
    --pd-green-lt:    #e8faf2;

    --pd-radius:      16px;
    --pd-radius-sm:   10px;
    --pd-radius-xs:   7px;

    --pd-shadow:      0 2px 12px rgba(0,0,0,.07);
    --pd-shadow-lg:   0 8px 32px rgba(0,0,0,.12);

    --pd-ease:        cubic-bezier(.25,.8,.25,1);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

/* ═══════════════════════════════════════════════
   PAGE SHELL
═══════════════════════════════════════════════ */
.pd-page {
    background: var(--pd-bg);
    min-height: calc(100vh - 160px);
    padding: 40px 0 72px;
}
.pd-container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ═══════════════════════════════════════════════
   BREADCRUMB
═══════════════════════════════════════════════ */
.pd-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--pd-text-m);
    margin-bottom: 28px;
    flex-wrap: wrap;
}
.pd-breadcrumb a { color: var(--pd-text-m); text-decoration: none; transition: color .15s; }
.pd-breadcrumb a:hover { color: var(--pd-accent); }
.pd-breadcrumb i { font-size: 9px; opacity: .6; }
.pd-breadcrumb span { color: var(--pd-text-b); font-weight: 500; }

/* ═══════════════════════════════════════════════
   LAYOUT GRID
═══════════════════════════════════════════════ */
.pd-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
}
@media (max-width: 820px) {
    .pd-layout { grid-template-columns: 1fr; gap: 28px; }
}

/* ═══════════════════════════════════════════════
   GALLERY
═══════════════════════════════════════════════ */
.pd-gallery { position: sticky; top: 24px; align-self: start; }

.pd-image-frame {
    position: relative;
    background: var(--pd-surface);
    border-radius: var(--pd-radius);
    border: 1px solid var(--pd-border);
    box-shadow: var(--pd-shadow);
    overflow: hidden;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.pd-image-frame img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 24px;
    box-sizing: border-box;
    transition: transform .4s var(--pd-ease);
}
.pd-image-frame:hover img { transform: scale(1.04); }

/* ── Gallery thumbnails + video ── */
.pd-thumbs {
    display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;
}
.pd-thumb {
    width: 68px; height: 68px; border-radius: 10px; padding: 0;
    border: 2px solid var(--pd-border); background: #fff; overflow: hidden;
    cursor: pointer; transition: all .2s ease; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.pd-thumb:hover { border-color: var(--pd-accent); }
.pd-thumb.is-active { border-color: var(--pd-accent); box-shadow: 0 0 0 2px rgba(230,57,80,.15); }
.pd-thumb--video { background: #15172b; color: #fff; font-size: 18px; }
.pd-thumb--video:hover { background: var(--pd-accent); border-color: var(--pd-accent); }

.pd-video-embed {
    position: absolute; inset: 0; z-index: 3; background: #000;
}
.pd-video-embed iframe { width: 100%; height: 100%; border: 0; display: block; }

.pd-ribbon {
    position: absolute;
    top: 20px;
    left: -2px;
    background: var(--pd-accent);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 5px 14px 5px 12px;
    border-radius: 0 6px 6px 0;
    letter-spacing: .4px;
    box-shadow: 2px 2px 8px rgba(230,57,80,.35);
}

.pd-no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    color: var(--pd-text-m);
}
.pd-no-image i { font-size: 56px; opacity: .3; }
.pd-no-image span { font-size: 14px; }

/* ═══════════════════════════════════════════════
   PRODUCT INFO
═══════════════════════════════════════════════ */
.pd-info {
    background: var(--pd-surface);
    border-radius: var(--pd-radius);
    border: 1px solid var(--pd-border);
    box-shadow: var(--pd-shadow);
    padding: 36px;
}
@media (max-width: 480px) { .pd-info { padding: 24px; } }

.pd-brand {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: var(--pd-accent);
    margin: 0 0 10px;
}

.pd-title {
    font-size: 26px;
    font-weight: 700;
    color: var(--pd-text-h);
    margin: 0 0 22px;
    line-height: 1.3;
}

/* Pricing */
.pd-pricing { margin-bottom: 24px; }
.pd-price-current {
    display: block;
    font-size: 36px;
    font-weight: 800;
    color: var(--pd-accent);
    letter-spacing: -.5px;
    line-height: 1.1;
    transition: all .2s ease;
}
.pd-price-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
}
.pd-price-original {
    font-size: 16px;
    color: var(--pd-text-m);
    text-decoration: line-through;
}
.pd-savings {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--pd-green);
    background: var(--pd-green-lt);
    padding: 3px 10px;
    border-radius: 20px;
}

.pd-divider { height: 1px; background: var(--pd-border); margin: 24px 0; }

/* ── Options ── */
.pd-option { margin-bottom: 24px; }
.pd-option__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.pd-option__label {
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--pd-text-b);
}
.pd-option__selected-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--pd-text-m);
    transition: color .15s;
}
.pd-required { color: var(--pd-accent); }

/* Sizes */
.pd-size-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.pd-size-btn {
    position: relative;
    padding: 12px 24px;
    background: var(--pd-surface);
    border: 2px solid var(--pd-border);
    border-radius: var(--pd-radius-sm);
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    color: var(--pd-text-b);
    cursor: pointer;
    transition: all .2s var(--pd-ease);
    min-width: 60px;
    text-align: center;
}
.pd-size-btn:hover:not(:disabled) {
    border-color: var(--pd-accent);
    color: var(--pd-accent);
    background: var(--pd-accent-lt);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(230,57,80,.15);
}
.pd-size-btn.active {
    background: var(--pd-accent);
    border-color: var(--pd-accent);
    color: #fff;
    box-shadow: 0 4px 16px rgba(230,57,80,.35);
    transform: translateY(-2px);
}
.pd-size-btn--disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: var(--pd-bg);
    border-color: var(--pd-border);
    color: var(--pd-text-m);
}
.pd-size-btn--disabled:hover {
    transform: none;
    box-shadow: none;
}
.pd-size-btn__stock-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--pd-text-m);
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Colors */
.pd-color-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}
.pd-color-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: var(--swatch);
    border: 3px solid transparent;
    cursor: pointer;
    position: relative;
    transition: transform .25s var(--pd-ease), box-shadow .25s var(--pd-ease), border-color .25s;
    box-shadow: 0 3px 8px rgba(0,0,0,.15);
}
.pd-color-btn:hover:not(:disabled) {
    transform: scale(1.15);
    box-shadow: 0 5px 16px rgba(0,0,0,.25);
}
.pd-color-btn.active {
    border-color: var(--pd-accent);
    box-shadow: 0 0 0 4px white, 0 0 0 6px var(--pd-accent);
    transform: scale(1.1);
}
.pd-color-btn--disabled {
    opacity: 0.35;
    cursor: not-allowed;
    filter: grayscale(0.8);
}
.pd-color-btn--disabled:hover {
    transform: none;
    box-shadow: 0 3px 8px rgba(0,0,0,.15);
}
.pd-color-btn__check {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    opacity: 0;
    transition: opacity .2s;
    text-shadow: 0 1px 4px rgba(0,0,0,.5);
}
.pd-color-btn.active .pd-color-btn__check { opacity: 1; }
.pd-color-btn__stock-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: var(--pd-text-m);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Error */
.pd-error {
    font-size: 12.5px;
    color: var(--pd-accent);
    font-weight: 600;
    margin: 8px 0 0;
    display: flex;
    align-items: center;
    gap: 5px;
    animation: pd-shake .35s ease;
}

/* Qty + Stock row */
.pd-qty-row {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}
.pd-qty {
    display: flex;
    align-items: center;
    gap: 0;
    margin-top: 10px;
}
.pd-qty__btn {
    width: 40px;
    height: 44px;
    background: var(--pd-bg);
    border: 1.5px solid var(--pd-border);
    color: var(--pd-text-b);
    font-size: 14px;
    cursor: pointer;
    transition: all .15s;
}
.pd-qty__btn:first-child { border-radius: var(--pd-radius-sm) 0 0 var(--pd-radius-sm); border-right: none; }
.pd-qty__btn:last-child  { border-radius: 0 var(--pd-radius-sm) var(--pd-radius-sm) 0; border-left: none; }
.pd-qty__btn:hover { background: var(--pd-accent); color: #fff; border-color: var(--pd-accent); }
.pd-qty__input {
    width: 64px;
    height: 44px;
    border: 1.5px solid var(--pd-border);
    background: #fff;
    text-align: center;
    font-size: 16px;
    font-weight: 700;
    color: var(--pd-text-h);
    font-family: inherit;
    outline: none;
    box-sizing: border-box;
}
.pd-qty__input:focus { border-color: var(--pd-accent); box-shadow: 0 0 0 3px rgba(230,57,80,.1); }

.pd-stock-status { display: flex; align-items: flex-end; padding-bottom: 2px; }
.pd-stock { font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 6px; }
.pd-stock--in  { color: var(--pd-green); }
.pd-stock--out { color: var(--pd-accent); }

/* Add to Cart button */
.pd-cart-btn {
    width: 100%;
    padding: 16px;
    background: var(--pd-accent);
    color: #fff;
    border: none;
    border-radius: var(--pd-radius-sm);
    font-family: inherit;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: .3px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background .18s, transform .18s var(--pd-ease), box-shadow .18s;
    box-shadow: 0 4px 14px rgba(230,57,80,.35);
    margin-top: 28px;
}
.pd-cart-btn:hover {
    background: var(--pd-accent-dk);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(230,57,80,.4);
}
.pd-cart-btn:active { transform: translateY(0); }
.pd-cart-btn:disabled {
    background: #ccc;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
}

.pd-actions { display: flex; gap: 12px; margin-top: 28px; }
.pd-actions .pd-cart-btn { margin-top: 0; flex: 1; }
.pd-buy-btn {
    flex: 1;
    padding: 16px;
    background: linear-gradient(135deg, #d4b25f, #9c7c33);
    color: #fff;
    border: none;
    border-radius: var(--pd-radius-sm);
    font-family: inherit;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: .3px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background .18s, transform .18s var(--pd-ease), box-shadow .18s;
    box-shadow: 0 4px 14px rgba(156, 124, 51, .35);
}
.pd-buy-btn:hover {
    background: linear-gradient(135deg, #c9a24b, #8a6d2f);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(156, 124, 51, .4);
}
.pd-buy-btn:active { transform: translateY(0); }
.pd-buy-btn:disabled {
    background: #ccc;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
}
@media (max-width: 480px) {
    .pd-actions { flex-direction: column; }
}
}

/* Description accordion */
.pd-desc { margin-top: 28px; border-top: 1px solid var(--pd-border); padding-top: 20px; }
.pd-desc__toggle {
    width: 100%;
    background: none;
    border: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: inherit;
    font-size: 14px;
    font-weight: 700;
    color: var(--pd-text-h);
    cursor: pointer;
    padding: 0;
    text-align: left;
}
.pd-desc__toggle:hover { color: var(--pd-accent); }
.pd-desc__toggle-icon { transition: transform .25s var(--pd-ease); font-size: 12px; color: var(--pd-text-m); }
.pd-desc__toggle.open .pd-desc__toggle-icon { transform: rotate(180deg); }
.pd-desc__body {
    overflow: hidden;
    max-height: 0;
    transition: max-height .35s var(--pd-ease);
}
.pd-desc__body.open { max-height: 600px; }
.pd-desc__content {
    padding-top: 16px;
    font-size: 14.5px;
    line-height: 1.8;
    color: var(--pd-text-b);
}

/* ═══════════════════════════════════════════════
   TOAST
═══════════════════════════════════════════════ */
.pd-toast {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: var(--pd-radius-sm);
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    box-shadow: var(--pd-shadow-lg);
    animation: pd-toast-in .3s var(--pd-ease);
    max-width: 340px;
}
.pd-toast--success { background: var(--pd-green); }
.pd-toast--error   { background: var(--pd-accent); }
.pd-toast__icon { font-size: 18px; }

/* ═══════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════ */
@keyframes pd-toast-in {
    from { transform: translateX(110%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
@keyframes pd-toast-out {
    from { transform: translateX(0);    opacity: 1; }
    to   { transform: translateX(110%); opacity: 0; }
}
@keyframes pd-shake {
    0%, 100% { transform: translateX(0); }
    25%       { transform: translateX(-5px); }
    75%       { transform: translateX(5px); }
}
@keyframes pd-price-pop {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.08); color: var(--pd-accent); }
    100% { transform: scale(1); }
}
.pd-price-pop { animation: pd-price-pop .3s ease; }

/* ═══════════════════════════════════════════════
   RELATED PRODUCTS
═══════════════════════════════════════════════ */
.pd-related { margin-top: 56px; }
.pd-related__title {
    font-size: 22px; font-weight: 700; color: var(--pd-text-h);
    margin: 0 0 24px 0; position: relative; padding-left: 14px;
}
.pd-related__title::before {
    content: ''; position: absolute; left: 0; top: 50%;
    transform: translateY(-50%); width: 4px; height: 22px;
    background: var(--pd-accent); border-radius: 2px;
}
.pd-related__grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
}
@media (max-width: 1024px) { .pd-related__grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px)  { .pd-related__grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } }

/* Product Card (same style as products index) */
.pd-related__grid .product-card {
    background: white; border-radius: 12px; overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s ease;
    display: flex; flex-direction: column; border: 1px solid #f0f0f0;
    position: relative;
}
.pd-related__grid .product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: #c9a24b;
}
.pd-related__grid .product-link {
    text-decoration: none; color: inherit; flex: 1; display: block;
}
.pd-related__grid .product-image {
    height: auto; aspect-ratio: 4 / 5; width: 100%; position: relative; overflow: hidden; background: #f4f4f5;
}
.pd-related__grid .product-image img {
    width: 100%; height: 100%; object-fit: contain; transition: transform 0.4s ease;
}
.pd-related__grid .product-card:hover .product-image img { transform: scale(1.04); }
.pd-related__grid .no-image {
    aspect-ratio: 4 / 5; background: linear-gradient(135deg, #f5f5f5 0%, #e9e9e9 100%);
    display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 48px;
}
.pd-related__grid .wishlist-heart {
    position: absolute; top: 10px; right: 10px; z-index: 6;
    width: 34px; height: 34px; border-radius: 50%; border: none;
    background: #fff; color: #9ca3af; font-size: 15px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,.12); transition: all .2s ease;
}
.pd-related__grid .wishlist-heart:hover { color: #ef4444; transform: scale(1.08); }
.pd-related__grid .wishlist-heart.active { color: #ef4444; }
.pd-related__grid .discount {
    position: absolute; top: 12px; left: 12px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;
    padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: bold;
    box-shadow: 0 2px 8px rgba(239,68,68,0.4); display: flex; align-items: center; gap: 4px;
}
.pd-related__grid .product-details { padding: 16px; flex: 1; display: flex; flex-direction: column; }
.pd-related__grid .name {
    font-size: 15px; font-weight: 600; margin: 0 0 6px 0; color: #333; line-height: 1.4;
    min-height: auto; overflow: visible; display: block;
    word-wrap: break-word; word-break: break-word;
}
.pd-related__grid .category {
    font-size: 12px; color: #666; margin: 0 0 8px 0;
    display: flex; align-items: center; gap: 5px;
}
.pd-related__grid .category i { font-size: 11px; }
.pd-related__grid .price {
    margin: 8px 0; display: flex; align-items: baseline; gap: 8px;
}
.pd-related__grid .current { font-size: 18px; font-weight: bold; color: #111; }
.pd-related__grid .original { font-size: 13px; color: #999; text-decoration: line-through; }
.pd-related__grid .stock {
    font-size: 11px; padding: 5px 8px; border-radius: 6px;
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: 6px; font-weight: 600; width: fit-content;
}
.pd-related__grid .in-stock  { background: #d1fae5; color: #065f46; }
.pd-related__grid .low-stock  { background: #fef3c7; color: #7a6126; }
.pd-related__grid .out-of-stock { background: #fee2e2; color: #991b1b; }
.pd-related__grid .card-actions {
    display: flex; gap: 8px; padding: 0 16px 16px; margin-top: auto;
}
.pd-related__grid .add-to-cart,
.pd-related__grid .buy-now {
    flex: 1; border: none; padding: 10px 6px; font-size: 12px; font-weight: 600;
    cursor: pointer; border-radius: 8px; transition: all 0.3s ease;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.pd-related__grid .add-to-cart {
    background: linear-gradient(135deg, #c9a24b 0%, #a07f2a 100%); color: white;
}
.pd-related__grid .buy-now {
    background: linear-gradient(135deg, #1d1912 0%, #3a3125 100%); color: white;
}
.pd-related__grid .add-to-cart:hover,
.pd-related__grid .buy-now:hover {
    background: linear-gradient(135deg, #e63950 0%, #c62840 100%);
    transform: translateY(-2px); box-shadow: 0 4px 12px rgba(29,25,18,0.35);
}
@media (max-width: 640px) {
    .pd-related__grid .card-actions { flex-direction: column; gap: 6px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let selectedSizeId  = null;
    let selectedColorId = null;
    let basePrice       = {{ $product->price }};
    let sizePriceAdj    = 0;
    let colorPriceAdj   = 0;

    // ── Size selection ──
    document.querySelectorAll('.pd-size-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.pd-size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedSizeId = this.dataset.sizeId;
            sizePriceAdj   = parseFloat(this.dataset.priceAdj) || 0;
            document.getElementById('selected_size').value = selectedSizeId;
            document.getElementById('size-error').style.display = 'none';
            document.getElementById('selectedSizeName').textContent = this.dataset.sizeName;
            updatePrice();
        });
    });

    // ── Color selection ──
    document.querySelectorAll('.pd-color-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.pd-color-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedColorId = this.dataset.colorId;
            colorPriceAdj   = parseFloat(this.dataset.priceAdj) || 0;
            document.getElementById('selected_color').value = selectedColorId;
            document.getElementById('color-error').style.display = 'none';
            document.getElementById('selectedColorName').textContent = this.dataset.colorName;
            updatePrice();
        });
    });

    // ── Price update with animation ──
    function updatePrice() {
        let total = basePrice + sizePriceAdj + colorPriceAdj;
        const el = document.getElementById('currentPrice');
        el.textContent = '৳' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        el.classList.remove('pd-price-pop');
        void el.offsetWidth; // reflow
        el.classList.add('pd-price-pop');
    }

    // ── Quantity ──
    const qtyInput = document.getElementById('quantity');
    document.getElementById('qtyMinus').addEventListener('click', function () {
        let v = parseInt(qtyInput.value);
        if (v > 1) qtyInput.value = v - 1;
    });
    document.getElementById('qtyPlus').addEventListener('click', function () {
        let v   = parseInt(qtyInput.value);
        let max = parseInt(qtyInput.getAttribute('max'));
        if (v < max) qtyInput.value = v + 1;
    });

    // ── Description accordion ──
    const descToggle = document.getElementById('descToggle');
    const descBody   = document.getElementById('descBody');
    if (descToggle) {
        descToggle.addEventListener('click', function () {
            const isOpen = descBody.classList.toggle('open');
            descToggle.classList.toggle('open', isOpen);
        });
        // Open by default
        descBody.classList.add('open');
        descToggle.classList.add('open');
    }

    // ── Add to Cart ──
    const form = document.getElementById('addToCartForm');
    if (!form) return;

    let pendingBtn = null;
    let wantCheckout = false;

    const buyNowBtn = document.getElementById('buyNowBtn');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function () {
            pendingBtn    = buyNowBtn;
            wantCheckout  = true;
            form.requestSubmit();
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn      = pendingBtn || document.getElementById('addToCartBtn');
        const checkout = wantCheckout;
        pendingBtn     = null;
        wantCheckout   = false;
        let hasError = false;

        @if($product->sizes && $product->sizes->count() > 0)
        if (!selectedSizeId) {
            const err = document.getElementById('size-error');
            err.style.display = 'flex';
            err.style.animation = 'none';
            void err.offsetWidth;
            err.style.animation = '';
            hasError = true;
        }
        @endif

        @if($product->colors && $product->colors->count() > 0)
        if (!selectedColorId) {
            const err = document.getElementById('color-error');
            err.style.display = 'flex';
            err.style.animation = 'none';
            void err.offsetWidth;
            err.style.animation = '';
            hasError = true;
        }
        @endif

        if (hasError) return;

        const originalHTML = btn.innerHTML;
        btn.innerHTML      = checkout
            ? '<i class="fas fa-spinner fa-spin"></i><span>Ordering…</span>'
            : '<i class="fas fa-spinner fa-spin"></i><span>Adding…</span>';
        btn.disabled       = true;

        const formData = new FormData(form);
        if (selectedSizeId) {
            formData.set('size_id', selectedSizeId);
        }
        if (selectedColorId) {
            formData.set('color_id', selectedColorId);
        }
        if (checkout) {
            formData.set('redirect_to_checkout', '1');
        }

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':      document.querySelector('meta[name="csrf-token"]').content,
                'Accept':            'application/json',
                'X-Requested-With':  'XMLHttpRequest'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            if (data.success) {
                const badge = document.querySelector('.cart-count-badge');
                if (badge) {
                    badge.textContent = data.cartCount;
                } else if (data.cartCount > 0) {
                    const cartLink = document.querySelector('.cart-link');
                    if (cartLink) {
                        const b = document.createElement('span');
                        b.className   = 'cart-count-badge';
                        b.textContent = data.cartCount;
                        cartLink.appendChild(b);
                    }
                }
                showToast('Added to your cart!', 'success');
                btn.innerHTML = '<i class="fas fa-check"></i><span>Added!</span>';
                setTimeout(() => { btn.innerHTML = originalHTML; btn.disabled = false; }, 2000);

                // Track AddToCart event
                if (typeof fbq !== 'undefined' && data.pixelEvent) {
                    fbq('track', 'AddToCart', data.pixelEvent.data, {eventID: data.pixelEvent.event_id});
                }
            } else if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                showToast('Could not add to cart', 'error');
                btn.innerHTML = originalHTML;
                btn.disabled  = false;
            }
        })
        .catch(() => {
            showToast('Something went wrong', 'error');
            btn.innerHTML = originalHTML;
            btn.disabled  = false;
        });
    });

    @if(isset($pixelViewData))
    // Track ViewContent event
    if (typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent', @json($pixelViewData), {eventID: '{{ $pixelEventId ?? '' }}'});
    }
    @endif

    // ── Wishlist Button (Removed as requested) ──

    // ── Toast ──
    function showToast(message, type) {
        document.querySelectorAll('.pd-toast').forEach(t => t.remove());
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const toast = document.createElement('div');
        toast.className = 'pd-toast pd-toast--' + type;
        toast.innerHTML = '<i class="fas ' + icon + ' pd-toast__icon"></i><span>' + message + '</span>';
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'pd-toast-out .3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ── Gallery thumbnails + YouTube video ──
    const mainImage = document.getElementById('mainProductImage');
    const videoEmbed = document.getElementById('pdVideoEmbed');
    const videoIframe = document.getElementById('pdVideoIframe');

    document.querySelectorAll('.pd-thumb').forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('is-active'));
            this.classList.add('is-active');

            if (this.dataset.type === 'video' && videoEmbed && videoIframe) {
                if (!videoIframe.src) videoIframe.src = videoIframe.dataset.src;
                videoEmbed.style.display = 'block';
                if (mainImage) mainImage.style.visibility = 'hidden';
            } else {
                if (videoEmbed && videoIframe) {
                    videoEmbed.style.display = 'none';
                    videoIframe.src = '';
                }
                if (mainImage && this.dataset.src) {
                    mainImage.style.visibility = 'visible';
                    mainImage.src = this.dataset.src;
                }
            }
        });
    });
});
</script>

@endsection
