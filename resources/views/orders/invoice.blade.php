<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        .invoice-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 16px;
            color: #666;
        }

        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .detail-section h3 {
            color: #EC4899;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .detail-section p {
            margin: 5px 0;
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            background-color: #EC4899;
            color: white;
            font-weight: bold;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
        }

        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            color: #EC4899;
            border-top: 2px solid #EC4899;
            padding-top: 15px;
            margin-top: 15px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        @media print {
            body {
                background-color: white;
            }

            .invoice-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo" style="margin-bottom: 15px;">
                <img src="{{ public_path('images/logo-sakura.jpg') }}" alt="Logo"
                    style="width: 60px; height: 60px; border-radius: 10px;">
            </div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">No. {{ $order->order_code }}</div>
        </div>

        <div class="invoice-details">
            <div class="detail-section">
                <h3>Informasi Pelanggan</h3>
                <p><strong>Nama:</strong> {{ $order->recipient_name }}</p>
                <p><strong>Telepon:</strong> {{ $order->recipient_phone }}</p>
                <p><strong>Alamat:</strong> {{ $order->address_line }}</p>
                @if($order->user)
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                @endif
            </div>
            <div class="detail-section">
                <h3>Informasi Pesanan</h3>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->payment_status) }}</p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->line_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Ongkos Kirim:</span>
                <span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_total > 0)
                <div class="total-row">
                    <span>Diskon:</span>
                    <span>- Rp {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row final">
                <span>Total:</span>
                <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah memesan di Dapur Sakura!</p>
            <p>Website: {{ config('app.url') }} | Email: info@dapursakura.com</p>
        </div>
    </div>
</body>

</html>