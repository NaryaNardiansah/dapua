<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Driver Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Universal Loading Screen Component -->
    <x-loading-screen title="Dapur Sakura Driver" subtitle="Loading Driver Panel..." />

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        :root {
            --primary-pink: #ec4899;
            --secondary-pink: #f472b6;
            --light-pink: #fce7f3;
            --dark-pink: #be185d;
            --pure-white: #ffffff;
            --soft-white: #fefefe;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        /* Pink Luxury Theme Background */
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 50%, var(--gray-50) 100%) !important;
            min-height: 100vh !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }

        .driver-interface {
            min-height: 100vh;
            position: relative;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 50%, var(--gray-50) 100%);
        }

        .driver-interface-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background: radial-gradient(circle at 10% 10%, rgba(236, 72, 153, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(244, 114, 182, 0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        .driver-content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Navigation Bar - Pink Theme */
        .driver-navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px) saturate(120%);
            -webkit-backdrop-filter: blur(20px) saturate(120%);
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.08);
            padding: 1rem 0;
        }

        .driver-navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .driver-navbar-top {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .driver-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .driver-mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border: none;
            border-radius: 12px;
            color: white;
            width: 44px;
            height: 44px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .driver-mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 90, 43, 0.4);
        }

        .driver-mobile-menu-toggle:active {
            transform: translateY(0);
        }

        .driver-mobile-menu-toggle.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(248, 113, 113, 0.95));
        }

        .driver-mobile-menu-toggle.active i::before {
            content: "\f00d";
        }

        .driver-logo-circle {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
            border: none;
        }

        .driver-logo-text {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        .driver-nav-links {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex: 1;
            justify-content: center;
        }

        .driver-nav-link {
            color: #4a5568;
            text-decoration: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            background: transparent;
        }

        .driver-nav-link i,
        .driver-nav-link span {
            position: relative;
            z-index: 1;
        }

        .driver-nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            background: var(--light-pink);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .driver-nav-link:hover::before {
            opacity: 1;
        }

        .driver-nav-link:hover {
            color: var(--primary-pink);
            transform: translateY(-2px);
        }

        .driver-nav-link.active {
            background: var(--light-pink);
            color: var(--primary-pink);
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.1);
        }

        .driver-nav-link.active::before {
            opacity: 1;
        }

        .driver-user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .driver-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 12px;
            border: 1px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 2px 10px rgba(236, 72, 153, 0.05);
        }

        .driver-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .driver-user-details {
            display: flex;
            flex-direction: column;
        }

        .driver-user-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 0.875rem;
        }

        .driver-user-role {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .driver-logout-btn {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .driver-logout-btn:hover {
            transform: translateY(-2px);
            box-shadow:
                0 6px 20px rgba(139, 90, 43, 0.4),
                0 3px 10px rgba(139, 90, 43, 0.3);
        }

        /* Content Area */
        .driver-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Glassmorphism Cards */
        .kitchen-card {
            background: white;
            border-radius: 20px;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.05);
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .kitchen-card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 25px 70px rgba(139, 90, 43, 0.2),
                0 12px 35px rgba(139, 90, 43, 0.15),
                0 4px 15px rgba(139, 90, 43, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
        }

        /* Buttons - Kitchen Theme */
        .kitchen-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow:
                0 4px 15px rgba(139, 90, 43, 0.2),
                0 2px 8px rgba(139, 90, 43, 0.15);
        }

        .kitchen-btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
        }

        .kitchen-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.4);
        }

        .kitchen-btn-success {
            background: linear-gradient(135deg, rgba(34, 139, 34, 0.9), rgba(50, 160, 50, 0.9));
            color: white;
            border: 1px solid rgba(34, 139, 34, 0.4);
        }

        .kitchen-btn-success:hover {
            transform: translateY(-2px);
            box-shadow:
                0 8px 25px rgba(34, 139, 34, 0.3),
                0 4px 12px rgba(34, 139, 34, 0.2);
        }

        /* Alerts */
        .kitchen-alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 2px solid;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .kitchen-alert-success {
            background: linear-gradient(135deg, rgba(209, 250, 229, 0.8), rgba(167, 243, 208, 0.7));
            border-color: rgba(16, 185, 129, 0.3);
            color: #065f46;
        }

        .kitchen-alert-error {
            background: linear-gradient(135deg, rgba(254, 226, 226, 0.8), rgba(252, 165, 165, 0.7));
            border-color: rgba(239, 68, 68, 0.3);
            color: #991b1b;
        }

        /* Responsive Design */

        /* Tablet (768px - 1024px) */
        @media (max-width: 1024px) {
            .driver-content {
                padding: 1.5rem;
            }

            .kitchen-card {
                padding: 1.5rem;
            }
        }

        /* Mobile & Tablet (max-width: 768px) */
        @media (max-width: 768px) {
            .driver-navbar {
                padding: 0.75rem 0;
            }

            .driver-navbar-content {
                flex-direction: column;
                padding: 0 1rem;
                gap: 0;
            }

            .driver-navbar-top {
                width: 100%;
                justify-content: space-between;
                margin-bottom: 0;
            }

            .driver-mobile-menu-toggle {
                display: flex;
            }

            .driver-logo {
                flex: 1;
            }

            .driver-logo-text {
                font-size: 1.25rem;
            }

            .driver-logo-circle {
                width: 45px;
                height: 45px;
                font-size: 1.25rem;
            }

            .driver-nav-links {
                width: 100%;
                flex-direction: column;
                gap: 0.5rem;
                max-height: 0;
                overflow: hidden;
                opacity: 0;
                transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease, margin 0.3s ease;
                padding: 0;
                margin: 0;
            }

            .driver-nav-links.mobile-open {
                max-height: 500px;
                opacity: 1;
                padding: 1rem 0;
                margin-top: 0.75rem;
                border-top: 1px solid rgba(236, 72, 153, 0.1);
            }

            .driver-nav-link {
                width: 100%;
                padding: 0.875rem 1rem;
                font-size: 0.9375rem;
                justify-content: flex-start;
                border-radius: 12px;
                min-height: 44px;
            }

            .driver-nav-link span {
                display: inline;
                margin-left: 0.75rem;
            }

            .driver-nav-link i {
                margin: 0;
                width: 20px;
                text-align: center;
            }

            .driver-user-menu {
                width: 100%;
                flex-direction: column;
                gap: 0.75rem;
                max-height: 0;
                overflow: hidden;
                opacity: 0;
                transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease, margin 0.3s ease;
                padding: 0;
                margin: 0;
            }

            .driver-user-menu.mobile-open {
                max-height: 200px;
                opacity: 1;
                padding: 1rem 0;
                margin-top: 0.75rem;
                border-top: 1px solid rgba(236, 72, 153, 0.1);
            }

            .driver-user-info {
                width: 100%;
                justify-content: flex-start;
            }

            .driver-logout-btn {
                width: 100%;
                justify-content: center;
                min-height: 44px;
            }

            .driver-content {
                padding: 1rem;
            }

            .kitchen-card {
                padding: 1.25rem;
                border-radius: 16px;
            }

            .kitchen-btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }
        }

        /* Mobile (max-width: 480px) */
        @media (max-width: 480px) {
            .driver-navbar {
                padding: 0.5rem 0;
            }

            .driver-navbar-content {
                padding: 0 0.75rem;
            }

            .driver-navbar-top {
                padding: 0;
            }

            .driver-mobile-menu-toggle {
                width: 40px;
                height: 40px;
                font-size: 1.125rem;
            }

            .driver-logo-text {
                font-size: 1rem;
            }

            .driver-logo-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .driver-nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                min-height: 44px;
            }

            .driver-user-info {
                padding: 0.5rem 0.75rem;
            }

            .driver-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.875rem;
            }

            .driver-user-name {
                font-size: 0.8125rem;
            }

            .driver-user-role {
                font-size: 0.6875rem;
            }

            .driver-content {
                padding: 0.75rem;
            }

            .kitchen-card {
                padding: 1rem;
                border-radius: 12px;
            }

            .kitchen-btn {
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
                min-height: 44px;
            }

            .kitchen-alert {
                padding: 0.875rem 1rem;
                font-size: 0.875rem;
            }
        }

        /* Tablet specific adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .driver-navbar-content {
                padding: 0 1.5rem;
                gap: 1.5rem;
            }

            .driver-nav-link {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }

            .driver-logo-text {
                font-size: 1.375rem;
            }

            .driver-user-info {
                padding: 0.5rem 0.875rem;
            }
        }

        /* Touch-friendly for mobile */
        @media (hover: none) and (pointer: coarse) {

            .kitchen-btn,
            .driver-nav-link,
            .driver-logout-btn {
                min-height: 44px;
                min-width: 44px;
            }

            .kitchen-card:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>
    <div class="driver-interface">
        <!-- Background Overlay -->
        <div class="driver-interface-overlay"></div>

        <div class="driver-content-wrapper">
            <!-- Navigation -->
            <nav class="driver-navbar">
                <div class="driver-navbar-content">
                    <div class="driver-navbar-top">
                        <div class="driver-logo">
                            <div class="driver-logo-circle">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="driver-logo-text">Driver Panel</div>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <button class="driver-mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu"
                            type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="driver-nav-links" id="navLinks">
                        <a href="{{ route('driver.dashboard') }}"
                            class="driver-nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('driver.orders.index') }}"
                            class="driver-nav-link {{ request()->routeIs('driver.orders.*') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Pesanan</span>
                        </a>
                    </div>

                    <div class="driver-user-menu" id="userMenu">
                        <div class="driver-user-info">
                            <div class="driver-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="driver-user-details">
                                <div class="driver-user-name">{{ auth()->user()->name }}</div>
                                <div class="driver-user-role">Driver</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="driver-logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="driver-content">
                @if(session('success'))
                    <div class="kitchen-alert kitchen-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="kitchen-alert kitchen-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.kitchen-alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const navLinks = document.getElementById('navLinks');
            const userMenu = document.getElementById('userMenu');

            if (mobileMenuToggle && navLinks && userMenu) {
                mobileMenuToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = navLinks.classList.contains('mobile-open');

                    if (isOpen) {
                        navLinks.classList.remove('mobile-open');
                        userMenu.classList.remove('mobile-open');
                        mobileMenuToggle.classList.remove('active');
                    } else {
                        navLinks.classList.add('mobile-open');
                        userMenu.classList.add('mobile-open');
                        mobileMenuToggle.classList.add('active');
                    }
                });

                // Close menu when clicking on a link (mobile)
                const navLinkElements = navLinks.querySelectorAll('.driver-nav-link');
                navLinkElements.forEach(link => {
                    link.addEventListener('click', function () {
                        if (window.innerWidth <= 768) {
                            navLinks.classList.remove('mobile-open');
                            userMenu.classList.remove('mobile-open');
                            mobileMenuToggle.classList.remove('active');
                        }
                    });
                });

                // Close menu when clicking outside (mobile)
                document.addEventListener('click', function (event) {
                    if (window.innerWidth <= 768) {
                        const isClickInsideNav = event.target.closest('.driver-navbar');
                        const isClickOnToggle = event.target.closest('#mobileMenuToggle');

                        if (!isClickInsideNav && !isClickOnToggle && navLinks.classList.contains('mobile-open')) {
                            navLinks.classList.remove('mobile-open');
                            userMenu.classList.remove('mobile-open');
                            mobileMenuToggle.classList.remove('active');
                        }
                    }
                });

                // Handle window resize
                let resizeTimer;
                window.addEventListener('resize', function () {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function () {
                        if (window.innerWidth > 768) {
                            navLinks.classList.remove('mobile-open');
                            userMenu.classList.remove('mobile-open');
                            mobileMenuToggle.classList.remove('active');
                        }
                    }, 250);
                });

                // Prevent menu from closing when clicking inside menu
                navLinks.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                userMenu.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>

</html>