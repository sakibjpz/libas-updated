@extends('admin.layouts.app')

@section('title', 'Edit Banner')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Banner</h3>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Banner Title *</label>
                                    <input type="text" 
                                           name="title" 
                                           id="title" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $banner->title) }}" 
                                           required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="link">Banner Link (Optional)</label>
                                    <input type="url" 
                                           name="link" 
                                           id="link" 
                                           class="form-control @error('link') is-invalid @enderror" 
                                           value="{{ old('link', $banner->link) }}" 
                                           placeholder="https://example.com">
                                    @error('link')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Where users should be redirected when clicking the banner
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order">Display Order</label>
                                            <input type="number" 
                                                   name="order" 
                                                   id="order" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   value="{{ old('order', $banner->order) }}" 
                                                   min="0">
                                            @error('order')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Lower numbers appear first
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch mt-4">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       id="is_active" 
                                                       name="is_active" 
                                                       value="1" 
                                                       {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">
                                                    Active Banner
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="image">Current Banner Image</label>
                                    <div class="mb-3">
                                        @if($banner->image_url)
                                            <img src="{{ $banner->image_url }}" 
                                                 alt="{{ $banner->title }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px; width: 100%; object-fit: cover;">
                                        @else
                                            <div class="text-center text-muted p-4 border rounded">
                                                No image uploaded
                                            </div>
                                        @endif
                                    </div>

                                    <label for="image">Update Image (Optional)</label>
                                    <div class="custom-file">
                                        <input type="file" 
                                               name="image" 
                                               id="image" 
                                               class="custom-file-input @error('image') is-invalid @enderror" 
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        <label class="custom-file-label" for="image">Choose new image...</label>
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Leave empty to keep current image. Recommended size: 1200x400px
                                    </small>
                                    
                                    <div class="mt-3">
                                        <img id="image-preview" 
                                             src="{{ $banner->image_url ?: asset('images/placeholder.png') }}" 
                                             alt="Banner Preview" 
                                             class="img-fluid rounded" 
                                             style="max-height: 200px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Banner
                            </button>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const fileLabel = input.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
        fileLabel.textContent = input.files[0].name;
    }
}

// Update file input label
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose new image...';
    e.target.nextElementSibling.textContent = fileName;
});
</script>
@endsection