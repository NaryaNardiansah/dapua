@extends('layouts.admin')

@section('content')
<div class="luxury-admin-dashboard">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-crown"
        title="Dashboard Admin"
        subtitle="Dapur Sakura"
        description="Selamat datang di panel administrasi yang modern dan efisien"
        :showCircle="true"
    />

    <!-- Status Alert -->
    @if(session('status'))
        <div class="status-alert fade-in-up delay-100" data-aos="fade-down">
            <div class="alert-content">
                <i class="fas fa-check-circle alert-icon"></i>
                <span class="alert-text">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Quick Actions Bar -->
    <div class="quick-actions-bar fade-in-up delay-300" data-aos="fade-up">
        <div class="actions-container">
            <a href="{{ route('admin.orders.index') }}" class="action-btn primary">
                <i class="fas fa-shopping-bag mr-2"></i>Manajemen Pesanan
                @if($pending > 0)
                    <span class="notification-badge">{{ $pending > 99 ? '99+' : $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.products.index') }}" class="action-btn secondary">
                <i class="fas fa-box mr-2"></i>Manajemen Produk
            </a>
            <a href="{{ route('admin.users.index') }}" class="action-btn secondary">
                <i class="fas fa-users mr-2"></i>Manajemen Pengguna
            </a>
            <a href="{{ route('admin.delivery.index') }}" class="action-btn secondary">
                <i class="fas fa-truck mr-2"></i>Pengiriman
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Left Column - Statistics -->
        <div class="main-content-left">
            <!-- Statistics Cards -->
            <!-- Stats Section -->
            <x-admin-responsive-grid class="stats-section auto-fit" :delay="400">
                <x-admin-stat-card 
                    icon="fas fa-shopping-cart"
                    :value="$ordersToday"
                    label="Pesanan Hari Ini"
                    change="+12%"
                    changeType="positive"
                    iconType="primary"
                    :delay="500"
                />
                
                <x-admin-stat-card 
                    icon="fas fa-dollar-sign"
                    :value="'Rp ' . number_format($revenueToday,0,',','.')"
                    label="Pendapatan Hari Ini"
                    change="+8%"
                    changeType="positive"
                    iconType="success"
                    :delay="600"
                />
                
                <x-admin-stat-card 
                    icon="fas fa-box"
                    :value="$totalProducts"
                    label="Produk Aktif"
                    change="0%"
                    changeType="neutral"
                    iconType="info"
                    :delay="700"
                />
                
                <x-admin-stat-card 
                    icon="fas fa-users"
                    :value="$totalUsers"
                    label="Total Pengguna"
                    change="+5%"
                    changeType="positive"
                    iconType="primary"
                    :delay="800"
                />
                
                <x-admin-stat-card 
                    icon="fas fa-clock"
                    :value="$pending"
                    label="Pending/Proses"
                    change="Perhatian"
                    changeType="warning"
                    iconType="warning"
                    :delay="900"
                />
            </x-admin-responsive-grid>

            <!-- Charts Section -->
            <div class="charts-section fade-in-up delay-1000" data-aos="fade-up">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="chart-title">
                            <h3>Penjualan @if(($range ?? 'week')==='day') 7 Hari @elseif(($range ?? 'week')==='month') 6 Bulan @else 8 Minggu @endif Terakhir</h3>
                            <p>Trend penjualan dan performa bisnis</p>
                        </div>
                        <div class="chart-controls">
                            <form method="get" class="period-selector">
                                <select name="range" onchange="this.form.submit()">
                                    <option value="day" @selected(($range ?? 'week')==='day')>7 Hari</option>
                                    <option value="week" @selected(($range ?? 'week')==='week')>8 Minggu</option>
                                    <option value="month" @selected(($range ?? 'week')==='month')>6 Bulan</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="chart-content">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Activity -->
        <div class="main-content-right">
            <!-- Top Products -->
            <div class="activity-card fade-in-up delay-500" data-aos="fade-left">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="card-title">
                        <h3>Top 5 Produk</h3>
                        <p>Produk terlaris</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="product-list">
                        @foreach($topProducts as $index => $product)
                            <div class="product-item" data-aos="slide-right" data-aos-delay="{{ ($index + 1) * 100 }}">
                                <div class="product-rank">{{ $index + 1 }}</div>
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-sales">{{ $product->sold }} terjual</div>
                                </div>
                                <div class="product-trend">
                                    <i class="fas fa-fire"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Latest Orders -->
            <div class="activity-card fade-in-up delay-600" data-aos="fade-left">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-title">
                        <h3>Pesanan Terbaru</h3>
                        <p>Aktivitas pesanan terkini</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="view-all-link">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-content">
                    <div class="order-list">
                        @foreach($latestOrders as $order)
                            <div class="order-item" data-aos="slide-left" data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                                <div class="order-id">#{{ $order->id }}</div>
                                <div class="order-info">
                                    <div class="order-customer">{{ $order->recipient_name }}</div>
                                    <div class="order-total">Rp {{ number_format($order->grand_total,0,',','.') }}</div>
                                </div>
                                <div class="order-status status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Category Chart -->
            <div class="activity-card fade-in-up delay-700" data-aos="fade-left">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-title">
                        <h3>Distribusi Kategori</h3>
                        <p>Persebaran produk per kategori</p>
                    </div>
                </div>
                <div class="card-content">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Dashboard Styles -->
<style>
/* Luxury Admin Dashboard Styles */
.luxury-admin-dashboard {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    padding: 4rem 0;
    margin: -2rem -2rem 0 -2rem;
    position: relative;
    overflow: hidden;
}

.hero-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
}

.hero-title-container {
    text-align: center;
    color: var(--pure-white);
}

.hero-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-icon-wrapper {
    position: relative;
}

.hero-icon {
    font-size: 3rem;
    animation: bounce 2s infinite;
}

.hero-title-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.hero-title-main {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1;
}

.hero-title-sub {
    font-size: 1.2rem;
    font-weight: 300;
    opacity: 0.9;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.hero-decorative-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.decorative-line {
    position: absolute;
    top: 50%;
    left: 10%;
    width: 3px;
    height: 120px;
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) rotate(15deg);
    border-radius: 2px;
}

.decorative-dots {
    position: absolute;
    top: 30%;
    right: 15%;
    width: 24px;
    height: 24px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.decorative-circle {
    position: absolute;
    bottom: 20%;
    left: 20%;
    width: 40px;
    height: 40px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    animation: rotate 10s linear infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 0.2; }
    50% { transform: scale(1.2); opacity: 0.4; }
    100% { transform: scale(1); opacity: 0.2; }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Status Alert */
.status-alert {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    margin-bottom: 2rem;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-icon {
    font-size: 1.2rem;
}

.alert-text {
    font-weight: 500;
}

/* Quick Actions Bar */
.quick-actions-bar {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    margin: 2rem 0;
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.actions-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
    font-size: 0.95rem;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn.primary {
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    color: var(--pure-white);
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.action-btn.secondary {
    background: var(--gray-200);
    color: var(--gray-700);
}

.action-btn.secondary:hover {
    background: var(--gray-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.notification-badge {
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    margin-left: 0.5rem;
    animation: pulse 2s infinite;
}

/* Main Content Grid */
.main-content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

@media (min-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.main-content-left {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.main-content-right {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Section Headers */
.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.section-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pure-white);
    font-size: 1.2rem;
    box-shadow: var(--shadow-md);
}

.section-title h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
}

.section-title p {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin: 0.25rem 0 0 0;
}

/* Statistics */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink));
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-2xl);
}

.stat-icon {
    font-size: 2rem;
    color: var(--primary-pink);
    margin-bottom: 1rem;
}

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--gray-800);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.stat-trend.positive {
    color: #10b981;
}

.stat-trend.warning {
    color: #f59e0b;
}

.stat-trend.neutral {
    color: var(--gray-500);
}

/* Charts */
.chart-card {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.chart-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.chart-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pure-white);
    font-size: 1rem;
}

.chart-title {
    flex: 1;
}

.chart-title h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
}

.chart-title p {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin: 0.25rem 0 0 0;
}

.chart-controls {
    display: flex;
    align-items: center;
}

.period-selector select {
    padding: 0.5rem 1rem;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    background: var(--gray-50);
    color: var(--gray-700);
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.period-selector select:focus {
    outline: none;
    border-color: var(--primary-pink);
    background: var(--pure-white);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.chart-content {
    height: 300px;
    position: relative;
}

/* Activity Cards */
.activity-card {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.activity-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-2xl);
}

.card-header {
    background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255,255,255,0.8) 100%);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pure-white);
    font-size: 1rem;
    box-shadow: var(--shadow-md);
}

.card-title {
    flex: 1;
}

.card-title h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
}

.card-title p {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin: 0.25rem 0 0 0;
}

.view-all-link {
    color: var(--primary-pink);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.view-all-link:hover {
    color: var(--dark-pink);
    transform: translateX(2px);
}

.card-content {
    padding: 1.5rem;
}

/* Product List */
.product-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255,255,255,0.8) 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.product-item:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-md);
}

.product-rank {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
    color: var(--pure-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
}

.product-info {
    flex: 1;
}

.product-name {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.product-sales {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.product-trend {
    color: var(--primary-pink);
    font-size: 1.25rem;
}

/* Order List */
.order-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255,255,255,0.8) 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.order-item:hover {
    transform: translateX(-5px);
    box-shadow: var(--shadow-md);
}

.order-id {
    font-weight: 700;
    color: var(--primary-pink);
    font-size: 0.875rem;
}

.order-info {
    flex: 1;
}

.order-customer {
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.order-total {
    color: var(--gray-600);
    font-size: 0.875rem;
}

.order-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-diproses {
    background: #dbeafe;
    color: #2563eb;
}

.status-dikirim {
    background: #e0e7ff;
    color: #7c3aed;
}

.status-selesai {
    background: #d1fae5;
    color: #059669;
}

.status-dibatalkan {
    background: #fee2e2;
    color: #dc2626;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-title-main {
        font-size: 2.5rem;
    }
    
    .hero-icon {
        font-size: 2.5rem;
    }
    
    .actions-container {
        flex-direction: column;
    }
    
    .action-btn {
        justify-content: center;
    }
    
    .main-content-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .hero-title-main {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
}

/* Animations */
.fade-in-up {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-out forwards;
}

.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }
.delay-500 { animation-delay: 0.5s; }
.delay-600 { animation-delay: 0.6s; }
.delay-700 { animation-delay: 0.7s; }
.delay-800 { animation-delay: 0.8s; }
.delay-900 { animation-delay: 0.9s; }
.delay-1000 { animation-delay: 1.0s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Penjualan',
                data: @json($sales),
                borderColor: '#ec4899',
                backgroundColor: 'rgba(236, 72, 153, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ec4899',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(236, 72, 153, 0.1)'
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#be185d'
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryLabels),
            datasets: [{
                data: @json($categoryValues),
                backgroundColor: [
                    '#ec4899',
                    '#f472b6',
                    '#f9a8d4',
                    '#fce7f3',
                    '#fdf2f8'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        color: '#6b7280'
                    }
                }
            }
        }
    });
</script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
});
</script>
@endsection
