<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use App\Models\Color;
use App\Models\Size;
use App\Services\FraudDetectionService;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    private $fraudDetectionService;

    public function __construct(FraudDetectionService $fraudDetectionService)
    {
        $this->fraudDetectionService = $fraudDetectionService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);
        
        // Get selected size and color
        $sizeId = $request->input('size_id');
        $colorId = $request->input('color_id');
        
        // Create a unique key for cart item (product_id + size_id + color_id)
        $cartKey = $id;
        $sizeName = null;
        $colorName = null;
        $colorHex = null;
        $sizePriceAdj = 0;
        $colorPriceAdj = 0;
        
        // Get size details if selected
        if ($sizeId) {
            $size = Size::find($sizeId);
            if ($size) {
                $sizeName = $size->name;
                // Get price adjustment for this specific product
                $pivotData = $size->products()->where('product_id', $id)->first();
                $sizePriceAdj = $pivotData ? ($pivotData->pivot->price_adjustment ?? 0) : 0;
                $cartKey .= '_size_' . $sizeId;
            }
        }
        
        // Get color details if selected
        if ($colorId) {
            $color = Color::find($colorId);
            if ($color) {
                $colorName = $color->name;
                $colorHex = $color->hex_code;
                // Get price adjustment for this specific product
                $pivotData = $color->products()->where('product_id', $id)->first();
                $colorPriceAdj = $pivotData ? ($pivotData->pivot->price_adjustment ?? 0) : 0;
                $cartKey .= '_color_' . $colorId;
            }
        }
        
        // Calculate final price with adjustments
        $finalPrice = $product->price + $sizePriceAdj + $colorPriceAdj;
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $finalPrice,
                'base_price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
                'size_id' => $sizeId,
                'size_name' => $sizeName,
                'color_id' => $colorId,
                'color_name' => $colorName,
                'color_hex' => $colorHex,
                'size_price_adj' => $sizePriceAdj,
                'color_price_adj' => $colorPriceAdj,
            ];
        }

        session()->put('cart', $cart);

        // calculate cart count
        $cartCount = array_sum(array_column($cart, 'quantity'));

        $eventId = \App\Services\MetaConversionsApi::eventId('atc');

        $response = [
            'success' => true,
            'cartCount' => $cartCount,
            'pixelEvent' => [
                'eventName' => 'AddToCart',
                'event_id' => $eventId,
                'data' => [
                    'content_name' => $product->name,
                    'content_category' => $product->categoryRelation ? $product->categoryRelation->name : 'General',
                    'content_ids' => [(string)$product->id],
                    'content_type' => 'product',
                    'value' => $finalPrice,
                    'currency' => 'BDT',
                    'quantity' => $quantity
                ]
            ]
        ];

        // Server-side AddToCart (Meta CAPI), deduped via shared event_id
        \App\Services\MetaConversionsApi::send('AddToCart', $response['pixelEvent']['data'], [], $eventId);

        if ($request->has('redirect_to_checkout')) {
            $response['redirect'] = route('cart.checkout');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($response);
        }

        if ($request->has('redirect_to_checkout')) {
            return redirect()->route('cart.checkout')->with('success', 'Added to cart');
        }

        return redirect()->route('cart.index')->with('success', 'Added to cart');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        $removed = false;
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            $removed = true;
        }
        
        // Calculate new cart count and subtotal
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // If AJAX request, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => $removed,
                'cartCount' => $cartCount,
                'subtotal' => $subtotal,
                'cartEmpty' => empty($cart),
                'message' => $removed ? 'Item removed successfully' : 'Item not found'
            ]);
        }
        
        // For non-AJAX requests (fallback)
        return back()->with('success', 'পণ্যটি কার্ট থেকে মুছে ফেলা হয়েছে!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'আপনার কার্ট খালি। কিছু যোগ করে আবার চেষ্টা করুন।');
        }

        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += (float)$item['price'] * (int)$item['quantity'];
        }

        $coupon = null;
        $discount = 0;
        $deliveryArea = session()->get('delivery_area');
        $shippingCost = session()->get('shipping_cost', 0);

        if ($request->has('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                ->active()
                ->first();

            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Server-side InitiateCheckout (Meta CAPI), deduped with browser event
        $icValue = $subtotal + $shippingCost - $discount;
        $pixelEventId = \App\Services\MetaConversionsApi::eventId('ic');
        $pixelInitiateData = [
            'content_ids' => array_values(array_map(fn ($item) => (string) $item['product_id'], $cart)),
            'contents' => array_values(array_map(fn ($item) => [
                'id' => (string) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'item_price' => (float) $item['price'],
            ], $cart)),
            'content_type' => 'product',
            'value' => $icValue,
            'currency' => 'BDT',
            'num_items' => array_sum(array_column($cart, 'quantity')),
        ];
        \App\Services\MetaConversionsApi::send('InitiateCheckout', $pixelInitiateData, [], $pixelEventId);

        return view('cart.checkout', compact('cart', 'subtotal', 'coupon', 'discount', 'deliveryArea', 'shippingCost', 'pixelEventId', 'pixelInitiateData'));
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('delivery_area');
        session()->forget('shipping_cost');
        return back()->with('success', 'কার্ট খালি করা হয়েছে!');
    }

    public function saveDeliveryArea(Request $request)
    {
        $request->validate([
            'delivery_area' => 'required|string|in:inside_dhaka,outside_dhaka',
        ]);

        $deliveryArea = $request->delivery_area;
        $shippingCost = $deliveryArea === 'inside_dhaka' ? 60 : 120;

        session()->put('delivery_area', $deliveryArea);
        session()->put('shipping_cost', $shippingCost);

        return response()->json([
            'success' => true,
            'shipping_cost' => $shippingCost
        ]);
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'আপনার কার্ট খালি।');
        }

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'coupon_code' => 'nullable|string',
            'delivery_area' => 'required|string|in:inside_dhaka,outside_dhaka',
        ]);

        // Calculate subtotal from cart
        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += (float)$item['price'] * (int)$item['quantity'];
        }

        // Shipping computed server-side from the posted area — never trust session or client totals
        $deliveryArea = $request->delivery_area;
        $shippingCost = $deliveryArea === 'inside_dhaka' ? 60.0 : 120.0;
        session()->put('delivery_area', $deliveryArea);
        session()->put('shipping_cost', $shippingCost);

        // Always compute total server-side — never trust the client-submitted total
        $grandTotal = $subtotal + $shippingCost;

        // Apply coupon if provided
        $appliedCoupon = null;
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                ->active()
                ->first();

            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
                if ($discount > 0) {
                    $grandTotal -= $discount;
                    $appliedCoupon = $coupon;
                    $coupon->increment('uses');
                }
            }
        }

        // Fraud detection check
        $orderData = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'total' => $grandTotal,
        ];

        $fraudResult = $this->fraudDetectionService->analyzeOrder($orderData, $request);

        if ($fraudResult['should_block']) {
            Log::warning('Order blocked due to fraud detection', [
                'ip' => $request->ip(),
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'score' => $fraudResult['suspicious_score'],
                'flags' => $fraudResult['flags'],
            ]);
            $this->fraudDetectionService->recordFailedOrder($request);
            return redirect()->back()->with('error', 'আপনার অর্ডারটি নিরাপত্তা যাচাইয়ের জন্য সাময়িকভাবে স্থগিত করা হয়েছে। দয়া করে পরে আবার চেষ্টা করুন অথবা আমাদের সাথে যোগাযোগ করুন।');
        }

        if ($fraudResult['is_suspicious']) {
            Log::warning('Suspicious order detected', [
                'ip' => $request->ip(),
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'score' => $fraudResult['suspicious_score'],
                'flags' => $fraudResult['flags'],
            ]);
            // Mark order for manual review
            $orderData['fraud_flag'] = true;
            $orderData['fraud_score'] = $fraudResult['suspicious_score'];
        }

        // Start database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Create the order with shipping cost and fraud detection data
            $order = Order::create(array_merge([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'fraud_flag' => $orderData['fraud_flag'] ?? false,
                'fraud_score' => $orderData['fraud_score'] ?? null,
                'fraud_flags' => $fraudResult['flags'] ?? null,
                'fraud_checked_at' => now(),
                'coupon_id' => $appliedCoupon ? $appliedCoupon->id : null,
                'items' => json_encode($cart), // Keep for backward compatibility
                'total' => $grandTotal,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
            ], \Illuminate\Support\Facades\Schema::hasColumn('orders', 'delivery_area')
                ? ['delivery_area' => $deliveryArea] : []));
            
            // Loop through cart items and create order items & deduct stock
            foreach ($cart as $cartKey => $item) {
                // Create order item
                $orderItem = $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'size_id' => $item['size_id'] ?? null,
                    'color_id' => $item['color_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                
                // Deduct stock from product
                $product = Product::find($item['product_id']);
                if ($product) {
                    // Check if sufficient stock exists
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}");
                    }
                    $product->decrement('stock', $item['quantity']);
                }
                
                // Deduct stock from product_size pivot if size exists
                if (isset($item['size_id']) && $item['size_id']) {
                    $productSize = DB::table('product_size')
                        ->where('product_id', $item['product_id'])
                        ->where('size_id', $item['size_id'])
                        ->first();

                    if ($productSize) {
                        // Check if sufficient stock exists
                        if ($productSize->stock < $item['quantity']) {
                            throw new \Exception("Insufficient stock for size variant. Available: {$productSize->stock}, Requested: {$item['quantity']}");
                        }
                        $newStock = $productSize->stock - $item['quantity'];
                        DB::table('product_size')
                            ->where('product_id', $item['product_id'])
                            ->where('size_id', $item['size_id'])
                            ->update(['stock' => $newStock]);
                    }
                }
                
                // Deduct stock from product_color pivot if color exists
                if (isset($item['color_id']) && $item['color_id']) {
                    $productColor = DB::table('product_color')
                        ->where('product_id', $item['product_id'])
                        ->where('color_id', $item['color_id'])
                        ->first();

                    if ($productColor) {
                        // Check if sufficient stock exists
                        if ($productColor->stock < $item['quantity']) {
                            throw new \Exception("Insufficient stock for color variant. Available: {$productColor->stock}, Requested: {$item['quantity']}");
                        }
                        $newStock = $productColor->stock - $item['quantity'];
                        DB::table('product_color')
                            ->where('product_id', $item['product_id'])
                            ->where('color_id', $item['color_id'])
                            ->update(['stock' => $newStock]);
                    }
                }
            }
            
            // Commit the transaction
            DB::commit();

            // Record successful order for fraud detection
            $this->fraudDetectionService->recordSuccessfulOrder($orderData, $request);

            // Order confirmation SMS to customer (Bangla/unicode) + alert SMS to admin
            try {
                \App\Services\SmsService::send(
                    $order->customer_phone,
                    "প্রিয় {$order->customer_name}, আপনার লিবাস অর্ডার #{$order->id} সফলভাবে গৃহীত হয়েছে। মোট মূল্য: " . number_format($grandTotal) . " টাকা (COD)। শীঘ্রই যোগাযোগ করা হবে। হটলাইন: 01333-257604"
                );
                if ($adminNumber = config('services.sms.admin_number')) {
                    \App\Services\SmsService::send(
                        $adminNumber,
                        "Libas New Order #{$order->id}: {$order->customer_name}, {$order->customer_phone}, Tk " . number_format($grandTotal) . ". Check admin panel."
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('Order SMS failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            }

            // Send order confirmation email if email is provided
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
                } catch (\Exception $e) {
                    Log::warning('Failed to send order confirmation email', [
                        'order_id' => $order->id,
                        'email' => $order->customer_email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Load the order with its orderItems, products, sizes, and colors
            $order->load('orderItems.product', 'orderItems.size', 'orderItems.color');

            // Prepare pixel event data for purchase
            $pixelPurchaseData = [
                'content_ids' => [],
                'content_name' => [],
                'content_category' => [],
                'content_type' => 'product',
                'value' => $grandTotal,
                'currency' => 'BDT',
                'num_items' => count($cart)
            ];

            foreach ($order->orderItems as $orderItem) {
                $pixelPurchaseData['content_ids'][] = (string)$orderItem->product_id;
                $pixelPurchaseData['content_name'][] = $orderItem->product->name;
                if ($orderItem->product->categoryRelation) {
                    $pixelPurchaseData['content_category'][] = $orderItem->product->categoryRelation->name;
                }
            }

            // Server-side Purchase (Meta CAPI) with hashed customer data for match quality
            $pixelEventId = \App\Services\MetaConversionsApi::eventId('pur');
            \App\Services\MetaConversionsApi::send('Purchase', [
                'value' => $grandTotal,
                'currency' => 'BDT',
                'content_ids' => $pixelPurchaseData['content_ids'],
                'content_type' => 'product',
                'num_items' => $pixelPurchaseData['num_items'],
            ], [
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'first_name' => $order->customer_name,
            ], $pixelEventId, url('/cart/place-order'));

            // Clear the cart and delivery info
            session()->forget('cart');
            session()->forget('delivery_area');
            session()->forget('shipping_cost');

            // Pass the full order object and pixel data to the confirmation page
            return view('cart.order-confirmation')
                ->with('order', $order)
                ->with('pixelPurchaseData', $pixelPurchaseData)
                ->with('pixelEventId', $pixelEventId);
            
        } catch (\Exception $e) {
            // Rollback the transaction if something went wrong
            DB::rollBack();
            
            // Log the error
            Log::error('Order placement failed: ' . $e->getMessage());
            
            // Return with error message
            return redirect()->back()->with('error', 'অর্ডারটি সম্পন্ন করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $quantity = max(1, (int)$request->quantity);
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);

            // Calculate new subtotal and cart total
            $itemSubtotal = $cart[$id]['price'] * $quantity;
            
            $cartTotal = 0;
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
            
            $cartCount = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'success' => true,
                'subtotal' => $itemSubtotal,
                'cartTotal' => $cartTotal,
                'cartCount' => $cartCount,
                'quantity' => $quantity
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ]);
    }
}
