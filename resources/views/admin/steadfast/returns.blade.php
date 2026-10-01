@extends('admin.layouts.app')

@section('title', 'Return Requests - Steadfast')

@section('page_title', 'Return Requests')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Return Requests</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Return Requests from Steadfast</h3>
    </div>
    <div class="card-body">
        @if(count($returns) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                        <tr>
                            <td>{{ $return['id'] ?? 'N/A' }}</td>
                            <td>{{ $return['order_id'] ?? $return['consignment_id'] ?? 'N/A' }}</td>
                            <td>{{ $return['customer_name'] ?? $return['recipient_name'] ?? 'N/A' }}</td>
                            <td>{{ $return['reason'] ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $return['status'] == 'approved' ? 'success' : ($return['status'] == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ $return['status'] ?? 'pending' }}
                                </span>
                            </td>
                            <td>{{ isset($return['created_at']) ? date('d M Y', strtotime($return['created_at'])) : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('admin.steadfast.returns.show', $return['id'] ?? 0) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                No return requests found.
            </div>
        @endif
    </div>
</div>
@endsection