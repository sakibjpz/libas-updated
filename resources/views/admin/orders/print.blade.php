<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #c9a24b;
            --brand-dark: #a8832f;
            --dark: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f3f4f6;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--dark);
            margin: 0;
            padding: 15px;
            font-size: 13px;
            line-height: 1.45;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .invoice-header {
            background: #fff;
            padding: 22px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--brand);
        }

        .invoice-header .brand img {
            max-height: 60px;
            width: auto;
            display: block;
        }

        .invoice-header .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }

        .invoice-header .brand-url {
            color: var(--muted);
            font-size: 12px;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title-block .invoice-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .invoice-title-block .invoice-meta {
            font-size: 12px;
            color: var(--muted);
        }

        .invoice-body {
            padding: 22px 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 18px;
        }

        .info-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 15px;
            background: #fff;
            page-break-inside: avoid;
        }

        .info-card h5 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--muted);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-card p {
            margin: 0 0 4px;
        }

        .info-card .value {
            font-weight: 600;
            color: var(--dark);
        }

        .status-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-pill.status-pending { background: #fef3c7; color: #92400e; }
        .badge-pill.status-processing { background: #e0f2fe; color: #075985; }
        .badge-pill.status-shipped { background: #dbeafe; color: #1e40af; }
        .badge-pill.status-completed { background: #d1fae5; color: #065f46; }
        .badge-pill.status-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-pill.area-inside { background: #f3f4f6; color: #374151; }
        .badge-pill.area-outside { background: #f3f4f6; color: #374151; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table thead th {
            background: var(--dark);
            color: #fff;
            padding: 9px 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            text-align: left;
            border: 1px solid var(--dark);
        }

        .items-table tbody td {
            padding: 8px 12px;
            border: 1px solid var(--border);
            vertical-align: middle;
        }

        .items-table .text-right { text-align: right; }

        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 14px;
        }

        .summary-box {
            width: 260px;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 14px;
            border-bottom: 1px solid var(--border);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row.grand-total {
            background: var(--brand);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .summary-row.grand-total .label,
        .summary-row.grand-total .amount {
            color: #fff;
        }

        .invoice-footer {
            margin-top: 22px;
            padding-top: 14px;
            border-top: 1px dashed var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        .invoice-footer .brand-tagline {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .no-print {
            text-align: center;
            margin-bottom: 18px;
        }

        .no-print .btn {
            margin: 0 4px;
            border-radius: 6px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 13px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                font-size: 12px;
            }

            .no-print {
                display: none !important;
            }

            .invoice-wrapper {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }

            .invoice-header {
                border-bottom: 3px solid var(--brand) !important;
                padding: 18px 25px;
            }

            .invoice-body {
                padding: 18px 25px;
            }

            .items-table thead th {
                background: var(--dark) !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .summary-row.grand-total,
            .badge-pill {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .info-card,
            .summary-box {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            Close
        </button>
    </div>

    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div class="brand">
                <img src="{{ asset('images/logos/logo.png') }}" alt="Libas" onerror="this.parentElement.innerHTML='<div class=\'brand-name\'>Libas</div><div class=\'brand-url\'>www.libasbd.com</div>'">
            </div>
            <div class="invoice-title-block">
                <div class="invoice-title">Invoice</div>
                <div class="invoice-meta">Order #{{ $order->id }}</div>
                <div class="invoice-meta">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>
        </div>

        <div class="invoice-body">
            <div class="info-grid">
                <div class="info-card">
                    <h5>Billed To</h5>
                    <p class="value">{{ $order->customer_name }}</p>
                    @if($order->customer_phone)
                        <p>Phone: {{ $order->customer_phone }}</p>
                    @endif
                    @if($order->customer_email)
                        <p>Email: {{ $order->customer_email }}</p>
                    @endif
                </div>
                <div class="info-card">
                    <h5>Shipping Details</h5>
                    <p class="value">{{ $order->shipping_address ?? 'N/A' }}</p>
                    <div class="status-row">
                        @if($order->delivery_area)
                            <span class="badge-pill area-{{ $order->delivery_area === 'outside_dhaka' ? 'outside' : 'inside' }}">
                                {{ $order->delivery_area === 'outside_dhaka' ? 'Outside Dhaka' : 'Inside Dhaka' }}
                            </span>
                        @endif
                        <span class="badge-pill status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th>Product</th>
                        <th style="width: 14%">Size</th>
                        <th style="width: 14%">Color</th>
                        <th style="width: 14%" class="text-right">Price</th>
                        <th style="width: 9%" class="text-right">Qty</th>
                        <th style="width: 16%" class="text-right">Total</th>
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
                            <td><strong>{{ $item->product->name ?? 'Product Not Found' }}</strong></td>
                            <td>{{ $item->size->name ?? '-' }}</td>
                            <td>{{ $item->color->name ?? '-' }}</td>
                            <td class="text-right">৳{{ number_format($item->price, 2) }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right"><strong>৳{{ number_format($itemTotal, 2) }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @php
                $shipping = (float) ($order->shipping_cost ?? 0);
                $discount = max(0, $subtotal + $shipping - (float) $order->total);
            @endphp

            <div class="summary-section">
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="label text-muted">Subtotal</span>
                        <span class="amount">৳{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label text-muted">Shipping</span>
                        <span class="amount">৳{{ number_format($shipping, 2) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="summary-row">
                            <span class="label text-danger">Discount</span>
                            <span class="amount text-danger">-৳{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="summary-row grand-total">
                        <span class="label">Grand Total</span>
                        <span class="amount">৳{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($order->notes)
                <div class="info-card mt-3">
                    <h5>Order Notes</h5>
                    <p>{{ $order->notes }}</p>
                </div>
            @endif

            <div class="invoice-footer">
                <div class="brand-tagline">Thank you for shopping with Libas!</div>
                <p>
                    Hotline: 01333-257604 &nbsp;|&nbsp; Website: www.libasbd.com
                </p>
                <p class="small text-muted mt-1">
                    If you have any questions about this invoice, please contact us with your order number.
                </p>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 400);
        });
    </script>
</body>
</html>
