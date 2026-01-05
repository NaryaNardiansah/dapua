@extends('layouts.admin')

@section('content')
    <div class="luxury-dashboard-wrapper">
        <!-- Main Dashboard Container -->
        <div class="luxury-dashboard">
            <!-- Page Header -->
            <div class="dashboard-header">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-chart-line"></i>
                        Dashboard Admin
                    </h1>
                    <p class="page-subtitle">Selamat datang kembali di Dapur Sakura Admin Panel</p>
                </div>
                <div class="header-actions">
                    <span class="current-date">{{ now()->format('d F Y') }}</span>
                </div>
            </div>

            <!-- Statistics Cards -->
            <section class="stats-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-primary">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
                            <div class="stat-label">Pendapatan Hari Ini</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-icon-info">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $ordersToday }}</div>
                            <div class="stat-label">Pesanan Hari Ini</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-icon-success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $totalProducts }}</div>
                            <div class="stat-label">Total Produk</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon stat-icon-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $pending }}</div>
                            <div class="stat-label">Pesanan Pending</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $totalReviews }}</div>
                            <div class="stat-label">Total Ulasan</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-section">
                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="card-header">
                            <h3 class="card-title">Trend Penjualan</h3>
                            <div class="card-actions">
                                <select id="salesRange" class="form-select form-select-sm">
                                    <option value="day" {{ $range === 'day' ? 'selected' : '' }}>7 Hari</option>
                                    <option value="week" {{ $range === 'week' ? 'selected' : '' }}>8 Minggu</option>
                                    <option value="month" {{ $range === 'month' ? 'selected' : '' }}>6 Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-wrapper">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="card-header">
                            <h3 class="card-title">Distribusi Kategori</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-wrapper chart-wrapper-small">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Activity Section -->
            <section class="activity-section">
                <div class="activity-grid">
                    <!-- Top Products -->
                    <div class="activity-card">
                        <div class="card-header">
                            <h3 class="card-title">Produk Terlaris</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama Produk</th>
                                            <th class="text-end">Terjual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProducts as $index => $product)
                                            <tr>
                                                <td><span class="badge badge-primary">{{ $index + 1 }}</span></td>
                                                <td><strong>{{ $product->name }}</strong></td>
                                                <td class="text-end">
                                                    <span class="badge badge-success">{{ $product->sold }} item</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Belum ada data produk</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="activity-card">
                        <div class="card-header">
                            <h3 class="card-title">Pesanan Terbaru</h3>
                            <a href="{{ route('admin.orders.index') }}" class="btn-link">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pelanggan</th>
                                            <th class="text-end">Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestOrders as $order)
                                            <tr>
                                                <td><a href="{{ route('admin.orders.show', $order) }}"
                                                        class="text-primary">#{{ $order->id }}</a></td>
                                                <td>{{ $order->recipient_name }}</td>
                                                <td class="text-end">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge badge-status-{{ $order->status }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada pesanan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Hidden data elements for JavaScript -->
    <div id="sales-labels" style="display: none;">{!! $labelsJson !!}</div>
    <div id="sales-data" style="display: none;">{!! $salesJson !!}</div>
    <div id="category-labels" style="display: none;">{!! $categoryLabelsJson !!}</div>
    <div id="category-data" style="display: none;">{!! $categoryValuesJson !!}</div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Dashboard Premium Styles */
        .luxury-dashboard-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            padding: 0;
        }

        .luxury-dashboard-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .luxury-dashboard {
            padding: 2rem;
            width: 100%;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Dashboard Header - Enhanced */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInLeft 0.8s ease-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .page-title i {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            filter: drop-shadow(0 4px 8px rgba(236, 72, 153, 0.3));
        }

        .page-subtitle {
            color: #6b7280;
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .current-date {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
            animation: fadeInRight 0.8s ease-out;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Section Title - Enhanced */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1f2937 0%, #4b5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            border-radius: 2px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stats Section - Premium Cards */
        .stats-section {
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-icon {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            transition: all 0.4s ease;
        }

        .stat-card:hover .stat-icon {
            transform: rotate(10deg) scale(1.1);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
        }

        .stat-icon-primary {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
        }

        .stat-icon-info {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }

        .stat-icon-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }

        .stat-icon-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }

        .stat-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1f2937 0%, #4b5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Charts Section - Premium */
        .charts-section {
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease-out 0.6s both;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 2rem;
        }

        .chart-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .chart-card:hover {
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.3) 100%);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1f2937 0%, #4b5563 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .form-select {
            padding: 0.625rem 1.25rem;
            border: 2px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            font-size: 0.875rem;
            background: white;
            color: #1f2937;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .form-select:hover {
            border-color: #ec4899;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
        }

        .form-select:focus {
            outline: none;
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .card-body {
            padding: 2rem;
        }

        .chart-wrapper {
            height: 320px;
            position: relative;
        }

        .chart-wrapper-small {
            height: 280px;
        }

        /* Activity Section - Premium */
        .activity-section {
            margin-bottom: 2.5rem;
            animation: fadeInUp 0.6s ease-out 0.8s both;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 2rem;
        }

        .activity-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .activity-card:hover {
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.875rem;
            font-weight: 700;
            color: #4b5563;
            background: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(243, 244, 246, 0.6) 100%);
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            color: #1f2937;
            transition: all 0.3s ease;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(236, 72, 153, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
            transform: scale(1.01);
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(90deg, rgba(236, 72, 153, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6b7280;
        }

        .badge-primary {
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
            color: white;
            padding: 0.5rem 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .badge-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            padding: 0.375rem 0.75rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .badge-status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .badge-status-diproses {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #2563eb;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .badge-status-dikirim {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #7c3aed;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .badge-status-selesai {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .badge-status-dibatalkan {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
        }

        .btn-link {
            color: #ec4899;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .btn-link:hover {
            background: rgba(236, 72, 153, 0.1);
            text-decoration: none;
            transform: translateX(4px);
        }

        .text-primary {
            color: #ec4899;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .text-primary:hover {
            color: #be185d;
            text-decoration: underline;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .luxury-dashboard {
                padding: 1rem;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                height: 250px;
            }

            .chart-wrapper-small {
                height: 220px;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize charts
            initCharts();

            // Handle range selector change
            document.getElementById('salesRange')?.addEventListener('change', function () {
                const range = this.value;
                window.location.href = '{{ route("admin.dashboard") }}?range=' + range;
            });
        });

        function initCharts() {
            const salesCtx = document.getElementById('salesChart').getContext('2d');

            // Get data from hidden elements
            const labels = JSON.parse(document.getElementById('sales-labels').textContent);
            const salesData = JSON.parse(document.getElementById('sales-data').textContent);

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Penjualan',
                        data: salesData,
                        borderColor: 'rgba(236, 72, 153, 1)',
                        backgroundColor: 'rgba(236, 72, 153, 0.15)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.5,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ec4899',
                        pointBorderWidth: 3,
                        pointRadius: 7,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: '#ec4899',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 3,
                        shadowOffsetX: 0,
                        shadowOffsetY: 4,
                        shadowBlur: 10,
                        shadowColor: 'rgba(236, 72, 153, 0.3)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.95)',
                            padding: 16,
                            titleFont: {
                                size: 15,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14,
                                weight: '500'
                            },
                            borderColor: '#ec4899',
                            borderWidth: 2,
                            borderRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: '#be185d',
                            hoverRadius: 6
                        },
                        line: {
                            borderWidth: 2
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart',
                        delay: 100
                    }
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');

            // Get category data from hidden elements
            const categoryLabels = JSON.parse(document.getElementById('category-labels').textContent);
            const categoryData = JSON.parse(document.getElementById('category-data').textContent);

            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: [
                            'rgba(236, 72, 153, 0.9)',
                            'rgba(139, 92, 246, 0.9)',
                            'rgba(59, 130, 246, 0.9)',
                            'rgba(16, 185, 129, 0.9)',
                            'rgba(245, 158, 11, 0.9)',
                            'rgba(239, 68, 68, 0.9)',
                            'rgba(168, 85, 247, 0.9)',
                            'rgba(14, 165, 233, 0.9)'
                        ],
                        borderColor: [
                            '#ec4899',
                            '#8b5cf6',
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#a855f7',
                            '#0ea5e9'
                        ],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                color: '#6b7280',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.95)',
                            padding: 16,
                            titleFont: {
                                size: 15,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 14,
                                weight: '500'
                            },
                            borderColor: '#ec4899',
                            borderWidth: 2,
                            borderRadius: 12,
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    return label + ': ' + value + ' item';
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500,
                        easing: 'easeOutQuart',
                        delay: 200
                    }
                }
            });
        }

    </script>
@endsection