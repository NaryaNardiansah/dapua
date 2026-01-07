<!DOCTYPE html>
<html>

<head>
    <title>Daftar Produk - Dapur Sakura</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ec4899;
            padding-bottom: 10px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ec4899;
            margin-bottom: 5px;
        }

        .title {
            font-size: 18px;
            color: #666;
        }

        .date {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #fce7f3;
            color: #be185d;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #f9a8d4;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border: 1px solid #f9f1f1;
            font-size: 10px;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #fffafb;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-best {
            background-color: #fef3c7;
            color: #92400e;
        }

        .footer {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .img-placeholder {
            width: 30px;
            height: 30px;
            background: #eee;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Dapur Sakura</div>
        <div class="title">Laporan Inventaris Produk</div>
        <div class="date">Dicetak pada: {{ now()->format('d M Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-right">Harga</th>
                <th class="text-center">Status</th>
                <th class="text-center">Terjual</th>
                <th class="text-right">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong>
                        @if($product->is_best_seller)
                            <br><span class="badge badge-best">Best Seller</span>
                        @endif
                    </td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($product->is_active)
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Draft</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($product->purchase_count) }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-center">{{ number_format($products->sum('purchase_count')) }}</td>
                <td class="text-right">Rp {{ number_format($products->sum('total_sales'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Dapur Sakura Admin Panel. Dokumen ini adalah laporan resmi inventaris.
    </div>
</body>

</html>