@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Category: {{ $menu->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="name">Category Name *</label>
                            <input type="text" name="name" id="name" class="form-control" required 
                                   value="{{ old('name', $menu->name) }}"
                                   placeholder="Enter category name">
                            <small class="form-text text-muted">This will be displayed on the website</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug *</label>
                            <input type="text" name="slug" id="slug" class="form-control" required 
                                   value="{{ old('slug', $menu->slug) }}"
                                   placeholder="Enter URL slug">
                            <small class="form-text text-muted">
                                Unique identifier for URL. Use lowercase letters, numbers, and hyphens only.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">— None (top-level category) —</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Leave empty for a main category, or pick a parent to make this a subcategory.</small>
                        </div>

                        <!-- Current Category Image -->
                        @if($menu->image)
                        <div class="form-group text-center">
                            <label>Current Image</label>
                            <div class="mb-2">
                                <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" style="max-width: 200px; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                            <p class="text-muted small">Upload a new image below to replace this one.</p>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <label for="image">Category Image</label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 400x400px. Will be displayed on homepage.</small>
                        </div>

                        <div class="form-group">
                            <label for="description">SEO Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" maxlength="1000" placeholder="Used as the meta description on this category's product page">{{ old('description', $menu->description) }}</textarea>
                            <small class="form-text text-muted">150-160 characters ideal for Google search results.</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" maxlength="500" value="{{ old('meta_keywords', $menu->meta_keywords) }}" placeholder="comma, separated, keywords">
                            <small class="form-text text-muted">Comma-separated keywords for this category page.</small>
                        </div>
                        
                        <!-- Category Statistics -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Category Statistics</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $productCount = \App\Models\Product::where('category_id', $menu->id)->count();
                                    $products = \App\Models\Product::where('category_id', $menu->id)->latest()->take(5)->get();
                                @endphp
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-primary rounded-circle p-3 mr-3">
                                                <i class="fas fa-box text-white"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ $productCount }}</h4>
                                                <p class="text-muted mb-0">Total Products</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success rounded-circle p-3 mr-3">
                                                <i class="fas fa-calendar text-white"></i>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">{{ $menu->created_at->format('M d, Y') }}</h4>
                                                <p class="text-muted mb-0">Created Date</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($productCount > 0)
                                    <div class="mt-3">
                                        <h6>Recent Products in this Category:</h6>
                                        <div class="list-group">
                                            @foreach($products as $product)
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $product->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">৳{{ number_format($product->price, 2) }} | Stock: {{ $product->stock }}</small>
                                                    </div>
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            @endforeach
                                        </div>
                                        @if($productCount > 5)
                                            <div class="text-center mt-2">
                                                <a href="{{ route('admin.products.index') }}?category={{ $menu->id }}" class="btn btn-sm btn-outline-primary">
                                                    View All {{ $productCount }} Products
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Category
                            </button>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate slug format
    const slugInput = document.getElementById('slug');
    
    slugInput.addEventListener('blur', function() {
        const slug = this.value;
        const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
        
        if (slug && !slugRegex.test(slug)) {
            alert('Invalid slug format. Use only lowercase letters, numbers, and hyphens.');
            this.focus();
        }
    });
    
    // Warn if changing slug (might affect URLs)
    const originalSlug = '{{ $menu->slug }}';
    slugInput.addEventListener('change', function() {
        if (this.value !== originalSlug) {
            if (!confirm('Warning: Changing the slug will affect URLs. Existing links to this category may break. Continue?')) {
                this.value = originalSlug;
            }
        }
    });
});
</script>

<style>
    .rounded-circle {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .list-group-item {
        transition: all 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
</style>
@endsection