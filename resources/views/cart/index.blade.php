@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Keranjang - {{ config('app.name', 'Dapur Sakura') }}</title>

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

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
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

        /* Progress Steps */
        .progress-steps {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.08);
        }

        .steps-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .steps-container::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: var(--gray-200);
            z-index: 0;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: white;
            border: 3px solid var(--gray-300);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gray-400);
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-color: var(--primary-pink);
            color: white;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
        }

        .step.completed .step-circle {
            background: linear-gradient(135deg, #10b981, #34d399);
            border-color: #10b981;
            color: white;
        }

        .step-label {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--gray-600);
        }

        .step.active .step-label {
            color: var(--primary-pink);
        }

        /* Hero Section */
        .cart-hero {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 4rem 3rem;
            margin-bottom: 3rem;
            box-shadow:
                0 20px 60px rgba(236, 72, 153, 0.15),
                0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(236, 72, 153, 0.1);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .cart-hero::before {
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

        .cart-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .cart-hero-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.7;
        }

        .cart-hero-btn {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.0625rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
        }

        .cart-hero-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.4);
        }

        /* Alert */
        .cart-alert {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 1.25rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            font-weight: 600;
        }

        /* Main Grid */
        .cart-main-grid {
            display: grid;
            grid-template-columns: 1.3fr 0.7fr;
            gap: 2.5rem;
            align-items: start;
        }

        /* Cart Items */
        .cart-items-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.1);
        }

        .cart-items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
        }

        .cart-items-title {
            font-size: 2rem;
            font-weight: 900;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .cart-items-title i {
            color: var(--primary-pink);
        }

        .cart-items-count {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9375rem;
        }

        /* Cart Item */
        .cart-item {
            background: white;
            border: 2px solid rgba(236, 72, 153, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 2rem;
            align-items: center;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            transform: translateX(5px);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.15);
            border-color: var(--primary-pink);
        }

        .cart-item-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .cart-item-placeholder {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-pink);
            font-size: 3rem;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
        }

        .cart-item-price {
            font-size: 1.0625rem;
            color: var(--gray-600);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            padding: 0.25rem;
            border-radius: 12px;
            border: 2px solid var(--light-pink);
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: var(--light-pink);
            color: var(--primary-pink);
            font-weight: 800;
            font-size: 1.125rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            background: var(--primary-pink);
            color: white;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 800;
            color: var(--primary-pink);
            -moz-appearance: textfield;
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-input:focus {
            outline: none;
        }

        .cart-item-actions {
            display: flex;
            gap: 0.75rem;
        }

        .action-btn {
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .action-btn.remove {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .action-btn.remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(220, 38, 38, 0.4);
        }

        .cart-item-subtotal {
            text-align: right;
        }

        .subtotal-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-bottom: 0.375rem;
        }

        .subtotal-value {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Empty State */
        .cart-empty {
            text-align: center;
            padding: 5rem 3rem;
        }

        .cart-empty-icon {
            width: 140px;
            height: 140px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            color: var(--primary-pink);
        }

        .cart-empty-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .cart-empty-text {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 2.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Checkout Sidebar */
        .checkout-sidebar {
            position: sticky;
            top: 100px;
        }

        .checkout-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.1);
            margin-bottom: 2rem;
        }

        .checkout-title {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkout-title i {
            color: var(--primary-pink);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            font-size: 1.0625rem;
        }

        .summary-label {
            color: var(--gray-600);
            font-weight: 600;
        }

        .summary-value {
            color: var(--gray-900);
            font-weight: 700;
        }

        .summary-total {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
        }

        .summary-total .summary-label {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .summary-total .summary-value {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .promo-input-group {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .promo-input {
            flex: 1;
            padding: 0.875rem 1.25rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            font-weight: 600;
        }

        .promo-input:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .promo-btn {
            padding: 0.875rem 1.75rem;
            background: white;
            color: var(--primary-pink);
            border: 2px solid var(--primary-pink);
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .promo-btn:hover {
            background: var(--light-pink);
        }

        .checkout-btn {
            width: 100%;
            padding: 1.25rem 2rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 900;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
            margin-top: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.875rem;
        }

        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.4);
        }

        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .secure-badge i {
            color: #10b981;
        }

        .cart-form-group {
            margin-bottom: 1.5rem;
        }

        .cart-form-label {
            display: block;
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.625rem;
        }

        .cart-form-input,
        .cart-form-textarea {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-900);
            background: white;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .cart-form-input:focus,
        .cart-form-textarea:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .cart-form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .cart-map-container {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(236, 72, 153, 0.2);
            margin-top: 0.5rem;
        }

        .cart-map {
            width: 100%;
            height: 300px;
        }

        .cart-map-hint {
            font-size: 0.8125rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
        }

        .cart-guest-message {
            color: var(--gray-600);
            font-size: 1rem;
            padding: 2rem;
            background: var(--light-pink);
            border-radius: 12px;
            text-align: center;
        }

        .cart-guest-message a {
            color: var(--primary-pink);
            font-weight: 700;
            text-decoration: underline;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            font-size: 1.0625rem;
        }

        .summary-label {
            color: var(--gray-600);
            font-weight: 600;
        }

        .summary-value {
            color: var(--gray-900);
            font-weight: 700;
        }

        .summary-total {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
        }

        .summary-total .summary-label {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .summary-total .summary-value {
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cart-main-grid {
                grid-template-columns: 1fr;
            }

            .checkout-sidebar {
                position: static;
            }

            .cart-item {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .cart-item-subtotal {
                text-align: left;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .cart-hero {
                padding: 3rem 2rem;
            }

            .cart-hero-title {
                font-size: 2.5rem;
            }

            .steps-container {
                flex-direction: column;
                gap: 1.5rem;
            }

            .steps-container::before {
                display: none;
            }

            .cart-item-controls {
                flex-direction: column;
                align-items: stretch;
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
                <span class="current">Keranjang</span>
            </nav>

            <!-- Progress Steps -->
            <div class="progress-steps" data-aos="fade-up">
                <div class="steps-container">
                    <div class="step active">
                        <div class="step-circle">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-label">Keranjang</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="step-label">Pengiriman</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="step-label">Pembayaran</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-label">Selesai</div>
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="cart-alert" data-aos="fade-down">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(!empty($items))
                <!-- Main Grid -->
                <div class="cart-main-grid">
                    <!-- Cart Items -->
                    <div>
                        <div class="cart-items-card" data-aos="fade-right">
                            <div class="cart-items-header">
                                <h2 class="cart-items-title">
                                    <i class="fas fa-shopping-bag"></i> Item Pesanan
                                </h2>
                                <div class="cart-items-count">
                                    {{ count($items) }} Item
                                </div>
                            </div>

                            @foreach($items as $row)
                                <div class="cart-item" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                                    @if($row['product']->image_path)
                                        <img src="{{ Storage::url($row['product']->image_path) }}" alt="{{ $row['product']->name }}"
                                            class="cart-item-image">
                                    @else
                                        <div class="cart-item-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif

                                    <div class="cart-item-info">
                                        <h3 class="cart-item-name">{{ $row['product']->name }}</h3>
                                        <div class="cart-item-price">
                                            @if($row['product']->has_active_discount)
                                                <span style="text-decoration: line-through; color: var(--gray-400); font-size: 0.875rem; margin-right: 0.5rem;">
                                                    Rp {{ number_format($row['product']->price, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            <span style="color: var(--primary-pink); font-weight: 700;">
                                                Rp {{ number_format($row['product']->current_price, 0, ',', '.') }}
                                            </span>
                                            / item
                                        </div>
                                        <div class="cart-item-controls">
                                            <form action="{{ route('cart.update', $row['product']) }}" method="POST" class="quantity-form">
                                                @csrf
                                                <div class="quantity-control">
                                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity" value="{{ $row['quantity'] }}" min="1" 
                                                        class="qty-input" onchange="this.form.submit()">
                                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                            <div class="cart-item-actions">
                                                <form action="{{ route('cart.remove', $row['product']) }}" method="post"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn remove"
                                                        onclick="return confirm('Hapus dari keranjang?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cart-item-subtotal">
                                        <div class="subtotal-label">Subtotal</div>
                                        <div class="subtotal-value">Rp {{ number_format($row['line_total'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Checkout Sidebar -->
                    <div class="checkout-sidebar">
                        <!-- Order Summary -->
                        <div class="checkout-card" data-aos="fade-left" style="margin-bottom: 2rem;">
                            <h3 class="checkout-title">
                                <i class="fas fa-receipt"></i> Ringkasan Pesanan
                            </h3>

                            <div class="summary-row">
                                <span class="summary-label">Subtotal ({{ count($items) }} item)</span>
                                <span class="summary-value">Rp {{ number_format($subtotal,0,',','.') }}</span>
                            </div>

                            <div class="summary-row">
                                <span class="summary-label">Biaya Pengiriman</span>
                                <span class="summary-value" id="shippingCostDisplay">-</span>
                            </div>

                            <div class="summary-row summary-total">
                                <span class="summary-label">Total</span>
                                <span class="summary-value" id="grandTotalDisplay">Rp {{ number_format($subtotal,0,',','.') }}</span>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <div class="checkout-card" data-aos="fade-left">
                            <h3 class="checkout-title">
                                <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman
                            </h3>

                            @auth
                                <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                                    @csrf
                                    
                                    <div class="cart-form-group">
                                        <label class="cart-form-label">Nama Penerima</label>
                                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="cart-form-input" required />
                                        @error('name')
                                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="cart-form-group">
                                        <label class="cart-form-label">Nomor Telepon</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" class="cart-form-input" placeholder="08xxxxxxxxxx" required />
                                        @error('phone')
                                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="cart-form-group">
                                        <label class="cart-form-label">Pin Lokasi Pengiriman</label>
                                        <div class="cart-map-container">
                                            <div id="map" class="cart-map"></div>
                                        </div>
                                        <p class="cart-map-hint">
                                            <i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi pengiriman
                                        </p>
                                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" required>
                                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" required>
                                        @error('latitude')
                                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="checkout-btn" {{ empty($items) ? 'disabled' : '' }}>
                                        <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                                    </button>
                                    
                                    <div class="secure-badge">
                                        <i class="fas fa-shield-alt"></i>
                                        <span>Pembayaran Aman & Terenkripsi</span>
                                    </div>
                                </form>
                            @else
                                <div class="cart-guest-message">
                                    Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu untuk melakukan checkout
                                </div>
                            @endauth
                        </div>

                        <!-- Trust Badges -->
                        <div class="checkout-card" data-aos="fade-left" data-aos-delay="100"
                            style="background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink)); color: white; border: none;">
                            <div style="text-align: center;">
                                <i class="fas fa-shipping-fast" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                                <h4 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem;">Pengiriman Cepat
                                </h4>
                                <p style="font-size: 0.9375rem; opacity: 0.95;">Pesanan diantar dalam 30-45 menit</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="cart-items-card" data-aos="zoom-in">
                    <div class="cart-empty">
                        <div class="cart-empty-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="cart-empty-title">Keranjang Anda Masih Kosong</h2>
                        <p class="cart-empty-text">Yuk, mulai belanja dan tambahkan menu favorit Anda ke keranjang!</p>
                        <a href="{{ route('products.index') }}" class="cart-hero-btn">
                            <i class="fas fa-utensils"></i> Mulai Belanja Sekarang
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

        // Quantity Change Function
        function changeQuantity(btn, delta) {
            const form = btn.closest('form');
            const input = form.querySelector('.qty-input');
            let newValue = parseInt(input.value) + delta;
            
            if (newValue >= 1) {
                input.value = newValue;
                form.submit();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        @auth
        // Initialize map directly (no modal)
        const storeLocation = [{{ $storeLatitude ?? '-6.2088' }}, {{ $storeLongitude ?? '106.8456' }}];
        const subtotal = {{ $subtotal }};
        const shippingBaseCost = {{ $shippingBaseCost ?? 5000 }};
        const shippingCostPerKm = {{ $shippingCostPerKm ?? 2000 }};
        
        const map = L.map('map').setView(storeLocation, 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add store marker with custom icon
        const storeIcon = L.divIcon({
            className: 'custom-store-marker',
            html: '<div style="background: linear-gradient(135deg, #ec4899, #f472b6); width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.4); display: flex; align-items: center; justify-content: center;"><i class="fas fa-store" style="color: white; font-size: 16px; transform: rotate(45deg);"></i></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        const storeMarker = L.marker(storeLocation, { icon: storeIcon }).addTo(map);
        storeMarker.bindPopup('<div style="text-align: center; font-weight: 700; color: #ec4899;"><i class="fas fa-store"></i> Dapur Sakura<br><small style="color: #6b7280;">Lokasi Toko</small></div>');

        let deliveryMarker;
        let currentShippingCost = 0;

        function calculateShipping(distanceKm) {
            // Formula: Base Cost + (Distance * Cost Per Km)
            return shippingBaseCost + (distanceKm * shippingCostPerKm);
        }

        function updateOrderSummary(shippingCost) {
            currentShippingCost = shippingCost;
            const grandTotal = subtotal + shippingCost;
            
            document.getElementById('shippingCostDisplay').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
            document.getElementById('grandTotalDisplay').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // Click handler for delivery location
        map.on('click', function(e) {
            if (deliveryMarker) {
                map.removeLayer(deliveryMarker);
            }
            
            // Custom delivery marker icon
            const deliveryIcon = L.divIcon({
                className: 'custom-delivery-marker',
                html: '<div style="background: linear-gradient(135deg, #10b981, #34d399); width: 35px; height: 35px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); display: flex; align-items: center; justify-content: center;"><i class="fas fa-map-marker-alt" style="color: white; font-size: 14px; transform: rotate(45deg);"></i></div>',
                iconSize: [35, 35],
                iconAnchor: [17, 35]
            });
            
            deliveryMarker = L.marker(e.latlng, { icon: deliveryIcon }).addTo(map);
            
            // Calculate distance
            const distance = map.distance(storeLocation, e.latlng) / 1000; // in km
            const distanceFormatted = distance.toFixed(2);
            
            // Calculate shipping cost
            const shippingCost = Math.round(calculateShipping(distance));
            
            // Update order summary
            updateOrderSummary(shippingCost);
            
            deliveryMarker.bindPopup(`<div style="text-align: center; font-weight: 700; color: #10b981;"><i class="fas fa-map-marker-alt"></i> Lokasi Pengiriman<br><small style="color: #6b7280;">Jarak: ${distanceFormatted} km<br>Ongkir: Rp ${shippingCost.toLocaleString('id-ID')}</small></div>`).openPopup();
            
            // Update hidden inputs
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // Set initial marker if old values exist
        const oldLat = document.getElementById('latitude').value;
        const oldLng = document.getElementById('longitude').value;
        if (oldLat && oldLng) {
            const deliveryIcon = L.divIcon({
                className: 'custom-delivery-marker',
                html: '<div style="background: linear-gradient(135deg, #10b981, #34d399); width: 35px; height: 35px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); display: flex; align-items: center; justify-content: center;"><i class="fas fa-map-marker-alt" style="color: white; font-size: 14px; transform: rotate(45deg);"></i></div>',
                iconSize: [35, 35],
                iconAnchor: [17, 35]
            });
            deliveryMarker = L.marker([oldLat, oldLng], { icon: deliveryIcon }).addTo(map);
            
            const distance = map.distance(storeLocation, [oldLat, oldLng]) / 1000;
            const distanceFormatted = distance.toFixed(2);
            const shippingCost = Math.round(calculateShipping(distance));
            
            updateOrderSummary(shippingCost);
            
            deliveryMarker.bindPopup(`<div style="text-align: center; font-weight: 700; color: #10b981;"><i class="fas fa-map-marker-alt"></i> Lokasi Pengiriman<br><small style="color: #6b7280;">Jarak: ${distanceFormatted} km<br>Ongkir: Rp ${shippingCost.toLocaleString('id-ID')}</small></div>`);
            map.setView([oldLat, oldLng], 15);
        }
        @endauth
    </script>
</body>

</html>


