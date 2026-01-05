<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Menu - {{ config('app.name', 'Dapur Sakura') }}</title>

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

        .menu-container {
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

        @keyframes shimmer {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Navigation */
        .menu-nav {
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
        .menu-content {
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

        /* Hero Section */
        .menu-hero {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 4rem 3rem;
            margin-bottom: 3rem;
            box-shadow:
                0 20px 60px rgba(236, 72, 153, 0.15),
                0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(236, 72, 153, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .menu-hero::before {
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

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1));
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 1.25rem;
        }

        .menu-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .menu-hero-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Toolbar */
        .toolbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .results-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .results-count {
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--gray-900);
        }

        .results-count span {
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .view-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
            background: white;
            padding: 0.375rem;
            border-radius: 12px;
            border: 2px solid rgba(236, 72, 153, 0.15);
        }

        .view-btn {
            padding: 0.625rem 1rem;
            border: none;
            background: transparent;
            color: var(--gray-600);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.125rem;
        }

        .view-btn.active {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .sort-select {
            padding: 0.75rem 1.25rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            font-weight: 600;
            color: var(--gray-900);
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        /* Sidebar Layout */
        .content-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Sidebar Filters */
        .sidebar-filters {
            position: sticky;
            top: 100px;
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08);
        }

        .filter-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .filter-title i {
            color: var(--primary-pink);
        }

        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-900);
            background: white;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .category-list {
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .category-item {
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(236, 72, 153, 0.1);
            background: white;
            color: var(--gray-700);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-item:hover {
            border-color: var(--primary-pink);
            color: var(--primary-pink);
            transform: translateX(5px);
        }

        .category-item.active {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3);
        }

        .category-count {
            font-size: 0.8125rem;
            opacity: 0.8;
        }

        .filter-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .filter-btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .filter-btn.primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .filter-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .filter-btn.secondary {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
        }

        .filter-btn.secondary:hover {
            background: var(--light-pink);
        }

        /* Products Grid */
        .products-section {
            min-height: 600px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .products-grid.list-view {
            grid-template-columns: 1fr;
        }

        /* Empty State */
        .empty-state {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 5rem 3rem;
            text-align: center;
            border: 2px solid rgba(236, 72, 153, 0.1);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--primary-pink);
        }

        .empty-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .empty-text {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 4rem;
        }

        .pagination {
            display: flex;
            gap: 0.625rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 1.25rem 2rem;
            border-radius: 16px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08);
        }

        .pagination>* {
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            color: var(--gray-700);
            background: white;
            border: 2px solid rgba(236, 72, 153, 0.15);
        }

        .pagination>*:hover:not(.disabled):not(.active) {
            background: var(--light-pink);
            border-color: var(--primary-pink);
            color: var(--primary-pink);
            transform: translateY(-2px);
        }

        .pagination>.active {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .pagination>.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-layout {
                grid-template-columns: 1fr;
            }

            .sidebar-filters {
                position: static;
            }

            .filter-card {
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .menu-hero {
                padding: 3rem 2rem;
            }

            .menu-hero-title {
                font-size: 2.5rem;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .view-controls {
                justify-content: space-between;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="menu-container">
        <!-- Background Decorations -->
        <div class="bg-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>

        <!-- Navigation -->
        <nav class="menu-nav">
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
        <div class="menu-content">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" data-aos="fade-right">
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <span class="separator">/</span>
                <span class="current">Menu</span>
                @if($categorySlug)
                    <span class="separator">/</span>
                    <span class="current">{{ $categories->firstWhere('slug', $categorySlug)->name ?? 'Kategori' }}</span>
                @endif
            </nav>

            <!-- Hero Section -->
            <section class="menu-hero" data-aos="fade-up">
                <div class="hero-badge">
                    <i class="fas fa-sparkles"></i>
                    <span>{{ $products->total() }} Menu Tersedia</span>
                </div>
                <h1 class="menu-hero-title">Jelajahi Menu Kami</h1>
                <p class="menu-hero-subtitle">Temukan berbagai pilihan hidangan lezat yang siap memanjakan selera Anda
                    dengan cita rasa autentik dan bahan berkualitas premium.</p>
            </section>

            <!-- Toolbar -->
            <div class="toolbar" data-aos="fade-up" data-aos-delay="100">
                <div class="results-info">
                    <i class="fas fa-list-ul" style="color: var(--primary-pink); font-size: 1.25rem;"></i>
                    <div class="results-count">
                        Menampilkan <span>{{ $products->count() }}</span> dari <span>{{ $products->total() }}</span>
                        menu
                    </div>
                </div>
                <div class="view-controls">
                    <div class="view-toggle">
                        <button class="view-btn active" onclick="setView('grid')" title="Grid View">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="view-btn" onclick="setView('list')" title="List View">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    <select class="sort-select" onchange="window.location.href=this.value">
                        <option
                            value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'default'])) }}">
                            Urutkan: Default</option>
                        <option
                            value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}"
                            {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A-Z</option>
                        <option
                            value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}"
                            {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama: Z-A</option>
                        <option
                            value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah - Tinggi</option>
                        <option
                            value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi - Rendah</option>
                    </select>
                </div>
            </div>

            <!-- Content Layout -->
            <div class="content-layout">
                <!-- Sidebar Filters -->
                <aside class="sidebar-filters">
                    <!-- Search Filter -->
                    <div class="filter-card" data-aos="fade-right" data-aos-delay="200">
                        <h3 class="filter-title">
                            <i class="fas fa-search"></i> Cari Menu
                        </h3>
                        <form method="get">
                            <input type="hidden" name="category" value="{{ $categorySlug }}">
                            <input type="text" name="q" value="{{ $search }}" placeholder="Ketik nama menu..."
                                class="filter-input">
                            <div class="filter-buttons">
                                <button type="submit" class="filter-btn primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('products.index') }}" class="filter-btn secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-card" data-aos="fade-right" data-aos-delay="300">
                        <h3 class="filter-title">
                            <i class="fas fa-th-large"></i> Kategori
                        </h3>
                        <div class="category-list">
                            @php($qs = array_filter(['q' => $search]))
                            @php($isAll = empty($categorySlug))
                            @php($totalProducts = $categories->sum('products_count'))
                            <a href="{{ route('products.index', $qs) }}"
                                class="category-item {{ $isAll ? 'active' : '' }}">
                                <span><i class="fas fa-th"></i> Semua Kategori</span>
                                <span class="category-count">{{ $totalProducts }}</span>
                            </a>
                            @foreach($categories as $cat)
                            @php($active = $categorySlug === $cat->slug)
                            @php($params = array_merge($qs, ['category' => $cat->slug]))
                            <a href="{{ route('products.index', $params) }}"
                                class="category-item {{ $active ? 'active' : '' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="category-count">{{ $cat->products_count ?? 0 }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="filter-card" data-aos="fade-right" data-aos-delay="400"
                        style="background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink)); color: white; border: none;">
                        <h3 class="filter-title" style="color: white;">
                            <i class="fas fa-info-circle"></i> Info
                        </h3>
                        <div style="font-size: 0.9375rem; line-height: 1.8;">
                            <p style="margin-bottom: 0.75rem;">
                                <i class="fas fa-check-circle"></i> Bahan 100% Segar
                            </p>
                            <p style="margin-bottom: 0.75rem;">
                                <i class="fas fa-shipping-fast"></i> Pengiriman 30 Menit
                            </p>
                            <p>
                                <i class="fas fa-star"></i> Rating 4.9/5.0
                            </p>
                        </div>
                    </div>
                </aside>

                <!-- Products Section -->
                <section class="products-section">
                    @if($products->count())
                        <div class="products-grid" id="productsGrid">
                            @foreach($products as $index => $product)
                                <div data-aos="zoom-in" data-aos-delay="{{ $index * 50 }}">
                                    <x-product-card :product="$product" />
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="pagination-wrapper">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="empty-state" data-aos="fade-up">
                            <div class="empty-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h3 class="empty-title">Tidak Ada Menu Ditemukan</h3>
                            <p class="empty-text">Maaf, kami tidak menemukan menu yang sesuai dengan pencarian Anda. Silakan
                                coba kata kunci lain atau lihat semua menu kami.</p>
                            <a href="{{ route('products.index') }}" class="filter-btn primary"
                                style="display: inline-block; text-decoration: none;">
                                <i class="fas fa-arrow-left"></i> Lihat Semua Menu
                            </a>
                        </div>
                    @endif
                </section>
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

        function setView(view) {
            const grid = document.getElementById('productsGrid');
            const buttons = document.querySelectorAll('.view-btn');

            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.closest('.view-btn').classList.add('active');

            if (view === 'list') {
                grid.classList.add('list-view');
            } else {
                grid.classList.remove('list-view');
            }

        }

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