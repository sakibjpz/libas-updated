<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
   // Show all orders
public function index(Request $request)
{
    $query = Order::query();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_email', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%");
    }

    // Change this line from get() to paginate()
    $orders = $query->latest()->paginate(15);

    return view('admin.orders.index', compact('orders'));
}

    // Show single order details
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,completed,cancelled'
    ]);
    
    $order->update(['status' => $request->status]);
    
    return back()->with('success', 'Order status updated successfully!');
}
}
