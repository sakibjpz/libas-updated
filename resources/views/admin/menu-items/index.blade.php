@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Header Navigation Menus</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Menu Title</th>
                                    <th>Slug</th>
                                    <th>Links To</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td><strong>{{ $menu->title }}</strong></td>
                                        <td>{{ $menu->slug }}</td>
                                        <td>
                                            @if($menu->children->count())
                                                <span class="badge badge-info">Dropdown</span>
                                            @else
                                                <code>{{ $menu->getUrl() }}</code>
                                            @endif
                                        </td>
                                        <td>{{ $menu->order }}</td>
                                        <td>
                                            <span class="badge badge-{{ $menu->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($menu->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.menu-items.edit', $menu) }}" class="btn btn-sm btn-info mb-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.menu-items.destroy', $menu) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this menu{{ $menu->children->count() ? ' and its submenus' : '' }}?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @foreach($menu->children as $sub)
                                        <tr class="submenu-row">
                                            <td class="submenu-title"><i class="fas fa-level-up-alt fa-rotate-90 text-muted mr-1"></i> {{ $sub->title }}</td>
                                            <td>{{ $sub->slug }}</td>
                                            <td><code>{{ $sub->getUrl() }}</code></td>
                                            <td>{{ $sub->order }}</td>
                                            <td>
                                                <span class="badge badge-{{ $sub->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($sub->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.menu-items.edit', $sub) }}" class="btn btn-sm btn-info mb-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.menu-items.destroy', $sub) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this submenu?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-bars fa-3x mb-3"></i>
                                                <h4>No menus found</h4>
                                                <p>Add your first menu item to get started</p>
                                                <a href="{{ route('admin.menu-items.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Add First Menu
                                                </a>
                                            </div>
                                        </td>
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

<style>
    .submenu-row { background: #fafbfc; }
    .submenu-title { padding-left: 30px !important; }
</style>
@endsection
