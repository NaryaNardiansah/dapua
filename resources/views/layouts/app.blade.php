<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Universal Loading Screen Component -->
    <x-loading-screen />

    <!-- Legacy Loading Animation Styles (kept for compatibility) -->
    <style>
        :root {
            --primary-pink: #ec4899;
            --secondary-pink: #f472b6;
            --light-pink: #fce7f3;
            --dark-pink: #be185d;
            --pure-white: #ffffff;
        }

        /* Luxury Loading Animation */
        .luxury-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 50%, var(--light-pink) 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 1;
            visibility: visible;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .luxury-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .loader-container {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
        }

        /* Animated Logo */
        .loader-logo {
            position: relative;
            width: 120px;
            height: 120px;
            animation: logoFloat 3s ease-in-out infinite;
        }

        .logo-ring {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            background: linear-gradient(45deg, var(--pure-white), var(--primary-pink), var(--secondary-pink), var(--pure-white));
            background-size: 400% 400%;
            animation: gradientRotate 2s ease-in-out infinite;
        }

        .logo-ring::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink), var(--primary-pink));
            background-size: 400% 400%;
            animation: gradientRotate 2s ease-in-out infinite reverse;
            z-index: -1;
            filter: blur(8px);
            opacity: 0.7;
        }

        .logo-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--light-pink) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
        }

        .logo-icon {
            font-size: 2.5rem;
            color: var(--primary-pink);
            animation: iconPulse 2s ease-in-out infinite;
        }

        /* Loading Text */
        .loader-text {
            color: var(--pure-white);
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: textFade 2s ease-in-out infinite;
        }

        .loader-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 400;
            text-align: center;
            margin-top: 0.5rem;
            animation: textFade 2s ease-in-out infinite 0.5s;
        }

        /* Progress Bar */
        .loader-progress {
            width: 300px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--pure-white), var(--primary-pink), var(--pure-white));
            background-size: 200% 100%;
            border-radius: 2px;
            animation: progressFlow 2s ease-in-out infinite;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: progressShimmer 1.5s ease-in-out infinite;
        }

        /* Floating Particles */
        .loader-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: var(--pure-white);
            border-radius: 50%;
            opacity: 0.6;
            animation: particleFloat 4s ease-in-out infinite;
        }

        .particle:nth-child(1) {
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            top: 30%;
            right: 25%;
            animation-delay: 0.5s;
        }

        .particle:nth-child(3) {
            bottom: 30%;
            left: 30%;
            animation-delay: 1s;
        }

        .particle:nth-child(4) {
            bottom: 20%;
            right: 20%;
            animation-delay: 1.5s;
        }

        .particle:nth-child(5) {
            top: 50%;
            left: 10%;
            animation-delay: 2s;
        }

        .particle:nth-child(6) {
            top: 60%;
            right: 15%;
            animation-delay: 2.5s;
        }

        /* Decorative Elements */
        .loader-decoration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .decoration-line {
            position: absolute;
            width: 2px;
            height: 100px;
            background: linear-gradient(180deg, transparent, var(--pure-white), transparent);
            animation: lineFloat 3s ease-in-out infinite;
        }

        .decoration-line:nth-child(1) {
            top: 20%;
            left: 15%;
            animation-delay: 0s;
        }

        .decoration-line:nth-child(2) {
            top: 40%;
            right: 20%;
            animation-delay: 1s;
        }

        .decoration-line:nth-child(3) {
            bottom: 30%;
            left: 25%;
            animation-delay: 2s;
        }

        /* Animations */
        @keyframes logoFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(5deg);
            }
        }

        @keyframes gradientRotate {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes iconPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        @keyframes textFade {

            0%,
            100% {
                opacity: 1;
                transform: translateY(0);
            }

            50% {
                opacity: 0.7;
                transform: translateY(-2px);
            }
        }

        @keyframes progressFlow {
            0% {
                width: 0%;
            }

            50% {
                width: 70%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes progressShimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes particleFloat {

            0%,
            100% {
                transform: translateY(0px) scale(1);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-20px) scale(1.2);
                opacity: 1;
            }
        }

        @keyframes lineFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-15px) rotate(10deg);
                opacity: 0.8;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .loader-logo {
                width: 100px;
                height: 100px;
            }

            .logo-inner {
                width: 70px;
                height: 70px;
            }

            .logo-icon {
                font-size: 2rem;
            }

            .loader-text {
                font-size: 1.25rem;
            }

            .loader-progress {
                width: 250px;
            }
        }

        @media (max-width: 480px) {
            .loader-logo {
                width: 80px;
                height: 80px;
            }

            .logo-inner {
                width: 60px;
                height: 60px;
            }

            .logo-icon {
                font-size: 1.5rem;
            }

            .loader-text {
                font-size: 1rem;
            }

            .loader-progress {
                width: 200px;
            }
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sakura.jpg') }}">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script defer src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Global Loading Manager -->
    <script src="{{ asset('js/global-loading.js') }}"></script>

</head>

<body class="font-sans antialiased bg-brand-50">
    <!-- Loading screen is now handled by the component -->

    <!-- Page transition overlay -->
    <div id="page-transition"
        class="fixed inset-0 bg-white/70 backdrop-blur hidden opacity-0 transition-opacity duration-500 z-50"></div>
    <div class="min-h-screen">
        @include('layouts.navigation')

        <!-- Spacer for fixed navbar -->
        <div class="h-20"></div>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white/70 backdrop-blur shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>
    </div>

    <!-- runtime-extra is bundled by Vite via resources/js/app.js -->
</body>

</html>

<script>
    // Luxury Loader
    window.addEventListener('load', function () {
        const loader = document.getElementById('luxuryLoader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1500);
        }
    });

    // Show loader on page navigation
    document.addEventListener('DOMContentLoaded', function () {
        // Hide loader immediately if page is already loaded
        if (document.readyState === 'complete') {
            const loader = document.getElementById('luxuryLoader');
            if (loader) {
                setTimeout(() => {
                    loader.classList.add('hidden');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 800);
                }, 500);
            }
        }
    });

    // Show loader on link clicks
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function (e) {
            // Only show loader for internal links
            if (this.hostname === window.location.hostname) {
                const loader = document.getElementById('luxuryLoader');
                if (loader) {
                    loader.classList.remove('hidden');
                    loader.style.display = 'flex';
                    loader.style.opacity = '1';
                    loader.style.visibility = 'visible';
                }
            }
        });
    });

    window.addEventListener('DOMContentLoaded', function () {
        // Init AOS
        if (window.AOS) { AOS.init({ once: true, duration: 700, easing: 'ease-out-cubic' }); }

        // Page transition fade on nav link click
        const overlay = document.getElementById('page-transition');
        function fadeOutAndNavigate(href) {
            if (!overlay) { window.location = href; return; }
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => { overlay.classList.add('opacity-100'); });
            setTimeout(() => { window.location = href; }, 350);
        }
        document.querySelectorAll('a[href^="/"]').forEach(a => {
            a.addEventListener('click', (e) => {
                const href = a.getAttribute('href');
                if (href && !a.hasAttribute('data-no-transition') && e.button === 0 && !e.ctrlKey && !e.metaKey && a.target !== '_blank') {
                    e.preventDefault();
                    fadeOutAndNavigate(href);
                }
            });
        });

        // Micro tilt effect
        document.querySelectorAll('.tilt').forEach(el => {
            const strength = 10;
            el.addEventListener('mousemove', (e) => {
                const rect = el.getBoundingClientRect();
                const x = e.clientX - rect.left; const y = e.clientY - rect.top;
                const rx = ((y - rect.height / 2) / rect.height) * -strength;
                const ry = ((x - rect.width / 2) / rect.width) * strength;
                el.style.transform = `perspective(600px) rotateX(${rx}deg) rotateY(${ry}deg) translateZ(0)`;
            });
            el.addEventListener('mouseleave', () => { el.style.transform = 'perspective(600px) rotateX(0) rotateY(0)'; });
        });
    });
</script>
</body>

</html>