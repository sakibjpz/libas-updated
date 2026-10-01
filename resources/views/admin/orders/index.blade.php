@extends('admin.layouts.app')

@section('title', 'Order Management')
@section('page_title', 'Orders')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Orders</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order List</h3>
                    
                    <!-- Search Form -->
                    <div class="card-tools">
                        <form method="GET" class="form-inline">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="form-control float-right" 
                                       placeholder="Search orders...">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 15%">Customer</th>
                                    <th style="width: 12%">Contact</th>
                                    <th style="width: 8%">Items</th>
                                    <th style="width: 8%">Total</th>
                                    <th style="width: 8%">Status</th>
                                    <th style="width: 12%">Steadfast</th>
                                    <th style="width: 12%">Date</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong>
                                        @if($order->customer_email)
                                            <br><small class="text-muted">{{ $order->customer_email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->customer_phone)
                                            <i class="fas fa-phone text-muted mr-1"></i>
                                            {{ $order->customer_phone }}
                                        @else
                                            <span class="text-muted">No phone</span>
                                        @endif
                                    </td>
                                    <td>
                                      @php
    $itemsCount = 0;
    $items = $order->items;
    
    // If items is a string (JSON), decode it
    if (is_string($items)) {
        $items = json_decode($items, true);
    }
    
    // If items is an array, count it
    if (is_array($items)) {
        $itemsCount = count($items);
    }
@endphp
                                        <span class="badge badge-info">{{ $itemsCount }} item(s)</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($order->total, 2) }}</strong>
                                    </td>
                                    <td>
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
                                        <span class="badge badge-{{ $statusColor }}">
                                            @if($order->status === 'pending')
                                                <i class="fas fa-clock"></i>
                                            @elseif($order->status === 'processing')
                                                <i class="fas fa-cogs"></i>
                                            @elseif($order->status === 'completed')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($order->status === 'cancelled')
                                                <i class="fas fa-times-circle"></i>
                                            @elseif($order->status === 'shipped')
                                                <i class="fas fa-shipping-fast"></i>
                                            @endif
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->is_sent_to_steadfast)
                                            <span class="badge badge-success mb-1">✓ Sent</span>
                                            <br>
                                            <small class="text-info">{{ $order->steadfast_tracking_code }}</small>
                                            <br>
                                            <small class="text-muted">{{ $order->steadfast_status }}</small>
                                        @else
                                            <form action="{{ route('admin.steadfast.send-order', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Send this order to Steadfast courier?')">
                                                    <i class="fas fa-truck"></i> Send
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                                            <div class="text-muted">{{ $order->created_at->format('h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.orders.show', $order) }}" 
                                               class="btn btn-info" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <!-- Quick Status Update Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" 
                                                        data-toggle="dropdown" 
                                                        aria-haspopup="true" 
                                                        aria-expanded="false"
                                                        title="Update Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <h6 class="dropdown-header">Change Status</h6>
                                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="px-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="form-group mb-2">
                                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                           <a href="javascript:void(0)" 
   class="btn btn-secondary" 
   title="Print Order"
   onclick="window.print()">
    <i class="fas fa-print"></i>
</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                            <h4>No orders found</h4>
                                            <p>When customers place orders, they will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Order Stats -->
                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending</span>
                                    <span class="info-box-number">
                                        {{ $orders->where('status', 'pending')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Processing</span>
                                    <span class="info-box-number">
                                        {{ $orders->where('status', 'processing')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-shipping-fast"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Shipped</span>
                                    <span class="info-box-number">
                                        {{ $orders->where('status', 'shipped')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Completed</span>
                                    <span class="info-box-number">
                                        {{ $orders->where('status', 'completed')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($orders->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .info-box {
        margin-bottom: 0;
        box-shadow: 0 0 1px rgba(0,0,0,.125);
        border-radius: 0.25rem;
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
    .dropdown-menu {
        min-width: 200px;
        padding: 10px;
    }
    /* Pagination Styling */
    .card-footer .pagination {
        margin: 0;
        display: flex;
        list-style: none;
        padding-left: 0;
        gap: 3px;
    }
    .card-footer .pagination .page-item .page-link {
        color: #495057;
        border: 1px solid #dee2e6;
        border-radius: 0;
        padding: 0.25rem 0.6rem;
        background-color: #fff;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
        min-width: 32px;
        text-align: center;
    }
    .card-footer .pagination .page-item:first-child .page-link,
    .card-footer .pagination .page-item:last-child .page-link {
        border-radius: 0.25rem;
    }
    .card-footer .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: #fff;
        z-index: 3;
    }
    .card-footer .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    .card-footer .pagination .page-link:hover:not(.disabled):not(.active) {
        background-color: #e9ecef;
        color: #007bff;
        border-color: #dee2e6;
    }
    .card-footer .pagination .page-link:focus {
        box-shadow: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit status update forms
    $(document).ready(function() {
        $('select[name="status"]').on('change', function() {
            if(confirm('Update order status?')) {
                $(this).closest('form').submit();
            } else {
                $(this).val($(this).data('current'));
            }
        });
    });
</script>
@endpush