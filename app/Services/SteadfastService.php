<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SteadfastService
{
    protected $apiKey;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.steadfast.api_key');
        $this->secretKey = config('services.steadfast.secret_key');
        $this->baseUrl = config('services.steadfast.base_url', 'https://portal.packzy.com/api/v1');
    }

    /**
     * Make API request with proper headers
     */
    public function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = $this->baseUrl . $endpoint;
            
            Log::info('Steadfast Request: ' . $method . ' ' . $url);
            Log::info('Steadfast Headers:', [
                'Api-Key' => substr($this->apiKey, 0, 5) . '...',
                'Secret-Key' => substr($this->secretKey, 0, 5) . '...'
            ]);
            
            $headers = [
                'Api-Key' => $this->apiKey,
                'Secret-Key' => $this->secretKey,
                'Accept' => 'application/json',
            ];
            
            $http = Http::withHeaders($headers);
            
            if ($method === 'GET') {
                if (!empty($data)) {
                    $response = $http->get($url, $data);
                } else {
                    $response = $http->get($url);
                }
            } else {
                $response = $http->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, $data);
            }
            
            // Log response for debugging
            Log::info('Steadfast Response Status: ' . $response->status());
            Log::info('Steadfast Response Headers: ', $response->headers());
            Log::info('Steadfast Response Body: ' . $response->body());
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                // Detailed error logging
                Log::error('Steadfast Error Details:', [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'status' => $response->status(),
                    'message' => 'Failed to connect to Steadfast API',
                    'error' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Steadfast Exception: ' . $e->getMessage());
            Log::error('Steadfast Exception Trace: ' . $e->getTraceAsString());
            
            return [
                'success' => false,
                'message' => 'Failed to connect to Steadfast API',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    /**
     * Create a new order
     * Path: /create_order
     * Method: POST
     */
    public function createOrder(array $data)
    {
        $required = ['invoice', 'recipient_name', 'recipient_phone', 'recipient_address', 'cod_amount'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return [
                    'success' => false,
                    'message' => "Missing required field: {$field}"
                ];
            }
        }
        
        $payload = [
            'invoice' => $data['invoice'],
            'recipient_name' => $data['recipient_name'],
            'recipient_phone' => $data['recipient_phone'],
            'recipient_address' => $data['recipient_address'],
            'cod_amount' => (float) $data['cod_amount'],
        ];
        
        // Optional fields
        if (isset($data['alternative_phone'])) {
            $payload['alternative_phone'] = $data['alternative_phone'];
        }
        
        if (isset($data['recipient_email'])) {
            $payload['recipient_email'] = $data['recipient_email'];
        }
        
        if (isset($data['note'])) {
            $payload['note'] = $data['note'];
        }
        
        if (isset($data['item_description'])) {
            $payload['item_description'] = $data['item_description'];
        }
        
        if (isset($data['total_lot'])) {
            $payload['total_lot'] = (int) $data['total_lot'];
        }
        
        if (isset($data['delivery_type'])) {
            $payload['delivery_type'] = (int) $data['delivery_type'];
        }
        
        return $this->makeRequest('POST', '/create_order', $payload);
    }

    /**
     * Bulk order creation
     * Path: /create_order/bulk-order
     * Method: POST
     */
    public function createBulkOrders(array $orders)
    {
        if (count($orders) > 500) {
            return [
                'success' => false,
                'message' => 'Maximum 500 orders allowed per bulk request'
            ];
        }
        
        $data = [];
        foreach ($orders as $order) {
            $item = [
                'invoice' => $order['invoice'],
                'recipient_name' => $order['recipient_name'],
                'recipient_phone' => $order['recipient_phone'],
                'recipient_address' => $order['recipient_address'],
                'cod_amount' => (float) $order['cod_amount'],
            ];
            
            if (isset($order['note'])) {
                $item['note'] = $order['note'];
            }
            
            $data[] = $item;
        }
        
        return $this->makeRequest('POST', '/create_order/bulk-order', [
            'data' => json_encode($data)
        ]);
    }

    /**
     * Track order by consignment ID
     * Path: /status_by_cid/{id}
     * Method: GET
     */
    public function trackByConsignment(string $consignmentId)
    {
        return $this->makeRequest('GET', '/status_by_cid/' . $consignmentId);
    }

    /**
     * Track order by invoice ID
     * Path: /status_by_invoice/{invoice}
     * Method: GET
     */
    public function trackByInvoice(string $invoice)
    {
        return $this->makeRequest('GET', '/status_by_invoice/' . $invoice);
    }

    /**
     * Track order by tracking code
     * Path: /status_by_trackingcode/{trackingCode}
     * Method: GET
     */
    public function trackByTrackingCode(string $trackingCode)
    {
        return $this->makeRequest('GET', '/status_by_trackingcode/' . $trackingCode);
    }

    /**
     * Check current balance
     * Path: /get_balance
     * Method: GET
     */
    public function getBalance()
    {
        return $this->makeRequest('GET', '/get_balance');
    }

    /**
     * Create return request
     * Path: /create_return_request
     * Method: POST
     */
    public function createReturnRequest($identifier, ?string $reason = null)
    {
        $data = [];
        
        if (is_numeric($identifier)) {
            $data['consignment_id'] = $identifier;
        } elseif (strlen($identifier) === 8 && ctype_alnum($identifier)) {
            $data['tracking_code'] = $identifier;
        } else {
            $data['invoice'] = $identifier;
        }
        
        if ($reason) {
            $data['reason'] = $reason;
        }
        
        return $this->makeRequest('POST', '/create_return_request', $data);
    }

    /**
     * Get single return request
     * Path: /get_return_request/{id}
     * Method: GET
     */
    public function getReturnRequest(int $id)
    {
        return $this->makeRequest('GET', '/get_return_request/' . $id);
    }

    /**
     * Get all return requests
     * Path: /get_return_requests
     * Method: GET
     */
    public function getReturnRequests()
    {
        return $this->makeRequest('GET', '/get_return_requests');
    }

    /**
     * Get payments
     * Path: /payments
     * Method: GET
     */
    public function getPayments()
    {
        return $this->makeRequest('GET', '/payments');
    }

    /**
     * Get single payment with consignments
     * Path: /payments/{payment_id}
     * Method: GET
     */
    public function getPayment(int $paymentId)
    {
        return $this->makeRequest('GET', '/payments/' . $paymentId);
    }

    /**
     * Get police stations (delivery zones)
     * Path: /police_stations
     * Method: GET
     */
    public function getPoliceStations()
    {
        return $this->makeRequest('GET', '/police_stations');
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        $result = $this->getBalance();
        
        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'Successfully connected to Steadfast API',
                'balance' => $result['data']['current_balance'] ?? 'N/A',
                'data' => $result['data']
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to connect to Steadfast API',
                'error' => $result['error'] ?? 'Unknown error',
                'status' => $result['status'] ?? null
            ];
        }
    }
}