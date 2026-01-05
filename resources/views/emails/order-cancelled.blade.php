<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #EC4899;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #EC4899;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .order-info h3 {
            color: #EC4899;
            margin-bottom: 15px;
        }
        .order-info p {
            margin: 5px 0;
            color: #333;
        }
        .reason {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .reason h4 {
            color: #856404;
            margin-bottom: 10px;
        }
        .reason p {
            color: #856404;
            margin: 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">Dapur Sakura</div>
            <div class="title">Order Cancelled</div>
        </div>

        <div class="order-info">
            <h3>Informasi Pesanan</h3>
            <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
            <p><strong>Customer:</strong> {{ $order->recipient_name }}</p>
            <p><strong>Phone:</strong> {{ $order->recipient_phone }}</p>
            <p><strong>Address:</strong> {{ $order->address_line }}</p>
            <p><strong>Total Amount:</strong> Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
            <p><strong>Cancelled At:</strong> {{ $order->cancelled_at->format('d M Y, H:i') }}</p>
        </div>

        @if($order->cancellation_reason)
        <div class="reason">
            <h4>Alasan Pembatalan:</h4>
            <p>{{ $order->cancellation_reason }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Order ini telah dibatalkan oleh customer.</p>
            <p>Dapur Sakura - Modern Japanese & Local Cuisine</p>
        </div>
    </div>
</body>
</html>






