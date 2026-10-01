<main class="main-content">
<style>
        /* ============================================
           PROFESSIONAL E-COMMERCE PRODUCT SLIDER
           Fully Responsive | Modern Design | Inline CSS with !important
           SMALLER CARDS & CATEGORIES VERSION
           ============================================ */
        
        /* Container */
        .pc-container {
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 0 2px !important;
        }
        
        /* ============================================
           CATEGORY SECTION - CLEAN & MODERN (SMALLER)
           ============================================ */
        .category-section {
            margin-bottom: 16px !important;
            padding: 14px !important;
            background: white !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid #e5e7eb !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .category-section::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 3px !important;
            background: linear-gradient(90deg, #8a6d2f, #c9a24b, #e5c877) !important;
            z-index: 1 !important;
        }
        
        .category-section:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
            transform: translateY(-2px) !important;
        }
        
        /* Section Header */
        .section-header {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            margin-bottom: 16px !important;
            padding-bottom: 12px !important;
            border-bottom: 2px solid #f3f4f6 !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
        }
        
        .section-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
            margin: 0 !important;
            position: relative !important;
            padding-left: 14px !important;
            line-height: 1.2 !important;
        }
        
        .section-title::before {
            content: '' !important;
            position: absolute !important;
            left: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 4px !important;
            height: 22px !important;
            background: linear-gradient(180deg, #c9a24b, #a07f2a) !important;
            border-radius: 2px !important;
        }
        
        /* View All Button */
        .view-all-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 8px 16px !important;
            background: linear-gradient(135deg, #c9a24b, #a07f2a) !important;
            color: white !important;
            text-decoration: none !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 8px rgba(160, 127, 42, 0.25) !important;
            border: none !important;
        }
        
        .view-all-btn:hover {
            background: linear-gradient(135deg, #a07f2a, #8a6d2f) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(160, 127, 42, 0.35) !important;
        }
        
        .view-all-btn:active {
            transform: translateY(0) !important;
        }
        
        /* ============================================
           SLIDER WRAPPER & CONTROLS
           ============================================ */
        .products-slider-wrapper {
            position: relative !important;
            padding: 0 30px !important;
        }
        
        .products-slider {
            display: flex !important;
            gap: 14px !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            scroll-behavior: smooth !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
            padding: 10px 4px 16px 4px !important;
        }
        
        .products-slider::-webkit-scrollbar {
            display: none !important;
        }
        
        /* Slider Arrows */
        .slider-arrow {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 40px !important;
            height: 40px !important;
            background: white !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            color: #1f2937 !important;
            font-size: 14px !important;
        }
        
        .slider-arrow:hover {
            background: #a07f2a !important;
            color: white !important;
            border-color: #a07f2a !important;
            transform: translateY(-50%) scale(1.1) !important;
            box-shadow: 0 6px 16px rgba(160, 127, 42, 0.3) !important;
        }
        
        .slider-arrow:active {
            transform: translateY(-50%) scale(0.95) !important;
        }
        
        .slider-arrow.prev {
            left: 0 !important;
        }
        
        .slider-arrow.next {
            right: 0 !important;
        }
        
        /* ============================================
           PRODUCT CARD - SMALLER VERSION
           ============================================ */
        .product-card {
            width: 160px !important;
            min-width: 160px !important;
            max-width: 160px !important;
            background: white !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: flex !important;
            flex-direction: column !important;
            position: relative !important;
        }
        
        .product-card:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15) !important;
            border-color: #a07f2a !important;
        }
        
        .product-link {
            text-decoration: none !important;
            color: inherit !important;
            display: flex !important;
            flex-direction: column !important;
            flex-grow: 1 !important;
        }
        
        /* Product Image - SMALLER */
        .product-image-wrapper {
            width: 100% !important;
            height: 150px !important;
            position: relative !important;
            overflow: hidden !important;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%) !important;
            flex-shrink: 0 !important;
        }
        
        .product-image {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .product-image-wrapper::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%) !important;
            animation: shimmer 1.5s infinite !important;
            z-index: 1 !important;
        }

        .product-image-wrapper img {
            position: relative !important;
            z-index: 2 !important;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1) !important;
        }
        
        /* Discount Badge */
        .discount-badge {
            position: absolute !important;
            top: 10px !important;
            left: 10px !important;
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            padding: 5px 10px !important;
            border-radius: 16px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            z-index: 5 !important;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4) !important;
            letter-spacing: 0.5px !important;
        }
        
        /* Product Info */
        .product-info {
            padding: 12px !important;
            flex-grow: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 6px !important;
        }
        
        .product-name {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #1f2937 !important;
            line-height: 1.4 !important;
            min-height: auto !important;
            overflow: visible !important;
            display: block !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            margin: 0 !important;
        }
        
        /* Price Section */
        .price-section {
            display: flex !important;
            flex-direction: column !important;
            gap: 3px !important;
            margin-top: auto !important;
        }
        
        .current-price {
            font-size: 18px !important;
            font-weight: 700 !important;
            color: #8a6d2f !important;
            display: block !important;
            letter-spacing: -0.5px !important;
        }
        
        .original-price {
            font-size: 13px !important;
            color: #9ca3af !important;
            text-decoration: line-through !important;
            display: block !important;
        }
        
        /* Stock Status */
        .stock-status {
            font-size: 10px !important;
            font-weight: 600 !important;
            padding: 3px 7px !important;
            border-radius: 5px !important;
            display: inline-block !important;
            width: fit-content !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        
        .in-stock {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #059669 !important;
            border: 1px solid rgba(16, 185, 129, 0.2) !important;
        }
        
        .low-stock {
            background: rgba(245, 158, 11, 0.1) !important;
            color: #9c7c33 !important;
            border: 1px solid rgba(245, 158, 11, 0.2) !important;
        }
        
        .out-of-stock {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #dc2626 !important;
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
        }
        
        /* Add to Cart Button */
        .add-to-cart-btn {
            width: 100% !important;
            background: linear-gradient(135deg, #c9a24b, #a07f2a) !important;
            color: white !important;
            border: none !important;
            padding: 10px 14px !important;
            border-radius: 8px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            margin-top: 10px !important;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 8px rgba(201, 162, 75, 0.3) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        
        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #1d1912, #3a3125) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(29, 25, 18, 0.35) !important;
        }
        
        .add-to-cart-btn:active {
            transform: translateY(0) !important;
        }
        
        .add-to-cart-btn:disabled {
            background: linear-gradient(135deg, #9ca3af, #6b7280) !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
            opacity: 0.6 !important;
        }
        
        /* ============================================
           RESPONSIVE DESIGN - MOBILE FIRST
           ============================================ */
        
        /* Extra Small Devices (320px - 479px) */
        @media (max-width: 479px) {
            .container {
                padding: 0 10px !important;
            }
            
            .category-section {
                padding: 12px !important;
                margin-bottom: 20px !important;
                border-radius: 10px !important;
            }
            
            .section-header {
                margin-bottom: 12px !important;
                padding-bottom: 10px !important;
            }
            
            .section-title {
                font-size: 16px !important;
                padding-left: 10px !important;
            }
            
            .section-title::before {
                width: 3px !important;
                height: 18px !important;
            }
            
            .view-all-btn {
                padding: 6px 12px !important;
                font-size: 11px !important;
                gap: 5px !important;
            }
            
            .products-slider-wrapper {
                padding: 0 !important;
            }
            
            .products-slider {
                gap: 10px !important;
                padding: 6px 2px 12px 2px !important;
            }
            
            .slider-arrow {
                display: none !important;
            }
            
            .product-card {
                width: 140px !important;
                min-width: 140px !important;
                max-width: 140px !important;
                border-radius: 8px !important;
            }
            
            .product-image-wrapper {
                height: 140px !important;
            }
            
            .product-info {
                padding: 10px !important;
                gap: 5px !important;
            }
            
            .product-name {
                font-size: 12px !important;
                min-height: auto !important;
                line-height: 1.3 !important;
            }
            
            .current-price {
                font-size: 16px !important;
            }
            
            .original-price {
                font-size: 12px !important;
            }
            
            .stock-status {
                font-size: 9px !important;
                padding: 2px 5px !important;
            }
            
            .add-to-cart-btn {
                padding: 8px 10px !important;
                font-size: 11px !important;
                margin-top: 8px !important;
            }
            
            .discount-badge {
                top: 8px !important;
                left: 8px !important;
                padding: 4px 8px !important;
                font-size: 10px !important;
            }
        }
        
        /* Small Devices (480px - 767px) */
        @media (min-width: 480px) and (max-width: 767px) {
            .category-section {
                padding: 16px !important;
                margin-bottom: 24px !important;
            }
            
            .section-title {
                font-size: 18px !important;
            }
            
            .view-all-btn {
                padding: 7px 14px !important;
                font-size: 12px !important;
            }
            
            .products-slider-wrapper {
                padding: 0 6px !important;
            }
            
            .products-slider {
                gap: 12px !important;
            }
            
            .slider-arrow {
                display: none !important;
            }
            
            .product-card {
                width: 155px !important;
                min-width: 155px !important;
                max-width: 155px !important;
            }
            
            .product-image-wrapper {
                height: 155px !important;
            }
            
            .product-info {
                padding: 11px !important;
            }
            
            .product-name {
                font-size: 12px !important;
                min-height: auto !important;
            }
            
            .current-price {
                font-size: 17px !important;
            }
            
            .add-to-cart-btn {
                padding: 9px 12px !important;
                font-size: 11px !important;
            }
        }
        
        /* Medium Devices (768px - 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .category-section {
                padding: 18px !important;
                margin-bottom: 28px !important;
            }
            
            .section-title {
                font-size: 19px !important;
            }
            
            .products-slider-wrapper {
                padding: 0 36px !important;
            }
            
            .products-slider {
                gap: 13px !important;
            }
            
            .slider-arrow {
                width: 38px !important;
                height: 38px !important;
            }
            
            .product-card {
                width: 170px !important;
                min-width: 170px !important;
                max-width: 170px !important;
            }
            
            .product-image-wrapper {
                height: 170px !important;
            }
            
            .product-info {
                padding: 12px !important;
            }
            
            .product-name {
                font-size: 13px !important;
            }
            
            .add-to-cart-btn {
                padding: 10px 13px !important;
            }
        }
        
        /* Large Devices (1024px - 1279px) */
        @media (min-width: 1024px) and (max-width: 1279px) {
            .products-slider-wrapper {
                padding: 0 40px !important;
            }
            
            .product-card {
                width: 175px !important;
                min-width: 175px !important;
                max-width: 175px !important;
            }
            
            .product-image-wrapper {
                height: 175px !important;
            }
        }
        
        /* Extra Large Devices (1280px+) */
        @media (min-width: 1280px) {
            .category-section {
                padding: 18px !important;
                margin-bottom: 28px !important;
            }
            
            .products-slider-wrapper {
                padding: 0 42px !important;
            }
            
            .product-card {
                width: 180px !important;
                min-width: 180px !important;
                max-width: 180px !important;
            }
            
            .product-image-wrapper {
                height: 180px !important;
            }
        }
        
        /* ============================================
           CATEGORY COLOR VARIATIONS (OPTIONAL)
           ============================================ */
        .category-section:nth-child(6n+1)::before {
            background: linear-gradient(90deg, #a07f2a, #c9a24b) !important;
        }
        
        .category-section:nth-child(6n+2)::before {
            background: linear-gradient(90deg, #10b981, #34d399) !important;
        }
        
        .category-section:nth-child(6n+3)::before {
            background: linear-gradient(90deg, #c9a24b, #fbbf24) !important;
        }
        
        .category-section:nth-child(6n+4)::before {
            background: linear-gradient(90deg, #c9a24b, #e5c877) !important;
        }
        
        .category-section:nth-child(6n+5)::before {
            background: linear-gradient(90deg, #ef4444, #f87171) !important;
        }
        
        .category-section:nth-child(6n+6)::before {
            background: linear-gradient(90deg, #ec4899, #f472b6) !important;
        }
        
        /* ============================================
           ACCESSIBILITY & UX IMPROVEMENTS
           ============================================ */
        .product-card:focus-within {
            outline: 2px solid #a07f2a !important;
            outline-offset: 2px !important;
        }
        
        .add-to-cart-btn:focus {
            outline: 2px solid #10b981 !important;
            outline-offset: 2px !important;
        }
        
        .slider-arrow:focus {
            outline: 2px solid #a07f2a !important;
            outline-offset: 2px !important;
        }
        
        /* Loading skeleton (optional) */
        @keyframes shimmer {
            0% {
                background-position: -468px 0;
            }
            100% {
                background-position: 468px 0;
            }
        }
    </style>

    <style>
        /* ============================================
           GRID LAYOUT — 4 per row, full visible images
           ============================================ */
        .products-slider {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 22px !important;
            overflow: visible !important;
            padding: 6px 2px 12px !important;
        }

        .products-slider-wrapper {
            padding: 0 !important;
        }

        .slider-arrow {
            display: none !important;
        }

        .product-card {
            width: 100% !important;
            min-width: 0 !important;
            max-width: none !important;
        }

        /* Full image visible — no crop */
        .product-image-wrapper {
            height: auto !important;
            aspect-ratio: 4 / 5 !important;
            background: #f4f4f5 !important;
        }

        .product-image {
            object-fit: contain !important;
        }

        .product-card:hover .product-image {
            transform: scale(1.04) !important;
        }

        /* Wishlist heart (top-right, like reference) */
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

        /* Variant chips (size + color preview) */
        .product-variants {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 2px;
        }
        .variant-line {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
        }
        .variant-label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .variant-size {
            font-size: 10px;
            font-weight: 600;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 1px 6px;
            background: #f9fafb;
            cursor: pointer;
            transition: all .15s ease;
        }
        .variant-size:hover { border-color: #c9a24b; }
        .variant-size.sel { border-color: #a07f2a; background: #faf6ec; color: #a07f2a; }
        .variant-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1.5px solid #e5e7eb;
            display: inline-block;
            cursor: pointer;
            padding: 0;
            transition: all .15s ease;
        }
        .variant-dot:hover { transform: scale(1.15); }
        .variant-dot.sel { border-color: #a07f2a; box-shadow: 0 0 0 2px #fff inset, 0 0 0 2.5px #a07f2a; }

        /* Card action buttons */
        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            padding: 0 10px 10px;
        }
        .card-actions .add-to-cart-btn,
        .card-actions .order-now-btn {
            flex: 1;
            margin-top: 0 !important;
            padding: 9px 6px !important;
            font-size: 11px !important;
        }
        .order-now-btn {
            background: #1d1912 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all .3s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            text-transform: uppercase !important;
            letter-spacing: .5px !important;
            box-shadow: 0 2px 8px rgba(29,25,18,.25) !important;
        }
        .order-now-btn:hover {
            background: linear-gradient(135deg, #c9a24b, #a07f2a) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(160,127,42,.35) !important;
        }
        .order-now-btn:disabled {
            background: #e5e7eb !important;
            color: #9ca3af !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }

        /* Responsive grid */
        @media (max-width: 1023px) {
            .products-slider { grid-template-columns: repeat(3, 1fr) !important; gap: 16px !important; }
        }
        @media (max-width: 767px) {
            .products-slider { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
            .card-actions { flex-direction: column; gap: 6px; }
        }
    </style>
    <div class="pc-container">
        @if(isset($categoryProducts) && count($categoryProducts) > 0)
            @foreach($categoryProducts as $categoryName => $productsInCategory)
                @if($productsInCategory->count() > 0)
                <section class="category-section">
                    <div class="section-header">
                        <h2 class="section-title">{{ $categoryName }}</h2>
                        @if($productsInCategory->first() && $productsInCategory->first()->categoryRelation)
    <a href="{{ url('/products') }}?category={{ $productsInCategory->first()->categoryRelation->slug }}" class="view-all-btn">
@else
    <a href="{{ url('/products') }}" class="view-all-btn">
@endif
                            <i class="fas fa-arrow-right"></i> View All
                        </a>
                    </div>

                    <div class="products-slider-wrapper">
                        <button class="slider-arrow prev" data-slider="{{ Str::slug($categoryName) }}" aria-label="Previous products">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </button>

                        <div class="products-slider" data-slider-id="{{ Str::slug($categoryName) }}">
                            @foreach($productsInCategory as $product)
                            <div class="product-card">
                                <a href="{{ route('products.show', $product) }}" class="product-link">
                                    <div class="product-image-wrapper">
                                        @if($product->image)
                                            <img src="{{ $product->image_url }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image"
                                                 loading="lazy" decoding="async" fetchpriority="low" width="320" height="320">
                                        @else
                                            <img src="{{ asset('images/placeholder.png') }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image"
                                                 loading="lazy" decoding="async" fetchpriority="low" width="320" height="320">
                                        @endif
                                        @if($product->discount)
                                            <div class="discount-badge">{{ $product->discount }}% OFF</div>
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-name">{{ $product->name }}</h3>

                                        <!-- Size & Color selection -->
                                        @if(($product->sizes && $product->sizes->count()) || ($product->colors && $product->colors->count()))
                                        <div class="product-variants"
                                             data-has-sizes="{{ $product->sizes->count() ? 1 : 0 }}"
                                             data-has-colors="{{ $product->colors->count() ? 1 : 0 }}">
                                            @if($product->sizes && $product->sizes->count())
                                                <div class="variant-line">
                                                    <span class="variant-label">Size:</span>
                                                    @foreach($product->sizes->take(5) as $size)
                                                        <button type="button" class="variant-size card-chip" data-type="size" data-id="{{ $size->id }}">{{ $size->name }}</button>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($product->colors && $product->colors->count())
                                                <div class="variant-line">
                                                    <span class="variant-label">Color:</span>
                                                    @foreach($product->colors->take(6) as $color)
                                                        <button type="button" class="variant-dot card-chip" data-type="color" data-id="{{ $color->id }}" style="background: {{ $color->hex_code ?: '#ccc' }};" title="{{ $color->name }}"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        @endif

                                        <!-- Stock Status -->
                                        @if($product->stock > 5)
                                            <span class="stock-status in-stock">✓ In Stock</span>
                                        @elseif($product->stock <= 5 && $product->stock > 0)
                                            <span class="stock-status low-stock">⚠ Only {{ $product->stock }} left</span>
                                        @elseif($product->stock == 0)
                                            <span class="stock-status out-of-stock">✗ Out of Stock</span>
                                        @endif
                                        
                                        <!-- Price Section -->
                                        <div class="price-section">
                                            <span class="current-price">৳{{ number_format($product->price, 2) }}</span>
                                            @if($product->original_price)
                                                <span class="original-price">৳{{ number_format($product->original_price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                <!-- Wishlist heart -->
                                <button type="button" class="wishlist-heart" data-product-id="{{ $product->id }}" aria-label="Add {{ $product->name }} to wishlist">
                                    <i class="far fa-heart" aria-hidden="true"></i>
                                </button>

                                <!-- Add to Cart + Order Now -->
                                <div class="card-actions">
                                    <button class="add-to-cart-btn"
                                            data-product-id="{{ $product->id }}"
                                            aria-label="Add {{ $product->name }} to cart"
                                            @if($product->stock == 0) disabled @endif>
                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i> Add to Cart
                                    </button>
                                    <button class="order-now-btn"
                                            data-product-id="{{ $product->id }}"
                                            aria-label="Order {{ $product->name }} now"
                                            @if($product->stock == 0) disabled @endif>
                                        <i class="fas fa-bolt" aria-hidden="true"></i> Order Now
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button class="slider-arrow next" 
                                data-slider="{{ Str::slug($categoryName) }}"
                                aria-label="Next products">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </section>
                @endif
            @endforeach
        @else
            <!-- Fallback if no categories -->
            <section class="category-section">
                <div class="section-header">
                    <h2 class="section-title">Our Products</h2>
                    <a href="{{ url('/products') }}" class="view-all-btn">
                        <i class="fas fa-arrow-right"></i> View All
                    </a>
                </div>
                <p style="color: #6b7280 !important; text-align: center !important; padding: 40px 20px !important;">No products available at the moment.</p>
            </section>
        @endif
    </div>
</main>

<!-- JavaScript for Slider Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    // =============================================
    // SLIDER
    // =============================================
    const sliders = document.querySelectorAll('.products-slider');

    sliders.forEach(function(slider) {
        var sliderId = slider.dataset.sliderId;
        var prevBtn = document.querySelector('.slider-arrow.prev[data-slider="' + sliderId + '"]');
        var nextBtn = document.querySelector('.slider-arrow.next[data-slider="' + sliderId + '"]');

        if (!prevBtn || !nextBtn) return;

        // Add these styles directly on the slider element for smooth mobile scrolling
        // scroll-snap makes each card snap cleanly into place
        // -webkit-overflow-scrolling gives iOS momentum/inertia
        slider.style.scrollSnapType = 'x mandatory';
        slider.style.WebkitOverflowScrolling = 'touch';
        slider.style.overscrollBehaviorX = 'contain';

        // Each .product-card also needs scroll-snap-align
        // Adding it via JS so you don't need to touch your CSS file
        slider.querySelectorAll('.product-card').forEach(function(card) {
            card.style.scrollSnapAlign = 'start';
        });

        var autoSlideTimer = null;

        // How much to scroll — one full card width + gap
        function getScrollAmount() {
            var card = slider.querySelector('.product-card');
            if (!card) return 220;
            var style = window.getComputedStyle(slider);
            var gap = parseFloat(style.gap) || 16;
            return card.offsetWidth + gap;
        }

        function scrollNext() {
            var maxScroll = slider.scrollWidth - slider.clientWidth;
            if (slider.scrollLeft >= maxScroll - 10) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
            }
        }

        function scrollPrev() {
            slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        }

        function updateButtons() {
            var maxScroll = slider.scrollWidth - slider.clientWidth;
            prevBtn.style.opacity = slider.scrollLeft <= 10 ? '0.3' : '1';
            prevBtn.style.pointerEvents = slider.scrollLeft <= 10 ? 'none' : 'auto';
            nextBtn.style.opacity = slider.scrollLeft >= maxScroll - 10 ? '0.3' : '1';
            nextBtn.style.pointerEvents = slider.scrollLeft >= maxScroll - 10 ? 'none' : 'auto';
        }

        // Auto slide — desktop only
        function startAutoSlide() {
            stopAutoSlide();
            autoSlideTimer = setInterval(function() {
                scrollNext();
            }, 4000);
        }

        function stopAutoSlide() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        // Prev / Next button clicks
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            scrollPrev();
            stopAutoSlide();
            setTimeout(startAutoSlide, 6000);
        });

        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            scrollNext();
            stopAutoSlide();
            setTimeout(startAutoSlide, 6000);
        });

        // Pause auto slide on hover (desktop)
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', function() {
            if (window.innerWidth >= 768) startAutoSlide();
        });

        // Update button opacity on scroll
        slider.addEventListener('scroll', updateButtons);

        // Init
        updateButtons();
        if (window.innerWidth >= 768) startAutoSlide();

        // On resize re-check
        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                updateButtons();
                stopAutoSlide();
                if (window.innerWidth >= 768) startAutoSlide();
            }, 250);
        });
    });

    // =============================================
    // ADD TO CART
    // =============================================
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

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
            // Missing required pick → open modal with card selections preselected
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
    document.querySelectorAll('.order-now-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            cardAction(this, true);
        });
    });

    // =============================================
    // CART COUNT UPDATE
    // =============================================
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

    // =============================================
    // TOAST NOTIFICATIONS
    // =============================================
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

    // =============================================
    // LAZY LOAD IMAGES
    // =============================================
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    observer.unobserve(img);
                }
            });
        }, { rootMargin: '50px' });

        document.querySelectorAll('.product-image[data-src]').forEach(function(img) {
            imageObserver.observe(img);
        });
    }

    // =============================================
    // WISHLIST HEART — real add/remove via /wishlist routes
    // =============================================
    document.querySelectorAll('.wishlist-heart').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            libasWishlistToggle(this.getAttribute('data-product-id'), this);
        });
    });

});
</script>
