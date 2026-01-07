@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $product->name }} - {{ config('app.name', 'Dapur Sakura') }}</title>

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
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
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
            width: 700px;
            height: 700px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            top: -250px;
            right: -250px;
            animation: float 35s ease-in-out infinite;
        }

        .decoration-circle-2 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, var(--secondary-pink), var(--primary-pink));
            bottom: -200px;
            left: -200px;
            animation: float 30s ease-in-out infinite reverse;
        }

        .decoration-circle-3 {
            width: 350px;
            height: 350px;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            top: 40%;
            right: 10%;
            animation: pulse 25s ease-in-out infinite;
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

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.06;
            }

            50% {
                transform: scale(1.3);
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
        .product-nav {
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
        .product-content {
            position: relative;
            z-index: 10;
            max-width: 900px;
            margin: 0 auto;
            padding: 1rem 0.75rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0.75rem;
            font-size: 0.6875rem;
        }

        .breadcrumb a {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.375rem;
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

        /* Product Detail Grid */
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 2rem;
        }

        .product-image-section {
            grid-column: 1;
            grid-row: 1;
        }

        .product-info-section {
            grid-column: 2;
            grid-row: 1 / span 10;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.08);
            position: sticky;
            top: 2rem;
            z-index: 100;
        }

        .reviews-section {
            grid-column: 1;
            grid-row: 2;
        }

        .related-section {
            margin-top: 4rem;
            width: 100%;
        }

        /* Product Image Section */
        .product-image-section {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            padding: 0.75rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.08);
            position: relative;
            overflow: hidden;
        }

        .product-image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink), var(--primary-pink));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        .main-image-container {
            position: relative;
            margin-bottom: 0;
            width: 100%;
            overflow: hidden;
            border-radius: 8px;
        }

        .product-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: block;
        }

        .product-image-placeholder {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.1));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-pink);
            font-size: 7rem;
        }

        .image-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.6875rem;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }



        .product-category {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1));
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.6875rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.125rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.375rem;
            line-height: 1.2;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-bottom: 0.75rem;
        }

        .stars {
            display: flex;
            gap: 0.125rem;
        }

        .stars i {
            color: #fbbf24;
            font-size: 0.8125rem;
        }

        .rating-text {
            color: var(--gray-600);
            font-size: 0.6875rem;
            font-weight: 600;
        }

        .price-section {
            background: linear-gradient(135deg, var(--light-pink), rgba(236, 72, 153, 0.05));
            border-radius: 8px;
            padding: 0.625rem;
            margin-bottom: 0.625rem;
            border: 2px solid rgba(236, 72, 153, 0.15);
        }

        .price-label {
            font-size: 0.6875rem;
            color: var(--gray-600);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .original-price-detail {
            font-size: 1rem;
            color: var(--gray-400);
            text-decoration: line-through;
            font-weight: 600;
            -webkit-text-fill-color: var(--gray-400);
        }

        .discount-badge-detail {
            background: #fef2f2;
            color: #ef4444;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid #fee2e2;
        }

        .product-description {
            font-size: 0.75rem;
            color: var(--gray-700);
            line-height: 1.5;
            margin-bottom: 0.625rem;
            padding-bottom: 0.625rem;
            border-bottom: 2px solid rgba(236, 72, 153, 0.1);
        }

        .product-features {
            margin-bottom: 0.625rem;
        }

        .features-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.375rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-bottom: 0.25rem;
            padding: 0.375rem;
            background: white;
            border-radius: 5px;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .feature-icon {
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.6875rem;
            flex-shrink: 0;
        }

        .feature-text {
            font-size: 0.6875rem;
            color: var(--gray-700);
            font-weight: 600;
        }

        .quantity-section {
            margin-bottom: 0.625rem;
        }

        .quantity-label {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.375rem;
            display: block;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            background: var(--light-pink);
            padding: 0.375rem 0.5rem;
            border-radius: 6px;
            width: fit-content;
            border: 2px solid rgba(236, 72, 153, 0.2);
        }

        .quantity-btn {
            width: 24px;
            height: 24px;
            background: white;
            border: 2px solid var(--primary-pink);
            border-radius: 5px;
            color: var(--primary-pink);
            font-weight: 800;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: var(--primary-pink);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .quantity-value {
            font-size: 1rem;
            font-weight: 900;
            color: var(--primary-pink);
            min-width: 35px;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .add-to-cart-btn {
            flex: 1;
            padding: 0.625rem 0.875rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 900;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(236, 72, 153, 0.4);
        }

        .wishlist-btn {
            width: 40px;
            height: 40px;
            background: white;
            border: 2px solid var(--primary-pink);
            border-radius: 8px;
            color: var(--primary-pink);
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-btn:hover {
            background: var(--light-pink);
            transform: scale(1.05);
        }

        .product-meta {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid rgba(236, 72, 153, 0.1);
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .meta-label {
            color: var(--gray-600);
            font-weight: 600;
        }

        .meta-value {
            color: var(--gray-900);
            font-weight: 700;
        }

        /* Related Products */
        .related-section {
            margin-top: 3rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(244, 114, 182, 0.1));
            border: 2px solid rgba(236, 72, 153, 0.2);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin-bottom: 0.875rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.375rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            font-size: 1rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .product-image-section,
            .product-info-section,
            .reviews-section,
            .related-section {
                grid-column: 1 !important;
                grid-row: auto !important;
                position: static !important;
            }

            .product-info-section {
                margin-top: 0;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }

            .product-name {
                font-size: 2rem;
            }

            .product-price {
                font-size: 2.25rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .wishlist-btn {
                width: 100%;
            }
        }

        /* Reviews Section Styles */
        .reviews-section {
            margin-top: 3rem;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 12px;
            padding: 2rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 8px 20px rgba(236, 72, 153, 0.08);
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-pink);
        }

        .reviews-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--gray-900);
        }

        .review-card {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.3s ease;
        }

        .review-card:last-child {
            border-bottom: none;
        }

        .review-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .user-info .name {
            font-weight: 700;
            color: var(--gray-900);
            display: block;
        }

        .user-info .date {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .review-rating {
            margin-bottom: 0.75rem;
        }

        .review-comment {
            font-size: 0.9375rem;
            color: var(--gray-700);
            line-height: 1.6;
        }

        .review-form-container {
            margin-top: 3rem;
            padding: 2rem;
            background: var(--gray-50);
            border-radius: 12px;
            border: 2px dashed var(--primary-pink);
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: var(--primary-pink);
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--gray-300);
            transition: all 0.2s ease;
        }

        .rating-input label:hover,
        .rating-input label:hover~label,
        .rating-input input:checked~label {
            color: #fbbf24;
        }

        .textarea-input {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid var(--gray-200);
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .textarea-input:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        .submit-review-btn {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-review-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .guest-notice {
            text-align: center;
            padding: 2rem;
            background: var(--light-pink);
            border-radius: 12px;
            color: var(--dark-pink);
            font-weight: 600;
        }

        .guest-notice a {
            color: var(--primary-pink);
            text-decoration: underline;
            font-weight: 800;
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
    <nav class="product-nav">
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
    <div class="product-content">
        <!-- Breadcrumb -->
        <nav class="breadcrumb" data-aos="fade-right">
            <a href="{{ route('home') }}">
                <i class="fas fa-home"></i> Home
            </a>
            <span class="separator">/</span>
            <a href="{{ route('products.index') }}">Menu</a>
            <span class="separator">/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <!-- Product Detail -->
        <div class="product-detail-grid">
            <!-- Product Image -->
            <div class="product-image-section" data-aos="fade-right">
                <div class="main-image-container">
                    @if($product->image_path)
                        <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"
                            class="product-image">
                    @else
                        <div class="product-image-placeholder">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                    <div class="image-badge">
                        <i class="fas fa-fire"></i> Populer
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="product-info-section" data-aos="fade-left">
                @if($product->category)
                    <span class="product-category">
                        <i class="fas fa-tag"></i> {{ $product->category->name }}
                    </span>
                @endif

                <h1 class="product-name">{{ $product->name }}</h1>

                <div class="product-rating">
                    <div class="stars">
                        @php $rating = (float) ($product->reviews_avg_rating ?? 0); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $rating)
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                            @elseif($i - 0.5 <= $rating)
                                <i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>
                            @else
                                <i class="far fa-star" style="color: #fbbf24;"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-text">
                        {{ number_format($rating, 1) }} ({{ $product->reviews_count }} ulasan)
                    </span>
                </div>

                <div class="price-section">
                    <div class="price-label">Harga</div>
                    <div class="product-price">
                        <span>Rp {{ number_format($product->current_price, 0, ',', '.') }}</span>
                        @if($product->has_active_discount)
                            <span class="original-price-detail">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="discount-badge-detail">{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                </div>

                <div class="product-features">
                    <h3 class="features-title">Keunggulan Produk</h3>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="feature-text">Bahan segar & berkualitas tinggi</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-text">Higienis & aman dikonsumsi</div>
                    </div>
                </div>

                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf

                    <div class="quantity-section">
                        <label class="quantity-label">Jumlah Pesanan</label>
                        <div class="quantity-control">
                            <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="quantity-value" id="quantityDisplay">1</span>
                            <input type="hidden" name="quantity" id="quantityInput" value="1">
                            <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>

                <form action="{{ route('wishlist.toggle', $product) }}" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="wishlist-btn"
                        style="width: 100%; justify-content: center; display: flex; align-items: center; gap: 0.5rem; background: transparent; border: 2px solid #ec4899; color: #ec4899; padding: 0.75rem; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s ease;">
                        @auth
                            @if($product->isWishlistedBy(auth()->user()))
                                <i class="fas fa-heart"></i>
                                <span>Hapus dari Wishlist</span>
                            @else
                                <i class="far fa-heart"></i>
                                <span>Tambah ke Wishlist</span>
                            @endif
                        @else
                            <i class="far fa-heart"></i>
                            <span>Tambah ke Wishlist</span>
                        @endauth
                    </button>
                </form>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">SKU</span>
                        <span class="meta-value">{{ $product->id }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Kategori</span>
                        <span class="meta-value">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value" style="color: #10b981;">
                            <i class="fas fa-check-circle"></i> Tersedia
                        </span>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="reviews-section" data-aos="fade-up">
                <div class="reviews-header">
                    <h2 class="reviews-title">Ulasan Pelanggan</h2>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $rating)
                                <i class="fas fa-star" style="color: #fbbf24; font-size: 1.25rem;"></i>
                            @elseif($i - 0.5 <= $rating)
                                <i class="fas fa-star-half-alt" style="color: #fbbf24; font-size: 1.25rem;"></i>
                            @else
                                <i class="far fa-star" style="color: #fbbf24; font-size: 1.25rem;"></i>
                            @endif
                        @endfor
                        <span style="font-weight: 800; margin-left: 0.5rem; color: var(--gray-900);">
                            {{ number_format($rating, 1) }} / 5.0
                        </span>
                    </div>
                </div>

                @if($product->reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($product->reviews as $review)
                            <div class="review-card">
                                <div class="review-user">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div class="user-info">
                                        <span class="name">{{ $review->user->name }}</span>
                                        <span class="date">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star"
                                            style="color: {{ $i <= $review->rating ? '#fbbf24' : '#d1d5db' }}; font-size: 0.875rem;"></i>
                                    @endfor
                                </div>
                                @if($review->comment)
                                    <p class="review-comment">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 3rem; color: var(--gray-500);">
                        <i class="far fa-comment-dots" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <p>Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @endif

                <!-- Review Form -->
                <div class="review-form-container">
                    @auth
                        <h3 class="form-title">Tulis Ulasan Anda</h3>
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 1.5rem;">
                                <label
                                    style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--gray-700);">Rating</label>
                                <div class="rating-input">
                                    <input type="radio" name="rating" id="star5" value="5" required /><label for="star5"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star4" value="4" /><label for="star4"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star3" value="3" /><label for="star3"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star2" value="2" /><label for="star2"><i
                                            class="fas fa-star"></i></label>
                                    <input type="radio" name="rating" id="star1" value="1" /><label for="star1"><i
                                            class="fas fa-star"></i></label>
                                </div>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <label
                                    style="display: block; font-weight: 700; margin-bottom: 0.5rem; color: var(--gray-700);">Komentar
                                    (Opsional)</label>
                                <textarea name="comment" class="textarea-input"
                                    placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                            </div>
                            <button type="submit" class="submit-review-btn">
                                <i class="fas fa-paper-plane"></i> Kirim Ulasan
                            </button>
                        </form>
                    @else
                        <div class="guest-notice">
                            <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>
                            Silakan <a href="{{ route('login') }}">Masuk</a> atau <a
                                href="{{ route('register') }}">Daftar</a> untuk memberikan ulasan.
                        </div>
                    @endauth
                </div>
            </div>

        </div>

        <!-- Related Products (Full Width Below Grid) -->
        @if($related->count() > 0)
            <div class="related-section" data-aos="fade-up">
                <div class="section-header">
                    <div class="section-badge">
                        <i class="fas fa-sparkles"></i> Rekomendasi
                    </div>
                    <h2 class="section-title">Produk Terkait</h2>
                    <p class="section-subtitle">Menu lainnya yang mungkin Anda sukai dari kategori yang sama</p>
                </div>
                <div class="related-grid">
                    @foreach($related as $relatedProduct)
                        <x-product-card :product="$relatedProduct" />
                    @endforeach
                </div>
            </div>
        @endif


    </div>

    <!-- AOS Animation Script -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 60
        });

        let quantity = 1;

        function increaseQuantity() {
            quantity++;
            updateQuantity();
        }

        function decreaseQuantity() {
            if (quantity > 1) {
                quantity--;
                updateQuantity();
            }
        }

        function updateQuantity() {
            document.getElementById('quantityDisplay').textContent = quantity;
            document.getElementById('quantityInput').value = quantity;
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