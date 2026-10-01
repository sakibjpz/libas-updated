@extends('admin.layouts.app')

@section('title', 'Product Management')
@section('page_title', 'Products')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product List</h3>
                    <div class="card-tools d-flex align-items-center">
                        <form method="GET" action="{{ route('admin.products.index') }}" class="input-group input-group-sm mr-2" style="width: 260px;">
                            <input type="text" name="search" class="form-control" placeholder="Search name, slug, brand, category..." value="{{ $search ?? request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" title="Search"><i class="fas fa-search"></i></button>
                            </div>
                            @if(request('search'))
                                <div class="input-group-append">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" title="Clear"><i class="fas fa-times"></i></a>
                                </div>
                            @endif
                        </form>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Add New Product
                        </a>
                    </div>
                </div>
                
                @if(request('search'))
                    <div class="px-3 pt-2">
                        <span class="text-muted">Search results for "<strong>{{ request('search') }}</strong>" — {{ $products->total() }} found</span>
                    </div>
                @endif
                
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th style="width: 15%">Image</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('products-images/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-width: 80px; max-height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 80px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->brand)
                                            <br><small class="text-muted">{{ $product->brand }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $product->category_name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td>
                                        <strong>৳{{ number_format($product->price) }}</strong>
                                        @if($product->original_price && $product->original_price > $product->price)
                                            <br>
                                            <del class="text-muted small">৳{{ number_format($product->original_price) }}</del>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="stock-editor" data-product-id="{{ $product->id }}">
                                            <div class="current-stock-display">
                                                @if($product->stock > 10)
                                                    <span class="badge badge-success stock-badge">{{ $product->stock }} in stock</span>
                                                @elseif($product->stock > 0)
                                                    <span class="badge badge-warning stock-badge">Low stock ({{ $product->stock }})</span>
                                                @else
                                                    <span class="badge badge-danger stock-badge">Out of stock</span>
                                                @endif
                                                <button class="btn btn-xs btn-outline-secondary ml-1 edit-stock-btn" 
                                                        title="Edit stock">
                                                    <i class="fas fa-edit fa-xs"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="stock-edit-form d-none">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                           class="form-control stock-input" 
                                                           value="{{ $product->stock }}" 
                                                           min="0" 
                                                           style="width: 80px;">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-success btn-sm save-stock-btn" type="button">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm cancel-stock-btn" type="button">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->discount)
                                            <span class="badge badge-danger">{{ $product->discount }}% OFF</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge status-badge 
                                            @php
                                                if($product->stock <= 0) {
                                                    echo 'badge-secondary out_of_stock';
                                                } elseif($product->stock < 5) {
                                                    echo 'badge-warning low_stock';
                                                } else {
                                                    echo 'badge-success active';
                                                }
                                            @endphp">
                                            @php
                                                if($product->stock <= 0) {
                                                    echo '<i class="fas fa-times-circle"></i> Out of Stock';
                                                } elseif($product->stock < 5) {
                                                    echo '<i class="fas fa-exclamation-triangle"></i> Low Stock';
                                                } else {
                                                    echo '<i class="fas fa-check-circle"></i> Active';
                                                }
                                            @endphp
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="btn btn-info" 
                                               title="View" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('landing.show', $product) }}" 
                                               class="btn btn-warning" 
                                               title="View Landing Page ({{ \App\Http\Controllers\ProductLandingController::THEMES[$product->landing_theme ?: 'gold'] ?? 'Royal Gold' }})" 
                                               target="_blank">
                                                <i class="fas fa-rocket"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="btn btn-primary" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-box-open fa-2x mb-2"></i>
                                            <h5>No products found</h5>
                                            <p>Get started by adding your first product</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Add First Product
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($products->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    @endif
                </div>
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
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    .action-buttons .btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px !important;
    }
    .action-buttons form {
        display: inline-block;
    }
    
    /* Stock Editor Styles */
    .stock-editor {
        min-width: 120px;
    }
    
    .current-stock-display {
        display: flex;
        align-items: center;
    }
    
    .edit-stock-btn {
        padding: 1px 5px;
        font-size: 10px;
        line-height: 1.2;
        border-radius: 3px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    
    .edit-stock-btn:hover {
        opacity: 1;
    }
    
    .stock-input {
        text-align: center;
    }
    
    .input-group-append .btn {
        padding: 0.25rem 0.5rem;
    }
    
    /* Status badge for easier JS targeting */
    .status-badge {
        cursor: default;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Show edit form
    $(document).on('click', '.edit-stock-btn', function() {
        var $editor = $(this).closest('.stock-editor');
        $editor.find('.current-stock-display').addClass('d-none');
        $editor.find('.stock-edit-form').removeClass('d-none');
        $editor.find('.stock-input').focus().select();
    });

    // Cancel editing
    $(document).on('click', '.cancel-stock-btn', function() {
        var $editor = $(this).closest('.stock-editor');
        $editor.find('.stock-edit-form').addClass('d-none');
        $editor.find('.current-stock-display').removeClass('d-none');
    });

    // Save stock
    $(document).on('click', '.save-stock-btn', function() {
        var $editor = $(this).closest('.stock-editor');
        var productId = $editor.data('product-id');
        var newStock = $editor.find('.stock-input').val();
        
        // Validate
        if (newStock === '' || newStock < 0) {
            alert('Please enter a valid stock quantity (minimum 0)');
            return;
        }

        // Show loading
        var $saveBtn = $(this);
        var originalHtml = $saveBtn.html();
        $saveBtn.html('<i class="fas fa-spinner fa-spin"></i>');
        $saveBtn.prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: '/admin/products/' + productId + '/update-stock',
            method: 'POST',
            data: {
                stock: newStock,
                _method: 'PATCH'
            },
            success: function(response) {
                if (response.success) {
                    // Update the badge
                    var $badge = $editor.find('.stock-badge');
                    var stockNum = parseInt(newStock);
                    
                    // Remove existing badge classes
                    $badge.removeClass('badge-success badge-warning badge-danger');
                    
                    // Add new badge class and text
                    if (stockNum > 10) {
                        $badge.addClass('badge-success').text(stockNum + ' in stock');
                    } else if (stockNum > 0) {
                        $badge.addClass('badge-warning').text('Low stock (' + stockNum + ')');
                    } else {
                        $badge.addClass('badge-danger').text('Out of stock');
                    }
                    
                    // Show success message
                    showAlert('Stock updated successfully!', 'success');
                } else {
                    showAlert('Failed to update stock: ' + response.message, 'danger');
                }
            },
            error: function(xhr) {
                var errorMessage = 'Failed to update stock. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, 'danger');
            },
            complete: function() {
                // Hide edit form and show display
                $editor.find('.stock-edit-form').addClass('d-none');
                $editor.find('.current-stock-display').removeClass('d-none');
                $saveBtn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Update stock on Enter key press
    $(document).on('keypress', '.stock-input', function(e) {
        if (e.which === 13) { // Enter key
            $(this).closest('.stock-edit-form').find('.save-stock-btn').click();
            e.preventDefault();
        }
    });

    // Show alert message
    function showAlert(message, type) {
        // Remove existing alerts
        $('.custom-alert').remove();
        
        // Create new alert
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show custom-alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                        message +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '</div>';
        
        $('body').append(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.custom-alert').alert('close');
        }, 5000);
    }
});
</script>
@endpush