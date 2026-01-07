<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
        }

        /* Decorative Background Elements */
        .bg-decoration {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }

        .decoration-circle-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            top: -100px;
            right: -100px;
            animation: float 20s ease-in-out infinite;
        }

        .decoration-circle-2 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, var(--secondary-pink), var(--primary-pink));
            bottom: -50px;
            left: -50px;
            animation: float 15s ease-in-out infinite reverse;
        }

        .decoration-circle-3 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 10s ease-in-out infinite;
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

        @keyframes pulse {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.1;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.2);
                opacity: 0.15;
            }
        }

        /* Auth Card */
        .auth-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 360px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 18px;
            padding: 1.5rem 1.5rem;
            box-shadow:
                0 20px 60px rgba(236, 72, 153, 0.15),
                0 8px 25px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(236, 72, 153, 0.1);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Top Gradient Bar */
        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink), var(--primary-pink));
            background-size: 200% 100%;
            border-radius: 24px 24px 0 0;
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

        /* Logo Section */
        .logo-container {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 6px 20px rgba(236, 72, 153, 0.3),
                0 2px 8px rgba(236, 72, 153, 0.2);
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .logo-icon i {
            font-size: 1.25rem;
            color: white;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.375rem;
        }

        .auth-subtitle {
            font-size: 0.8125rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .auth-card {
                padding: 1.5rem 1.25rem;
                border-radius: 16px;
            }

            .auth-title {
                font-size: 1.25rem;
            }

            .logo-icon {
                width: 38px;
                height: 38px;
            }

            .logo-icon i {
                font-size: 1.125rem;
            }

            .logo-text {
                font-size: 1.125rem;
            }

            .decoration-circle-1,
            .decoration-circle-2,
            .decoration-circle-3 {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 0.75rem;
            }

            .auth-card {
                padding: 1.25rem 1rem;
            }

            .auth-title {
                font-size: 1.125rem;
            }

            .auth-subtitle {
                font-size: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <!-- Background Decorations -->
        <div class="bg-decoration">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
            <div class="decoration-circle decoration-circle-3"></div>
        </div>

        <!-- Auth Card -->
        <div class="auth-card">
            <!-- Logo -->
            <div class="logo-container">
                <div class="logo-wrapper">
                    <div class="logo-icon" style="overflow: hidden;">
                        <img src="{{ asset('images/logo-sakura.jpg') }}" alt="Logo"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="logo-text">{{ config('app.name', 'Dapur Sakura') }}</div>
                </div>
            </div>

            <!-- Content Slot -->
            {{ $slot }}
        </div>
    </div>
</body>

</html>