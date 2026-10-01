@extends('layouts.app')

@section('title', 'Order Confirmation - libasbd')
@section('robots', 'noindex, follow')

@section('content')
<div class="order-confirmation-page">
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>Order Placed Successfully!</h1>
            <p class="confirmation-message">
                Thank you for your order. We have received your order and will process it shortly.
            </p>
            
            <div class="confirmation-details">
                <p><strong>Order Summary:</strong></p>
                
                @if(isset($order) && $order)
                    <div class="order-info">
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}</p>
                        <p><strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                        <p><strong>Total Amount:</strong> ৳{{ number_format($order->total, 2) }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Status:</strong> <span class="status-badge">{{ ucfirst($order->status) }}</span></p>
                    </div>
                    
                    <div class="order-items">
                        <p><strong>Items Ordered:</strong></p>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'Product Not Found' }}</td>
                                    <td>{{ $item->size->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($item->color)
                                            <span style="display:inline-block; width:15px; height:15px; background:{{ $item->color->hex_code ?? '#000' }}; border-radius:3px; margin-right:5px;"></span>
                                            {{ $item->color->name ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>৳{{ number_format($item->price, 2) }}</td>
                                    <td>৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="5" style="text-align: right;"><strong>Grand Total:</strong></td>
                                    <td><strong>৳{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @elseif(isset($order_details))
                    @php
                        $orderInfo = $order_details;
                    @endphp
                    <div class="order-info">
                        <p><strong>Order ID:</strong> #{{ $orderInfo['id'] ?? 'N/A' }}</p>
                        <p><strong>Customer Name:</strong> {{ $orderInfo['customer_name'] ?? 'N/A' }}</p>
                        <p><strong>Total Amount:</strong> ৳{{ number_format($orderInfo['total'] ?? 0, 2) }}</p>
                        <p><strong>Order Date:</strong> {{ $orderInfo['created_at'] ?? now()->format('Y-m-d H:i') }}</p>
                    </div>
                @elseif(session('order_details'))
                    @php
                        $orderInfo = session('order_details');
                    @endphp
                    <div class="order-info">
                        <p><strong>Order ID:</strong> #{{ $orderInfo['id'] ?? 'N/A' }}</p>
                        <p><strong>Customer Name:</strong> {{ $orderInfo['customer_name'] ?? 'N/A' }}</p>
                        <p><strong>Total Amount:</strong> ৳{{ number_format($orderInfo['total'] ?? 0, 2) }}</p>
                        <p><strong>Order Date:</strong> {{ $orderInfo['created_at'] ?? now()->format('Y-m-d H:i') }}</p>
                    </div>
                @else
                    <p>Order details will be sent to your email.</p>
                @endif
            </div>
            
            <div class="confirmation-actions">
                <a href="{{ url('/products') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Continue Shopping
                </a>
                <a href="{{ url('/') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i> Go to Homepage
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .order-confirmation-page {
        padding: 60px 0;
        min-height: 70vh;
        display: flex;
        align-items: center;
    }
    
    .confirmation-card {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .confirmation-icon {
        font-size: 80px;
        color: #10b981;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .confirmation-card h1 {
        color: #10b981;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .confirmation-message {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 30px;
        line-height: 1.6;
        text-align: center;
    }
    
    .confirmation-details {
        background: #f9fafb;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
    }
    
    .confirmation-details p {
        margin: 10px 0;
    }
    
    .order-info p {
        margin: 8px 0;
        padding-left: 15px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #c9a24b;
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .order-items {
        margin-top: 25px;
        padding-top: 15px;
        border-top: 1px solid #e5e7eb;
    }
    
    .order-items p {
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    
    .items-table th,
    .items-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .items-table th {
        background: #f3f4f6;
        font-weight: 600;
    }
    
    .total-row td {
        border-top: 2px solid #d1d5db;
        border-bottom: none;
        padding-top: 12px;
        font-weight: 600;
    }
    
    .confirmation-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .confirmation-actions .btn {
        min-width: 180px;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 8px;
        display: inline-block;
        text-align: center;
    }
    
    .btn-primary {
        background: #10b981;
        color: white;
        border: none;
    }
    
    .btn-primary:hover {
        background: #059669;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
        border: none;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    @media (max-width: 576px) {
        .confirmation-card {
            padding: 25px 20px;
        }
        
        .confirmation-actions {
            flex-direction: column;
        }
        
        .confirmation-actions .btn {
            width: 100%;
        }
        
        .items-table {
            font-size: 12px;
        }
        
        .items-table th,
        .items-table td {
            padding: 6px;
        }
    }
</style>

@if(isset($pixelPurchaseData))
<script>
    // Track Purchase event
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Purchase', {
            content_ids: @json($pixelPurchaseData['content_ids']),
            content_type: 'product',
            content_name: @json($pixelPurchaseData['content_name']),
            content_category: @json($pixelPurchaseData['content_category']),
            value: {{ $pixelPurchaseData['value'] }},
            currency: '{{ $pixelPurchaseData['currency'] }}',
            num_items: {{ $pixelPurchaseData['num_items'] }}
        }, {eventID: '{{ $pixelEventId ?? '' }}'});
    }
</script>
@endif
@endsection