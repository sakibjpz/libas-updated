@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Category</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
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
                                   value="{{ old('name') }}"
                                   placeholder="Enter category name (e.g., চা, কফি এবং পানীয়)">
                            <small class="form-text text-muted">This will be displayed on the website</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug *</label>
                            <input type="text" name="slug" id="slug" class="form-control" required 
                                   value="{{ old('slug') }}"
                                   placeholder="Enter URL slug (e.g., tea-coffee-drinks)">
                            <small class="form-text text-muted">
                                Unique identifier for URL. Use lowercase letters, numbers, and hyphens only.
                                <br>Example: "চা, কফি এবং পানীয়" becomes "tea-coffee-drinks"
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">— None (top-level category) —</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Leave empty for a main category, or pick a parent to make this a subcategory.</small>
                        </div>

                        <div class="form-group">
                            <label for="image">Category Image</label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 400x400px. Will be displayed on homepage.</small>
                        </div>

                        <div class="form-group">
                            <label for="description">SEO Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" maxlength="1000" placeholder="Used as the meta description on this category's product page">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">150-160 characters ideal for Google search results.</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" maxlength="500" value="{{ old('meta_keywords') }}" placeholder="comma, separated, keywords">
                            <small class="form-text text-muted">Comma-separated keywords for this category page.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Category
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
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value) {
            // Generate slug from name
            let slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-')     // Replace spaces with hyphens
                .replace(/--+/g, '-')     // Replace multiple hyphens with single
                .trim();
            
            // Handle Bengali to English conversion (basic)
            const bengaliMap = {
                'চা': 'tea', 'কফি': 'coffee', 'এবং': 'and', 'পানীয়': 'drinks',
                'শিশুর': 'baby', 'যত্ন': 'care', 'দুধ': 'milk', 'ডিম': 'eggs',
                'দৈনন্দিন': 'daily', 'চাহিদা': 'needs', 'মোবাইল': 'mobile', 'এক্সেসরিজ': 'accessories'
            };
            
            // Simple bengali word replacement
            let generatedSlug = slug;
            Object.keys(bengaliMap).forEach(bengali => {
                const regex = new RegExp(bengali, 'g');
                generatedSlug = generatedSlug.replace(regex, bengaliMap[bengali]);
            });
            
            slugInput.value = generatedSlug;
        }
    });
    
    // Validate slug format
    slugInput.addEventListener('blur', function() {
        const slug = this.value;
        const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
        
        if (slug && !slugRegex.test(slug)) {
            alert('Invalid slug format. Use only lowercase letters, numbers, and hyphens.');
            this.focus();
        }
    });
});
</script>
@endsection