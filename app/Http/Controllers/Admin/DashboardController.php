<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    // Total stats
    $totalProducts = Product::count();
    $totalOrders = Order::count();
    $totalRevenue = Order::sum('total');
    
    // Pending orders count
    $pendingOrdersCount = Order::where('status', 'pending')->count();
    
    // Last 7 days stats
    $last7Days = collect();
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i)->format('Y-m-d');
        
        $ordersCount = Order::whereDate('created_at', $date)->count();
        $revenue = Order::whereDate('created_at', $date)->sum('total');
        
        $last7Days->push([
            'date' => $date,
            'orders' => $ordersCount,
            'revenue' => $revenue ?: 0,
        ]);
    }
    
    return view('admin.dashboard', compact(
        'totalProducts',
        'totalOrders',
        'totalRevenue',
        'pendingOrdersCount',
        'last7Days'
    ));
}
}
