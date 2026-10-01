<div class="product-card">
    <a href="{{ route('products.show', $product) }}" class="product-link">
        <div class="product-image">
            @if($product->image)
                <img src="{{ asset('products-images/' . $product->image) }}" 
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="no-image">
                    <i class="fas fa-box"></i>
                </div>
            @endif
            
            @if($product->discount)
                <div class="discount">
                    <i class="fas fa-tag"></i> {{ $product->discount }}% OFF
                </div>
            @endif
        </div>
        
        <div class="product-details">
            <h3 class="name">{{ $product->name }}</h3>

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

            @if($product->category)
                <p class="category">
                    <i class="fas fa-folder"></i> {{ $product->category->name }}
                </p>
            @endif
            
            <div class="price">
                <span class="current">৳{{ number_format($product->price, 2) }}</span>
                @if($product->original_price && $product->original_price > $product->price)
                    <span class="original">৳{{ number_format($product->original_price, 2) }}</span>
                @endif
            </div>
            
            <div class="stock {{ $product->stock > 5 ? 'in-stock' : ($product->stock > 0 ? 'low-stock' : 'out-of-stock') }}">
                @if($product->stock > 5)
                    <i class="fas fa-check-circle"></i> In Stock
                @elseif($product->stock > 0)
                    <i class="fas fa-exclamation-circle"></i> Only {{ $product->stock }} left
                @else
                    <i class="fas fa-times-circle"></i> Out of Stock
                @endif
            </div>
        </div>
    </a>
    
    <button type="button" class="wishlist-heart" data-product-id="{{ $product->id }}" aria-label="Add {{ $product->name }} to wishlist">
        <i class="far fa-heart" aria-hidden="true"></i>
    </button>

    <div class="card-actions">
        <button class="add-to-cart" 
                data-product-id="{{ $product->id }}"
                @if($product->stock == 0) disabled @endif>
            <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
        <button class="order-now" 
                data-product-id="{{ $product->id }}"
                @if($product->stock == 0) disabled @endif>
            <i class="fas fa-bolt"></i> Order Now
        </button>
    </div>
</div>
