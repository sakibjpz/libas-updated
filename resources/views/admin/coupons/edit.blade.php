@extends('layouts.app')

@section('title', 'Edit Coupon - libasbd')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Coupon</h2>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Back to Coupons</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="code" name="code" required value="{{ strtoupper($coupon->code) }}">
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">Value</label>
                            <input type="number" class="form-control" id="value" name="value" required step="0.01" min="0" value="{{ $coupon->value }}">
                        </div>

                        <div class="mb-3">
                            <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                            <input type="number" class="form-control" id="min_order_amount" name="min_order_amount" step="0.01" min="0" value="{{ $coupon->min_order_amount }}">
                        </div>

                        <div class="mb-3">
                            <label for="max_uses" class="form-label">Max Uses</label>
                            <input type="number" class="form-control" id="max_uses" name="max_uses" min="1" value="{{ $coupon->max_uses }}">
                        </div>

                        <div class="mb-3">
                            <label for="valid_from" class="form-label">Valid From</label>
                            <input type="date" class="form-control" id="valid_from" name="valid_from" value="{{ $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '' }}">
                        </div>

                        <div class="mb-3">
                            <label for="valid_until" class="form-label">Valid Until</label>
                            <input type="date" class="form-control" id="valid_until" name="valid_until" value="{{ $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : '' }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Coupon</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
