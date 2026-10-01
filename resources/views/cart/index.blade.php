@extends('layouts.app')

@section('title', 'Shopping Cart - libasbd')
@section('robots', 'noindex, follow')

@section('content')
<div class="cart-page">
    <div class="cart-container">

        {{-- ── Page Header ── --}}
        <div class="cart-page-header">
            <div class="cart-page-header__left">
                <div class="cart-page-header__icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <h1 class="cart-page-header__title">Shopping Cart</h1>
                    <p class="cart-page-header__subtitle">
                        @if(!empty($cart))
                            {{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'items' }} in your cart
                        @else
                            Your cart is waiting to be filled
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ url('/products') }}" class="cart-btn cart-btn--ghost">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>

        {{-- ── Flash Messages ── --}}
        @if(session('success'))
            <div class="cart-alert cart-alert--success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="cart-alert cart-alert--error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- ══════════════════════════════
             EMPTY STATE
        ══════════════════════════════ --}}
        @if(empty($cart))
        <div class="cart-empty">
            <div class="cart-empty__illustration">
                <i class="fas fa-shopping-bag"></i>
                <div class="cart-empty__dot cart-empty__dot--1"></div>
                <div class="cart-empty__dot cart-empty__dot--2"></div>
                <div class="cart-empty__dot cart-empty__dot--3"></div>
            </div>
            <h2 class="cart-empty__title">Your cart is empty</h2>
            <p class="cart-empty__text">Looks like you haven't added anything yet. Explore our products and find something you love.</p>
            <a href="{{ url('/') }}" class="cart-btn cart-btn--primary cart-btn--lg">
                <i class="fas fa-store"></i> Browse Products
            </a>
        </div>

        {{-- ══════════════════════════════
             CART WITH ITEMS
        ══════════════════════════════ --}}
        @else
        @php $subtotal = 0; @endphp

        <div class="cart-layout">

            {{-- ── Items Column ── --}}
            <div class="cart-items-col">

                @foreach($cart as $id => $item)
                @php
                    $itemTotal = $item['price'] * $item['quantity'];
                    $subtotal += $itemTotal;
                @endphp

                <div class="cart-item" data-product-id="{{ $id }}">

                    {{-- Image --}}
                    <div class="cart-item__image">
                        @if($item['image'])
                            <img src="{{ asset('products-images/' . $item['image']) }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async">
                        @else
                            <div class="cart-item__no-img"><i class="fas fa-image"></i></div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="cart-item__details">
                        <h3 class="cart-item__name">{{ $item['name'] }}</h3>

                        <div class="cart-item__variants">
                            @if(isset($item['size_name']) && $item['size_name'])
                                <div class="cart-item__variant">
                                    <span class="cart-item__variant-label">Size</span>
                                    <span class="cart-item__variant-value">{{ $item['size_name'] }}</span>
                                </div>
                            @endif
                            @if(isset($item['color_name']) && $item['color_name'])
                                <div class="cart-item__variant">
                                    <span class="cart-item__variant-label">Color</span>
                                    <div class="cart-item__color-wrapper">
                                        @if(isset($item['color_hex']))
                                            <span class="cart-item__color-swatch" style="background: {{ $item['color_hex'] }};"></span>
                                        @endif
                                        <span class="cart-item__variant-value">{{ $item['color_name'] }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <p class="cart-item__unit-price">৳{{ number_format($item['price'], 2) }} each</p>

                        {{-- Quantity controls --}}
                        <div class="cart-item__controls">
                            <div class="cart-qty">
                                <button class="cart-qty__btn quantity-btn decrement" data-id="{{ $id }}">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number"
                                       class="cart-qty__input quantity-input"
                                       value="{{ $item['quantity'] }}"
                                       min="1"
                                       data-id="{{ $id }}">
                                <button class="cart-qty__btn quantity-btn increment" data-id="{{ $id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <button class="cart-item__remove remove-item" data-id="{{ $id }}">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>

                    {{-- Line total --}}
                    <div class="cart-item__total">
                        <span class="cart-item__total-label">Subtotal</span>
                        <span class="cart-item__total-value item-total">৳{{ number_format($itemTotal, 2) }}</span>
                    </div>

                </div>
                @endforeach

            </div>{{-- /cart-items-col --}}

            {{-- ── Summary Column ── --}}
            <div class="cart-summary-col">
                <div class="cart-summary">

                    <div class="cart-summary__header">
                        <span class="cart-summary__header-dot"></span>
                        <h2 class="cart-summary__title">Order Summary</h2>
                    </div>

                    <div class="cart-summary__body">

                        <div class="cart-summary__row">
                            <span>Subtotal</span>
                            <span class="summary-subtotal">৳{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="cart-summary__row">
                            <span>Delivery Area <span style="color: red;">*</span></span>
                            <select id="cart_delivery_area" required style="padding: 5px 8px; border-radius: 6px; border: 1px solid var(--c-border); background: white; font-family: inherit; font-size: 13px;">
                                <option value="">Select Delivery Area</option>
                                <option value="inside_dhaka">Inside Dhaka (+৳60)</option>
                                <option value="outside_dhaka">Outside Dhaka (+৳120)</option>
                            </select>
                        </div>

                        <div class="cart-summary__row">
                            <span>Shipping</span>
                            <span class="cart-summary__shipping" id="cart_shipping">৳0.00</span>
                        </div>

                        <div class="cart-summary__divider"></div>

                        <div class="cart-summary__row cart-summary__row--total">
                            <span>Total</span>
                            <span class="summary-total" id="cart_total">৳{{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="cart-summary__shipping-note">
                            <i class="fas fa-truck"></i>
                            Standard delivery · 3–5 business days
                        </div>

                    </div>

                    <div class="cart-summary__footer">
                        <a href="{{ url('/cart/checkout') }}" class="cart-btn cart-btn--primary cart-btn--full" id="proceedToCheckout">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                        <a href="{{ url('/products') }}" class="cart-btn cart-btn--ghost cart-btn--full">
                            <i class="fas fa-store"></i> Continue Shopping
                        </a>

                        <form action="{{ url('/cart/clear') }}" method="POST" class="cart-clear-form">
                            @csrf
                            <button type="submit"
                                    class="cart-clear-btn"
                                    onclick="return confirm('Are you sure you want to clear your cart?')">
                                <i class="fas fa-trash-alt"></i> Clear Cart
                            </button>
                        </form>
                    </div>

                </div>
            </div>{{-- /cart-summary-col --}}

        </div>{{-- /cart-layout --}}
        @endif

    </div>{{-- /cart-container --}}
</div>{{-- /cart-page --}}


<style>
/* ═══════════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════════ */
:root {
    --c-bg:         #f0f2f8;
    --c-surface:    #ffffff;
    --c-surface-2:  #f7f8fc;
    --c-border:     #e4e7f0;

    --c-accent:     #e63950;
    --c-accent-dk:  #c62840;
    --c-accent-lt:  #fff0f2;

    --c-text-h:     #15172b;
    --c-text-b:     #4a4f6a;
    --c-text-m:     #8c93b0;

    --c-green:      #22c87a;
    --c-green-lt:   #e8faf2;
    --c-green-dk:   #166534;

    --c-blue:       #4f6ef7;
    --c-blue-lt:    #eef1fe;

    --c-red:        #ef4444;

    --c-radius:     14px;
    --c-radius-sm:  9px;
    --c-radius-xs:  6px;

    --c-shadow:     0 1px 4px rgba(0,0,0,.06), 0 2px 8px rgba(0,0,0,.05);
    --c-shadow-lg:  0 8px 32px rgba(0,0,0,.12);

    --c-ease: cubic-bezier(.25,.8,.25,1);

    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
}

/* ═══════════════════════════════════════════════
   PAGE SHELL
═══════════════════════════════════════════════ */
.cart-page {
    background: var(--c-bg);
    min-height: calc(100vh - 160px);
    padding: 40px 0 72px;
}
.cart-container {
    max-width: 1160px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ═══════════════════════════════════════════════
   PAGE HEADER
═══════════════════════════════════════════════ */
.cart-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 4px 0 28px;
}
.cart-page-header__left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.cart-page-header__icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--c-accent) 0%, #f26a7c 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 22px;
    box-shadow: 0 4px 12px rgba(230,57,80,.35);
    flex-shrink: 0;
}
.cart-page-header__title {
    font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--c-text-h);
    margin: 0;
    line-height: 1.2;
}
.cart-page-header__subtitle {
    font-size: 13px;
    color: var(--c-text-m);
    margin: 3px 0 0;
}

/* ═══════════════════════════════════════════════
   FLASH ALERTS
═══════════════════════════════════════════════ */
.cart-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border-radius: var(--c-radius-sm);
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 20px;
    animation: c-slide-down .25s var(--c-ease);
}
.cart-alert--success { background: var(--c-green-lt); color: var(--c-green-dk); border: 1px solid #a7f3d0; }
.cart-alert--success i { color: var(--c-green); }
.cart-alert--error   { background: #fff5f5; color: #b91c1c; border: 1px solid #fecaca; }
.cart-alert--error i { color: var(--c-red); }

/* ═══════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════ */
.cart-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 80px 24px;
    background: var(--c-surface);
    border-radius: var(--c-radius);
    border: 1px solid var(--c-border);
    box-shadow: var(--c-shadow);
    animation: c-fade-in .35s var(--c-ease);
}
.cart-empty__illustration {
    position: relative;
    width: 100px;
    height: 100px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-empty__illustration i {
    font-size: 64px;
    color: #e2e5f1;
    position: relative;
    z-index: 1;
}
.cart-empty__dot {
    position: absolute;
    border-radius: 50%;
    background: var(--c-accent);
    opacity: .15;
    animation: c-pulse 2s ease-in-out infinite;
}
.cart-empty__dot--1 { width: 100%; height: 100%; top: 0; left: 0; }
.cart-empty__dot--2 { width: 70%; height: 70%; top: 15%; left: 15%; animation-delay: .3s; }
.cart-empty__dot--3 { width: 40%; height: 40%; top: 30%; left: 30%; animation-delay: .6s; }

.cart-empty__title {
    font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--c-text-h);
    margin: 0 0 10px;
}
.cart-empty__text {
    font-size: 14.5px;
    color: var(--c-text-m);
    max-width: 380px;
    line-height: 1.6;
    margin: 0 0 28px;
}

/* ═══════════════════════════════════════════════
   LAYOUT GRID
═══════════════════════════════════════════════ */
.cart-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 28px;
    align-items: start;
}
@media (max-width: 960px) {
    .cart-layout { grid-template-columns: 1fr; }
}

/* ═══════════════════════════════════════════════
   CART ITEMS
═══════════════════════════════════════════════ */
.cart-items-col {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto;
    gap: 20px;
    align-items: center;
    background: var(--c-surface);
    border: 1px solid var(--c-border);
    border-radius: var(--c-radius);
    padding: 20px;
    box-shadow: var(--c-shadow);
    transition: box-shadow .2s var(--c-ease), transform .2s var(--c-ease);
    animation: c-fade-in .3s var(--c-ease) both;
}
.cart-item:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,.09);
    transform: translateY(-1px);
}
@media (max-width: 580px) {
    .cart-item { grid-template-columns: 80px 1fr; }
    .cart-item__total { grid-column: 1 / -1; display: flex; justify-content: flex-end; gap: 10px; align-items: center; }
}

/* Image */
.cart-item__image {
    width: 100px;
    height: 100px;
    border-radius: var(--c-radius-sm);
    overflow: hidden;
    border: 1px solid var(--c-border);
    background: var(--c-surface-2);
    flex-shrink: 0;
}
.cart-item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s var(--c-ease);
}
.cart-item:hover .cart-item__image img { transform: scale(1.05); }
.cart-item__no-img {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--c-text-m);
    font-size: 28px;
}

/* Details */
.cart-item__details { min-width: 0; }
.cart-item__name {
    font-size: 15.5px;
    font-weight: 700;
    color: var(--c-text-h);
    margin: 0 0 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cart-item__variants {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
}
.cart-item__variant {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}
.cart-item__variant-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--c-text-m);
    min-width: 40px;
}
.cart-item__variant-value {
    font-weight: 600;
    color: var(--c-text-b);
}
.cart-item__color-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-item__color-swatch {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid var(--c-border);
    box-shadow: 0 1px 3px rgba(0,0,0,.1);
    display: inline-block;
    flex-shrink: 0;
}

.cart-item__unit-price {
    font-size: 13px;
    color: var(--c-text-m);
    margin: 0 0 14px;
}

/* Qty controls */
.cart-item__controls {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.cart-qty {
    display: flex;
    align-items: center;
}
.cart-qty__btn {
    width: 34px;
    height: 36px;
    border: 1.5px solid var(--c-border);
    background: var(--c-surface-2);
    color: var(--c-text-b);
    font-size: 12px;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-qty__btn:first-child { border-radius: var(--c-radius-xs) 0 0 var(--c-radius-xs); border-right: none; }
.cart-qty__btn:last-child  { border-radius: 0 var(--c-radius-xs) var(--c-radius-xs) 0; border-left: none; }
.cart-qty__btn:hover { background: var(--c-accent); color: #fff; border-color: var(--c-accent); }
.cart-qty__input {
    width: 52px;
    height: 36px;
    border: 1.5px solid var(--c-border);
    background: var(--c-surface);
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    color: var(--c-text-h);
    font-family: inherit;
    outline: none;
    box-sizing: border-box;
    -moz-appearance: textfield;
}
.cart-qty__input::-webkit-outer-spin-button,
.cart-qty__input::-webkit-inner-spin-button { -webkit-appearance: none; }
.cart-qty__input:focus { border-color: var(--c-blue); box-shadow: 0 0 0 3px var(--c-blue-lt); }

.cart-item__remove {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--c-accent);
    background: var(--c-accent-lt);
    border: 1px solid #fcc;
    border-radius: var(--c-radius-xs);
    padding: 6px 12px;
    cursor: pointer;
    transition: all .15s;
}
.cart-item__remove:hover { background: var(--c-accent); color: #fff; border-color: var(--c-accent); }

/* Line total */
.cart-item__total {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
    min-width: 90px;
}
.cart-item__total-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--c-text-m);
    font-weight: 600;
}
.cart-item__total-value {
    font-size: 18px;
    font-weight: 800;
    color: var(--c-text-h);
    letter-spacing: -.3px;
}

/* ═══════════════════════════════════════════════
   ORDER SUMMARY
═══════════════════════════════════════════════ */
.cart-summary-col { position: sticky; top: 24px; }
.cart-summary {
    background: var(--c-surface);
    border: 1px solid var(--c-border);
    border-radius: var(--c-radius);
    box-shadow: var(--c-shadow);
    overflow: hidden;
    animation: c-fade-in .35s var(--c-ease) both;
    animation-delay: .1s;
}

.cart-summary__header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 18px 22px;
    background: var(--c-surface-2);
    border-bottom: 1px solid var(--c-border);
}
.cart-summary__header-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--c-accent);
    box-shadow: 0 0 0 3px var(--c-accent-lt);
    flex-shrink: 0;
}
.cart-summary__title {
    font-family: 'Sora', 'Plus Jakarta Sans', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--c-text-h);
    margin: 0;
}

.cart-summary__body { padding: 22px; }
.cart-summary__row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: var(--c-text-b);
    padding: 10px 0;
    border-bottom: 1px solid var(--c-border);
}
.cart-summary__row:last-of-type { border-bottom: none; }
.cart-summary__row--total {
    font-size: 18px;
    font-weight: 800;
    color: var(--c-text-h);
    padding-top: 16px;
    margin-top: 4px;
    border-top: 2px solid var(--c-border);
    border-bottom: none;
}
.cart-summary__shipping { color: var(--c-text-m); font-weight: 500; }

.cart-summary__divider { height: 1px; background: var(--c-border); margin: 4px 0; }

.summary-total { color: var(--c-accent); }

.cart-summary__shipping-note {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--c-text-m);
    background: var(--c-surface-2);
    border: 1px solid var(--c-border);
    border-radius: var(--c-radius-xs);
    padding: 10px 14px;
    margin-top: 16px;
}
.cart-summary__shipping-note i { color: var(--c-blue); }

.cart-summary__footer {
    padding: 0 22px 22px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cart-clear-form { text-align: center; margin-top: 4px; }
.cart-clear-btn {
    background: none;
    border: none;
    font-family: inherit;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--c-text-m);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: var(--c-radius-xs);
    transition: color .15s, background .15s;
}
.cart-clear-btn:hover { color: var(--c-accent); background: var(--c-accent-lt); }

/* ═══════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════ */
.cart-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 13.5px;
    font-weight: 700;
    font-family: inherit;
    border-radius: var(--c-radius-sm);
    border: 1.5px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all .18s var(--c-ease);
    white-space: nowrap;
    letter-spacing: .2px;
}
.cart-btn--primary {
    background: var(--c-accent);
    color: #fff;
    box-shadow: 0 3px 10px rgba(230,57,80,.3);
}
.cart-btn--primary:hover {
    background: var(--c-accent-dk);
    box-shadow: 0 5px 18px rgba(230,57,80,.4);
    transform: translateY(-1px);
}
.cart-btn--ghost {
    background: var(--c-surface);
    color: var(--c-text-b);
    border-color: var(--c-border);
}
.cart-btn--ghost:hover { background: var(--c-surface-2); color: var(--c-text-h); }
.cart-btn--full { width: 100%; padding: 13px; font-size: 14px; }
.cart-btn--lg   { padding: 14px 32px; font-size: 15px; }

/* ═══════════════════════════════════════════════
   TOAST
═══════════════════════════════════════════════ */
.c-toast {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border-radius: var(--c-radius-sm);
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 600;
    color: #fff;
    box-shadow: var(--c-shadow-lg);
    animation: c-toast-in .3s var(--c-ease);
    max-width: 320px;
}
.c-toast--success { background: var(--c-green); }
.c-toast--error   { background: var(--c-accent); }
.c-toast i { font-size: 16px; }

/* ═══════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════ */
@keyframes c-fade-in {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes c-slide-down {
    from { opacity: 0; transform: translateY(-8px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes c-toast-in {
    from { transform: translateX(110%); opacity: 0; }
    to   { transform: translateX(0);    opacity: 1; }
}
@keyframes c-toast-out {
    from { transform: translateX(0);    opacity: 1; }
    to   { transform: translateX(110%); opacity: 0; }
}
@keyframes c-pulse {
    0%, 100% { transform: scale(1);    opacity: .15; }
    50%       { transform: scale(1.08); opacity: .08; }
}

/* Stagger cart items */
.cart-item:nth-child(1)  { animation-delay: .04s; }
.cart-item:nth-child(2)  { animation-delay: .08s; }
.cart-item:nth-child(3)  { animation-delay: .12s; }
.cart-item:nth-child(4)  { animation-delay: .16s; }
.cart-item:nth-child(5)  { animation-delay: .20s; }
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Update totals in the DOM ──
    function updateCartTotals(subtotal, cartCount, cartEmpty) {
        const subtotalEl = document.querySelector('.summary-subtotal');
        if (subtotalEl) subtotalEl.textContent = '৳' + formatBDT(subtotal);
        
        // Update shipping and total based on selected delivery area
        updateCartShipping();
        
        const countEl = document.querySelector('.cart-count, #cart-count, .cart-count-badge');
        if (countEl) countEl.textContent = cartCount;

        if (cartEmpty) location.reload();
    }

    function formatBDT(n) {
        return parseFloat(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    
    function getSubtotalFromPage() {
        const subtotalEl = document.querySelector('.summary-subtotal');
        if (subtotalEl) {
            let text = subtotalEl.innerText.replace('৳', '').replace(/,/g, '');
            return parseFloat(text) || 0;
        }
        return 0;
    }
    
    function updateCartShipping() {
        let subtotal = getSubtotalFromPage();
        let shipping = 0;
        let selectedArea = deliverySelect ? deliverySelect.value : 'inside_dhaka';
        
        if (selectedArea === 'inside_dhaka') {
            shipping = 60;
        } else if (selectedArea === 'outside_dhaka') {
            shipping = 120;
        }
        
        let total = subtotal + shipping;
        
        if (shippingSpan) {
            shippingSpan.innerText = '৳' + shipping.toFixed(2);
        }
        if (totalSpan) {
            totalSpan.innerText = '৳' + total.toFixed(2);
        }
    }

    // ── Remove item ──
    function removeCartItem(id, triggerEl) {
        fetch(`${window.LIBAS_BASE || ''}/cart/remove/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const item = triggerEl.closest('.cart-item');
                if (item) {
                    item.style.transition = 'opacity .25s, transform .25s';
                    item.style.opacity    = '0';
                    item.style.transform  = 'translateX(16px)';
                    setTimeout(() => item.remove(), 260);
                }
                updateCartTotals(data.subtotal, data.cartCount, data.cartEmpty);
                showToast('Item removed from cart', 'success');
            } else {
                showToast('Failed to remove item', 'error');
            }
        })
        .catch(() => showToast('An error occurred', 'error'));
    }

    // ── Update quantity ──
    function updateCartItem(id, quantity, triggerEl) {
        fetch(`${window.LIBAS_BASE || ''}/cart/update/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const item = triggerEl.closest('.cart-item');
                const lineTotalEl = item ? item.querySelector('.item-total') : null;
                if (lineTotalEl) lineTotalEl.textContent = '৳' + formatBDT(data.subtotal);
                updateCartTotals(data.cartTotal, data.cartCount, false);
                showToast('Quantity updated', 'success');
            }
        })
        .catch(() => showToast('Failed to update quantity', 'error'));
    }
    
    // ── Dynamic shipping elements ──
    const deliverySelect = document.getElementById('cart_delivery_area');
    const shippingSpan = document.getElementById('cart_shipping');
    const totalSpan = document.getElementById('cart_total');

    if (deliverySelect) {
        deliverySelect.addEventListener('change', function() {
            updateCartShipping();
            // Save delivery area to session
            saveDeliveryArea(this.value);
        });
        updateCartShipping();
    }

    // ── Save delivery area to session ──
    function saveDeliveryArea(area) {
        fetch((window.LIBAS_BASE || '') + '/cart/save-delivery-area', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ delivery_area: area })
        }).catch(() => {});
    }

    // ── Quantity buttons ──
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let qty     = parseInt(input.value);

            if (this.classList.contains('increment')) qty++;
            else if (this.classList.contains('decrement') && qty > 1) qty--;

            input.value = qty;
            updateCartItem(id, qty, this);
        });
    });

    // ── Quantity input direct change ──
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const id  = this.dataset.id;
            let qty   = parseInt(this.value);
            if (qty < 1 || isNaN(qty)) qty = 1;
            this.value = qty;
            updateCartItem(id, qty, this);
        });
    });

    // ── Remove buttons ──
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            if (confirm('Remove this item from your cart?')) {
                removeCartItem(id, this);
            }
        });
    });

    // ── Delivery area validation before checkout ──
    const checkoutBtn = document.getElementById('proceedToCheckout');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function (e) {
            const deliveryArea = document.getElementById('cart_delivery_area');
            if (!deliveryArea || !deliveryArea.value) {
                e.preventDefault();
                alert('Please select a delivery area before proceeding to checkout.');
                if (deliveryArea) deliveryArea.focus();
            }
        });
    }

    // ── Toast ──
    function showToast(message, type) {
        document.querySelectorAll('.c-toast').forEach(t => t.remove());
        const icon  = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const toast = document.createElement('div');
        toast.className = 'c-toast c-toast--' + type;
        toast.innerHTML = `<i class="fas ${icon}"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'c-toast-out .3s var(--c-ease) forwards';
            setTimeout(() => toast.remove(), 310);
        }, 3000);
    }

});
</script>
@endsection