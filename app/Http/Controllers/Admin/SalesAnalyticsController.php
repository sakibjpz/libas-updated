<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class SalesAnalyticsController extends Controller
{
    public function index()
    {
        // Sales statistics
        $totalSales = Order::sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Today's sales
        $todaySales = Order::whereDate('created_at', today())->sum('total');
        $todayOrders = Order::whereDate('created_at', today())->count();

        // This week's sales
        $weekSales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total');
        $weekOrders = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // This month's sales
        $monthSales = Order::whereMonth('created_at', now()->month)->sum('total');
        $monthOrders = Order::whereMonth('created_at', now()->month)->count();

        // Top selling products
        $topProducts = \DB::table('order_items')
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get()
            ->map(fn ($row) => (object) [
                'product'         => Product::find($row->product_id),
                'total_quantity'  => $row->total_quantity,
            ]);

        // Recent orders
        $recentOrders = Order::latest()->limit(10)->get();

        return view('admin.analytics.index', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'todaySales',
            'todayOrders',
            'weekSales',
            'weekOrders',
            'monthSales',
            'monthOrders',
            'topProducts',
            'recentOrders'
        ));
    }
}
