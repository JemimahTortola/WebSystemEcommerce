<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #10b981;
            margin: 0 0 10px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            color: #374151;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item strong {
            display: inline-block;
            min-width: 120px;
            color: #6b7280;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-verified { background: #d1fae5; color: #065f46; }
        .status-pending  { background: #fef3c7; color: #92400e; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.1em;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        @media print {
            body { padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Flourista</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>

    <div class="section">
        <h3>Order Information</h3>
        <div class="info-grid">
            <div class="info-item">
                <strong>Status:</strong>
                <span class="status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="info-item">
                <strong>Payment:</strong>
                <span class="status status-{{ $order->payment_status }}">{{ ucfirst($order->payment_status ?? 'pending') }}</span>
            </div>
            <div class="info-item">
                <strong>Method:</strong>
                {{ $order->payment_method === 'gcash' ? 'E-Wallet' : ($order->payment_method === 'bank' ? 'Bank' : 'COD') }}
            </div>
            <div class="info-item">
                <strong>Date:</strong>
                {{ $order->created_at->format('M d, Y h:i A') }}
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Customer Information</h3>
        <div class="info-item">
            <strong>Name:</strong> {{ $order->shipping_name ?? ($order->user ? $order->user->name : 'N/A') }}
        </div>
        <div class="info-item">
            <strong>Email:</strong> {{ $order->user ? $order->user->email : 'N/A' }}
        </div>
        <div class="info-item">
            <strong>Phone:</strong> {{ $order->shipping_phone ?? 'N/A' }}
        </div>
    </div>

    <div class="section">
        <h3>Delivery Address</h3>
        <div class="info-item">
            {{ $order->shipping_address ?? 'N/A' }}
        </div>
        @if($order->delivery_date)
        <div class="info-item">
            <strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
        </div>
        @endif
        @if($order->delivery_time)
        <div class="info-item">
            <strong>Delivery Time:</strong> {{ $order->delivery_time }}
        </div>
        @endif
    </div>

    <div class="section">
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product ? $item->product->name : ($item->product_name ?? 'Product') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->price, 2) }}</td>
                    <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($order->payment_receipt)
    <div class="section">
        <h3>Payment Receipt</h3>
        <img src="{{ asset('storage/' . $order->payment_receipt) }}" alt="Payment Receipt" style="max-width: 300px;">
    </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Flourista - Fresh Flowers Delivered</p>
    </div>

    <script>
        // Use setTimeout to ensure page is fully rendered before printing
        setTimeout(function() {
            window.print();
        }, 500);
    </script>
</body>
</html>
