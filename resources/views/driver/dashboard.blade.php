@extends('layouts.driver')

@section('content')
    <div class="driver-dashboard-container">
        <!-- Hero Section -->
        <div class="dashboard-hero" data-aos="fade-down">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    <span>Driver Terbaik Dapur Sakura</span>
                </div>
                <h1 class="hero-title">
                    Selamat Datang, <span>{{ auth()->user()->name }}</span>
                </h1>
                <p class="hero-subtitle">Pantau dan kelola pesanan kuliner Anda dengan sentuhan premium hari ini.</p>
            </div>
            <div class="hero-icon">
                <i class="fas fa-tachometer-alt"></i>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-icon-wrapper blue">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $totalOrders }}</span>
                    <span class="stat-label">Total Pesanan</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrapper orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $pendingOrders }}</span>
                    <span class="stat-label">Pesanan Aktif</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrapper green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $completedOrders }}</span>
                    <span class="stat-label">Pesanan Selesai</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-wrapper purple">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $todayOrders }}</span>
                    <span class="stat-label">Pesanan Hari Ini</span>
                </div>
            </div>
        </div>

        <!-- Earnings & Performance -->
        <div class="performance-section" data-aos="fade-up" data-aos-delay="400">
            <div class="performance-card pink">
                <div class="performance-details">
                    <div class="perf-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <span class="perf-label">Total Pendapatan</span>
                        <h2 class="perf-value">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>

            <div class="performance-card gold">
                <div class="performance-details">
                    <div class="perf-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <span class="perf-label">Pendapatan Bulan Ini</span>
                        <h2 class="perf-value">Rp {{ number_format($monthlyEarnings, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-sections">
            <!-- Active Orders -->
            <div class="section-container" data-aos="fade-right">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="title-icon orange">
                            <i class="fas fa-truck-moving"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Pesanan Aktif</h2>
                            <p class="section-subtitle">Sedang dalam proses pengiriman</p>
                        </div>
                    </div>
                </div>

                <div class="orders-list">
                    @forelse($activeOrders as $order)
                        <div class="order-card active">
                            <div class="order-header">
                                <span class="order-id">{{ $order->order_code }}</span>
                                <span class="order-status-badge orange">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="order-body">
                                <div class="info-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $order->recipient_name }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $order->address_line }}</span>
                                </div>
                                <div class="info-item"
                                    style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fas fa-phone"></i>
                                    <span style="font-weight: 600;">{{ $order->recipient_phone }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->recipient_phone) }}?text=Halo%20{{ urlencode($order->recipient_name) }},%20saya%20driver%20Dapur%20Sakura%20ingin%20konfirmasi%20pengiriman%20pesanan%20{{ $order->order_code }}"
                                        target="_blank"
                                        style="color: #25D366; text-decoration: none; display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; font-weight: 700;">
                                        <i class="fab fa-whatsapp" style="font-size: 1.1rem;"></i>
                                        Chat WA
                                    </a>
                                </div>
                            </div>
                            <div class="order-footer">
                                <a href="{{ route('driver.orders.show', $order) }}" class="btn-detail">
                                    <span>Detail</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-check-double"></i>
                            <p>Tidak ada pesanan aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent History -->
            <div class="section-container" data-aos="fade-left">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <div class="title-icon blue">
                            <i class="fas fa-history"></i>
                        </div>
                        <div>
                            <h2 class="section-title">Riwayat Terbaru</h2>
                            <p class="section-subtitle">Aktivitas terakhir Anda</p>
                        </div>
                    </div>
                </div>

                <div class="orders-list">
                    @forelse($recentOrders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">{{ $order->order_code }}</span>
                                <span class="order-status-badge {{ $order->status == 'selesai' ? 'green' : 'blue' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="order-body">
                                <div class="info-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $order->recipient_name }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="order-footer">
                                <a href="{{ route('driver.orders.show', $order) }}" class="btn-detail-outline">
                                    <span>Detail</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Belum ada riwayat pesanan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .driver-dashboard-container {
            padding: 1rem 0;
            display: grid;
            gap: 2.5rem;
        }

        /* Hero Section */
        .dashboard-hero {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.05);
            position: relative;
            overflow: hidden;
        }

        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.05), transparent);
            border-radius: 50%;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--light-pink);
            color: var(--primary-pink);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: var(--gray-600);
            font-size: 1.1rem;
            max-width: 500px;
        }

        .hero-icon {
            font-size: 5rem;
            color: var(--primary-pink);
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border: 2px solid rgba(236, 72, 153, 0.05);
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.02);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-pink);
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.1);
        }

        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon-wrapper.blue {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .stat-icon-wrapper.orange {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .stat-icon-wrapper.green {
            background: linear-gradient(135deg, #10b981, #34d399);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .stat-icon-wrapper.purple {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        /* Performance Section */
        .performance-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .performance-card {
            padding: 2.5rem;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .performance-card.pink {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.3);
        }

        .performance-card.gold {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
        }

        .performance-details {
            display: flex;
            align-items: center;
            gap: 2rem;
            position: relative;
            z-index: 1;
        }

        .perf-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .perf-label {
            font-size: 1rem;
            font-weight: 600;
            opacity: 0.9;
            display: block;
            margin-bottom: 0.5rem;
        }

        .perf-value {
            font-size: 2.25rem;
            font-weight: 900;
            margin: 0;
        }

        /* Content Area */
        .content-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.5rem;
        }

        .section-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .section-title-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .title-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .title-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .title-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            margin: 0;
        }

        .section-subtitle {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin: 0;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .order-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            border: 2px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: rgba(236, 72, 153, 0.1);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.05);
        }

        .order-card.active {
            border-left: 5px solid #f59e0b;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .order-id {
            font-weight: 800;
            color: var(--gray-900);
            font-size: 1.1rem;
        }

        .order-status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-status-badge.orange {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }

        .order-status-badge.green {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #dcfce7;
        }

        .order-status-badge.blue {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        .order-body {
            display: grid;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .info-item i {
            color: var(--primary-pink);
            width: 16px;
            text-align: center;
        }

        .order-footer {
            display: flex;
            justify-content: flex-end;
        }

        .btn-detail {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            transform: translateX(3px);
            box-shadow: 0 5px 15px rgba(236, 72, 153, 0.3);
        }

        .btn-detail-outline {
            border: 2px solid var(--gray-200);
            color: var(--gray-700);
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-detail-outline:hover {
            border-color: var(--primary-pink);
            color: var(--primary-pink);
            background: var(--light-pink);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 16px;
            border: 2px dashed var(--gray-200);
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-sections {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-icon {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .dashboard-hero {
                padding: 2rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .perf-value {
                font-size: 1.75rem;
            }

            .stat-card {
                padding: 1.5rem;
            }
        }
    </style>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>
@endsection