@extends('admin.layouts.app')

@section('title', 'Fraud Detection')
@section('page_title', 'Fraud Detection Dashboard')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Fraud Detection</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Orders</h5>
                    <h3>{{ $recentOrders->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-warning card-outline">
                <div class="card-body">
                    <h5 class="card-title text-muted">Flagged Orders</h5>
                    <h3>{{ $suspiciousOrders->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-danger card-outline">
                <div class="card-body">
                    <h5 class="card-title text-muted">Suspicious IPs</h5>
                    <h3>{{ count($fraudReport['suspicious_ips'] ?? []) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-dark card-outline">
                <div class="card-body">
                    <h5 class="card-title text-muted">Blacklisted</h5>
                    <h3>{{ count($fraudReport['blacklisted_emails'] ?? []) + count($fraudReport['blacklisted_phones'] ?? []) + count($fraudReport['blacklisted_ips'] ?? []) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-success card-outline">
                <div class="card-header bg-success">
                    <h3 class="card-title">Blacklist Management</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.fraud.add-blacklist') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="ip">IP Address</option>
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="value" class="form-control" placeholder="Enter value" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-block">Add to Blacklist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-danger card-outline">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Suspicious Orders</h3>
                </div>
                <div class="card-body">
                    @if($suspiciousOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Total</th>
                                        <th>Fraud Score</th>
                                        <th>Flags</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->customer_email ?? 'N/A' }}</td>
                                        <td>{{ $order->customer_phone ?? 'N/A' }}</td>
                                        <td>৳{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->fraud_score >= 75 ? 'danger' : 'warning' }}">
                                                {{ $order->fraud_score ?? 0 }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->fraud_flags)
                                                @foreach(array_slice($order->fraud_flags, 0, 2) as $flag)
                                                    <span class="badge badge-info">{{ $flag }}</span>
                                                @endforeach
                                                @if(count($order->fraud_flags) > 2)
                                                    <span class="badge badge-secondary">+{{ count($order->fraud_flags) - 2 }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No flags</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.fraud.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                            <form action="{{ route('admin.fraud.mark-safe', $order->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this order as safe?')">Safe</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $suspiciousOrders->links() }}
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> No suspicious orders detected.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
