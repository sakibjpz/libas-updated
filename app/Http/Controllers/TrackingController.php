<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\SteadfastService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Parcel tracking page - GET /track?code=XXXX
     * Accepts: Order ID, Tracking Code, Consignment ID, or Invoice
     */
    public function index(Request $request)
    {
        $result = null;   // courier (Steadfast) data
        $order  = null;   // local order data
        $error  = null;
        $query  = trim((string) $request->query('code', ''));

        if ($query !== '') {
            $service = app(SteadfastService::class);
            $clean = ltrim($query, '#');                    // allow "#123" order id
            $order = ctype_digit($clean) ? Order::find((int) $clean) : null;

            if ($order) {
                // Local order found → look up courier status via saved identifiers
                if ($order->steadfast_consignment_id) {
                    $res = $service->trackByConsignment((string) $order->steadfast_consignment_id);
                } elseif ($order->steadfast_tracking_code) {
                    $res = $service->trackByTrackingCode($order->steadfast_tracking_code);
                } else {
                    $res = null; // not sent to courier yet
                }

                if ($res && $res['success'] && !empty($res['data']['consignment_id'])) {
                    $result = $res['data'];
                }
                // Order info is shown regardless — no error when courier data missing
            } else {
                // Not a local order → Steadfast: numeric = consignment, 8-char alnum = tracking code, else invoice
                if (ctype_digit($clean)) {
                    $response = $service->trackByConsignment($clean);
                } elseif (strlen($clean) === 8 && ctype_alnum($clean)) {
                    $response = $service->trackByTrackingCode($clean);
                } else {
                    $response = $service->trackByInvoice($clean);
                }

                if ($response['success'] && !empty($response['data']['consignment_id'])) {
                    $result = $response['data'];
                } elseif ($response['success']) {
                    $error = 'এই Order ID / Tracking Code / Invoice নম্বরে কিছু পাওয়া যায়নি। সঠিক নম্বর দিন।';
                } else {
                    $error = 'এই মুহূর্তে tracking সার্ভিসে সংযোগ করা যাচ্ছে না। কিছুক্ষণ পর আবার চেষ্টা করুন।';
                }
            }
        }

        return view('track', compact('result', 'order', 'error', 'query'));
    }
}
