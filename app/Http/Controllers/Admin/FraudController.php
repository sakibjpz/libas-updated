<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FraudController extends Controller
{
    protected $fraudDetectionService;

    public function __construct(FraudDetectionService $fraudDetectionService)
    {
        $this->fraudDetectionService = $fraudDetectionService;
    }

    /**
     * Display fraud detection dashboard
     */
    public function index()
    {
        $suspiciousOrders = Order::where('fraud_flag', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $recentOrders = Order::orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $fraudReport = $this->fraudDetectionService->getFraudReport();

        return view('admin.fraud.index', compact(
            'suspiciousOrders',
            'recentOrders',
            'fraudReport'
        ));
    }

    /**
     * Show fraud details for a specific order
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        return view('admin.fraud.show', compact('order'));
    }

    /**
     * Mark order as safe (clear fraud flag)
     */
    public function markAsSafe($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'fraud_flag' => false,
            'fraud_score' => null,
            'fraud_flags' => null,
        ]);

        Log::info('Order marked as safe', ['order_id' => $order->id]);

        return redirect()->back()->with('success', 'Order marked as safe successfully.');
    }

    /**
     * Mark order as fraudulent
     */
    public function markAsFraudulent(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'fraud_flag' => true,
            'fraud_score' => 100,
            'fraud_flags' => array_merge($order->fraud_flags ?? [], ['Manually marked as fraudulent: ' . $request->reason]),
            'status' => 'cancelled',
        ]);

        // Add to blacklist
        if ($order->customer_email) {
            $this->fraudDetectionService->addToBlacklist('email', $order->customer_email);
        }
        if ($order->customer_phone) {
            $this->fraudDetectionService->addToBlacklist('phone', $order->customer_phone);
        }

        Log::warning('Order marked as fraudulent', [
            'order_id' => $order->id,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Order marked as fraudulent and cancelled.');
    }

    /**
     * Add IP to blacklist
     */
    public function addToBlacklist(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ip,email,phone',
            'value' => 'required|string|max:255',
        ]);

        $this->fraudDetectionService->addToBlacklist($request->type, $request->value);

        Log::info('Added to blacklist', [
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return redirect()->back()->with('success', 'Added to blacklist successfully.');
    }

    /**
     * Remove from blacklist
     */
    public function removeFromBlacklist(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ip,email,phone',
            'value' => 'required|string|max:255',
        ]);

        $cacheKey = "blacklisted_{$request->type}s";
        $blacklist = cache()->get($cacheKey, []);
        $blacklist = array_diff($blacklist, [$request->value]);
        cache()->put($cacheKey, $blacklist, now()->addDays(30));

        Log::info('Removed from blacklist', [
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return redirect()->back()->with('success', 'Removed from blacklist successfully.');
    }

    /**
     * Get fraud statistics
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'fraud_flagged_orders' => Order::where('fraud_flag', true)->count(),
            'suspicious_ips' => count(cache()->get('suspicious_ips', [])),
            'blacklisted_ips' => count(cache()->get('blacklisted_ips', [])),
            'blacklisted_emails' => count(cache()->get('blacklisted_emails', [])),
            'blacklisted_phones' => count(cache()->get('blacklisted_phones', [])),
        ];

        return response()->json($stats);
    }
}
