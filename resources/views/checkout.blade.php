@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Checkout - {{ config('app.name', 'Dapur Sakura') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-pink: #ec4899;
            --secondary-pink: #f472b6;
            --dark-pink: #db2777;
            --light-pink: #fce7f3;
            --pure-white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 50%, var(--gray-50) 100%);
            min-height: 100vh;
        }

        .checkout-container {
            min-height: 100vh;
            position: relative;
        }

        /* Decorative Background */
        .bg-decoration {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            top: 0;
            left: 0;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.06;
        }

        .decoration-circle-1 {
            width: 600px;
            height: 600px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            top: -200px;
            right: -200px;
            animation: float 30s ease-in-out infinite;
        }

        .decoration-circle-2 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--secondary-pink), var(--primary-pink));
            bottom: -150px;
            left: -150px;
            animation: float 25s ease-in-out infinite reverse;
        }

        .decoration-circle-3 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }

            66% {
                transform: translate(-20px, 20px) rotate(240deg);
            }
        }

        /* Navigation */
        .checkout-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.08);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-icon:hover {
            transform: rotate(5deg) scale(1.05);
        }

        .logo-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            text-decoration: none;
            transition: all 0.3s ease;
            color: var(--gray-700);
        }

        .nav-link:hover {
            background: var(--light-pink);
            color: var(--primary-pink);
        }

        .nav-link.primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .cart-link {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.6875rem;
            font-weight: 900;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            animation: pulse-badge 2s ease-in-out infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(236, 72, 153, 0.15);
            border: 2px solid rgba(236, 72, 153, 0.1);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .profile-dropdown.active .profile-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.25rem;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 0.9375rem;
            font-weight: 600;
        }

        .profile-dropdown-item:first-child {
            border-radius: 10px 10px 0 0;
        }

        .profile-dropdown-item:last-child {
            border-radius: 0 0 10px 10px;
        }

        .profile-dropdown-item:hover {
            background: var(--light-pink);
            color: var(--primary-pink);
        }

        .profile-dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .profile-dropdown-divider {
            height: 1px;
            background: rgba(236, 72, 153, 0.1);
            margin: 0.25rem 0;
        }

        /* Content */
        .checkout-content {
            position: relative;
            z-index: 10;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Hero Section */
        .checkout-hero {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem 3rem;
            margin-bottom: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.15);
            position: relative;
            overflow: hidden;
        }

        .checkout-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink), var(--primary-pink));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .checkout-hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .checkout-hero-title {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .checkout-hero-subtitle {
            font-size: 1.125rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .checkout-hero-badge {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.08));
            backdrop-filter: blur(4px);
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 16px;
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .checkout-hero-badge-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .checkout-hero-badge-value {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Main Grid */
        .checkout-main-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 2rem;
        }

        /* Card */
        .checkout-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.15);
            padding: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .checkout-card-title {
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Order Info */
        .checkout-order-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .checkout-info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .checkout-info-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .checkout-info-value {
            font-size: 1.125rem;
            color: var(--gray-900);
            font-weight: 700;
        }

        .checkout-info-item.full-width {
            grid-column: 1 / -1;
        }

        /* Order Items */
        .checkout-items-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .checkout-item {
            display: flex;
            gap: 1rem;
            align-items: center;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(2px);
            border-radius: 12px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .checkout-item:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.15);
            border-color: rgba(236, 72, 153, 0.25);
        }

        .checkout-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid rgba(236, 72, 153, 0.2);
            flex-shrink: 0;
        }

        .checkout-item-image-placeholder {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(248, 250, 252, 0.2));
            border: 2px solid rgba(236, 72, 153, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(236, 72, 153, 0.4);
            flex-shrink: 0;
        }

        .checkout-item-info {
            flex: 1;
            min-width: 0;
        }

        .checkout-item-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .checkout-item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
        }

        .checkout-item-quantity {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        .checkout-item-price {
            font-size: 1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Cost Breakdown */
        .checkout-cost-breakdown {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(3px);
            border-radius: 16px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            padding: 1.5rem;
        }

        .checkout-cost-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
        }

        .checkout-cost-row:last-child {
            border-bottom: none;
        }

        .checkout-cost-label {
            font-size: 0.95rem;
            color: var(--gray-700);
            font-weight: 600;
        }

        .checkout-cost-value {
            font-size: 0.95rem;
            color: var(--gray-900);
            font-weight: 700;
        }

        .checkout-cost-row.total {
            margin-top: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.2);
        }

        .checkout-cost-row.total .checkout-cost-label {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .checkout-cost-row.total .checkout-cost-value {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Payment Section */
        .checkout-payment-section {
            position: sticky;
            top: 2rem;
            height: fit-content;
        }

        .checkout-payment-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.15);
            padding: 2.5rem;
        }

        .checkout-payment-title {
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkout-payment-info {
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.08));
            backdrop-filter: blur(3px);
            border-radius: 12px;
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .checkout-payment-info-text {
            font-size: 0.95rem;
            color: var(--gray-700);
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .checkout-payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .checkout-payment-method {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(2px);
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--gray-700);
            font-weight: 600;
        }

        .checkout-pay-button {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            border-radius: 16px;
            padding: 1.25rem 2rem;
            font-size: 1.25rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .checkout-pay-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.4);
        }

        .checkout-pay-loading {
            display: none;
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(2px);
            border-radius: 12px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            margin-top: 1rem;
        }

        .checkout-pay-loading.active {
            display: block;
        }

        .checkout-pay-loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(236, 72, 153, 0.2);
            border-top-color: var(--primary-pink);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .checkout-pay-loading-text {
            font-size: 0.95rem;
            color: var(--gray-700);
            font-weight: 600;
        }

        /* Snap Overlay */
        .checkout-snap-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(4px);
            z-index: 40;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .checkout-snap-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .checkout-main-grid {
                grid-template-columns: 1fr;
            }

            .checkout-payment-section {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .checkout-hero-title {
                font-size: 2rem;
            }

            .checkout-hero {
                padding: 1.5rem;
            }

            .checkout-card,
            .checkout-payment-card {
                padding: 1.5rem;
            }

            .checkout-order-info {
                grid-template-columns: 1fr;
            }

            .checkout-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .checkout-item-image,
            .checkout-item-image-placeholder {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="checkout-container">
        <!-- Background Decorations -->
        <div class="bg-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>

        <!-- Navigation -->
        <nav class="checkout-nav">
            <div class="nav-container">
                <div class="logo-section">
                    <div class="logo-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="logo-text">{{ config('app.name', 'Dapur Sakura') }}</div>
                </div>
                <div class="nav-links">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link">Menu</a>
                    <a href="{{ route('cart.index') }}" class="nav-link cart-link">
                        Keranjang
                        @php
                            $cart = session('cart', []);
                            $cartCount = array_sum($cart);
                        @endphp
                        @if($cartCount > 0)
                            <span class="cart-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="nav-link primary">Dashboard</a>
                        @else
                            <div class="profile-dropdown">
                                <div class="nav-link profile-dropdown-toggle" onclick="toggleProfileDropdown()">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                                </div>
                                <div class="profile-dropdown-menu">
                                    <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">
                                        <i class="fas fa-user-edit"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="profile-dropdown-item">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                    <div class="profile-dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="profile-dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                        <a href="{{ route('register') }}" class="nav-link primary">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="checkout-content">
            <!-- Hero Section -->
            <div class="checkout-hero">
                <div class="checkout-hero-content">
                    <div>
                        <h1 class="checkout-hero-title">
                            <i class="fas fa-credit-card"></i> Konfirmasi Pembayaran
                        </h1>
                        <p class="checkout-hero-subtitle">Periksa ringkasan pesanan Anda lalu lanjutkan pembayaran</p>
                    </div>
                    <div class="checkout-hero-badge">
                        <div class="checkout-hero-badge-label">Order ID</div>
                        <div class="checkout-hero-badge-value">#{{ $order->id }}</div>
                    </div>
                </div>
            </div>

            <div class="checkout-main-grid">
                <!-- Order Summary Section -->
                <div class="checkout-summary-section">
                    <!-- Order Information -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-info-circle"></i> Informasi Pesanan
                        </h2>
                        <div class="checkout-order-info">
                            <div class="checkout-info-item">
                                <div class="checkout-info-label">Nama Penerima</div>
                                <div class="checkout-info-value">{{ $order->recipient_name }}</div>
                            </div>
                            <div class="checkout-info-item">
                                <div class="checkout-info-label">Telepon</div>
                                <div class="checkout-info-value">{{ $order->recipient_phone }}</div>
                            </div>
                            <div class="checkout-info-item full-width">
                                <div class="checkout-info-label">Alamat Pengiriman</div>
                                <div class="checkout-info-value">{{ $order->address_line }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-box"></i> Item Pesanan
                        </h2>
                        <div class="checkout-items-list">
                            @foreach($order->orderItems as $item)
                                <div class="checkout-item">
                                    @if($item->product && $item->product->image_path)
                                        <img src="{{ Storage::url($item->product->image_path) }}"
                                            alt="{{ $item->product_name }}" class="checkout-item-image">
                                    @else
                                        <div class="checkout-item-image-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    <div class="checkout-item-info">
                                        <div class="checkout-item-name">{{ $item->product_name }}</div>
                                        <div class="checkout-item-details">
                                            <div class="checkout-item-quantity">Qty: {{ $item->quantity }}</div>
                                            <div class="checkout-item-price">Rp
                                                {{ number_format($item->line_total, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">
                            <i class="fas fa-receipt"></i> Rincian Biaya
                        </h2>
                        <div class="checkout-cost-breakdown">
                            <div class="checkout-cost-row">
                                <span class="checkout-cost-label">Subtotal</span>
                                <span class="checkout-cost-value">Rp
                                    {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="checkout-cost-row">
                                <span class="checkout-cost-label">Ongkir</span>
                                <span class="checkout-cost-value">Rp
                                    {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount_total > 0)
                                <div class="checkout-cost-row">
                                    <span class="checkout-cost-label">Diskon</span>
                                    <span class="checkout-cost-value" style="color: rgba(34, 139, 34, 0.9);">- Rp
                                        {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="checkout-cost-row total">
                                <span class="checkout-cost-label">Total Pembayaran</span>
                                <span class="checkout-cost-value">Rp
                                    {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="checkout-payment-section">
                    <div class="checkout-payment-card">
                        <h2 class="checkout-payment-title">
                            <i class="fas fa-lock"></i> Pembayaran
                        </h2>

                        <div class="checkout-payment-info">
                            <p class="checkout-payment-info-text">
                                <i class="fas fa-shield-alt"></i> Pembayaran aman melalui Midtrans Snap
                            </p>
                            <div class="checkout-payment-methods">
                                <span class="checkout-payment-method">
                                    <i class="fas fa-credit-card"></i> Kartu
                                </span>
                                <span class="checkout-payment-method">
                                    <i class="fas fa-wallet"></i> E-Wallet
                                </span>
                                <span class="checkout-payment-method">
                                    <i class="fas fa-university"></i> Virtual Account
                                </span>
                            </div>
                        </div>

                        <button id="pay-button" class="checkout-pay-button">
                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                        </button>

                        <div id="pay-loading" class="checkout-pay-loading">
                            <div class="checkout-pay-loading-spinner"></div>
                            <div class="checkout-pay-loading-text">Memuat Snap Payment...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Snap Overlay -->
        <div id="snap-overlay" class="checkout-snap-overlay"></div>
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ $clientKey }}"></script>
    <script>
        // Profile Dropdown
        function toggleProfileDropdown() {
            const dropdown = document.querySelector('.profile-dropdown');
            dropdown.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Payment
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('pay-button');
            var overlay = document.getElementById('snap-overlay');
            var loading = document.getElementById('pay-loading');

            if (!btn) return;

            btn.addEventListener('click', function () {
                if (loading) loading.classList.add('active');
                if (overlay) overlay.classList.add('active');

                snap.pay(@json($snapToken), {
                    onSuccess: function (result) {
                        // Create form and submit result to backend for verification
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = "{{ route('payment.success') }}";

                        // CSRF Token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        // Payment Result
                        const resultInput = document.createElement('input');
                        resultInput.type = 'hidden';
                        resultInput.name = 'payment_result';
                        resultInput.value = JSON.stringify(result);
                        form.appendChild(resultInput);

                        document.body.appendChild(form);
                        form.submit();
                    },
                    onPending: function (result) {
                        alert('Menunggu pembayaran');
                        if (overlay) overlay.classList.remove('active');
                        if (loading) loading.classList.remove('active');
                    },
                    onError: function (result) {
                        alert('Terjadi kesalahan pembayaran');
                        if (overlay) overlay.classList.remove('active');
                        if (loading) loading.classList.remove('active');
                    },
                    onClose: function () {
                        if (overlay) overlay.classList.remove('active');
                        if (loading) loading.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>

</html>