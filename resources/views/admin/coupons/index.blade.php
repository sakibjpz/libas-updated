@extends('layouts.app')

@section('title', 'Coupons - libasbd')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Coupons</h2>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Coupon
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($coupons->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                        <th>Min Order</th>
                                        <th>Max Uses</th>
                                        <th>Uses</th>
                                        <th>Valid From</th>
                                        <th>Valid Until</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                    <tr>
                                        <td><strong>{{ strtoupper($coupon->code) }}</strong></td>
                                        <td>{{ ucfirst($coupon->type) }}</td>
                                        <td>{{ $coupon->type == 'percentage' ? $coupon->value . '%' : '৳' . number_format($coupon->value, 2) }}</td>
                                        <td>{{ $coupon->min_order_amount ? '৳' . number_format($coupon->min_order_amount, 2) : 'N/A' }}</td>
                                        <td>{{ $coupon->max_uses ?? 'Unlimited' }}</td>
                                        <td>{{ $coupon->uses }}</td>
                                        <td>{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : 'N/A' }}</td>
                                        <td>{{ $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            @if($coupon->active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-info">Edit</a>
                                            <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">{{ $coupon->active ? 'Deactivate' : 'Activate' }}</button>
                                            </form>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="DELETE" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this coupon?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $coupons->links() }}
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No coupons found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
