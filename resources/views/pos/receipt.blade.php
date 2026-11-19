<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }
        table { width: 100%; font-size: 13px; }
        th { text-align: left; }
        .btn-box { margin-top: 20px; text-align: center; }
        @media print {
            .btn-box { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h2 style="margin:0;">Madras Waffle House</h2>
        <p style="margin:4px 0; font-size:12px;">Fresh Juices, Waffles & Snacks</p>
        <p style="margin:0; font-size:11px;">{{ $order->created_at->format('d M Y, h:i A') }}</p>
        <p style="margin:2px 0; font-size:12px;" class="bold">Receipt: {{ $order->order_number }}</p>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td>Discount:</td>
            <td class="text-right">-₹{{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="bold" style="font-size:15px;">
            <td>Grand Total:</td>
            <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
        </tr>
        <tr>
            <td>Paid via:</td>
            <td class="text-right">{{ strtoupper($order->payment_method) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="text-center" style="font-size:12px;">
        <p style="margin:4px 0;">Thank you! Visit again! 🍹</p>
    </div>

    <div class="btn-box">
        <a href="{{ route('pos.index') }}" style="display:inline-block; padding:8px 16px; background:#10B981; color:#fff; text-decoration:none; border-radius:6px; font-weight:bold; font-family:sans-serif; font-size:13px;">
            ← Back to Billing
        </a>
    </div>

</body>
</html>