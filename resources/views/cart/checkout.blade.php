@extends('layouts.app')

@section('title', 'Checkout - libasbd')
@section('robots', 'noindex, follow')

@section('content')
<div class="checkout-page">
    <div class="container">
        <h1>Checkout</h1>
        
        @if(empty($cart))
            <div class="alert alert-warning">
                Your cart is empty. <a href="{{ url('/products') }}">Continue shopping</a>
            </div>
        @else
            <div class="checkout-container">
                <div class="checkout-form">
                    <h3>Customer Information</h3>
                    
                    <form action="{{ url('/cart/place-order') }}" method="POST" id="checkoutForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="customer_name">Full Name *</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="আপনার পূর্ণ নাম লিখুন" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email</label>
                            <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="example@email.com (ঐচ্ছিক)">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Phone Number *</label>
                            <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="01XXXXXXXXX" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address *</label>
                            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="3" placeholder="বাসা/হোল্ডিং, রোড, এলাকা, থানা, জেলা" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="coupon_code">Coupon Code</label>
                            <div class="input-group">
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter coupon code">
                                <button type="button" id="applyCoupon" class="btn btn-secondary">Apply</button>
                            </div>
                            @if($discount > 0)
                                <div class="alert alert-success mt-2">
                                    Coupon applied! You save ৳{{ number_format($discount, 2) }}
                                </div>
                            @endif
                        </div>
                        
                        <h3>Order Summary</h3>
                        
                        <div class="order-summary">
                            @php
                                $subtotal = 0;
                            @endphp
                            
                            @foreach($cart as $id => $item)
                                @php
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <div class="order-item">
                                    <div class="order-item-info">
                                        <div class="order-item-name">{{ $item['name'] }}</div>
                                        
                                        @if(isset($item['size_name']) && $item['size_name'])
                                            <div class="order-item-variant">
                                                <span class="variant-label">Size:</span> {{ $item['size_name'] }}
                                            </div>
                                        @endif
                                        
                                        @if(isset($item['color_name']) && $item['color_name'])
                                            <div class="order-item-variant">
                                                <span class="variant-label">Color:</span>
                                                @if(isset($item['color_hex']))
                                                    <span class="color-swatch" style="background-color: {{ $item['color_hex'] }};"></span>
                                                @endif
                                                {{ $item['color_name'] }}
                                            </div>
                                        @endif
                                        
                                        <div class="order-item-price-detail">
                                            ৳{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }} = <strong>৳{{ number_format($itemTotal, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="order-item-total">
                                        ৳{{ number_format($itemTotal, 2) }}
                                    </div>
                                </div>
                            @endforeach
                            
                            <div class="order-divider"></div>

                            <div class="delivery-area-row">
                                <label for="delivery_area" class="delivery-area-label">
                                    <i class="fas fa-truck-fast"></i> Delivery Area *
                                </label>
                                <select name="delivery_area" id="delivery_area" class="form-control delivery-area-select" required>
                                    <option value="inside_dhaka" {{ ($deliveryArea ?? 'inside_dhaka') === 'inside_dhaka' ? 'selected' : '' }}>
                                        ঢাকার ভিতরে (Inside Dhaka) — ৳60
                                    </option>
                                    <option value="outside_dhaka" {{ ($deliveryArea ?? '') === 'outside_dhaka' ? 'selected' : '' }}>
                                        ঢাকার বাইরে (Outside Dhaka) — ৳120
                                    </option>
                                </select>
                            </div>

                            <div class="order-total">
                                <span>Subtotal</span>
                                <span id="subtotal_display">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="order-total">
                                <span>Shipping</span>
                                <span id="shipping_display">৳{{ number_format($shippingCost, 2) }}</span>
                            </div>

                            <div class="order-total grand-total">
                                <span>Total</span>
                                <span id="total_display">৳{{ number_format($subtotal + $shippingCost - $discount, 2) }}</span>
                            </div>
                        </div>
                        
                        <input type="hidden" name="subtotal" id="subtotal_hidden" value="{{ $subtotal }}">
                        <input type="hidden" name="shipping_cost" id="shipping_cost_hidden" value="{{ $shippingCost }}">
                        <input type="hidden" name="total" id="total_hidden" value="{{ $subtotal + $shippingCost - $discount }}">
                        
                        <button type="submit" class="btn-primary btn-block">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .checkout-page {
        padding: 40px 0;
        background: #f5f5f5;
        min-height: calc(100vh - 200px);
    }
    
    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .checkout-form {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #e63950;
        box-shadow: 0 0 0 3px rgba(230,57,80,0.1);
    }
    
    textarea.form-control {
        resize: vertical;
    }
    
    h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 25px 0 15px 0;
        color: #333;
    }
    
    h3:first-of-type {
        margin-top: 0;
    }
    
    .order-summary {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .order-item-info {
        flex: 1;
    }
    
    .order-item-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }
    
    .order-item-variant {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .variant-label {
        font-weight: 500;
        color: #8c93b0;
    }
    
    .color-swatch {
        display: inline-block;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        margin-right: 5px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }
    
    .order-item-price-detail {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
    }
    
    .order-item-price-detail strong {
        color: #333;
    }
    
    .order-item-total {
        font-weight: 700;
        color: #333;
        min-width: 100px;
        text-align: right;
    }
    
    .order-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 15px 0;
    }
    
    .order-total {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-weight: 500;
        color: #333;
    }
    
    .grand-total {
        font-size: 18px;
        font-weight: bold;
        color: #e63950;
        border-top: 2px solid #e5e7eb;
        margin-top: 10px;
        padding-top: 15px;
    }
    
    .btn-primary {
        width: 100%;
        padding: 14px;
        font-size: 16px;
        font-weight: 600;
        background: #e63950;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-primary:hover {
        background: #c62840;
    }
    
    .btn-block {
        width: 100%;
    }
    
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-warning {
        background: #fef3c7;
        color: #7a6126;
        border: 1px solid #fde68a;
    }
    
    .alert-warning a {
        color: #7a6126;
        text-decoration: underline;
    }
    
    .text-danger {
        color: #e63950;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryArea = document.getElementById('delivery_area');
        const subtotalDisplay = document.getElementById('subtotal_display');
        const shippingDisplay = document.getElementById('shipping_display');
        const totalDisplay = document.getElementById('total_display');
        const shippingHidden = document.getElementById('shipping_cost_hidden');
        const totalHidden = document.getElementById('total_hidden');

        // Get subtotal value (remove currency symbol and commas)
        let subtotal = 0;
        if (subtotalDisplay) {
            subtotal = parseFloat(subtotalDisplay.innerText.replace('৳', '').replace(/,/g, '')) || 0;
        }

        function calculateTotal() {
            let shipping = 0;
            let selectedArea = deliveryArea.value;

            if (selectedArea === 'inside_dhaka') {
                shipping = 60;
            } else if (selectedArea === 'outside_dhaka') {
                shipping = 120;
            }

            let total = subtotal + shipping;

            if (shippingDisplay) {
                shippingDisplay.innerText = '৳' + shipping.toFixed(2);
            }
            if (totalDisplay) {
                totalDisplay.innerText = '৳' + total.toFixed(2);
            }
            if (shippingHidden) {
                shippingHidden.value = shipping;
            }
            if (totalHidden) {
                totalHidden.value = total;
            }
        }

        function saveArea() {
            fetch((window.LIBAS_BASE || '') + '/cart/save-delivery-area', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ delivery_area: deliveryArea.value })
            });
        }

        if (deliveryArea) {
            deliveryArea.addEventListener('change', function () {
                calculateTotal();
                saveArea();
            });
            calculateTotal();
            // Persist the selected area so the server uses the shown shipping charge
            saveArea();
        }
    });
</script>

@if(config('services.facebook.pixel_id'))
<script>
    if (typeof fbq !== 'undefined') {
        fbq('track', 'InitiateCheckout', {!! json_encode($pixelInitiateData ?? [
            'value' => $subtotal + $shippingCost - $discount,
            'currency' => 'BDT',
            'num_items' => array_sum(array_column($cart, 'quantity')),
        ]) !!}, {eventID: '{{ $pixelEventId ?? '' }}'});
    }
</script>
@endif

<style>
    .delivery-area-row { margin: 14px 0; }
    .delivery-area-label {
        display: flex; align-items: center; gap: 8px;
        font-size: 14px; font-weight: 600; color: #7a6126; margin-bottom: 8px;
    }
    .delivery-area-select {
        width: 100%; padding: 11px 12px;
        border: 1.5px solid #e5dcc4; border-radius: 8px;
        font-family: inherit; font-size: 14px; background: #fff; color: #1d1912;
    }
    .delivery-area-select:focus { outline: none; border-color: #c9a24b; }
</style>

@endsection