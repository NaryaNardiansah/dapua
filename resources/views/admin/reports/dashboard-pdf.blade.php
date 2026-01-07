<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Dashboard - Dapur Sakura</title>
    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #ec4899;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #ec4899;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 2px 0 0;
            color: #666;
            font-size: 11px;
        }

        .section-title {
            background-color: #fce7f3;
            color: #be185d;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0 8px;
            border-left: 3px solid #ec4899;
        }

        .stats-grid {
            width: 100%;
            margin-bottom: 10px;
        }

        .stats-grid td {
            width: 33.33%;
            padding: 5px;
        }

        .stat-card {
            border: 1px solid #f9a8d4;
            padding: 10px;
            border-radius: 6px;
            background-color: #fff;
            text-align: center;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #ec4899;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }

        .flex-container {
            width: 100%;
        }

        .col-half {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DAPUR SAKURA</h1>
        <p>Ringkasan Performa Bisnis - {{ $reportDate }}</p>
    </div>

    <div class="section-title">Ringkasan Pendapatan & Pesanan</div>
    <table class="stats-grid">
        <tr>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Hari Ini</div>
                    <div class="stat-value">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
                    <div style="font-size: 9px; color: #64748b;">{{ $ordersToday }} Pesanan</div>
                </div>
            </td>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Minggu Ini</div>
                    <div class="stat-value">Rp {{ number_format($revenueWeekly, 0, ',', '.') }}</div>
                    <div style="font-size: 9px; color: #64748b;">{{ $ordersWeekly }} Pesanan</div>
                </div>
            </td>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Bulan Ini</div>
                    <div class="stat-value">Rp {{ number_format($revenueMonthly, 0, ',', '.') }}</div>
                    <div style="font-size: 9px; color: #64748b;">{{ $ordersMonthly }} Pesanan</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Statistik Sistem Keseluruhan</div>
    <table class="stats-grid">
        <tr>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value" style="color: #333">{{ $totalOrders }}</div>
                </div>
            </td>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value" style="color: #333">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </td>
            <td>
                <div class="stat-card">
                    <div class="stat-label">Total User</div>
                    <div class="stat-value" style="color: #333">{{ $totalUsers }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="flex-container">
        <div class="col-half" style="margin-right: 4%;">
            <div class="section-title">5 Produk Terlaris</div>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-right">Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td class="text-right">{{ $product->sold }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-half">
            <div class="section-title">Status Pesanan</div>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-right">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statusDistribution as $status)
                        <tr>
                            <td>{{ ucfirst($status->status) }}</td>
                            <td class="text-right">{{ $status->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="section-title">5 Pesanan Terakhir</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($latestOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->recipient_name }}</td>
                    <td class="text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d/m H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Dapur Sakura - Dokumen ini ringkasan resmi performa sistem.</p>
    </div>
</body>

</html>