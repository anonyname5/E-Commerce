<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            float: left;
            width: 50%;
        }
        .invoice-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
            float: right;
            width: 50%;
        }
        .invoice-info {
            margin-bottom: 10px;
        }
        .address-block {
            width: 100%;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .address-block div {
            width: 45%;
            float: left;
        }
        .address-block div:last-child {
            float: right;
        }
        h4 {
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        .text-end {
            text-align: right;
        }
        .totals-table {
            width: 100%;
            border: none;
        }
        .totals-table td {
            border: none;
            text-align: right;
            padding: 5px 8px;
        }
        .totals-table .total-row td {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div class="logo">{{ $company['name'] }}</div>
        <div class="invoice-title">INVOICE</div>
    </div>
    
    <div class="invoice-info">
        <div><strong>Invoice #:</strong> {{ $order->id }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</div>
        <div><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</div>
        @if($order->transaction_id)
        <div><strong>Transaction ID:</strong> {{ $order->transaction_id }}</div>
        @endif
    </div>
    
    <div class="address-block">
        <div>
            <h4>Bill To:</h4>
            <div>{{ $order->billing_name ?? $order->user->name }}</div>
            <div>{{ $order->billing_address ?? 'No address provided' }}</div>
            @if(isset($order->billing_city))
            <div>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</div>
            <div>{{ $order->billing_country }}</div>
            @endif
            <div>{{ $order->user->email }}</div>
        </div>
        
        <div>
            <h4>Ship To:</h4>
            <div>{{ $order->shipping_name ?? $order->user->name }}</div>
            <div>{{ $order->shipping_address ?? 'No address provided' }}</div>
            @if(isset($order->shipping_city))
            <div>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</div>
            <div>{{ $order->shipping_country }}</div>
            @endif
        </div>
    </div>
    
    <h4>Order Items</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Product Unavailable' }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <table class="totals-table">
        <tr>
            <td width="80%">Subtotal:</td>
            <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping:</td>
            <td class="text-end">${{ number_format($order->shipping_cost, 2) }}</td>
        </tr>
        <tr>
            <td>Tax:</td>
            <td class="text-end">${{ number_format($order->tax, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total:</td>
            <td class="text-end">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
    
    <div class="payment-info">
        <h4>Payment Information</h4>
        <div><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</div>
        <div><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</div>
    </div>
    
    <div class="footer">
        <p>{{ $company['name'] }} - {{ $company['address'] }}, {{ $company['city'] }}, {{ $company['state'] }} {{ $company['zip'] }}</p>
        <p>If you have any questions about this invoice, please contact {{ $company['email'] }}</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html> 