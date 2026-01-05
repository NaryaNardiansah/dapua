<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Profil - {{ config('app.name', 'Dapur Sakura') }}</title>

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
        /* Pink Gradient Theme - Consistent with Login/Register */
        :root {
            --primary-pink: #ec4899;
            --secondary-pink: #f472b6;
            --dark-pink: #db2777;
            --light-pink: #fce7f3;
            --gray-900: #111827;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-500: #6b7280;
        }

        /* Navigation */
        .profile-nav {
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

        body {
            background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 25%, #f9a8d4 50%, #fbcfe8 75%, #fce7f3 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Background Decorations */
        .bg-decoration {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }

        .decoration-circle-1 {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .decoration-circle-2 {
            width: 400px;
            height: 400px;
            bottom: -100px;
            left: -100px;
            animation-delay: 7s;
        }

        .decoration-circle-3 {
            width: 500px;
            height: 500px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 14s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(60px, -60px) rotate(120deg);
            }

            66% {
                transform: translate(-50px, 50px) rotate(240deg);
            }
        }

        .profile-page {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Hero Section */
        .profile-hero {
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

        .profile-hero::before {
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

        .profile-hero-content {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .profile-hero-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
            flex-shrink: 0;
        }

        .profile-hero-info {
            flex: 1;
            min-width: 0;
        }

        .profile-hero-title {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .profile-hero-subtitle {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 1rem;
        }

        .profile-hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .profile-hero-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            color: var(--gray-700);
        }

        .profile-hero-meta-item i {
            color: var(--primary-pink);
        }

        /* Alert */
        .profile-alert {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        /* Sections Grid */
        .profile-sections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        /* Profile Card */
        .profile-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 20px 60px rgba(236, 72, 153, 0.15);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(236, 72, 153, 0.2);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
        }

        .profile-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
        }

        .profile-card-title {
            font-size: 1.75rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            flex: 1;
        }

        .profile-card-description {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        /* Form */
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .profile-form-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-form-label i {
            color: var(--primary-pink);
        }

        .profile-form-input {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            background: white;
            color: var(--gray-900);
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .profile-form-input:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
            outline: none;
        }

        .profile-form-error {
            color: #dc2626;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Verification Notice */
        .profile-verification-notice {
            background: rgba(245, 158, 11, 0.1);
            border-radius: 12px;
            border: 2px solid rgba(245, 158, 11, 0.2);
            padding: 1.25rem;
            margin-top: 1rem;
        }

        .profile-verification-text {
            color: var(--gray-700);
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .profile-verification-link {
            color: var(--primary-pink);
            font-weight: 700;
            text-decoration: underline;
            cursor: pointer;
            background: none;
            border: none;
            font-size: inherit;
            padding: 0;
        }

        .profile-verification-success {
            color: #10b981;
            font-size: 0.95rem;
            font-weight: 600;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Actions */
        .profile-form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
        }

        .profile-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .profile-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .profile-btn-danger {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .profile-btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        /* Delete Warning */
        .profile-delete-warning {
            background: rgba(220, 38, 38, 0.1);
            border-radius: 12px;
            border: 2px solid rgba(220, 38, 38, 0.2);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .profile-delete-warning-text {
            color: var(--gray-700);
            font-size: 0.95rem;
            margin: 0;
        }

        /* Modal */
        .profile-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .profile-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .profile-modal-container {
            background: white;
            border-radius: 20px;
            border: 2px solid rgba(236, 72, 153, 0.2);
            box-shadow: 0 25px 70px rgba(236, 72, 153, 0.3);
            max-width: 500px;
            width: 90%;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }

        .profile-modal-overlay.active .profile-modal-container {
            transform: scale(1);
        }

        .profile-modal-header {
            padding: 2rem 2.5rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-modal-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
        }

        .profile-modal-title i {
            color: #dc2626;
        }

        .profile-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-500);
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .profile-modal-close:hover {
            color: var(--gray-900);
        }

        .profile-modal-body {
            padding: 2rem 2.5rem;
        }

        .profile-modal-footer {
            padding: 1.5rem 2.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-hero {
                padding: 1.5rem;
            }

            .profile-hero-content {
                flex-direction: column;
                text-align: center;
            }

            .profile-hero-title {
                font-size: 2rem;
            }

            .profile-sections-grid {
                grid-template-columns: 1fr;
            }

            .profile-card {
                padding: 1.5rem;
            }

            .profile-form-actions {
                flex-direction: column;
            }

            .profile-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Background Decorations -->
    <div class="bg-decoration">
        <div class="decoration-circle decoration-circle-1"></div>
        <div class="decoration-circle decoration-circle-2"></div>
        <div class="decoration-circle decoration-circle-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="profile-nav">
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

    <div class="profile-page">
        <div class="profile-container">
            <!-- Hero Section -->
            <div class="profile-hero">
                <div class="profile-hero-content">
                    <div class="profile-hero-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-hero-info">
                        <h1 class="profile-hero-title">{{ $user->name }}</h1>
                        <p class="profile-hero-subtitle">Kelola informasi akun dan pengaturan profil Anda</p>
                        <div class="profile-hero-meta">
                            <div class="profile-hero-meta-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                            @if($user->email_verified_at)
                                <div class="profile-hero-meta-item">
                                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                    <span style="color: #10b981;">Email Terverifikasi</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="profile-alert">
                    <i class="fas fa-check-circle"></i>
                    <span>
                        @if(session('status') === 'profile-updated')
                            Profil berhasil diperbarui
                        @elseif(session('status') === 'password-updated')
                            Kata sandi berhasil diperbarui
                        @elseif(session('status') === 'verification-link-sent')
                            Link verifikasi telah dikirim ke email Anda
                        @else
                            {{ session('status') }}
                        @endif
                    </span>
                </div>
            @endif

            <!-- Profile Sections -->
            <div class="profile-sections-grid">
                <!-- Profile Information -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-card-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h2 class="profile-card-title">Informasi Profil</h2>
                    </div>
                    <p class="profile-card-description">
                        Perbarui informasi profil dan alamat email akun Anda.
                    </p>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
                        @csrf
                        @method('patch')

                        <div class="profile-form-group">
                            <label for="name" class="profile-form-label">
                                <i class="fas fa-user"></i> Nama
                            </label>
                            <input id="name" name="name" type="text" class="profile-form-input"
                                value="{{ old('name', $user->name) }}" required autofocus />
                            @error('name')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="email" class="profile-form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input id="email" name="email" type="email" class="profile-form-input"
                                value="{{ old('email', $user->email) }}" required />
                            @error('email')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="profile-verification-notice">
                                    <p class="profile-verification-text">
                                        Alamat email Anda belum terverifikasi.
                                        <button form="send-verification" class="profile-verification-link">
                                            Klik di sini untuk mengirim ulang email verifikasi.
                                        </button>
                                    </p>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="profile-verification-success">
                                            ✓ Link verifikasi baru telah dikirim ke alamat email Anda.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="profile-form-actions">
                            <button type="submit" class="profile-btn profile-btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-card-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h2 class="profile-card-title">Perbarui Kata Sandi</h2>
                    </div>
                    <p class="profile-card-description">
                        Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.
                    </p>

                    <form method="post" action="{{ route('password.update') }}" class="profile-form">
                        @csrf
                        @method('put')

                        <div class="profile-form-group">
                            <label for="update_password_current_password" class="profile-form-label">
                                <i class="fas fa-key"></i> Kata Sandi Saat Ini
                            </label>
                            <input id="update_password_current_password" name="current_password" type="password"
                                class="profile-form-input" />
                            @error('current_password', 'updatePassword')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="update_password_password" class="profile-form-label">
                                <i class="fas fa-lock"></i> Kata Sandi Baru
                            </label>
                            <input id="update_password_password" name="password" type="password"
                                class="profile-form-input" />
                            @error('password', 'updatePassword')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label for="update_password_password_confirmation" class="profile-form-label">
                                <i class="fas fa-lock"></i> Konfirmasi Kata Sandi
                            </label>
                            <input id="update_password_password_confirmation" name="password_confirmation"
                                type="password" class="profile-form-input" />
                            @error('password_confirmation', 'updatePassword')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-form-actions">
                            <button type="submit" class="profile-btn profile-btn-primary">
                                <i class="fas fa-save"></i> Simpan Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Delete Account -->
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="profile-card-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h2 class="profile-card-title">Hapus Akun</h2>
                    </div>
                    <p class="profile-card-description">
                        Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
                    </p>

                    <div class="profile-delete-warning">
                        <p class="profile-delete-warning-text">
                            <i class="fas fa-exclamation-triangle"></i>
                            Sebelum menghapus akun Anda, silakan unduh data atau informasi yang ingin Anda simpan.
                        </p>
                    </div>

                    <div class="profile-form-actions">
                        <button onclick="openDeleteModal()" class="profile-btn profile-btn-danger">
                            <i class="fas fa-trash-alt"></i> Hapus Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account Modal -->
        <div id="deleteModal" class="profile-modal-overlay">
            <div class="profile-modal-container">
                <div class="profile-modal-header">
                    <h3 class="profile-modal-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Konfirmasi Penghapusan Akun
                    </h3>
                    <button onclick="closeDeleteModal()" class="profile-modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="profile-modal-body">
                    <form method="post" action="{{ route('profile.destroy') }}" class="profile-form">
                        @csrf
                        @method('delete')

                        <p style="color: var(--gray-700); margin-bottom: 1.5rem;">
                            Apakah Anda yakin ingin menghapus akun Anda? Setelah akun Anda dihapus, semua sumber daya
                            dan
                            data akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi
                            bahwa
                            Anda ingin menghapus akun Anda secara permanen.
                        </p>

                        <div class="profile-form-group">
                            <label for="password" class="profile-form-label">
                                <i class="fas fa-key"></i> Kata Sandi
                            </label>
                            <input id="password" name="password" type="password" class="profile-form-input"
                                placeholder="Masukkan kata sandi Anda" required />
                            @error('password', 'userDeletion')
                                <div class="profile-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="profile-modal-footer">
                            <button type="button" onclick="closeDeleteModal()" class="profile-btn"
                                style="background: white; color: var(--gray-700); border: 2px solid rgba(236, 72, 153, 0.2);">
                                Batal
                            </button>
                            <button type="submit" class="profile-btn profile-btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        // Delete Modal Functions
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>

</html>