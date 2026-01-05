<!DOCTYPE html>
<html>

<head>
    <title>Daftar Kategori Produk - Dapur Sakura</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
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
            font-size: 12px;
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
            padding: 10px;
            border: 1px solid #f9a8d4;
            font-size: 12px;
        }

        td {
            padding: 10px;
            border: 1px solid #f9f1f1;
            font-size: 11px;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #fffafb;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
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

        .color-circle {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
            border: 1px solid #ccc;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 30px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Dapur Sakura</div>
        <div class="title">Laporan Daftar Kategori Produk</div>
        <div class="date">Dicetak pada: {{ now()->format('d M Y H:i:s') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Slug</th>
                <th>Warna</th>
                <th>Status</th>
                <th>Produk</th>
                <th>Total Sales</th>
                <th>Kuantitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $category->name }}</strong>
                        @if($category->parent)
                            <br><small style="color: #666">Induk: {{ $category->parent->name }}</small>
                        @endif
                    </td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <span class="color-circle" style="background-color: {{ $category->color ?? '#eee' }}"></span>
                        {{ $category->color ?? '-' }}
                    </td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-active">Aktif</span>
                        @else
                            <span class="badge badge-inactive">Non-Aktif</span>
                        @endif
                    </td>
                    <td style="text-align: center">{{ $category->product_count }}</td>
                    <td>Rp {{ number_format($category->total_sales, 0, ',', '.') }}</td>
                    <td style="text-align: center">{{ number_format($category->total_quantity_sold) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Dapur Sakura Admin Panel. All rights reserved.
    </div>
</body>

</html>