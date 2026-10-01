<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; padding: 20px; color: #000; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: 0 auto; padding: 30px; border: 1px solid #eee; }
        .invoice-header { border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .invoice-title { font-size: 28px; font-weight: bold; }
        .section-title { font-weight: bold; margin-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .info-block { width: 48%; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
        .text-right { text-align: right; }
        .total-table { width: 300px; margin-left: auto; margin-top: 20px; }
        .total-table td { border: none; padding: 5px; }
        .total-table tr:last-child td { border-top: 2px solid #000; font-weight: bold; font-size: 16px; }
        .no-print { margin-bottom: 20px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .invoice-box { border: none; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="text-center no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>

    <div class="invoice-box">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <div class="invoice-title">Libas</div>
                <small>www.libasbd.com</small>
            </div>
            <div class="text-end">
                <div class="h5 mb-0">INVOICE</div>
                <div>Order #{{ $order->id }}</div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-block">
                <div class="section-title">Billed To</div>
                <div>{{ $order->customer_name }}</div>
                @if($order->customer_phone)
                    <div>Phone: {{ $order->customer_phone }}</div>
                @endif
                @if($order->customer_email)
                    <div>Email: {{ $order->customer_email }}</div>
                @endif
            </div>
            <div class="info-block text-end">
                <div class="section-title">Order Details</div>
                <div>Date: {{ $order->created_at->format('F d, Y h:i A') }}</div>
                <div>Status: {{ ucfirst($order->status) }}</div>
                @if($order->delivery_area)
                    <div>Delivery: {{ $order->delivery_area === 'outside_dhaka' ? 'Outside Dhaka' : 'Inside Dhaka' }}</div>
                @endif
            </div>
        </div>

        <div class="section-title">Shipping Address</div>
        <p>{{ $order->shipping_address ?? 'N/A' }}</p>

        <div class="section-title mt-4">Order Items</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th>Product</th>
                    <th style="width: 15%">Size</th>
                    <th style="width: 15%">Color</th>
                    <th style="width: 12%" class="text-right">Price</th>
                    <th style="width: 10%" class="text-right">Qty</th>
                    <th style="width: 15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                @endphp
                @forelse($order->orderItems as $item)
                    @php
                        $itemTotal = (float) $item->price * (int) $item->quantity;
                        $subtotal += $itemTotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? 'Product Not Found' }}</td>
                        <td>{{ $item->size->name ?? '-' }}</td>
                        <td>{{ $item->color->name ?? '-' }}</td>
                        <td class="text-right">৳{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">৳{{ number_format($itemTotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @php
            $shipping = (float) ($order->shipping_cost ?? 0);
            $discount = max(0, $subtotal + $shipping - (float) $order->total);
        @endphp

        <table class="total-table">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">৳{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td class="text-right">৳{{ number_format($shipping, 2) }}</td>
            </tr>
            @if($discount > 0)
                <tr>
                    <td>Discount</td>
                    <td class="text-right">-৳{{ number_format($discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td>Grand Total</td>
                <td class="text-right">৳{{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div class="mt-5 text-center text-muted">
            <small>Thank you for shopping with Libas!</small>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
