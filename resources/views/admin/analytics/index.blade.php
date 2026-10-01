@extends('admin.layouts.app')

@section('title', 'Sales Analytics - libasbd')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Sales Analytics Dashboard</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h3>৳{{ number_format($totalSales, 2) }}</h3>
                    <small>{{ $totalOrders }} orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h3>{{ $completedOrders }}</h3>
                    <small>Orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h3>{{ $pendingOrders }}</h3>
                    <small>Orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Cancelled</h5>
                    <h3>{{ $cancelledOrders }}</h3>
                    <small>Orders</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Time-based Sales -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Today</h5>
                    <h3>৳{{ number_format($todaySales, 2) }}</h3>
                    <small>{{ $todayOrders }} orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">This Week</h5>
                    <h3>৳{{ number_format($weekSales, 2) }}</h3>
                    <small>{{ $weekOrders }} orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">This Month</h5>
                    <h3>৳{{ number_format($monthSales, 2) }}</h3>
                    <small>{{ $monthOrders }} orders</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $topProduct)
                                <tr>
                                    <td>{{ $topProduct->product->name ?? 'Unknown' }}</td>
                                    <td>{{ $topProduct->total_quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No sales data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>৳{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
