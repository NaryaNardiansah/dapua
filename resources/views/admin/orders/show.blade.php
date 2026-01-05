@extends('layouts.admin')

@section('content')
    <div class="luxury-order-detail-page">
        <!-- Hero Section -->
        <div class="hero-section fade-in-up">
            <div class="hero-content">
                <div class="hero-title-container">
                    <h1 class="hero-title fade-in-up delay-200">
                        <div class="hero-icon-wrapper">
                            <i class="fas fa-receipt hero-icon"></i>
                        </div>
                        <div class="hero-title-text">
                            <span class="hero-title-main">{{ $order->order_code ?? ('#' . $order->id) }}</span>
                            <span class="hero-title-sub">Detail Pesanan</span>
                        </div>
                    </h1>
                    <p class="hero-subtitle fade-in-up delay-300">Informasi lengkap dan manajemen pesanan pelanggan</p>
                </div>
                <div class="hero-decorative-elements">
                    <div class="decorative-line fade-in-up delay-400"></div>
                    <div class="decorative-dots fade-in-up delay-500"></div>
                    <div class="decorative-circle fade-in-up delay-600"></div>
                </div>
            </div>
        </div>

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
                <a href="{{ route('admin.orders.index') }}" class="action-btn secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>
                @if($order->tracking_code)
                    <a href="{{ $order->tracking_url }}" class="action-btn info"
                        onclick="showLoadingScreen('Membuka Tracking Link...')">
                        <i class="fas fa-truck mr-2"></i>Tracking Link
                    </a>
                @endif
                @if(!$order->driver_id)
                    <a href="{{ route('admin.delivery.index') }}" class="action-btn success">
                        <i class="fas fa-truck mr-2"></i>Assign Driver
                    </a>
                @endif
                <a href="{{ $whatsappLink }}" target="_blank" class="action-btn whatsapp">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp Customer
                </a>
            </div>
        </div>
        <!-- Main Content Grid -->
        <div class="main-content-grid">
            <!-- Left Column - Order Information -->
            <div class="main-content-left">
                <!-- Order Summary Card -->
                <div class="info-card fade-in-up delay-400" data-aos="fade-up">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="card-title">
                            <h3>Ringkasan Pesanan</h3>
                            <p>Informasi dasar pesanan dan pelanggan</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <label class="info-label">Tanggal Pesanan</label>
                                <div class="info-value">{{ $order->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Status</label>
                                <div class="info-value">
                                    <span class="status-badge status-{{ $order->status }}">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Penerima</label>
                                <div class="info-value">{{ $order->recipient_name }}</div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Nomor Telepon</label>
                                <div class="info-value">{{ $order->recipient_phone }}</div>
                            </div>
                            <div class="info-item full-width">
                                <label class="info-label">Alamat Pengiriman</label>
                                <div class="info-description">{{ $order->address_line }}</div>
                            </div>
                            @if($order->latitude && $order->longitude)
                                <div class="info-item">
                                    <label class="info-label">Koordinat</label>
                                    <div class="info-value-secondary">{{ $order->latitude }}, {{ $order->longitude }}</div>
                                </div>
                                @if($order->distance_meters)
                                    <div class="info-item">
                                        <label class="info-label">Jarak</label>
                                        <div class="info-value">{{ number_format($order->distance_meters / 1000, 2) }} km</div>
                                    </div>
                                @endif
                            @endif
                            @if($order->driver)
                                <div class="info-item">
                                    <label class="info-label">Driver</label>
                                    <div class="info-value">{{ $order->driver->name }}</div>
                                </div>
                                @if($order->driver->vehicle_type)
                                    <div class="info-item">
                                        <label class="info-label">Kendaraan</label>
                                        <div class="info-value">{{ $order->driver->vehicle_type }}
                                            {{ $order->driver->vehicle_number }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if($order->tracking_code)
                                <div class="info-item">
                                    <label class="info-label">Tracking Code</label>
                                    <div class="info-value">
                                        <a href="{{ $order->tracking_url }}" class="tracking-link"
                                            onclick="showLoadingScreen('Membuka Tracking Link...')">
                                            {{ $order->tracking_code }}
                                            <i class="fas fa-truck ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items Card -->
                <div class="info-card fade-in-up delay-500" data-aos="fade-up">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="card-title">
                            <h3>Item Pesanan</h3>
                            <p>Daftar produk yang dipesan</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="order-items-table">
                            <div class="table-header-row">
                                <div class="table-header-cell">Produk</div>
                                <div class="table-header-cell">Qty</div>
                                <div class="table-header-cell">Harga</div>
                                <div class="table-header-cell">Subtotal</div>
                            </div>
                            @foreach($order->orderItems as $item)
                                <div class="table-data-row">
                                    <div class="table-data-cell">
                                        <div class="product-name">{{ $item->product_name }}</div>
                                    </div>
                                    <div class="table-data-cell">
                                        <span class="quantity-badge">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="table-data-cell">
                                        <div class="price-text">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="table-data-cell">
                                        <div class="subtotal-text">Rp {{ number_format($item->line_total, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions & Map -->
            <div class="main-content-right">
                <!-- Status Update Card -->
                <div class="status-card fade-in-up delay-400" data-aos="fade-left">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="card-title">
                            <h3>Ubah Status</h3>
                            <p>Update status pesanan</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <form action="{{ route('admin.orders.update', $order) }}" method="post" class="status-form">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="form-label">Status Pesanan</label>
                                <select name="status" class="form-select">
                                    @foreach(['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $st)
                                        <option value="{{ $st }}" @selected($order->status === $st)>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="form-submit-btn">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Financial Summary Card -->
                <div class="financial-card fade-in-up delay-500" data-aos="fade-left">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="card-title">
                            <h3>Ringkasan Keuangan</h3>
                            <p>Detail pembayaran</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="financial-summary">
                            <div class="financial-item">
                                <span class="financial-label">Subtotal</span>
                                <span class="financial-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="financial-item">
                                <span class="financial-label">Ongkir</span>
                                <span class="financial-value">Rp
                                    {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="financial-item">
                                <span class="financial-label">Diskon</span>
                                <span class="financial-value discount">- Rp
                                    {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="financial-item total">
                                <span class="financial-label">Total</span>
                                <span class="financial-value">Rp
                                    {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information Card -->
                <div class="payment-info-card fade-in-up delay-550" data-aos="fade-left">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="card-title">
                            <h3>Informasi Pembayaran</h3>
                            <p>Detail transaksi Midtrans</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="payment-details">
                            <div class="payment-item">
                                <span class="payment-label">Status Pembayaran</span>
                                <span class="payment-value">
                                    @php
                                        $paymentStatusClass = match ($order->payment_status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'unpaid' => 'danger',
                                            'challenge' => 'info',
                                            default => 'secondary'
                                        };
                                        $paymentStatusText = match ($order->payment_status) {
                                            'paid' => 'Lunas',
                                            'pending' => 'Menunggu',
                                            'unpaid' => 'Belum Dibayar',
                                            'challenge' => 'Challenge',
                                            default => ucfirst($order->payment_status)
                                        };
                                    @endphp
                                    <span class="payment-status-badge {{ $paymentStatusClass }}">
                                        <i class="fas fa-circle status-indicator"></i>
                                        {{ $paymentStatusText }}
                                    </span>
                                </span>
                            </div>
                            <div class="payment-item">
                                <span class="payment-label">Metode Pembayaran</span>
                                <span class="payment-value">
                                    @if($order->payment_method === 'midtrans')
                                        <i class="fas fa-wallet mr-1"></i> Midtrans Payment Gateway
                                    @else
                                        {{ ucfirst($order->payment_method ?? 'Belum dipilih') }}
                                    @endif
                                </span>
                            </div>
                            @if($order->midtrans_order_id)
                                <div class="payment-item">
                                    <span class="payment-label">Midtrans Order ID</span>
                                    <span class="payment-value payment-id">
                                        <code>{{ $order->midtrans_order_id }}</code>
                                        <button onclick="copyToClipboard('{{ $order->midtrans_order_id }}')" class="copy-btn"
                                            title="Copy Order ID">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </span>
                                </div>
                            @endif
                            @if($order->payment_status === 'paid' && $order->updated_at)
                                <div class="payment-item">
                                    <span class="payment-label">Waktu Pembayaran</span>
                                    <span class="payment-value">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $order->updated_at->format('d M Y H:i') }}
                                        <span class="payment-time-ago">({{ $order->updated_at->diffForHumans() }})</span>
                                    </span>
                                </div>
                            @endif
                            @if($order->payment_status === 'paid')
                                <div class="payment-success-banner">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Pembayaran telah berhasil diverifikasi oleh Midtrans</span>
                                </div>
                            @elseif($order->payment_status === 'pending')
                                <div class="payment-pending-banner">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>Menunggu konfirmasi pembayaran dari Midtrans</span>
                                </div>
                            @elseif($order->payment_status === 'unpaid')
                                <div class="payment-unpaid-banner">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Pesanan belum dibayar oleh customer</span>
                                </div>
                            @endif
                            @if($order->midtrans_order_id)
                                <div class="payment-check-button-wrapper">
                                    <button onclick="checkPaymentStatus({{ $order->id }})" class="check-payment-btn"
                                        id="checkPaymentBtn">
                                        <i class="fas fa-sync-alt mr-2"></i>Check Payment Status
                                    </button>
                                    <small class="check-payment-hint">Klik untuk mengecek status pembayaran terbaru dari
                                        Midtrans</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Map Card -->
                @if($order->latitude && $order->longitude)
                    <div class="map-card fade-in-up delay-600" data-aos="fade-left">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="card-title">
                                <h3>Lokasi Pengiriman</h3>
                                <p>Peta lokasi customer</p>
                            </div>
                            <div class="map-controls">
                                <button id="toggle-layer" class="map-control-btn" data-tooltip="Toggle Layer">
                                    <i class="fas fa-layer-group"></i>
                                </button>
                                <button id="fullscreen-map" class="map-control-btn" data-tooltip="Fullscreen">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <div id="order-map" class="order-map"></div>
                            <div class="map-legend">
                                <div class="legend-item">
                                    <i class="fas fa-home legend-icon customer"></i>
                                    <span class="legend-text">Lokasi Customer</span>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-store legend-icon store"></i>
                                    <span class="legend-text">Lokasi Toko</span>
                                </div>
                                <div class="legend-item">
                                    <i class="fas fa-circle legend-icon radius"></i>
                                    <span class="legend-text">Radius Layanan</span>
                                </div>
                            </div>
                            <div class="map-actions">
                                <a href="https://www.google.com/maps/dir/{{ $storeLat }},{{ $storeLng }}/{{ $order->latitude }},{{ $order->longitude }}"
                                    target="_blank" class="map-action-btn navigation">
                                    <i class="fas fa-route mr-2"></i>Navigasi
                                </a>
                                <a href="{{ $whatsappLink }}" target="_blank" class="map-action-btn whatsapp">
                                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        /* Luxury Order Detail Styles */
        .luxury-order-detail-page {
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
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

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.2;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.4;
            }

            100% {
                transform: scale(1);
                opacity: 0.2;
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
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

        .action-btn.info {
            background: linear-gradient(45deg, #3b82f6, #2563eb);
            color: var(--pure-white);
        }

        .action-btn.info:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .action-btn.success {
            background: linear-gradient(45deg, #10b981, #059669);
            color: var(--pure-white);
        }

        .action-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .action-btn.whatsapp {
            background: linear-gradient(45deg, #25d366, #1da851);
            color: var(--pure-white);
        }

        .action-btn.whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
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

        /* Info Cards */
        .info-card,
        .status-card,
        .financial-card,
        .map-card {
            background: var(--pure-white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .info-card:hover,
        .status-card:hover,
        .financial-card:hover,
        .map-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-2xl);
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.8) 100%);
            padding: 1.5rem;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-icon {
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

        .card-title h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .card-title p {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin: 0.25rem 0 0 0;
        }

        .card-content {
            padding: 1.5rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .info-value-secondary {
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-600);
            font-family: 'Courier New', monospace;
            background: var(--gray-100);
            padding: 0.5rem;
            border-radius: 6px;
        }

        .info-description {
            font-size: 1rem;
            color: var(--gray-700);
            line-height: 1.6;
            background: var(--gray-50);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-pink);
        }

        .tracking-link {
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .tracking-link:hover {
            color: var(--dark-pink);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-diproses {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-dikirim {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .status-dibatalkan {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-dot {
            font-size: 0.5rem;
            animation: pulse 2s infinite;
        }

        /* Order Items Table */
        .order-items-table {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .table-header-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 8px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .table-data-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
            background: var(--pure-white);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .table-data-row:hover {
            background: var(--light-pink);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .table-header-cell,
        .table-data-cell {
            display: flex;
            align-items: center;
        }

        .product-name {
            font-weight: 600;
            color: var(--gray-800);
        }

        .quantity-badge {
            background: var(--primary-pink);
            color: var(--pure-white);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .price-text {
            font-weight: 500;
            color: var(--gray-700);
        }

        .subtotal-text {
            font-weight: 600;
            color: var(--primary-pink);
        }

        /* Status Form */
        .status-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
        }

        .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.875rem;
            background: var(--gray-50);
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            background: var(--pure-white);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .form-submit-btn {
            padding: 0.875rem 1.5rem;
            background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
            color: var(--pure-white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Financial Summary */
        .financial-summary {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .financial-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .financial-item.total {
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.8) 100%);
            border: 2px solid var(--primary-pink);
            font-weight: 700;
        }

        .financial-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
        }

        .financial-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .financial-value.discount {
            color: #ef4444;
        }

        .financial-item.total .financial-value {
            font-size: 1.125rem;
            color: var(--primary-pink);
        }

        /* Payment Information Card */
        .payment-info-card {
            background: var(--pure-white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .payment-info-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-2xl);
        }

        .payment-details {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .payment-item:hover {
            background: var(--light-pink);
            transform: translateX(5px);
        }

        .payment-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .payment-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .payment-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .payment-status-badge.success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .payment-status-badge.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .payment-status-badge.danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .payment-status-badge.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .payment-status-badge.secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .payment-id code {
            background: var(--gray-100);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            color: var(--primary-pink);
            border: 1px solid var(--gray-300);
        }

        .copy-btn {
            background: var(--primary-pink);
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .copy-btn:hover {
            background: var(--dark-pink);
            transform: scale(1.05);
        }

        .payment-time-ago {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: 400;
        }

        .payment-success-banner,
        .payment-pending-banner,
        .payment-unpaid-banner {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .payment-success-banner {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 2px solid #10b981;
            color: #065f46;
        }

        .payment-success-banner i {
            color: #10b981;
            font-size: 1.2rem;
        }

        .payment-pending-banner {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border: 2px solid #f59e0b;
            color: #92400e;
        }

        .payment-pending-banner i {
            color: #f59e0b;
            font-size: 1.2rem;
        }

        .payment-unpaid-banner {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border: 2px solid #ef4444;
            color: #991b1b;
        }

        .payment-unpaid-banner i {
            color: #ef4444;
            font-size: 1.2rem;
        }

        .payment-check-button-wrapper {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px dashed var(--gray-200);
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .check-payment-btn {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            font-size: 1rem;
        }

        .check-payment-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .check-payment-btn:active {
            transform: translateY(0);
        }

        .check-payment-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .check-payment-btn .fa-sync-alt {
            transition: transform 0.3s ease;
        }

        .check-payment-btn.loading .fa-sync-alt {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .check-payment-hint {
            color: var(--gray-500);
            font-size: 0.8rem;
            text-align: center;
            font-style: italic;
        }

        /* Map Card */
        .map-controls {
            display: flex;
            gap: 0.5rem;
        }

        .map-control-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--gray-200);
            background: var(--gray-100);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--gray-600);
        }

        .map-control-btn:hover {
            background: var(--gray-200);
            color: var(--gray-800);
        }

        .order-map {
            width: 100%;
            height: 300px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin: 1rem 0;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .legend-icon {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .legend-icon.customer {
            background: var(--primary-pink);
            color: var(--pure-white);
        }

        .legend-icon.store {
            background: #10b981;
            color: var(--pure-white);
        }

        .legend-icon.radius {
            background: #f59e0b;
            color: var(--pure-white);
        }

        .map-actions {
            display: flex;
            gap: 0.75rem;
        }

        .map-action-btn {
            flex: 1;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-action-btn.navigation {
            background: var(--primary-pink);
            color: var(--pure-white);
        }

        .map-action-btn.navigation:hover {
            background: var(--dark-pink);
            transform: translateY(-2px);
        }

        .map-action-btn.whatsapp {
            background: #25d366;
            color: var(--pure-white);
        }

        .map-action-btn.whatsapp:hover {
            background: #1da851;
            transform: translateY(-2px);
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

            .info-grid {
                grid-template-columns: 1fr;
            }

            .table-header-row,
            .table-data-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .table-header-cell,
            .table-data-cell {
                justify-content: space-between;
            }

            .map-legend {
                flex-direction: column;
            }

            .map-actions {
                flex-direction: column;
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

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @if($order->latitude && $order->longitude)
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Store location from settings
                const storeLat = {{ $storeLat }};
                const storeLng = {{ $storeLng }};

                // Customer location
                const customerLat = {{ $order->latitude }};
                const customerLng = {{ $order->longitude }};

                // Calculate delivery time estimate (roughly 30km/h average)
                const distanceKm = {{ $order->distance_meters / 1000 }};
                const estimatedMinutes = Math.round((distanceKm / 30) * 60);
                const estimatedTime = estimatedMinutes < 60 ? `${estimatedMinutes} menit` : `${Math.floor(estimatedMinutes / 60)} jam ${estimatedMinutes % 60} menit`;

                // Initialize map
                const map = L.map('order-map', {
                    zoomControl: false,
                    gestureHandling: true
                }).setView([storeLat, storeLng], 10);

                // Add zoom control
                L.control.zoom({ position: 'topright' }).addTo(map);

                // Layer management
                let currentLayer = 'street';
                const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                });

                const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '© Esri'
                });

                streetLayer.addTo(map);

                // Toggle layer functionality
                document.getElementById('toggle-layer').addEventListener('click', function () {
                    if (currentLayer === 'street') {
                        map.removeLayer(streetLayer);
                        satelliteLayer.addTo(map);
                        currentLayer = 'satellite';
                        this.innerHTML = '<i class="fas fa-layer-group mr-1"></i>Street';
                    } else {
                        map.removeLayer(satelliteLayer);
                        streetLayer.addTo(map);
                        currentLayer = 'street';
                        this.innerHTML = '<i class="fas fa-layer-group mr-1"></i>Satellite';
                    }
                });

                // Fullscreen functionality
                document.getElementById('fullscreen-map').addEventListener('click', function () {
                    const mapContainer = document.getElementById('order-map');
                    if (mapContainer.requestFullscreen) {
                        mapContainer.requestFullscreen();
                    } else if (mapContainer.webkitRequestFullscreen) {
                        mapContainer.webkitRequestFullscreen();
                    } else if (mapContainer.msRequestFullscreen) {
                        mapContainer.msRequestFullscreen();
                    }

                    // Resize map after fullscreen
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                });

                // Create custom icons with Font Awesome and enhanced styling
                const storeIcon = L.divIcon({
                    className: 'custom-marker store-marker',
                    html: `
                                                                <div class="marker-container">
                                                                    <div class="marker-icon">
                                                                        <i class="fas fa-store"></i>
                                                                    </div>
                                                                    <div class="marker-pulse"></div>
                                                                </div>
                                                            `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                const customerIcon = L.divIcon({
                    className: 'custom-marker customer-marker',
                    html: `
                                                                <div class="marker-container">
                                                                    <div class="marker-icon">
                                                                        <i class="fas fa-home"></i>
                                                                    </div>
                                                                    <div class="marker-pulse"></div>
                                                                </div>
                                                            `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                });

                // Add store marker
                const storeMarker = L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(map);

                storeMarker.bindPopup(`
                                                            <div class="popup-content">
                                                                <div class="popup-header">
                                                                    <i class="fas fa-store text-green-500 mr-2"></i>
                                                                    <strong>Dapur Sakura</strong>
                                                                </div>
                                                                <div class="popup-body">
                                                                    <div class="popup-info">
                                                                        <span class="popup-label">Lokasi Toko</span>
                                                                        <div class="popup-coords">${storeLat.toFixed(6)}, ${storeLng.toFixed(6)}</div>
                                                                    </div>
                                                                    <div class="popup-actions">
                                                                        <a href="https://www.google.com/maps?q=${storeLat},${storeLng}" target="_blank" class="popup-btn">
                                                                            <i class="fas fa-external-link-alt mr-1"></i>Buka Maps
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `, {
                    maxWidth: 250,
                    className: 'custom-popup'
                });

                // Add customer marker
                const customerMarker = L.marker([customerLat, customerLng], { icon: customerIcon }).addTo(map);

                customerMarker.bindPopup(`
                                                            <div class="popup-content">
                                                                <div class="popup-header">
                                                                    <i class="fas fa-home text-brand-500 mr-2"></i>
                                                                    <strong>{{ $order->recipient_name }}</strong>
                                                                </div>
                                                                <div class="popup-body">
                                                                    <div class="popup-info">
                                                                        <span class="popup-label">Lokasi Pengiriman</span>
                                                                        <div class="popup-address">{{ $order->address_line }}</div>
                                                                        <div class="popup-coords">${customerLat.toFixed(6)}, ${customerLng.toFixed(6)}</div>
                                                                    </div>
                                                                    <div class="popup-details">
                                                                        <div class="popup-detail">
                                                                            <i class="fas fa-box mr-1"></i>
                                                                            <span>Pesanan #{{ $order->id }}</span>
                                                                        </div>
                                                                        <div class="popup-detail">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            <span>Estimasi: ${estimatedTime}</span>
                                                                        </div>
                                                                        <div class="popup-detail">
                                                                            <i class="fas fa-truck mr-1"></i>
                                                                            <span>Status: {{ ucfirst($order->status) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="popup-actions">
                                                                        <a href="{{ $whatsappLink }}" 
                                                                           target="_blank" class="popup-btn popup-btn-whatsapp">
                                                                            <i class="fab fa-whatsapp mr-1"></i>WhatsApp
                                                                        </a>
                                                                        <a href="https://www.google.com/maps?q=${customerLat},${customerLng}" target="_blank" class="popup-btn">
                                                                            <i class="fas fa-external-link-alt mr-1"></i>Maps
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `, {
                    maxWidth: 300,
                    className: 'custom-popup'
                });

                // Add delivery radius circle (assuming 50km radius)
                const deliveryRadius = L.circle([storeLat, storeLng], {
                    color: '#F59E0B',
                    fillColor: '#FEF3C7',
                    fillOpacity: 0.1,
                    weight: 2,
                    radius: 50000 // 50km in meters
                }).addTo(map);

                // Add line between store and customer with enhanced styling
                const line = L.polyline([
                    [storeLat, storeLng],
                    [customerLat, customerLng]
                ], {
                    color: '#EC4899',
                    weight: 4,
                    dashArray: '15, 10',
                    opacity: 0.8
                }).addTo(map);

                // Fit map to show both markers with padding
                const group = new L.featureGroup([storeMarker, customerMarker, line, deliveryRadius]);
                map.fitBounds(group.getBounds().pad(0.15));

                // Add distance label with enhanced styling
                @if($order->distance_meters)
                    const distance = {{ $order->distance_meters / 1000 }};
                    const distanceText = `${distance.toFixed(2)} km`;

                    const midPoint = line.getBounds().getCenter();
                    L.marker(midPoint, {
                        icon: L.divIcon({
                            className: 'distance-label',
                            html: `
                                                                                            <div class="distance-container">
                                                                                                <div class="distance-icon">
                                                                                                    <i class="fas fa-route"></i>
                                                                                                </div>
                                                                                                <div class="distance-text">${distanceText}</div>
                                                                                            </div>
                                                                                        `,
                            iconSize: [80, 30],
                            iconAnchor: [40, 15]
                        })
                    }).addTo(map);
                @endif

                // Handle fullscreen change
                document.addEventListener('fullscreenchange', function () {
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                });
            });
        </script>

        <style>
            .custom-marker {
                background: transparent !important;
                border: none !important;
            }

            .marker-container {
                position: relative;
                width: 40px;
                height: 40px;
            }

            .marker-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                color: white;
                z-index: 2;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            .store-marker .marker-icon {
                background: linear-gradient(135deg, #10B981, #059669);
            }

            .customer-marker .marker-icon {
                background: linear-gradient(135deg, #EC4899, #DB2777);
            }

            .marker-pulse {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                animation: pulse 2s infinite;
            }

            .store-marker .marker-pulse {
                background: rgba(16, 185, 129, 0.3);
            }

            .customer-marker .marker-pulse {
                background: rgba(236, 72, 153, 0.3);
            }

            @keyframes pulse {
                0% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 1;
                }

                100% {
                    transform: translate(-50%, -50%) scale(2);
                    opacity: 0;
                }
            }

            .distance-label {
                background: transparent !important;
                border: none !important;
            }

            .distance-container {
                background: linear-gradient(135deg, #EC4899, #DB2777);
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 12px;
                font-weight: bold;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                white-space: nowrap;
            }

            .distance-icon {
                font-size: 10px;
            }

            .custom-popup .leaflet-popup-content-wrapper {
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }

            .popup-content {
                font-family: inherit;
            }

            .popup-header {
                display: flex;
                align-items: center;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 8px;
                padding-bottom: 8px;
                border-bottom: 1px solid #e5e7eb;
            }

            .popup-body {
                font-size: 14px;
            }

            .popup-info {
                margin-bottom: 12px;
            }

            .popup-label {
                display: block;
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 4px;
            }

            .popup-address {
                font-size: 13px;
                color: #374151;
                margin-bottom: 4px;
                line-height: 1.4;
            }

            .popup-coords {
                font-size: 11px;
                color: #9ca3af;
                font-family: monospace;
            }

            .popup-details {
                margin-bottom: 12px;
            }

            .popup-detail {
                display: flex;
                align-items: center;
                font-size: 12px;
                color: #6b7280;
                margin-bottom: 4px;
            }

            .popup-actions {
                display: flex;
                gap: 8px;
            }

            .popup-btn {
                flex: 1;
                padding: 6px 12px;
                background: #f3f4f6;
                color: #374151;
                text-decoration: none;
                border-radius: 6px;
                font-size: 12px;
                text-align: center;
                transition: all 0.2s;
            }

            .popup-btn:hover {
                background: #e5e7eb;
                color: #111827;
            }

            .popup-btn-whatsapp {
                background: #25D366;
                color: white;
            }

            .popup-btn-whatsapp:hover {
                background: #1DA851;
            }

            /* Mobile optimizations */
            @media (max-width: 640px) {
                .custom-popup .leaflet-popup-content-wrapper {
                    max-width: 280px;
                }

                .popup-header {
                    font-size: 14px;
                }

                .popup-actions {
                    flex-direction: column;
                }

                .popup-btn {
                    padding: 8px 12px;
                    font-size: 13px;
                }
            }
        </style>
    @endif

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                mirror: false
            });

            // Handle tracking links navigation
            const trackingLinks = document.querySelectorAll('a[href*="tracking"]');
            trackingLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    // Show loading screen
                    if (typeof showLoadingScreen === 'function') {
                        showLoadingScreen('Membuka Tracking Link...');
                    }

                    // Prevent multiple clicks
                    this.style.pointerEvents = 'none';

                    // Allow navigation to proceed
                    // The loading screen will be hidden when the new page loads
                });
            });

            // Handle page visibility change to prevent infinite loading
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    // Page is visible again, hide any loading screens
                    if (typeof hideLoadingScreen === 'function') {
                        setTimeout(() => {
                            hideLoadingScreen();
                        }, 500);
                    }
                }
            });

            // Handle window focus to prevent infinite loading
            window.addEventListener('focus', function () {
                if (typeof hideLoadingScreen === 'function') {
                    setTimeout(() => {
                        hideLoadingScreen();
                    }, 500);
                }
            });

            // Prevent loading screen from staying on page load
            setTimeout(() => {
                if (typeof hideLoadingScreen === 'function') {
                    hideLoadingScreen();
                }
            }, 1000);

            // WhatsApp link handler
            document.querySelectorAll('a[href*="wa.me"]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    const whatsappUrl = this.href;

                    // Try to open WhatsApp app first
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                    if (isMobile) {
                        // For mobile devices, try to open WhatsApp app
                        window.location.href = whatsappUrl;
                    } else {
                        // For desktop, open in new tab
                        const newWindow = window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

                        // If popup blocked, show fallback message
                        if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                            // Show notification
                            const notification = document.createElement('div');
                            notification.style.cssText = `
                                            position: fixed;
                                            top: 20px;
                                            right: 20px;
                                            background: #25D366;
                                            color: white;
                                            padding: 1rem 1.5rem;
                                            border-radius: 8px;
                                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                            z-index: 10000;
                                            font-size: 0.875rem;
                                            max-width: 300px;
                                        `;
                            notification.innerHTML = `
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fab fa-whatsapp" style="font-size: 1.25rem;"></i>
                                                <div>
                                                    <div style="font-weight: 600;">WhatsApp Link</div>
                                                    <div style="font-size: 0.75rem; opacity: 0.9;">Klik untuk membuka WhatsApp</div>
                                                </div>
                                            </div>
                                        `;

                            notification.addEventListener('click', function () {
                                window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
                                document.body.removeChild(notification);
                            });

                            document.body.appendChild(notification);

                            // Auto remove after 5 seconds
                            setTimeout(() => {
                                if (document.body.contains(notification)) {
                                    document.body.removeChild(notification);
                                }
                            }, 5000);
                        }
                    }
                });
            });
        });

        // Function to show loading screen
        function showLoadingScreen(message = 'Loading...') {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                const messageElement = loadingScreen.querySelector('.loading-message');
                if (messageElement) {
                    messageElement.textContent = message;
                }
                loadingScreen.style.display = 'flex';
                loadingScreen.style.opacity = '1';
            }
        }

        // Function to hide loading screen
        function hideLoadingScreen() {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                loadingScreen.style.opacity = '0';
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 300);
            }
        }

        // Function to copy text to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function () {
                // Show success notification
                const notification = document.createElement('div');
                notification.style.cssText = `
                                    position: fixed;
                                    top: 20px;
                                    right: 20px;
                                    background: linear-gradient(135deg, #10b981, #059669);
                                    color: white;
                                    padding: 1rem 1.5rem;
                                    border-radius: 12px;
                                    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
                                    z-index: 10000;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.75rem;
                                    animation: slideInRight 0.3s ease;
                                `;
                notification.innerHTML = `
                                    <i class="fas fa-check-circle"></i>
                                    <span>Order ID berhasil dicopy!</span>
                                `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 2000);
            }, function (err) {
                console.error('Could not copy text: ', err);
                alert('Gagal copy Order ID');
            });
        }

        // Function to check payment status from Midtrans
        async function checkPaymentStatus(orderId) {
            const btn = document.getElementById('checkPaymentBtn');
            const icon = btn.querySelector('.fa-sync-alt');

            // Disable button and show loading
            btn.disabled = true;
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Checking...';

            try {
                const response = await fetch(`/admin/orders/${orderId}/check-payment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success notification
                    showNotification(data.message, 'success');

                    // Reload page after 2 seconds to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Gagal mengecek status pembayaran', 'error');
                    btn.disabled = false;
                    btn.classList.remove('loading');
                    btn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Check Payment Status';
                }
            } catch (error) {
                console.error('Error checking payment status:', error);
                showNotification('Terjadi kesalahan saat mengecek status pembayaran', 'error');
                btn.disabled = false;
                btn.classList.remove('loading');
                btn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Check Payment Status';
            }
        }

        // Function to show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success'
                ? 'linear-gradient(135deg, #10b981, #059669)'
                : 'linear-gradient(135deg, #ef4444, #dc2626)';

            notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${bgColor};
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 12px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                    z-index: 10000;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    animation: slideInRight 0.3s ease;
                    max-width: 400px;
                `;

            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            notification.innerHTML = `
                    <i class="fas fa-${icon}"></i>
                    <span>${message}</span>
                `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 4000);
        }
    </script>
@endsection