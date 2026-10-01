@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Categories</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Category
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td><strong>#{{ $category->id }}</strong></td>
                                        <td>
                                            @if($category->image)
                                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                                            @else
                                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #aaa;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->parent)
                                                <br><span class="badge badge-info">↳ {{ $category->parent->name }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @php
                                                $products = \App\Models\Product::where('category_id', $category->id)->get();
                                                $productCount = $products->count();
                                            @endphp
                                            
                                            @if($productCount > 0)
                                                <div class="category-products">
                                                    <span class="badge badge-primary mb-2">{{ $productCount }} products</span>
                                                    <div class="product-list">
                                                        @foreach($products as $product)
                                                            <div class="product-item d-flex align-items-center mb-2 p-2 bg-light rounded">
                                                                @if($product->image)
                                                                    <img src="{{ asset('products-images/' . $product->image) }}" 
                                                                         alt="{{ $product->name }}" 
                                                                         class="mr-2 rounded" 
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <div class="mr-2 bg-white rounded d-flex align-items-center justify-content-center" 
                                                                         style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-box text-muted" style="font-size: 14px;"></i>
                                                                    </div>
                                                                @endif
                                                                <div style="flex: 1;">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span style="font-size: 13px;">{{ Str::limit($product->name, 25) }}</span>
                                                                        <span class="text-success" style="font-size: 12px; font-weight: bold;">
                                                                            ৳{{ number_format($product->price, 2) }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between" style="font-size: 11px;">
                                                                        <span class="text-muted">Stock: {{ $product->stock }}</span>
                                                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-primary">
                                                                            <i class="fas fa-edit"></i> Edit
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-secondary">0 products</span>
                                                <p class="text-muted mb-0" style="font-size: 12px;">No products in this category</p>
                                            @endif
                                        </td>
                                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.menus.edit', $category) }}" class="btn btn-sm btn-info mb-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This will set category_id to null for all products in this category.')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                @if($categories->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-tags fa-3x mb-3"></i>
                                                <h4>No categories found</h4>
                                                <p>Add your first category to get started</p>
                                                <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add First Category
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-products {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .category-products::-webkit-scrollbar {
        width: 4px;
    }
    
    .category-products::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .category-products::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .product-item {
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }
    
    .product-item:hover {
        background-color: #f0f9ff;
        border-color: #bae6fd;
    }
    
    .table td {
        vertical-align: top;
    }
</style>
@endsection