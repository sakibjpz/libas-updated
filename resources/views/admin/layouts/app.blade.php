<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel - E-Commerce')</title>
      <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logos/favicon1.png') }}">
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        document.addEventListener('error', function (e) {
            var t = e.target;
            if (t && t.tagName === 'IMG' && !t.dataset.imgFallback) {
                t.dataset.imgFallback = '1';
                t.src = '{{ asset('images/placeholder.png') }}';
            }
        }, true);

        // Fill missing input placeholders from their label (or humanized name)
        document.addEventListener('DOMContentLoaded', function () {
            var sel = 'input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=file]):not([type=submit]):not([type=button]):not([placeholder]), textarea:not([placeholder])';
            document.querySelectorAll(sel).forEach(function (el) {
                var label = el.id ? document.querySelector('label[for="' + el.id + '"]') : null;
                if (!label && el.parentElement) label = el.parentElement.querySelector('label');
                if (!label && el.closest('.form-group, .mb-3, .cp-field, .form-field')) label = el.closest('.form-group, .mb-3, .cp-field, .form-field').querySelector('label');
                if (label) {
                    el.placeholder = label.textContent.replace(/[*:]/g, '').trim();
                } else if (el.name) {
                    el.placeholder = 'Enter ' + el.name.replace(/[\[\]_-]+/g, ' ').trim();
                }
            });
        });
    </script>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" target="_blank" class="nav-link">
                    <i class="fas fa-external-link-alt"></i> View Store
                </a>
            </li>
        </ul>
        
        <!-- Right navbar links - FIXED with visible logout button -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link">
                    <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                </span>
            </li>
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" target="_blank" title="Visit Store">
                    <i class="fas fa-store"></i>
                </a>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link" style="border: none; background: none; color: inherit; text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
            <span class="brand-text font-weight-light">
                <i class="fas fa-crown"></i> Admin Panel
            </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x img-circle" style="color: #fff;"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                    <small class="text-success">
                        <i class="fas fa-circle"></i> {{ ucfirst(auth()->user()->role) }}
                    </small>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-header">MANAGEMENT</li>
                    
                    <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Products
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>All Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.products.create') }}" class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Orders
                                @if($pendingOrdersCount ?? 0 > 0)
                                <span class="badge badge-danger right">{{ $pendingOrdersCount }}</span>
                                @endif
                            </p>
                        </a>
                    </li>


<!-- Product Reviews -->
<li class="nav-item">
    <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-star"></i>
        <p>
            Reviews
            @php $pendingReviews = \App\Models\ProductReview::where('approved', false)->count(); @endphp
            @if($pendingReviews > 0)
                <span class="badge badge-warning right">{{ $pendingReviews }}</span>
            @endif
        </p>
    </a>
</li>

<!-- Contact Messages -->
<li class="nav-item">
    <a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-envelope"></i>
        <p>
            Contact Messages
            @php $unreadCount = \App\Models\ContactMessage::where('status', 'unread')->count(); @endphp
            @if($unreadCount > 0)
                <span class="badge badge-danger right">{{ $unreadCount }}</span>
            @endif
        </p>
    </a>
</li>

                    
                    <!-- Steadfast Delivery Management (Simplified) -->
                    <li class="nav-item {{ request()->routeIs('admin.steadfast.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.steadfast.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>
                                Steadfast Delivery
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.steadfast.index') }}" class="nav-link {{ request()->routeIs('admin.steadfast.index') ? 'active' : '' }}">
                                    <i class="fas fa-dashboard nav-icon"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index') }}" class="nav-link">
                                    <i class="fas fa-paper-plane nav-icon"></i>
                                    <p>Send Orders</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.steadfast.returns') }}" class="nav-link {{ request()->routeIs('admin.steadfast.returns') ? 'active' : '' }}">
                                    <i class="fas fa-undo-alt nav-icon"></i>
                                    <p>Return Requests</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.images.index') }}" class="nav-link {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-images"></i>
                            <p>Media Library</p>
                        </a>
                    </li> --}}
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Categories</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.menu-items.index') }}" class="nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bars"></i>
                            <p>Menus</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.fraud.index') }}" class="nav-link {{ request()->routeIs('admin.fraud.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>Fraud Detection</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('admin.banners.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-images"></i>
                            <p>
                                Banners
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.index') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>All Banners</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.banners.create') }}" class="nav-link {{ request()->routeIs('admin.banners.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus nav-icon"></i>
                                    <p>Add New</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            @yield('breadcrumbs')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('home') }}">Your Store</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>