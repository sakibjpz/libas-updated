@extends('layouts.app')

@section('title', 'Fraud Details - Order #' . $order->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('admin.fraud.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h2 class="mb-4">Order #{{ $order->id }} - Fraud Details</h2>
        </div>
    </div>

    <!-- Fraud Score Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-{{ $order->fraud_score >= 75 ? 'danger' : 'warning' }} text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Fraud Score: {{ $order->fraud_score ?? 0 }}/100</h5>
                            <p class="card-text mb-0">
                                @if($order->fraud_score >= 75)
                                    <strong>High Risk</strong> - Order automatically blocked
                                @elseif($order->fraud_score >= 50)
                                    <strong>Medium Risk</strong> - Flagged for manual review
                                @else
                                    <strong>Low Risk</strong> - Passed basic checks
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h5 class="card-title">Status: {{ ucfirst($order->status) }}</h5>
                            <p class="card-text mb-0">
                                {{ $order->fraud_flag ? 'Flagged for fraud' : 'No fraud flag' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fraud Flags -->
    @if($order->fraud_flags)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Fraud Flags</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($order->fraud_flags as $flag)
                        <li class="list-group-item">
                            <i class="fas fa-exclamation-triangle text-warning"></i> {{ $flag }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Order Details -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $order->customer_name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $order->customer_email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $order->customer_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Shipping Address:</th>
                            <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Order ID:</th>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td>৳{{ number_format($order->total, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>{{ ucfirst($order->status) }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Fraud Checked:</th>
                            <td>{{ $order->fraud_checked_at ? $order->fraud_checked_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
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
                                <td>{{ $item->color->name ?? 'N/A' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>৳{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fraud.mark-safe', $order->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Mark this order as safe and clear fraud flags?')">
                            <i class="fas fa-check"></i> Mark as Safe
                        </button>
                    </form>

                    <form action="{{ route('admin.fraud.mark-fraudulent', $order->id) }}" method="POST" style="display: inline;" class="ms-2">
                        @csrf
                        <div class="d-inline-block">
                            <input type="text" name="reason" class="form-control d-inline-block" style="width: 300px;" placeholder="Reason for marking as fraudulent" required>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban"></i> Mark as Fraudulent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
