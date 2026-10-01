@extends('layouts.app')

@section('title', 'My Wishlist - libasbd')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Wishlist</h1>
        </div>
    </div>

    @if($wishlistItems->count() > 0)
        <div class="row">
            @foreach($wishlistItems as $wishlistItem)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($wishlistItem->product->image)
                        <img src="{{ $wishlistItem->product->image_url }}" alt="{{ $wishlistItem->product->name }}" class="card-img-top" style="height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                    @else
                        <div class="card-img-top bg-secondary" style="height: 200px;"></div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $wishlistItem->product->name }}</h5>
                        <p class="card-text">৳{{ number_format($wishlistItem->product->price, 2) }}</p>
                        <a href="{{ route('products.show', $wishlistItem->product) }}" class="btn btn-primary btn-sm">View Product</a>
                        <form action="{{ route('wishlist.remove', $wishlistItem->product->id) }}" method="DELETE" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Your wishlist is empty.
        </div>
    @endif
</div>
@endsection
