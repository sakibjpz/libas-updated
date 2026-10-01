@extends('admin.layouts.app')

@section('title', 'Manage Banners')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Banner Management</h3>
                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Banner
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banners as $banner)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($banner->image_url)
                                            <img src="{{ $banner->image_url }}" 
                                                 alt="{{ $banner->title }}" 
                                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $banner->title }}</td>
                                    <td>
                                        @if($banner->link)
                                            <a href="{{ $banner->link }}" target="_blank" class="text-primary">
                                                {{ Str::limit($banner->link, 30) }}
                                            </a>
                                        @else
                                            <span class="text-muted">No Link</span>
                                        @endif
                                    </td>
                                    <td>{{ $banner->order }}</td>
                                    <td>
                                        <span class="badge badge-{{ $banner->is_active ? 'success' : 'danger' }}">
                                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.banners.edit', $banner) }}" 
                                           class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No banners found. Add your first banner!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection