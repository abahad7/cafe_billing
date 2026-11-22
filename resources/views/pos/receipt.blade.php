<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_number }}</title>
    <style>
     @page {
            margin: 0;
            size: 80mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            color: #000;
             font-size: 12px;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .dashed { border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { text-align: left; padding-bottom: 4px; }
        td { padding: 2px 0; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

  <div class="text-center">
        <h2 style="margin: 0; font-size: 16px; letter-spacing: 1px;">MADRAS WAFFLE HOUSE</h2>
        <p style="margin: 2px 0; font-size: 11px;">Fresh Waffles, Juices & Snacks</p>
        <p style="margin: 1px 0; font-size: 11px;">{{ $order->created_at->format('d/m/Y  h:i A') }}</p>
        <p style="margin: 2px 0; font-size: 12px;" class="bold">Bill No: {{ $order->order_number }}</p>
        @if($order->customer_phone)
            <p style="margin: 1px 0; font-size: 11px;">Customer Ph: <strong>{{ $order->customer_phone }}</strong></p>
        @endif
    </div>

    <div class="dashed"></div>

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
                <td>{{ Str::limit($item->product_name, 18) }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed"></div>

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
        <tr class="bold" style="font-size: 14px;">
            <td>TOTAL:</td>
            <td class="text-right">₹{{ number_format($order->total, 2) }}</td>
        </tr>
        <tr>
            <td>Payment:</td>
            <td class="text-right">{{ strtoupper($order->payment_method) }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

     <!-- Footer -->
    <div class="text-center" style="margin-top: 6px;">
        <p style="margin: 2px 0;">*** THANK YOU! VISIT AGAIN ***</p>
        <p style="margin: 0; font-size: 10px;">Taste the crunch in every bite 🧇</p>
    </div>
    <!-- Auto Print & Auto-Close Script -->
    <script>
        window.onload = function() {
            window.print();
        };
        // Auto-close print window when user prints or cancels
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>