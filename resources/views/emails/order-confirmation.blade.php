<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-info p {
            margin: 5px 0;
        }
        .order-info strong {
            color: #d4b25f;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-item h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        .order-item p {
            margin: 3px 0;
            color: #666;
            font-size: 14px;
        }
        .total {
            background-color: #d4b25f;
            color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: right;
        }
        .total p {
            margin: 5px 0;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #d4b25f 0%, #8a6d2f 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .header {
                padding: 15px;
            }
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order!</p>
        </div>

        <div class="content">
            <p>Dear {{ $order->customer_name }},</p>
            <p>We have received your order and it's being processed. Here are your order details:</p>

            <div class="order-info">
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                @if($order->steadfast_tracking_code)
                <p><strong>Tracking Code:</strong> {{ $order->steadfast_tracking_code }}</p>
                @endif
            </div>

            <div class="order-items">
                <h3>Order Items</h3>
                @if($order->orderItems)
                    @foreach($order->orderItems as $item)
                        <div class="order-item">
                            <h4>{{ $item->product->name ?? 'Product' }}</h4>
                            <p>Quantity: {{ $item->quantity }}</p>
                            <p>Price: ৳{{ number_format($item->price, 2) }}</p>
                            @if($item->size)
                            <p>Size: {{ $item->size->name }}</p>
                            @endif
                            @if($item->color)
                            <p>Color: {{ $item->color->name }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="total">
                <p>Subtotal: ৳{{ number_format($order->total - ($order->shipping_cost ?? 0), 2) }}</p>
                <p>Shipping: ৳{{ number_format($order->shipping_cost ?? 0, 2) }}</p>
                <p><strong>Total: ৳{{ number_format($order->total, 2) }}</strong></p>
            </div>

            <div class="order-info">
                <h3>Shipping Information</h3>
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ url('/contact') }}" class="btn">Contact Us</a>
            </p>

            <p>If you have any questions about your order, please don't hesitate to contact us.</p>
            <p>Best regards,<br>libasbd Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} libasbd. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>
