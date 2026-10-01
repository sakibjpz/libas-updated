@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-box"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Products</span>
                    <span class="info-box-number">
                        {{ $totalProducts }}
                        <small>items</small>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Orders</span>
                    <span class="info-box-number">{{ $totalOrders }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-dollar-sign"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Revenue</span>
                    <span class="info-box-number">৳{{ number_format($totalRevenue, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Avg. Order Value</span>
                    <span class="info-box-number">
                        ৳{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders & Revenue (Last 7 Days)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="ordersRevenueChart" height="250" style="height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Activity</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        <li class="item">
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    Dashboard Access
                                    <span class="badge badge-success float-right">Now</span>
                                </a>
                                <span class="product-description">
                                    Welcome back, {{ auth()->user()->name }}
                                </span>
                            </div>
                        </li>
                        <li class="item">
                            <div class="product-info">
                                <a href="{{ route('admin.products.index') }}" class="product-title">
                                    Product Management
                                </a>
                                <span class="product-description">
                                    {{ $totalProducts }} products in catalog
                                </span>
                            </div>
                        </li>
                        <li class="item">
                            <div class="product-info">
                                <a href="{{ route('admin.orders.index') }}" class="product-title">
                                    Order Management
                                </a>
                                <span class="product-description">
                                    {{ $totalOrders }} total orders
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.orders.index') }}" class="uppercase">View All Orders</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 col-6">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-app">
                                <i class="fas fa-plus"></i> Add Product
                            </a>
                        </div>
                        <div class="col-sm-3 col-6">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-app">
                                <i class="fas fa-shopping-cart"></i> View Orders
                            </a>
                        </div>
                        <div class="col-sm-3 col-6">
                            <a href="{{ route('admin.images.index') }}" class="btn btn-app">
                                <i class="fas fa-images"></i> Media Library
                            </a>
                        </div>
                        <div class="col-sm-3 col-6">
                            <a href="{{ route('home') }}" target="_blank" class="btn btn-app">
                                <i class="fas fa-external-link-alt"></i> Visit Store
                            </a>
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
    $(function () {
        'use strict';
        
        const last7Days = @json($last7Days);
        const dates = last7Days.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-US', { weekday: 'short' });
        });
        
        const ordersData = last7Days.map(d => d.orders);
        const revenueData = last7Days.map(d => d.revenue);
        
        // Orders & Revenue Combined Chart
        const salesChartCanvas = $('#ordersRevenueChart').get(0).getContext('2d');
        
        const salesChartData = {
            labels: dates,
            datasets: [
                {
                    label: 'Orders',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: 5,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: ordersData
                },
                {
                    label: 'Revenue (৳)',
                    backgroundColor: 'rgba(0,166,90,0.7)',
                    borderColor: 'rgba(0,166,90,1)',
                    pointRadius: 5,
                    pointColor: '#00a65a',
                    pointStrokeColor: 'rgba(0,166,90,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(0,166,90,1)',
                    data: revenueData,
                    type: 'line',
                    fill: false,
                    yAxisID: 'y1'
                }
            ]
        };
        
        const salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [
                    {
                        id: 'y',
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Orders'
                        }
                    },
                    {
                        id: 'y1',
                        type: 'linear',
                        position: 'right',
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return '৳' + value;
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Revenue'
                        },
                        gridLines: {
                            drawOnChartArea: false
                        }
                    }
                ]
            }
        };
        
        new Chart(salesChartCanvas, {
            type: 'bar',
            data: salesChartData,
            options: salesChartOptions
        });
    });
</script>
@endpush