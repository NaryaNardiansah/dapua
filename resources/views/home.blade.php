<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dapur Sakura') }} - Masakan Rumahan Terbaik</title>

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

        .home-container {
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
            opacity: 0.08;
        }

        .decoration-circle-1 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            top: -150px;
            right: -150px;
            animation: float 25s ease-in-out infinite;
        }

        .decoration-circle-2 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, var(--secondary-pink), var(--primary-pink));
            bottom: -100px;
            left: -100px;
            animation: float 20s ease-in-out infinite reverse;
        }

        .decoration-circle-3 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            top: 40%;
            right: 10%;
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) rotate(0deg);
            }

            33% {
                transform: translate(40px, -40px) rotate(120deg);
            }

            66% {
                transform: translate(-30px, 30px) rotate(240deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.08;
            }

            50% {
                transform: scale(1.3);
                opacity: 0.12;
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Navigation */
        .home-nav {
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

        .nav-link.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        /* Content */
        .home-content {
            position: relative;
            z-index: 10;
        }

        /* Hero Section - Enhanced */
        .hero-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 6rem 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content {
            animation: slideUp 0.8s ease-out;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1));
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 0.625rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 1.5rem;
        }

        .hero-badge i {
            animation: pulse 2s ease-in-out infinite;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--gray-900), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.125rem 2.5rem;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.0625rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .hero-btn.primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
        }

        .hero-btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.4);
        }

        .hero-btn.secondary {
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
        }

        .hero-btn.secondary:hover {
            background: var(--light-pink);
        }

        .hero-image {
            position: relative;
            animation: fadeIn 1s ease-out 0.3s both;
        }

        .hero-image-main {
            width: 100%;
            height: 500px;
            border-radius: 24px;
            object-fit: cover;
            box-shadow: 0 25px 60px rgba(236, 72, 153, 0.2);
            border: 4px solid white;
        }

        .hero-floating-card {
            position: absolute;
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .hero-floating-card.card-1 {
            top: 10%;
            right: -10%;
        }

        .hero-floating-card.card-2 {
            bottom: 10%;
            left: -10%;
        }

        /* Stats Section */
        .stats-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.15);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-pink);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.125rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        /* Section Container */
        .section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1));
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--gray-600);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Category Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 2rem;
        }

        .category-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(236, 72, 153, 0.1);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            transition: all 0.4s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .category-card:hover::before {
            transform: scaleX(1);
        }

        .category-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary-pink);
            box-shadow: 0 20px 50px rgba(236, 72, 153, 0.2);
        }

        .category-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-pink);
            transition: all 0.4s ease;
        }

        .category-card:hover .category-icon {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            transform: scale(1.15) rotate(10deg);
        }

        .category-name {
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .category-count {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 600;
        }

        /* Product Tabs */
        .product-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            border: 2px solid rgba(236, 72, 153, 0.2);
            background: white;
            color: var(--gray-700);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn:hover {
            border-color: var(--primary-pink);
            color: var(--primary-pink);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 2rem;
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .feature-desc {
            font-size: 1rem;
            color: var(--gray-600);
            line-height: 1.7;
        }

        /* Testimonials */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            position: relative;
        }

        .testimonial-quote {
            font-size: 3rem;
            color: var(--primary-pink);
            opacity: 0.2;
            position: absolute;
            top: 1rem;
            left: 1.5rem;
        }

        .testimonial-text {
            font-size: 1.0625rem;
            color: var(--gray-700);
            line-height: 1.8;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .author-info h4 {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .author-info .stars {
            color: #fbbf24;
        }

        /* Newsletter */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 24px;
            padding: 4rem 3rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .newsletter-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .newsletter-content {
            position: relative;
            z-index: 1;
        }

        .newsletter-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 1rem;
        }

        .newsletter-subtitle {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
        }

        .newsletter-input {
            flex: 1;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1rem;
            backdrop-filter: blur(10px);
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            background: white;
            color: var(--primary-pink);
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-section {
                grid-template-columns: 1fr;
                gap: 3rem;
                padding: 4rem 2rem;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-image {
                order: -1;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .newsletter-form {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="home-container">
        <!-- Background Decorations -->
        <div class="bg-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>

        <!-- Navigation -->
        <nav class="home-nav">
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
        <div class="home-content">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-fire"></i>
                        <span>Masakan Rumahan Terbaik #1</span>
                    </div>
                    <h1 class="hero-title">Nikmati Kelezatan Masakan Rumahan Autentik</h1>
                    <p class="hero-subtitle">Rasakan cita rasa istimewa dari dapur kami. Bahan segar, resep
                        turun-temurun, dan pelayanan terbaik untuk kepuasan Anda.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="hero-btn primary">
                            <i class="fas fa-shopping-bag"></i>
                            Pesan Sekarang
                        </a>
                        <a href="#kategori" class="hero-btn secondary">
                            <i class="fas fa-compass"></i>
                            Jelajahi Menu
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80"
                        alt="Delicious Food" class="hero-image-main">
                    <div class="hero-floating-card card-1">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div
                                style="width: 50px; height: 50px; background: linear-gradient(135deg, #ec4899, #f472b6); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 1.5rem; color: #ec4899;">4.9</div>
                                <div style="font-size: 0.875rem; color: #6b7280;">Rating Pelanggan</div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-floating-card card-2">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div
                                style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #34d399); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 1.5rem; color: #10b981;">100%</div>
                                <div style="font-size: 0.875rem; color: #6b7280;">Bahan Segar</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="stats-grid">
                    <div class="stat-card" data-aos="fade-up" data-aos-delay="0">
                        <div class="stat-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="stat-number">{{ $allProducts->count() }}+</div>
                        <div class="stat-label">Menu Tersedia</div>
                    </div>
                    <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">5000+</div>
                        <div class="stat-label">Pelanggan Puas</div>
                    </div>
                    <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Rating Bintang</div>
                    </div>
                    <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="stat-number">30</div>
                        <div class="stat-label">Menit Pengiriman</div>
                    </div>
                </div>
            </section>

            <!-- Categories Section -->
            <section id="kategori" class="section">
                <div class="section-header" data-aos="fade-up">
                    <span class="section-badge">Kategori Pilihan</span>
                    <h2 class="section-title">Jelajahi Menu Favorit</h2>
                    <p class="section-subtitle">Temukan berbagai pilihan menu lezat yang telah kami siapkan khusus untuk
                        Anda</p>
                </div>

                <div class="category-grid">
                    @foreach($categories as $index => $category)
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-card"
                            data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="category-icon">
                                @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}"
                                        class="w-full h-full object-cover rounded-full" alt="{{ $category->name }}">
                                @else
                                    <i class="fas fa-utensils"></i>
                                @endif
                            </div>
                            <h3 class="category-name">{{ $category->name }}</h3>
                            <span class="category-count">{{ $category->products_count ?? 0 }} Menu</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <!-- Products Section with Tabs -->
            <section class="section" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
                <div class="section-header" data-aos="fade-up">
                    <span class="section-badge">Menu Spesial</span>
                    <h2 class="section-title">Pilihan Menu Terbaik</h2>
                    <p class="section-subtitle">Koleksi menu pilihan yang paling disukai pelanggan kami</p>
                </div>

                <div class="product-tabs">
                    <button class="tab-btn active" onclick="switchTab('bestseller')">
                        <i class="fas fa-crown"></i> Best Seller
                    </button>
                    <button class="tab-btn" onclick="switchTab('latest')">
                        <i class="fas fa-clock"></i> Terbaru
                    </button>
                    <button class="tab-btn" onclick="switchTab('all')">
                        <i class="fas fa-th"></i> Semua Menu
                    </button>
                </div>

                <div id="bestseller" class="tab-content active">
                    <div class="product-grid">
                        @foreach($bestSellers as $product)
                            <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="latest" class="tab-content">
                    <div class="product-grid">
                        @foreach($latest as $product)
                            <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="all" class="tab-content">
                    <div class="product-grid">
                        @foreach($allProducts->take(12) as $product)
                            <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 50 }}">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="section">
                <div class="section-header" data-aos="fade-up">
                    <span class="section-badge">Keunggulan Kami</span>
                    <h2 class="section-title">Mengapa Memilih Kami?</h2>
                    <p class="section-subtitle">Kami berkomitmen memberikan yang terbaik untuk kepuasan Anda</p>
                </div>

                <div class="features-grid">
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="0">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3 class="feature-title">Bahan 100% Segar</h3>
                        <p class="feature-desc">Kami hanya menggunakan bahan-bahan segar pilihan terbaik yang dipilih
                            langsung setiap hari untuk menjamin kualitas masakan.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="feature-title">Pengiriman Cepat</h3>
                        <p class="feature-desc">Pesanan Anda akan sampai dalam waktu 30-45 menit dengan kondisi masih
                            hangat dan segar.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Pembayaran Aman</h3>
                        <p class="feature-desc">Sistem pembayaran yang aman dan terpercaya dengan berbagai metode
                            pembayaran yang tersedia.</p>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="section" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);">
                <div class="section-header" data-aos="fade-up">
                    <span class="section-badge">Testimoni</span>
                    <h2 class="section-title">Kata Mereka Tentang Kami</h2>
                    <p class="section-subtitle">Kepuasan pelanggan adalah prioritas utama kami</p>
                </div>

                <div class="testimonials-grid">
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="0">
                        <i class="fas fa-quote-left testimonial-quote"></i>
                        <p class="testimonial-text">"Makanannya enak banget! Rasanya seperti masakan rumah sendiri.
                            Pengirimannya juga cepat dan masih hangat. Recommended!"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">AS</div>
                            <div class="author-info">
                                <h4>Andi Saputra</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
                        <i class="fas fa-quote-left testimonial-quote"></i>
                        <p class="testimonial-text">"Pelayanannya ramah dan profesional. Menu yang ditawarkan juga
                            bervariasi. Jadi langganan saya sekarang!"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">SR</div>
                            <div class="author-info">
                                <h4>Siti Rahmawati</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                        <i class="fas fa-quote-left testimonial-quote"></i>
                        <p class="testimonial-text">"Harga terjangkau dengan kualitas premium. Porsinya juga pas dan
                            mengenyangkan. Puas banget!"</p>
                        <div class="testimonial-author">
                            <div class="author-avatar">BP</div>
                            <div class="author-info">
                                <h4>Budi Prasetyo</h4>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Newsletter Section -->
            <section class="section">
                <div class="newsletter-section" data-aos="zoom-in">
                    <div class="newsletter-content">
                        <h2 class="newsletter-title">Dapatkan Promo Spesial!</h2>
                        <p class="newsletter-subtitle">Subscribe newsletter kami dan dapatkan diskon hingga 20% untuk
                            pemesanan pertama Anda</p>
                        <form class="newsletter-form"
                            onsubmit="event.preventDefault(); alert('Terima kasih telah subscribe!');">
                            <input type="email" class="newsletter-input" placeholder="Masukkan email Anda" required>
                            <button type="submit" class="newsletter-btn">
                                <i class="fas fa-paper-plane"></i> Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100
        });

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
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