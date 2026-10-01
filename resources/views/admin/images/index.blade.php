@extends('admin.layouts.app')

@section('title', 'Media Library')
@section('page_title', 'Media Library')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Media Library</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Images</h3>
                </div>
                <div class="card-body">
                    <!-- Simple Upload Form -->
                    <form action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="image">Select Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <small class="text-muted">Max size: 2MB. Formats: JPEG, PNG, GIF, WebP</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </form>
                    
                    <!-- Uploaded Images -->
                    <div class="mt-5">
                        <h4>Uploaded Images</h4>
                        <div class="row mt-3">
                            @forelse($images as $image)
                            <div class="col-md-2 col-sm-4 col-6 mb-3">
                                <div class="card">
                                    <img src="{{ Storage::url($image) }}" 
                                         class="card-img-top" 
                                         alt="Image" 
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block">
                                            {{ basename($image) }}
                                        </small>
                                        <form action="{{ route('admin.images.delete') }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $image }}">
                                            <button type="submit" class="btn btn-danger btn-sm btn-block" 
                                                    onclick="return confirm('Delete this image?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No images uploaded yet.
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update file input label
    document.getElementById('image').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endpush