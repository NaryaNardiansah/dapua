@extends('layouts.app')

@section('title', 'Tracking Pesanan - Dapur Sakura')

@section('content')
    <div class="luxury-tracking-page">
        <!-- Hero Section -->
        <div class="hero-section fade-in-up">
            <div class="hero-content">
                <div class="hero-title-container">
                    <h1 class="hero-title fade-in-up delay-200">
                        <div class="hero-icon-wrapper">
                            <i class="fas fa-truck hero-icon"></i>
                        </div>
                        <div class="hero-title-text">
                            <span class="hero-title-main">Tracking Pesanan</span>
                            <span class="hero-title-sub">{{ $order->order_code }}</span>
                        </div>
                    </h1>
                    <p class="hero-subtitle fade-in-up delay-300">Pantau perjalanan pesanan Anda secara real-time</p>
                </div>
                <div class="hero-decorative-elements">
                    <div class="decorative-line fade-in-up delay-400"></div>
                    <div class="decorative-dots fade-in-up delay-500"></div>
                    <div class="decorative-circle fade-in-up delay-600"></div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="hero-actions fade-in-up delay-400">
                <button onclick="goBack()" class="back-btn">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Halaman Sebelumnya
                </button>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <!-- Order Summary Card -->
            <div class="luxury-card fade-in-up delay-100" data-aos="fade-up">
                <div class="card-header-section">
                    <div class="order-info-left">
                        <h2 class="order-title">Pesanan #{{ $order->order_code }}</h2>
                        <div class="order-meta">
                            <span class="meta-item">
                                <i class="fas fa-calendar"></i>
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-user"></i>
                                {{ $order->recipient_name }}
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-phone"></i>
                                {{ $order->recipient_phone }}
                            </span>
                        </div>
                    </div>
                    <div class="order-info-right">
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        @if($order->is_cancellable && in_array($order->status, ['pending', 'diproses']))
                            <button onclick="showCancelModal()" class="cancel-btn">
                                <i class="fas fa-times"></i>Batalkan
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-section">
                    <div class="progress-header">
                        <span>Progress Pesanan</span>
                        <span id="progress-percentage">{{ $progressPercentage }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div id="progress-bar" class="progress-bar-fill" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="progress-steps">
                        <div
                            class="progress-step {{ $progressPercentage >= 20 ? 'completed' : ($progressPercentage >= 10 ? 'active' : '') }}">
                            <div class="step-marker">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span class="step-label">Diterima</span>
                        </div>
                        <div
                            class="progress-step {{ $progressPercentage >= 50 ? 'completed' : ($progressPercentage >= 40 ? 'active' : '') }}">
                            <div class="step-marker">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span class="step-label">Diproses</span>
                        </div>
                        <div
                            class="progress-step {{ $progressPercentage >= 80 ? 'completed' : ($progressPercentage >= 70 ? 'active' : '') }}">
                            <div class="step-marker">
                                <i class="fas fa-truck"></i>
                            </div>
                            <span class="step-label">Dikirim</span>
                        </div>
                        <div
                            class="progress-step {{ $progressPercentage >= 100 ? 'completed' : ($progressPercentage >= 90 ? 'active' : '') }}">
                            <div class="step-marker">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <span class="step-label">Selesai</span>
                        </div>
                    </div>
                </div>

                <!-- ETA Information -->
                @if($eta)
                    <div class="eta-card">
                        <div class="eta-content">
                            <div class="eta-info">
                                <h3 class="eta-title">
                                    <i class="fas fa-clock"></i>
                                    Estimasi Waktu Tiba
                                </h3>
                                <p class="eta-time">
                                    {{ $eta['estimated_time'] }} - {{ $eta['estimated_date'] }}
                                </p>
                                @if($eta['is_delayed'])
                                    <p class="eta-status delayed">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Terlambat {{ $eta['delay_minutes'] }} menit
                                    </p>
                                @else
                                    <p class="eta-status on-time">
                                        <i class="fas fa-check-circle"></i>
                                        {{ $eta['minutes_remaining'] }} menit lagi
                                    </p>
                                @endif
                            </div>
                            <div class="eta-countdown">
                                <div class="countdown-number" id="countdown-timer">
                                    {{ $eta['minutes_remaining'] }}
                                </div>
                                <div class="countdown-label">menit</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                <!-- Timeline Section -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Timeline -->
                    <div class="luxury-card fade-in-up delay-200" data-aos="fade-up">
                        <div class="card-title-section">
                            <div class="card-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <h3 class="card-title">Riwayat Pesanan</h3>
                        </div>
                        <div class="timeline-container">
                            @forelse($order->timeline ?? [] as $timeline)
                                <div class="timeline-item">
                                    <div class="timeline-marker" style="background: {{ $timeline->color ?? '#ec4899' }}"></div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <div class="timeline-title-wrapper">
                                                @if($timeline->icon)
                                                    <i class="fas fa-{{ $timeline->icon }} timeline-icon"></i>
                                                @endif
                                                <h4 class="timeline-title">{{ $timeline->title }}</h4>
                                            </div>
                                            <span
                                                class="timeline-time">{{ \Carbon\Carbon::parse($timeline->timestamp)->format('d M Y, H:i') }}</span>
                                        </div>
                                        @if($timeline->description)
                                            <p class="timeline-description">{{ $timeline->description }}</p>
                                        @endif
                                        @if($timeline->notes)
                                            <p class="timeline-notes">{{ $timeline->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada riwayat pesanan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="luxury-card fade-in-up delay-300" data-aos="fade-up">
                        <div class="card-title-section">
                            <div class="card-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h3 class="card-title">Item Pesanan</h3>
                        </div>
                        <div class="order-items-list">
                            @forelse($order->orderItems ?? [] as $item)
                                <div class="order-item-card">
                                    <div class="item-image">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}">
                                        @else
                                            <div class="item-placeholder">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="item-details">
                                        <h4 class="item-name">{{ $item->product_name ?? $item->product->name }}</h4>
                                        <p class="item-quantity">{{ $item->quantity }} x Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="item-total">
                                        Rp {{ number_format($item->line_total, 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Tidak ada item pesanan</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="subtotal-amount">Rp
                                    {{ number_format($order->subtotal ?? $order->orderItems->sum('line_total'), 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Ongkir</span>
                                <span id="shipping-amount">Rp
                                    {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount_total > 0)
                                <div class="summary-row discount">
                                    <span>Diskon</span>
                                    <span>- Rp {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="summary-row total">
                                <span>Total</span>
                                <span id="grand-total-amount">Rp
                                    {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Delivery Address -->
                    <div class="luxury-card fade-in-up delay-400" data-aos="fade-up">
                        <div class="card-title-section">
                            <div class="card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="card-title">Alamat Pengiriman</h3>
                        </div>
                        <div class="address-content">
                            <p class="address-name">{{ $order->recipient_name }}</p>
                            <p class="address-phone">{{ $order->recipient_phone }}</p>
                            <p class="address-text">{{ $order->address_line }}</p>
                            @if($order->latitude && $order->longitude)
                                <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}"
                                    target="_blank" class="map-link">
                                    <i class="fas fa-external-link-alt"></i>
                                    Lihat di Maps
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="luxury-card fade-in-up delay-500" data-aos="fade-up">
                        <div class="card-title-section">
                            <div class="card-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3 class="card-title">Informasi Pembayaran</h3>
                        </div>
                        <div class="payment-content">
                            <div class="payment-row">
                                <span class="payment-label">Metode</span>
                                <span class="payment-value">{{ ucfirst($order->payment_method ?? 'COD') }}</span>
                            </div>
                            <div class="payment-row">
                                <span class="payment-label">Status</span>
                                <span class="payment-status {{ $order->payment_status ?? 'pending' }}">
                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                </span>
                            </div>
                            @if(isset($snapToken) && $snapToken)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <button id="pay-now-btn"
                                        class="w-full font-bold py-2.5 px-4 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                        style="background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink)); color: white; border: none;">
                                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Driver Information -->
                    @if($order->driver)
                        <div class="luxury-card driver-card fade-in-up delay-600" data-aos="fade-up">
                            <div class="card-title-section">
                                <div class="card-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h3 class="card-title">Driver Pengiriman</h3>
                            </div>
                            <div class="driver-content">
                                <div class="driver-profile">
                                    <div class="driver-avatar">
                                        @if($order->driver->photo)
                                            <img src="{{ asset('storage/' . $order->driver->photo) }}"
                                                alt="{{ $order->driver->name }}">
                                        @else
                                            <div class="driver-avatar-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="driver-info">
                                        <h4 class="driver-name">{{ $order->driver->name }}</h4>
                                        <p class="driver-phone">{{ $order->driver->phone ?? 'N/A' }}</p>
                                        <div class="driver-rating">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i <= ($order->driver->rating ?? 5) ? 'filled' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <span class="rating-text">{{ $order->driver->rating ?? 5.0 }}/5.0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="driver-actions">
                                    @php
                                        $driverPhone = preg_replace('/[^0-9]/', '', $order->driver->phone ?? '');
                                        if (str_starts_with($driverPhone, '0')) {
                                            $driverPhone = '62' . substr($driverPhone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $driverPhone ?: '6281234567890' }}?text=Halo%20{{ urlencode($order->driver->name) }},%20saya%20ingin%20menanyakan%20tentang%20pesanan%20%23{{ $order->order_code }}"
                                        target="_blank" class="driver-chat-btn">
                                        <i class="fab fa-whatsapp"></i>
                                        Chat Driver
                                    </a>
                                    @if($order->driver->phone)
                                        <a href="tel:{{ $order->driver->phone }}" class="driver-call-btn">
                                            <i class="fas fa-phone"></i>
                                            Telepon
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancel-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                onclick="closeCancelModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <i class="text-red-600 fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Batalkan Pesanan
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.
                                </p>
                                <div class="mt-4">
                                    <label for="cancel-reason" class="block text-sm font-medium text-gray-700">Alasan
                                        Pembatalan</label>
                                    <textarea id="cancel-reason" rows="3"
                                        class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                                        placeholder="Contoh: Salah pesan menu, ingin mengubah alamat, dll."></textarea>
                                    <p id="cancel-error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitCancel()"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Batalkan Pesanan
                    </button>
                    <button type="button" onclick="closeCancelModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        /* Luxury Tracking Page Styles */
        .luxury-tracking-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            padding: 4rem 0;
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
            font-size: 3rem;
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

        /* Luxury Card */
        .luxury-card {
            background: var(--pure-white);
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            border: 1px solid rgba(236, 72, 153, 0.1);
            transition: all 0.3s ease;
        }

        .luxury-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-2xl);
        }

        .card-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-info-left {
            flex: 1;
        }

        .order-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.75rem;
        }

        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .meta-item i {
            color: var(--primary-pink);
        }

        .order-info-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
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

        .cancel-btn {
            padding: 0.5rem 1rem;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cancel-btn:hover {
            background: #fecaca;
            transform: translateY(-2px);
        }

        /* Progress Section */
        .progress-section {
            margin-bottom: 2rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .progress-bar-container {
            width: 100%;
            height: 12px;
            background: var(--gray-200);
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 6px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 1rem;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-200);
            z-index: 0;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .step-marker {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            font-size: 0.875rem;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .progress-step.completed .step-marker {
            background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
            color: var(--pure-white);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.2);
        }

        .progress-step.active .step-marker {
            background: var(--primary-pink);
            color: var(--pure-white);
            animation: pulse 2s infinite;
        }

        .step-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-weight: 500;
            text-align: center;
        }

        .progress-step.completed .step-label {
            color: var(--primary-pink);
            font-weight: 600;
        }

        .progress-step.active .step-label {
            color: var(--primary-pink);
            font-weight: 600;
        }

        /* ETA Card */
        .eta-card {
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.8) 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid rgba(236, 72, 153, 0.2);
        }

        .eta-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .eta-info {
            flex: 1;
        }

        .eta-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .eta-title i {
            color: var(--primary-pink);
        }

        .eta-time {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.5rem;
        }

        .eta-status {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .eta-status.delayed {
            color: #dc2626;
        }

        .eta-status.on-time {
            color: #059669;
        }

        .eta-countdown {
            text-align: center;
        }

        .countdown-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-pink);
            line-height: 1;
        }

        .countdown-label {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        /* Card Title Section */
        .card-title-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-pink);
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
            font-size: 1.125rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        /* Timeline */
        .timeline-container {
            position: relative;
        }

        .timeline-item {
            position: relative;
            padding-left: 2.5rem;
            padding-bottom: 2rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 2rem;
            bottom: 0;
            width: 2px;
            background: var(--light-pink);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 50%;
            border: 3px solid var(--pure-white);
            box-shadow: 0 0 0 3px var(--light-pink);
            z-index: 1;
        }

        .timeline-content {
            background: var(--light-pink);
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .timeline-item:hover .timeline-content {
            background: rgba(236, 72, 153, 0.15);
            transform: translateX(5px);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            gap: 1rem;
        }

        .timeline-title-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .timeline-icon {
            color: var(--primary-pink);
            font-size: 1rem;
        }

        .timeline-title {
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1rem;
        }

        .timeline-time {
            font-size: 0.75rem;
            color: var(--gray-600);
            white-space: nowrap;
        }

        .timeline-description {
            font-size: 0.875rem;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .timeline-notes {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-style: italic;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(236, 72, 153, 0.2);
        }

        /* Order Items */
        .order-items-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .order-item-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--light-pink);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .order-item-card:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            font-size: 1.5rem;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .item-quantity {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .item-total {
            font-weight: 700;
            color: var(--primary-pink);
        }

        /* Order Summary */
        .order-summary {
            border-top: 2px solid var(--light-pink);
            padding-top: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            color: var(--gray-700);
        }

        .summary-row.discount {
            color: #059669;
            font-weight: 600;
        }

        .summary-row.total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
            border-top: 2px solid var(--light-pink);
            margin-top: 0.5rem;
            padding-top: 1rem;
        }

        /* Address Content */
        .address-content {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .address-name {
            font-weight: 700;
            color: var(--gray-800);
            font-size: 1.125rem;
        }

        .address-phone {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .address-text {
            color: var(--gray-700);
            line-height: 1.6;
        }

        .map-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .map-link:hover {
            color: var(--dark-pink);
            transform: translateX(5px);
        }

        /* Payment Content */
        .payment-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-label {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .payment-value {
            font-weight: 600;
            color: var(--gray-800);
        }

        .payment-status {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .payment-status.paid {
            background: #d1fae5;
            color: #059669;
        }

        .payment-status.pending {
            background: #fef3c7;
            color: #d97706;
        }


        /* Driver Card */
        .driver-card {
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.9) 100%);
            border: 2px solid rgba(236, 72, 153, 0.2);
        }

        .driver-content {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .driver-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .driver-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.2);
        }

        .driver-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .driver-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--pure-white);
            font-size: 1.5rem;
        }

        .driver-info {
            flex: 1;
        }

        .driver-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
        }

        .driver-phone {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .driver-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .rating-stars {
            display: flex;
            gap: 0.125rem;
        }

        .rating-stars .fa-star {
            color: var(--gray-300);
            font-size: 0.75rem;
        }

        .rating-stars .fa-star.filled {
            color: #fbbf24;
        }

        .rating-text {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        .driver-actions {
            display: flex;
            gap: 0.75rem;
        }

        .driver-chat-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: #25D366;
            color: var(--pure-white);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }

        .driver-chat-btn:hover {
            background: #1DA851;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
        }

        .driver-call-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--primary-pink);
            color: var(--pure-white);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .driver-call-btn:hover {
            background: var(--dark-pink);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-400);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Hero Actions */
        .hero-actions {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 10;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 1);
            border-color: var(--primary-pink);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.25);
            transform: translateY(-2px);
        }

        .back-btn i {
            font-size: 0.875rem;
            margin-right: 0.5rem;
        }

        /* Responsive Back Button */
        @media (max-width: 768px) {
            .hero-actions {
                top: 1rem;
                left: 1rem;
            }

            .back-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            .back-btn i {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .hero-actions {
                top: 0.5rem;
                left: 0.5rem;
            }

            .back-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
            }

            .back-btn i {
                font-size: 0.75rem;
            }
        }

        /* Animations */
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

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

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

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                flex-direction: column;
                gap: 1rem;
            }

            .hero-title-main {
                font-size: 2rem;
            }

            .hero-icon {
                font-size: 2.5rem;
            }

            .card-header-section {
                flex-direction: column;
            }

            .order-info-right {
                width: 100%;
                justify-content: flex-start;
            }

            .eta-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .countdown-number {
                font-size: 2rem;
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
                once: true,
                mirror: false
            });
        });

        // Function to go back to previous page
        function goBack() {
            // Show loading screen
            if (typeof showLoadingScreen === 'function') {
                showLoadingScreen('Kembali ke halaman sebelumnya...');
            }

            // Redirect to my orders page if authenticated, else home
            window.location.href = "{{ auth()->check() ? route('orders.index') : route('home') }}";
        }

        // Cancel Modal Functions
        function showCancelModal() {
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
            document.getElementById('cancel-reason').value = '';
            document.getElementById('cancel-error').classList.add('hidden');
        }

        function submitCancel() {
            const reason = document.getElementById('cancel-reason').value;
            if (!reason.trim()) {
                const errorEl = document.getElementById('cancel-error');
                errorEl.textContent = 'Mohon isi alasan pembatalan';
                errorEl.classList.remove('hidden');
                return;
            }

            if (typeof showLoadingScreen === 'function') {
                showLoadingScreen('Membatalkan pesanan...');
            }

            // CSRF Token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch("{{ route('tracking.cancel', $order->tracking_code) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        if (typeof hideLoadingScreen === 'function') hideLoadingScreen();
                        const errorEl = document.getElementById('cancel-error');
                        errorEl.textContent = data.message || 'Gagal membatalkan pesanan';
                        errorEl.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    if (typeof hideLoadingScreen === 'function') hideLoadingScreen();
                    console.error('Error:', error);
                    const errorEl = document.getElementById('cancel-error');
                    errorEl.textContent = 'Terjadi kesalahan sistem';
                    errorEl.classList.remove('hidden');
                });
        }

        // Handle browser back button
        window.addEventListener('popstate', function (event) {
            // Hide loading screen when navigating back
            if (typeof hideLoadingScreen === 'function') {
                setTimeout(() => {
                    hideLoadingScreen();
                }, 500);
            }
        });

        @if(isset($snapToken) && $snapToken)
            document.addEventListener('DOMContentLoaded', function () {
                const payBtn = document.getElementById('pay-now-btn');
                if (payBtn) {
                    payBtn.addEventListener('click', function () {
                        snap.pay('{{ $snapToken }}', {
                            onSuccess: function (result) {
                                if (typeof showLoadingScreen === 'function') showLoadingScreen('Memverifikasi pembayaran...');

                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = "{{ route('payment.success') }}";

                                const csrf = document.createElement('input');
                                csrf.type = 'hidden';
                                csrf.name = '_token';
                                csrf.value = "{{ csrf_token() }}";
                                form.appendChild(csrf);

                                const res = document.createElement('input');
                                res.type = 'hidden';
                                res.name = 'payment_result';
                                res.value = JSON.stringify(result);
                                form.appendChild(res);

                                document.body.appendChild(form);
                                form.submit();
                            },
                            onPending: function (result) { location.reload(); },
                            onError: function (result) { location.reload(); },
                            onClose: function () { location.reload(); }
                        });
                    });
                }
            });
        @endif
    </script>

    @if(isset($clientKey))
        <script
            src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ $clientKey }}"></script>
    @endif
@endsection