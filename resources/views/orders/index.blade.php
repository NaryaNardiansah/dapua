@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pesanan Saya - {{ config('app.name', 'Dapur Sakura') }}</title>

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

        .cart-container {
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
            animation: pulse 20s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(50px, -50px) rotate(120deg);
            }

            66% {
                transform: translate(-40px, 40px) rotate(240deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.06;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.4);
                opacity: 0.1;
            }
        }

        /* Navigation */
        .cart-nav {
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
        .cart-content {
            position: relative;
            z-index: 10;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            font-size: 0.9375rem;
        }

        .breadcrumb a {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: var(--primary-pink);
        }

        .breadcrumb .separator {
            color: var(--gray-400);
        }

        .breadcrumb .current {
            color: var(--primary-pink);
            font-weight: 600;
        }

        /* Orders */
        .orders-hero {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.15);
            border: 1px solid rgba(236, 72, 153, 0.1);
            text-align: center;
        }

        .orders-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .order-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.15);
            border-color: var(--primary-pink);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
        }

        .order-meta h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .order-meta p {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-diproses {
            background: #fef3c7;
            color: #d97706;
        }

        .status-dikirim {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-selesai {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-dibatalkan {
            background: #f3f4f6;
            color: #6b7280;
        }

        .order-items {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .order-item {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
        }

        .item-details h4 {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .item-details p {
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .item-price {
            font-weight: 700;
            color: var(--gray-900);
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(236, 72, 153, 0.1);
        }

        .total-price {
            font-size: 1.25rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .order-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-outline {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--primary-pink);
            color: var(--primary-pink);
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--light-pink);
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                gap: 1rem;
            }

            .order-item {
                grid-template-columns: 60px 1fr;
            }

            .item-price {
                grid-column: 2;
            }

            .order-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .order-actions {
                width: 100%;
            }

            .btn-outline {
                flex: 1;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="cart-container">
        <!-- Background Decorations -->
        <div class="bg-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>

        <!-- Navigation -->
        <nav class="cart-nav">
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
                    <a href="{{ route('wishlist.index') }}" class="nav-link">Wishlist</a>
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

        <!-- Content -->
        <div class="cart-content">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" data-aos="fade-right">
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <span class="separator">/</span>
                <span class="current">Pesanan Saya</span>
            </nav>

            <div class="orders-hero" data-aos="fade-up">
                <h1 class="orders-hero-title">Riwayat Pesanan</h1>
                <p>Status dan riwayat pesanan Anda di Dapur Sakura</p>
            </div>

            <div class="orders-list">
                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        <div class="order-card" data-aos="fade-up">
                            <div class="order-header">
                                <div class="order-meta">
                                    <h3>Order #{{ $order->order_code ?? $order->id }}</h3>
                                    <p>{{ $order->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                <span class="order-status status-{{ strtolower($order->status) }}">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <div class="order-items">
                                @foreach($order->orderItems as $item)
                                    <div class="order-item">
                                        @if($item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}" alt="{{ $item->product_name }}"
                                                class="item-image">
                                        @else
                                            <div class="item-image"
                                                style="background: var(--light-pink); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-utensils" style="color: var(--primary-pink);"></i>
                                            </div>
                                        @endif

                                        <div class="item-details">
                                            <h4>{{ $item->product_name }}</h4>
                                            <p>{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="item-price">
                                            Rp {{ number_format($item->line_total, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-footer">
                                <div class="total-price">
                                    Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </div>
                                <div class="order-actions">
                                    @if($order->tracking_code)
                                        <a href="{{ route('tracking.show', $order->tracking_code) }}" class="btn-outline">
                                            <i class="fas fa-truck"></i> Lacak Pengiriman
                                        </a>
                                    @endif
                                    <!-- Detail Invoice Button could go here -->
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-10" data-aos="fade-up">
                        <div style="font-size: 4rem; color: var(--gray-300); margin-bottom: 1rem;">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.5rem;">
                            Belum Ada Pesanan
                        </h3>
                        <p style="color: var(--gray-500); margin-bottom: 2rem;">
                            Yuk mulai pesan menu favoritmu sekarang!
                        </p>
                        <a href="{{ route('products.index') }}" class="nav-link primary" style="display: inline-block;">
                            Mulai Belanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

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
    </script>
</body>

</html>