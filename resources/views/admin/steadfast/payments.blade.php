@extends('admin.layouts.app')

@section('title', 'Payments - Steadfast')

@section('page_title', 'Steadfast Payments')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.steadfast.index') }}">Steadfast</a></li>
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payments from Steadfast</h3>
    </div>
    <div class="card-body">
        @if(count($payments) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment['payment_id'] ?? $payment['id'] ?? '-' }}</td>
                                <td>৳{{ number_format((float)($payment['amount'] ?? $payment['total'] ?? 0), 2) }}</td>
                                <td>{{ $payment['payment_date'] ?? $payment['created_at'] ?? '-' }}</td>
                                <td><span class="badge badge-success">{{ $payment['status'] ?? 'Paid' }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted mb-0">No payments found.</p>
        @endif
    </div>
</div>
@endsection
