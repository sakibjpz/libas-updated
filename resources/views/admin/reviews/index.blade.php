@extends('admin.layouts.app')

@section('title', 'Product Reviews')
@section('page-title', 'Product Reviews')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Customer Reviews</h3>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th style="width:150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>
                                @if($review->product)
                                    <a href="{{ route('products.show', $review->product->id) }}" target="_blank">{{ Str::limit($review->product->name, 35) }}</a>
                                @else
                                    <span class="text-muted">Deleted product</span>
                                @endif
                            </td>
                            <td>
                                {{ $review->customer_name ?? ($review->user->name ?? 'Customer') }}
                                @if($review->customer_email)<br><small class="text-muted">{{ $review->customer_email }}</small>@endif
                            </td>
                            <td><span class="text-warning">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span></td>
                            <td>{{ Str::limit($review->review, 80) }}</td>
                            <td>
                                @if($review->approved)
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" class="d-inline">
                                        @csrf
                                        <button class="btn {{ $review->approved ? 'btn-secondary' : 'btn-success' }}" title="{{ $review->approved ? 'Hide' : 'Approve' }}">
                                            <i class="fas {{ $review->approved ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No reviews yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reviews->hasPages())
        <div class="card-footer">{{ $reviews->links() }}</div>
    @endif
</div>
@endsection
