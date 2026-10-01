<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SteadfastService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SteadfastController extends Controller
{
    protected $steadfast;

    public function __construct(SteadfastService $steadfast)
    {
        $this->steadfast = $steadfast;
    }

    /**
     * Show Steadfast dashboard with balance
     */
    public function index()
    {
        $balance = $this->steadfast->getBalance();
        
        return view('admin.steadfast.index', compact('balance'));
    }

    /**
     * Send order to Steadfast with improved validation
     */
    public function sendOrder(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            // Check if already sent
            if ($order->is_sent_to_steadfast) {
                return redirect()->back()->with('error', 'Order #' . $order->id . ' already sent to Steadfast with Tracking: ' . ($order->steadfast_tracking_code ?? 'N/A'));
            }
            
            // Validate required fields
            $validator = $this->validateOrderForSteadfast($order);
            
            if (!$validator['valid']) {
                return redirect()->back()
                    ->with('error', $validator['message'])
                    ->with('validation_errors', $validator['errors']);
            }
            
            // Prepare data for Steadfast
            $steadfastData = $this->prepareSteadfastOrderData($order);
            
            // Send to Steadfast
            $result = $this->steadfast->createOrder($steadfastData);
            
            if ($result['success']) {
                // Update order with Steadfast data
                $order->update($this->prepareOrderUpdateData($result, $steadfastData));
                
                $trackingCode = $result['data']['consignment']['tracking_code'] ?? 'N/A';
                
                return redirect()->back()->with('success', 
                    "Order #{$order->id} sent to Steadfast successfully! Tracking Code: {$trackingCode}"
                );
            } else {
                // Log the full error for debugging
                Log::error('Steadfast API Error for Order #' . $order->id, [
                    'request_data' => $steadfastData,
                    'response' => $result
                ]);
                
                $errorMessage = $result['message'] ?? 'Unknown error';
                if (isset($result['error'])) {
                    $errorMessage = is_string($result['error']) ? $result['error'] : json_encode($result['error']);
                }
                
                return redirect()->back()->with('error', 
                    "Failed to send order #{$order->id} to Steadfast: {$errorMessage}"
                );
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Order not found: ' . $orderId);
            return redirect()->back()->with('error', 'Order not found with ID: ' . $orderId);
        } catch (\Exception $e) {
            Log::error('Exception in sendOrder for Order #' . $orderId, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 
                'System error while processing order: ' . $e->getMessage()
            );
        }
    }

    /**
     * Validate order before sending to Steadfast
     */
    private function validateOrderForSteadfast($order): array
    {
        $errors = [];
        
        // Check phone number
        if (!$order->customer_phone) {
            $errors[] = 'Customer phone number is required';
        } else {
            $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
            if (strlen($phone) !== 11) {
                $errors[] = "Phone number must be 11 digits. Current: {$order->customer_phone}";
            }
            if (!preg_match('/^01[3-9]\d{8}$/', $phone)) {
                $errors[] = "Phone number must be a valid Bangladeshi number (01XXXXXXXXX)";
            }
        }
        
        // Check customer name
        if (!$order->customer_name || strlen(trim($order->customer_name)) < 3) {
            $errors[] = 'Customer name is required and must be at least 3 characters';
        }
        
        // Check address
        if (!$order->shipping_address || strlen(trim($order->shipping_address)) < 10) {
            $errors[] = 'Shipping address is required and must be at least 10 characters';
        }
        
        // Check order total
        if (!$order->total || $order->total <= 0) {
            $errors[] = 'Order total must be greater than 0';
        }
        
        if ($order->total > 500000) {
            $errors[] = 'Order total exceeds maximum limit of 500,000 BDT';
        }
        
        if (empty($errors)) {
            return ['valid' => true, 'message' => 'Order is valid'];
        }
        
        return [
            'valid' => false, 
            'message' => 'Order validation failed. Please fix the following errors:', 
            'errors' => $errors
        ];
    }

    /**
     * Prepare order data for Steadfast API
     */
    private function prepareSteadfastOrderData($order): array
    {
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        
        $data = [
            'invoice' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'recipient_name' => trim($order->customer_name),
            'recipient_phone' => $phone,
            'recipient_address' => trim($order->shipping_address),
            'cod_amount' => (float) $order->total,
            'note' => "Order #{$order->id}" . ($order->customer_email ? " - Email: {$order->customer_email}" : ""),
        ];
        
        // Add item description if order has items
        if ($order->items && is_array($order->items) && count($order->items) > 0) {
            $itemNames = array_map(function($item) {
                return $item['name'] ?? $item['product_name'] ?? 'Product';
            }, array_slice($order->items, 0, 3));
            
            $itemCount = count($order->items);
            $description = implode(', ', $itemNames);
            if ($itemCount > 3) {
                $description .= " and " . ($itemCount - 3) . " more items";
            }
            
            $data['item_description'] = $description;
        }
        
        return $data;
    }

    /**
     * Prepare order update data from Steadfast response
     */
    private function prepareOrderUpdateData($result, $requestData): array
    {
        $consignment = $result['data']['consignment'] ?? $result['data'] ?? [];
        
        return [
            'steadfast_consignment_id' => $consignment['consignment_id'] ?? null,
            'steadfast_tracking_code' => $consignment['tracking_code'] ?? null,
            'steadfast_status' => $consignment['status'] ?? 'pending',
            'steadfast_response' => $result['data'],
            'steadfast_sent_at' => now(),
            'status' => 'processing', // Update order status to processing
        ];
    }

    /**
     * Track order by various methods
     */
    public function trackOrder(Request $request)
    {
        $request->validate([
            'tracking_type' => 'required|in:consignment,invoice,tracking_code',
            'tracking_value' => 'required|string'
        ]);

        try {
            $trackingType = $request->tracking_type;
            $trackingValue = $request->tracking_value;
            
            $result = match($trackingType) {
                'consignment' => $this->steadfast->trackByConsignment($trackingValue),
                'invoice' => $this->steadfast->trackByInvoice($trackingValue),
                'tracking_code' => $this->steadfast->trackByTrackingCode($trackingValue),
                default => ['success' => false, 'message' => 'Invalid tracking type']
            };
            
            if ($result['success']) {
                // Try to find and update local order if exists
                $this->updateLocalOrderFromTracking($result['data'], $trackingValue);
                
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to track order: ' . ($result['message'] ?? 'Unknown error')
                ], 400);
            }
            
        } catch (\Exception $e) {
            Log::error('Exception in trackOrder: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error tracking order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update local order from tracking data
     */
    private function updateLocalOrderFromTracking($trackingData, $trackingValue)
    {
        try {
            $consignment = $trackingData['consignment'] ?? $trackingData;
            
            if (isset($consignment['consignment_id'])) {
                $order = Order::where('steadfast_consignment_id', $consignment['consignment_id'])
                    ->orWhere('steadfast_tracking_code', $consignment['tracking_code'] ?? '')
                    ->first();
                
                if ($order && isset($consignment['status'])) {
                    $order->update([
                        'steadfast_status' => $consignment['status'],
                        'steadfast_response' => $trackingData,
                        'status' => $this->mapSteadfastStatusToOrderStatus($consignment['status'])
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update local order from tracking: ' . $e->getMessage());
        }
    }

    /**
     * Map Steadfast status to local order status
     */
    private function mapSteadfastStatusToOrderStatus($steadfastStatus): string
    {
        return match(strtolower($steadfastStatus)) {
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'returned' => 'returned',
            'pending', 'in_review', 'approved' => 'processing',
            default => 'processing'
        };
    }

    /**
     * Check balance
     */
    public function checkBalance()
    {
        $result = $this->steadfast->getBalance();
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'balance' => $result['data']['current_balance'] ?? 0,
                'data' => $result['data']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch balance',
                'error' => $result['error'] ?? null
            ], 400);
        }
    }

    /**
     * List all returns
     */
    public function returns()
    {
        $result = $this->steadfast->getReturnRequests();
        
        if ($result['success']) {
            $returns = $result['data']['data'] ?? $result['data'] ?? [];
            return view('admin.steadfast.returns', compact('returns'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch returns: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * View single return
     */
    public function showReturn($id)
    {
        $result = $this->steadfast->getReturnRequest($id);
        
        if ($result['success']) {
            $return = $result['data'] ?? [];
            return view('admin.steadfast.show-return', compact('return'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch return details: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * List payments
     */
    public function payments()
    {
        $result = $this->steadfast->getPayments();
        
        if ($result['success']) {
            $payments = $result['data']['data'] ?? $result['data'] ?? [];
            return view('admin.steadfast.payments', compact('payments'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch payments: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * View single payment
     */
    public function showPayment($id)
    {
        $result = $this->steadfast->getPayment($id);
        
        if ($result['success']) {
            $payment = $result['data'] ?? [];
            return view('admin.steadfast.show-payment', compact('payment'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch payment details: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * Get police stations
     */
    public function policeStations()
    {
        $result = $this->steadfast->getPoliceStations();
        
        if ($result['success']) {
            $stations = $result['data']['data'] ?? $result['data'] ?? [];
            return view('admin.steadfast.police-stations', compact('stations'));
        } else {
            return redirect()->back()->with('error', 'Failed to fetch police stations: ' . ($result['message'] ?? 'Unknown error'));
        }
    }

    /**
     * Bulk send orders to Steadfast
     */
    public function bulkSendOrders(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $results = [
            'success' => [],
            'failed' => []
        ];

        foreach ($request->order_ids as $orderId) {
            try {
                $order = Order::find($orderId);
                
                if ($order->is_sent_to_steadfast) {
                    $results['failed'][] = [
                        'id' => $orderId,
                        'reason' => 'Already sent to Steadfast'
                    ];
                    continue;
                }

                $validator = $this->validateOrderForSteadfast($order);
                
                if (!$validator['valid']) {
                    $results['failed'][] = [
                        'id' => $orderId,
                        'reason' => 'Validation failed: ' . implode(', ', $validator['errors'])
                    ];
                    continue;
                }

                $steadfastData = $this->prepareSteadfastOrderData($order);
                $result = $this->steadfast->createOrder($steadfastData);

                if ($result['success']) {
                    $order->update($this->prepareOrderUpdateData($result, $steadfastData));
                    $results['success'][] = $orderId;
                } else {
                    $results['failed'][] = [
                        'id' => $orderId,
                        'reason' => $result['message'] ?? 'API Error'
                    ];
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $orderId,
                    'reason' => 'Exception: ' . $e->getMessage()
                ];
            }
        }

        $message = count($results['success']) . ' orders sent successfully. ' . 
                  count($results['failed']) . ' orders failed.';

        return redirect()->back()->with('bulk_results', $results)->with('info', $message);
    }
}