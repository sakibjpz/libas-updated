@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Menu Item</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Menus
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-items.store') }}" method="POST">
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
                            <label for="title">Menu Title *</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                   value="{{ old('title') }}"
                                   placeholder="e.g., Burkha, New Arrivals, Contact">
                            <small class="form-text text-muted">Displayed in the header navigation</small>
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Menu (make this a submenu)</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">— None (top-level menu) —</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Choose a parent to show this item inside its dropdown submenu</small>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Link to Category</label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">— No category link —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Dynamic link — automatically points to that category's products page</small>
                        </div>

                        <div class="form-group">
                            <label for="url">Custom URL <span class="text-muted">(overrides category link)</span></label>
                            <input type="text" name="url" id="url" class="form-control"
                                   value="{{ old('url') }}"
                                   placeholder="e.g., /products, /contact, https://...">
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug') }}"
                                   placeholder="Auto-generated from title if empty">
                            <small class="form-text text-muted">Internal identifier. If no category or URL is set, used as <code>/products?category=slug</code>.</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="order">Sort Order</label>
                                <input type="number" name="order" id="order" class="form-control" min="0"
                                       value="{{ old('order', 0) }}">
                                <small class="form-text text-muted">Lower numbers appear first</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Status *</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Menu
                            </button>
                            <a href="{{ route('admin.menu-items.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.value) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();
        }
    });
});
</script>
@endsection
