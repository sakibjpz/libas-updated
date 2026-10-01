@extends('admin.layouts.app')

@section('title', 'Order Details #' . $order->id)
@section('page_title', 'Order Details')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Order Header Card -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Order #{{ $order->id }}</h3>
                    <div class="card-tools">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'processing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'shipped' => 'primary'
                            ];
                            $statusColor = $statusColors[$order->status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $statusColor }} badge-lg">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user mr-2"></i> Customer Information</h5>
                            <hr>
                            <table class="table table-sm">
                                <tr>
                                    <th style="width: 40%">Customer Name:</th>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>
                                        @if($order->customer_email)
                                            <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>
                                        @if($order->customer_phone)
                                            <a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('F d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5><i class="fas fa-map-marker-alt mr-2"></i> Shipping Information</h5>
                            <hr>
                            @if($order->shipping_address)
                                <div class="bg-light p-3 rounded">
                                    {{ $order->shipping_address }}
                                </div>
                            @else
                                <p class="text-muted">No shipping address provided.</p>
                            @endif

                            @if($order->delivery_area)
                                <p class="mt-2 mb-0">
                                    <strong>Delivery Area:</strong>
                                    <span class="badge badge-info">{{ $order->delivery_area === 'inside_dhaka' ? 'Inside Dhaka' : 'Outside Dhaka' }}</span>
                                </p>
                            @endif
                            
                            <div class="mt-4">
                                <h5><i class="fas fa-credit-card mr-2"></i> Payment Information</h5>
                                <hr>
                                <table class="table table-sm">
                                    <tr>
                                        <th style="width: 40%">Payment Method:</th>
                                        <td>{{ $order->payment_method ?? 'Cash on Delivery' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status:</th>
                                        <td>
                                            @if($order->payment_status === 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($order->payment_status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-secondary">Unknown</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items Card -->
            <div class="card card-info mt-3">
                <div class="card-header">
                    <h3 class="card-title">Order Items</h3>
                    <div class="card-tools">
                        <span class="badge badge-light">
                            {{ count(json_decode($order->items, true) ?? []) }} item(s)
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 10%">Image</th>
                                    <th>Product</th>
                                    <th style="width: 10%">Size</th>
                                    <th style="width: 10%">Color</th>
                                    <th style="width: 12%">Price</th>
                                    <th style="width: 10%">Quantity</th>
                                    <th style="width: 12%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $itemsData = json_decode($order->items, true) ?? [];
                                    $subtotal = 0;
                                @endphp
                                
                                @foreach($itemsData as $cartKey => $item)
                                @php
                                    if (!is_array($item)) continue;
                                    
                                    $itemPrice = (float)($item['price'] ?? 0);
                                    $itemQty = (int)($item['quantity'] ?? 0);
                                    $itemTotal = $itemPrice * $itemQty;
                                    $subtotal += $itemTotal;
                                    $productImage = $item['image'] ?? null;
                                    $itemName = $item['name'] ?? 'N/A';
                                    $sizeName = $item['size_name'] ?? $item['size'] ?? '-';
                                    $colorName = $item['color_name'] ?? $item['color'] ?? '-';
                                    $colorHex = $item['color_hex'] ?? '#000';
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        @if($productImage)
                                            <img src="{{ asset('products-images/' . $productImage) }}" alt="{{ $itemName }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" loading="lazy" decoding="async">
                                        @else
                                            <img src="{{ asset('images/placeholder.png') }}" alt="No image" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $itemName }}</strong>
                                    </td>
                                    <td>
                                        @if($sizeName != '-')
                                            <span class="order-variant-badge order-variant-badge--size">
                                                <i class="fas fa-ruler-horizontal"></i> {{ $sizeName }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($colorName != '-')
                                            <span class="order-variant-badge order-variant-badge--color">
                                                <span class="order-color-swatch" style="background-color: {{ $colorHex }};"></span>
                                                {{ $colorName }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>৳{{ number_format($itemPrice, 2) }}</td>
                                    <td>{{ $itemQty }}</td>
                                    <td><strong>৳{{ number_format($itemTotal, 2) }}</strong></td>
                                </tr>
                                @endforeach
                                
                                @if(empty($itemsData))
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                        <p>No items found in this order</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary & Actions Card -->
            <div class="card card-success mt-3">
                <div class="card-header">
                    <h3 class="card-title">Order Summary & Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-receipt mr-2"></i> Order Summary</h5>
                            <hr>
                            <table class="table table-sm">
                                <tr>
                                    <th>Subtotal:</th>
                                    <td class="text-right">৳{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                @php
                                    $shipping = $order->shipping_cost ?? 0;
                                    $tax = $order->tax ?? 0;
                                    $discount = $order->discount ?? 0;
                                @endphp
                                @if($shipping > 0)
                                <tr>
                                    <th>Shipping:</th>
                                    <td class="text-right">৳{{ number_format($shipping, 2) }}</td>
                                </tr>
                                @endif
                                @if($tax > 0)
                                <tr>
                                    <th>Tax:</th>
                                    <td class="text-right">৳{{ number_format($tax, 2) }}</td>
                                </tr>
                                @endif
                                @if($discount > 0)
                                <tr>
                                    <th>Discount:</th>
                                    <td class="text-right text-danger">-৳{{ number_format($discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="table-active">
                                    <th><strong>Grand Total:</strong></th>
                                    <td class="text-right">
                                        <strong class="text-success">৳{{ number_format($order->total, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5><i class="fas fa-cogs mr-2"></i> Order Actions</h5>
                            <hr>
                            
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <div class="form-group">
                                    <label for="status"><strong>Update Order Status</strong></label>
                                    <div class="input-group">
                                        <select name="status" id="status" class="form-control">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            @if($order->notes)
                            <div class="form-group">
                                <label><strong>Order Notes</strong></label>
                                <div class="bg-light p-3 rounded">
                                    {{ $order->notes }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="btn-group w-100 mt-3" role="group">
                                <a href="javascript:void(0)" class="btn btn-secondary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print Invoice
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back to Orders
                                </a>
                                @if($order->customer_email)
                                <a href="mailto:{{ $order->customer_email }}?subject=Order%20#{{ $order->id }}%20Update" class="btn btn-info">
                                    <i class="fas fa-envelope"></i> Email Customer
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Order ID: {{ $order->id }} | 
                        Created: {{ $order->created_at->format('M d, Y h:i A') }} |
                        Last Updated: {{ $order->updated_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5em 1em;
    }
    .table-sm th {
        font-weight: 600;
    }
    .btn-group .btn {
        border-radius: 0;
    }
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }

    /* Order variant badges */
    .order-variant-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .order-variant-badge--size {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    .order-variant-badge--color {
        background: #f3e5f5;
        color: #7b1fa2;
        border: 1px solid #e1bee7;
    }
    .order-color-swatch {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid rgba(0,0,0,0.1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        display: inline-block;
    }

    @media print {
        .card-tools, .btn-group, form {
            display: none !important;
        }
    }
</style>
@endpush