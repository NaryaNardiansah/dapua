<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Only show loading screen once per session/login -->
    @if(!session()->has('admin_splash_shown'))
        <x-loading-screen title="Dapur Sakura Admin" subtitle="Menyiapkan Panel Admin..." />
        @php session()->put('admin_splash_shown', true); @endphp
    @endif

    <!-- Custom Admin Styles -->
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
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Loading Animation */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .loader-content {
            text-align: center;
            color: var(--pure-white);
        }

        .loader-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: var(--pure-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .loader-logo img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .loader-text {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid var(--pure-white);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Admin Navbar Styles (deprecated, hidden) */
        .admin-navbar {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            box-shadow: var(--shadow-lg);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
            /* hidden because we use sidebar now */
        }

        .navbar-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 75px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .navbar-container::-webkit-scrollbar {
            display: none;
        }

        /* Admin Sidebar Styles - Enhanced */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #ec4899 0%, #f472b6 50%, #f9a8d4 100%);
            color: var(--pure-white);
            box-shadow: 4px 0 25px rgba(236, 72, 153, 0.3);
            z-index: 1100;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .sidebar-logo {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
            color: var(--pure-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .sidebar-logo:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            line-height: 1.3;
            flex: 1;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pure-white);
            letter-spacing: -0.5px;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            opacity: 0.7;
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 20px 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-section-title {
            padding: 12px 16px 8px 16px;
            margin-bottom: 8px;
            font-size: 0.7rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-item {
            position: relative;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            margin: 0 8px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            background: transparent;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(135deg, #ec4899, #f472b6);
            border-radius: 0 3px 3px 0;
            transition: height 0.3s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--pure-white);
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
        }

        .sidebar-link:hover::before {
            height: 60%;
            background: rgba(255, 255, 255, 0.8);
        }

        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: var(--pure-white);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }

        .sidebar-link.active::before {
            height: 80%;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-link span {
            flex: 1;
        }

        .sidebar-badge {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 12px;
            min-width: 22px;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 3px 10px rgba(239, 68, 68, 0.6);
            }
        }

        .sidebar-caret {
            margin-left: auto;
            font-size: 0.75rem;
            opacity: 0.7;
            transition: transform 0.3s ease;
        }

        .sidebar-item.open .sidebar-caret {
            transform: rotate(180deg);
            opacity: 1;
        }

        .sidebar-submenu {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin: 8px 8px 8px 44px;
            padding-left: 16px;
            border-left: 2px solid rgba(255, 255, 255, 0.3);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            opacity: 0;
        }

        .sidebar-item.open .sidebar-submenu {
            max-height: 600px;
            opacity: 1;
        }

        .sidebar-sublink {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-sublink::before {
            content: '';
            position: absolute;
            left: -16px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar-sublink:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--pure-white);
            padding-left: 16px;
        }

        .sidebar-sublink:hover::before {
            opacity: 1;
            background: rgba(255, 255, 255, 0.8);
        }

        .sidebar-sublink.active {
            background: rgba(255, 255, 255, 0.25);
            color: var(--pure-white);
            font-weight: 600;
            padding-left: 16px;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
        }

        .sidebar-sublink.active::before {
            opacity: 1;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
            width: 8px;
            height: 8px;
            left: -18px;
        }

        .sidebar-sublink i {
            font-size: 0.85rem;
            width: 16px;
            text-align: center;
        }

        .sidebar-sublink span {
            flex: 1;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-user:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .sidebar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #f472b6);
            color: var(--pure-white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--pure-white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-action {
            display: flex;
            gap: 8px;
            margin-left: auto;
            align-items: center;
        }

        .sidebar-user-link {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .sidebar-user-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--pure-white);
            transform: scale(1.1);
        }

        .sidebar-logout-btn {
            color: rgba(255, 100, 100, 0.9);
        }

        .sidebar-logout-btn:hover {
            background: rgba(255, 100, 100, 0.2);
            color: #ff6464;
        }

        .d-inline {
            display: inline;
        }

        .sidebar-footer form {
            margin: 0;
            padding: 0;
        }

        /* Sidebar toggle (mobile) */
        .sidebar-toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1200;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #ec4899, #f472b6);
            color: var(--pure-white);
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .sidebar-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Content layout with sidebar */
        .with-sidebar .main-content {
            margin-left: 280px;
            margin-top: 0;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .with-sidebar .main-content {
                margin-left: 260px;
            }

            .admin-sidebar {
                width: 260px;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                left: -280px;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
            }

            .admin-sidebar.active {
                left: 0;
            }

            .with-sidebar .main-content {
                margin-left: 0;
            }

            .sidebar-toggle-btn {
                display: inline-flex;
            }
        }

        .navbar-brand {
            flex-shrink: 0;
            margin-right: 2.5rem;
        }

        .brand-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--pure-white);
            transition: transform 0.3s ease;
        }

        .brand-link:hover {
            transform: scale(1.02);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--pure-white);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .logo-circle i {
            font-size: 1.5rem;
            color: var(--primary-pink);
        }

        .brand-link:hover .logo-circle {
            transform: rotate(5deg);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 500;
            margin-top: 1px;
            white-space: nowrap;
        }

        .navbar-menu {
            flex: 1;
            display: flex;
            justify-content: center;
            min-width: 0;
            margin: 0 1.5rem;
        }

        .nav-list {
            display: flex;
            list-style: none;
            gap: 1.75rem;
            margin: 0;
            padding: 0;
            min-width: max-content;
            align-items: center;
        }

        .nav-item {
            position: relative;
            flex-shrink: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 13px 20px;
            color: var(--pure-white);
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
            min-height: 46px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover::before {
            opacity: 1;
        }

        .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-link.active::before {
            opacity: 0;
        }

        .nav-icon {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .nav-link:hover .nav-icon {
            transform: scale(1.1);
        }

        .nav-text {
            position: relative;
            z-index: 2;
        }

        .nav-indicator {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: var(--pure-white);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .nav-link:hover .nav-indicator {
            width: 60%;
        }

        .nav-link.active .nav-indicator {
            width: 80%;
        }

        .notification-badge {
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .nav-item:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: var(--pure-white);
            border-radius: 15px;
            box-shadow: var(--shadow-xl);
            padding: 8px 0;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .nav-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .dropdown-link:hover {
            background: rgba(236, 72, 153, 0.05);
            color: var(--primary-pink);
            border-left-color: var(--primary-pink);
            transform: translateX(5px);
        }

        .dropdown-link i {
            font-size: 0.9rem;
            width: 16px;
            text-align: center;
        }

        /* User Section */
        .navbar-user {
            flex-shrink: 0;
            margin-left: 2.5rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 18px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            color: var(--pure-white);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            min-height: 46px;
        }

        .user-button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .user-avatar {
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: var(--pure-white);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-pink);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.15;
        }

        .user-name {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.15;
            white-space: nowrap;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
            line-height: 1.15;
            white-space: nowrap;
        }

        .user-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .user-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: var(--pure-white);
            border-radius: 15px;
            box-shadow: var(--shadow-xl);
            padding: 8px 0;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .user-dropdown:hover .user-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown:hover .user-arrow {
            transform: rotate(180deg);
        }

        .user-menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .user-menu-link:hover {
            background: rgba(236, 72, 153, 0.05);
            color: var(--primary-pink);
        }

        .user-menu-link i {
            font-size: 0.9rem;
            width: 16px;
            text-align: center;
        }

        .user-menu-divider {
            height: 1px;
            background: rgba(236, 72, 153, 0.1);
            margin: 8px 0;
            border: none;
        }

        .logout-btn {
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.05);
            color: #ef4444;
        }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        .hamburger-line {
            width: 25px;
            height: 3px;
            background: var(--pure-white);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .nav-link.active .nav-indicator {
            width: 80%;
        }

        .notification-badge {
            background: #ef4444;
            color: var(--pure-white);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            cursor: pointer;
        }

        .dropdown-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--pure-white);
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid var(--gray-200);
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-link {
            display: block;
            padding: 12px 20px;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--gray-100);
        }

        .dropdown-link:last-child {
            border-bottom: none;
        }

        .dropdown-link:hover {
            background: var(--light-pink);
            color: var(--primary-pink);
            padding-left: 24px;
        }

        /* User Section */
        .navbar-user {
            flex-shrink: 0;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            padding: 8px 16px;
            border-radius: 25px;
            color: var(--pure-white);
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .user-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .user-avatar {
            font-size: 1.5rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .user-arrow {
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .user-dropdown:hover .user-arrow {
            transform: rotate(180deg);
        }

        .user-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--pure-white);
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            border: 1px solid var(--gray-200);
            margin-top: 8px;
        }

        .user-dropdown:hover .user-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--gray-100);
        }

        .user-menu-link:last-child {
            border-bottom: none;
        }

        .user-menu-link:hover {
            background: var(--light-pink);
            color: var(--primary-pink);
        }

        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .user-menu-divider {
            border: none;
            height: 1px;
            background: var(--gray-200);
            margin: 8px 0;
        }

        /* Main Content */
        .main-content {
            margin-top: 0;
            min-height: 100vh;
            padding: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .navbar-container {
                padding: 0 1.25rem;
            }

            .nav-list {
                gap: 1.25rem;
            }

            .nav-link {
                padding: 11px 18px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 1024px) {
            .navbar-container {
                max-width: 100%;
                overflow-x: auto;
            }

            .nav-list {
                gap: 0.8rem;
            }

            .nav-link {
                padding: 8px 10px;
                font-size: 0.8rem;
            }

            .brand-text {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 1rem;
                height: 65px;
            }

            .navbar-menu {
                position: fixed;
                top: 65px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 65px);
                background: var(--pure-white);
                flex-direction: column;
                justify-content: flex-start;
                padding: 2rem 0;
                transition: left 0.3s ease;
                z-index: 1000;
                overflow-y: auto;
            }

            .navbar-menu.active {
                left: 0;
            }

            .nav-list {
                flex-direction: column;
                gap: 0;
                width: 100%;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                width: 100%;
                padding: 15px 2rem;
                color: #374151;
                border-radius: 0;
                justify-content: flex-start;
                border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            }

            .nav-link:hover {
                background: rgba(236, 72, 153, 0.05);
                color: var(--primary-pink);
                transform: none;
            }

            .nav-link.active {
                background: rgba(236, 72, 153, 0.1);
                color: var(--primary-pink);
            }

            .nav-text {
                display: block;
                font-size: 1rem;
            }

            .nav-icon {
                font-size: 1.2rem;
            }

            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                background: rgba(236, 72, 153, 0.05);
                border-radius: 0;
                margin: 0;
                padding: 0;
            }

            .dropdown-link {
                padding: 12px 3rem;
                color: #6b7280;
                border-left: none;
            }

            .dropdown-link:hover {
                background: rgba(236, 72, 153, 0.1);
                color: var(--primary-pink);
                transform: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .navbar-user {
                margin-left: 1rem;
            }

            .user-button {
                padding: 6px 12px;
            }

            .user-info {
                display: none;
            }

            .brand-text {
                display: none;
            }

            .main-content {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-container {
                padding: 0 0.75rem;
                height: 60px;
            }

            .navbar-menu {
                top: 60px;
                height: calc(100vh - 60px);
            }

            .nav-link {
                padding: 12px 1.5rem;
            }

            .dropdown-link {
                padding: 10px 2.5rem;
            }

            .logo-circle {
                width: 35px;
                height: 35px;
            }

            .logo-circle i {
                font-size: 1.2rem;
            }

            .user-avatar {
                width: 30px;
                height: 30px;
                font-size: 1rem;
            }

            .user-button {
                padding: 5px 10px;
            }
        }

        /* Scroll Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }

        .fade-in-left {
            opacity: 0;
            transform: translateX(-30px);
            animation: fadeInLeft 0.6s ease forwards;
        }

        .fade-in-right {
            opacity: 0;
            transform: translateX(30px);
            animation: fadeInRight 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .mobile-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .hamburger-line {
            width: 20px;
            height: 2px;
            background: var(--pure-white);
            margin: 2px 0;
            transition: all 0.3s ease;
            border-radius: 1px;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        /* Mobile Overlay */
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .navbar-container {
                padding: 0 1.5rem;
            }

            .nav-list {
                gap: 1.5rem;
            }

            .nav-text {
                font-size: 0.9rem;
            }

            .brand-title {
                font-size: 1.3rem;
            }

            .brand-subtitle {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }

            .navbar-menu {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 280px;
                height: calc(100vh - 70px);
                background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
                flex-direction: column;
                justify-content: flex-start;
                align-items: stretch;
                transition: left 0.3s ease;
                z-index: 1000;
                box-shadow: var(--shadow-xl);
                backdrop-filter: blur(20px);
            }

            .navbar-menu.active {
                left: 0;
            }

            .nav-list {
                flex-direction: column;
                gap: 0;
                padding: 1rem 0;
                width: 100%;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                width: 100%;
                padding: 16px 20px;
                border-radius: 0;
                justify-content: flex-start;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
                transform: none;
            }

            .nav-text {
                font-size: 1rem;
            }

            .nav-icon {
                font-size: 1.2rem;
            }

            /* Dropdown in mobile */
            .dropdown-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                background: rgba(255, 255, 255, 0.1);
                border: none;
                border-radius: 0;
                margin: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            .dropdown.active .dropdown-menu {
                max-height: 300px;
            }

            .dropdown-link {
                color: rgba(255, 255, 255, 0.9);
                padding: 12px 40px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .dropdown-link:hover {
                background: rgba(255, 255, 255, 0.1);
                color: var(--pure-white);
                padding-left: 40px;
            }

            /* User section in mobile */
            .navbar-user {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1001;
            }

            .user-button {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                padding: 0;
                justify-content: center;
            }

            .user-info {
                display: none;
            }

            .user-avatar {
                font-size: 2rem;
            }

            .user-menu {
                bottom: 100%;
                top: auto;
                right: 0;
                transform: translateY(10px);
            }

            .user-dropdown:hover .user-menu {
                transform: translateY(0);
            }

            /* Brand adjustments */
            .brand-text {
                display: none;
            }

            .logo-image {
                width: 40px;
                height: 40px;
            }

            .navbar-container {
                padding: 0 1rem;
                height: 70px;
            }
        }

        @media (max-width: 480px) {
            .navbar-container {
                padding: 0 0.75rem;
            }

            .navbar-menu {
                width: 100%;
                left: -100%;
            }

            .mobile-menu-toggle {
                width: 35px;
                height: 35px;
            }

            .hamburger-line {
                width: 18px;
            }

            .logo-image {
                width: 35px;
                height: 35px;
            }

            .user-button {
                width: 50px;
                height: 50px;
            }

            .user-avatar {
                font-size: 1.5rem;
            }
        }

        /* Tablet specific adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .nav-list {
                gap: 1rem;
            }

            .nav-text {
                font-size: 0.85rem;
            }

            .brand-title {
                font-size: 1.2rem;
            }

            .user-info {
                display: none;
            }

            .user-button {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                padding: 0;
                justify-content: center;
            }

            .user-avatar {
                font-size: 1.8rem;
            }
        }

        /* Admin Page Responsive Styles */
        .main-content {
            margin-top: 70px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
        }

        /* Hero Section Responsive */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-title {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            color: var(--pure-white);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hero-icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .hero-icon {
            font-size: 2rem;
            color: var(--pure-white);
        }

        .hero-title-text {
            display: flex;
            flex-direction: column;
        }

        .hero-title-main {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero-title-sub {
            font-size: 1rem;
            font-weight: 400;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.125rem;
            font-weight: 400;
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-decorative-elements {
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .decorative-line {
            position: absolute;
            top: 20%;
            right: 20px;
            width: 2px;
            height: 60px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 1px;
        }

        .decorative-dots {
            position: absolute;
            top: 40%;
            right: 40px;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
        }

        .decorative-circle {
            position: absolute;
            bottom: 20%;
            right: 30px;
            width: 60px;
            height: 60px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
        }

        /* Stats Section Responsive */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-pink), var(--secondary-pink));
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--pure-white);
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .stat-change {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-change.positive {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Content Cards Responsive */
        .content-card {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title i {
            color: var(--primary-pink);
        }

        .card-actions {
            display: flex;
            gap: 0.75rem;
        }

        /* Buttons Responsive */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: var(--pure-white);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: var(--pure-white);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: var(--pure-white);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: var(--pure-white);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        /* Tables Responsive */
        .table-container {
            background: var(--pure-white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--gray-50);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 1px solid var(--gray-200);
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table tr:hover {
            background: var(--gray-50);
        }

        /* Forms Responsive */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: var(--pure-white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 12px;
            font-size: 0.875rem;
            background: var(--pure-white);
            cursor: pointer;
        }

        /* Status Alert Responsive */
        .status-alert {
            background: linear-gradient(135deg, #10b981, #059669);
            color: var(--pure-white);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .alert-text {
            font-weight: 500;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
            }

            .hero-section {
                padding: 2rem 1.5rem;
            }

            .hero-title {
                font-size: 2rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .hero-title-main {
                font-size: 2rem;
            }

            .hero-icon-wrapper {
                width: 60px;
                height: 60px;
            }

            .hero-icon {
                font-size: 1.5rem;
            }

            .stats-section {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .content-card {
                padding: 1.5rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .card-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
                margin-top: 70px;
            }

            .hero-section {
                padding: 1.5rem 1rem;
                border-radius: 16px;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .hero-title-main {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .hero-icon {
                font-size: 1.25rem;
            }

            .stats-section {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .content-card {
                padding: 1rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .card-actions {
                width: 100%;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            /* Table responsive */
            .table-container {
                overflow-x: auto;
            }

            .table {
                min-width: 600px;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }

            .hero-section {
                padding: 1rem 0.75rem;
                border-radius: 12px;
            }

            .hero-title {
                font-size: 1.5rem;
            }

            .hero-title-main {
                font-size: 1.5rem;
            }

            .hero-subtitle {
                font-size: 0.875rem;
            }

            .hero-icon-wrapper {
                width: 40px;
                height: 40px;
            }

            .hero-icon {
                font-size: 1rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .content-card {
                padding: 0.75rem;
            }

            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }

            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
                font-size: 0.8rem;
            }
        }

        /* Delay Classes */
        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-800 {
            animation-delay: 0.8s;
        }

        .delay-900 {
            animation-delay: 0.9s;
        }

        .delay-1000 {
            animation-delay: 1.0s;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Global Loading Manager -->
    <script src="{{ asset('js/global-loading.js') }}"></script>
</head>

<body class="with-sidebar">
    <!-- Loading screen is now handled by the component -->

    <!-- Sidebar Toggle (mobile) -->
    <button id="sidebarToggle" class="sidebar-toggle-btn" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside id="adminSidebar" class="admin-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('images/logo-sakura.jpg') }}" alt="Logo"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
            </div>
            <div class="sidebar-brand">
                <span class="sidebar-title">Dapur Sakura</span>
                <span class="sidebar-subtitle">Admin Panel</span>
            </div>
        </div>

        <div class="sidebar-scroll">
            <div class="sidebar-section-title">Menu Utama</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item" data-group="orders">
                    <a href="#" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Pesanan</span>
                        @php
                            $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'diproses'])->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="sidebar-badge">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                        @endif
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.orders.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.orders.index') && !request()->has('status') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Semua Pesanan</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}?status=diproses"
                            class="sidebar-sublink {{ request()->get('status') == 'diproses' ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>Diproses</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}?status=dikirim"
                            class="sidebar-sublink {{ request()->get('status') == 'dikirim' ? 'active' : '' }}">
                            <i class="fas fa-truck"></i>
                            <span>Dikirim</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}?status=selesai"
                            class="sidebar-sublink {{ request()->get('status') == 'selesai' ? 'active' : '' }}">
                            <i class="fas fa-check-circle"></i>
                            <span>Selesai</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}?status=dibatalkan"
                            class="sidebar-sublink {{ request()->get('status') == 'dibatalkan' ? 'active' : '' }}">
                            <i class="fas fa-times-circle"></i>
                            <span>Dibatalkan</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item" data-group="products">
                    <a href="#" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Produk</span>
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.products.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.products.index') && !request()->has('filter') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Semua Produk</span>
                        </a>
                        <a href="{{ route('admin.products.create') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Produk</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}?filter=best_seller"
                            class="sidebar-sublink {{ request()->get('filter') == 'best_seller' ? 'active' : '' }}">
                            <i class="fas fa-star"></i>
                            <span>Best Seller</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">
                            <i class="fas fa-comment-dots"></i>
                            <span>Kelola Ulasan</span>
                        </a>
                        <a href="{{ route('admin.products.export') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.products.export') ? 'active' : '' }}">
                            <i class="fas fa-download"></i>
                            <span>Export Data</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item" data-group="categories">
                    <a href="#" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.categories.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Semua Kategori</span>
                        </a>
                        <a href="{{ route('admin.categories.create') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Kategori</span>
                        </a>
                        <a href="{{ route('admin.categories.export') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.categories.export') ? 'active' : '' }}">
                            <i class="fas fa-download"></i>
                            <span>Export Data</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item" data-group="users">
                    <a href="#"
                        class="sidebar-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.driver.*') || request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Pengguna</span>
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.users.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <i class="fas fa-user-friends"></i>
                            <span>Semua Pengguna</span>
                        </a>
                        <a href="{{ route('admin.driver.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.driver.index') || request()->routeIs('admin.drivers.index') ? 'active' : '' }}">
                            <i class="fas fa-id-card"></i>
                            <span>Driver</span>
                        </a>
                        <a href="{{ route('admin.driver.create') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.driver.create') ? 'active' : '' }}">
                            <i class="fas fa-user-plus"></i>
                            <span>Tambah Driver</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item" data-group="delivery">
                    <a href="#" class="sidebar-link {{ request()->routeIs('admin.delivery.*') ? 'active' : '' }}">
                        <i class="fas fa-truck"></i>
                        <span>Pengiriman</span>
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.delivery.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.delivery.index') ? 'active' : '' }}">
                            <i class="fas fa-route"></i>
                            <span>Manajemen Pengiriman</span>
                        </a>
                        <a href="{{ route('admin.delivery.zones') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.delivery.zones') || request()->routeIs('admin.zones.index') ? 'active' : '' }}">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Manajemen Ongkir</span>
                        </a>
                    </div>
                </li>

                <li class="sidebar-item" data-group="tracking">
                    <a href="{{ route('admin.tracking.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('admin.tracking.*') ? 'active' : '' }}">
                        <i class="fas fa-map-location-dot"></i>
                        <span>Tracking</span>
                    </a>
                </li>


                <li class="sidebar-item" data-group="settings">
                    <a href="#" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-gear"></i>
                        <span>Pengaturan</span>
                        <i class="fas fa-chevron-down sidebar-caret"></i>
                    </a>
                    <div class="sidebar-submenu">
                        <a href="{{ route('admin.settings.index') }}"
                            class="sidebar-sublink {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <i class="fas fa-sliders-h"></i>
                            <span>Pengaturan Sistem</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}</div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="sidebar-user-role">
                        @php
                            $userRole = 'Customer';
                            if (auth()->user()->isAdmin()) {
                                $userRole = 'Administrator';
                            } elseif (auth()->user()->isDriver()) {
                                $userRole = 'Driver';
                            }
                        @endphp
                        {{ $userRole }}
                    </div>
                </div>
                <div class="sidebar-user-action">
                    <a href="{{ route('profile.edit') }}" class="sidebar-user-link" title="Edit Profile">
                        <i class="fas fa-user-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="sidebar-user-link sidebar-logout-btn" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom Admin Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Luxury Loader
        window.addEventListener('load', function () {
            const loader = document.getElementById('luxuryLoader');
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1500);
        });

        // Show loader on page navigation
        document.addEventListener('DOMContentLoaded', function () {
            // Hide loader immediately if page is already loaded
            if (document.readyState === 'complete') {
                const loader = document.getElementById('luxuryLoader');
                setTimeout(() => {
                    loader.classList.add('hidden');
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 800);
                }, 500);
            }
        });

        // Admin Sidebar interactions
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            document.body.classList.add('with-sidebar');

            // open/close sidebar on mobile
            function openSidebar() {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    if (sidebar.classList.contains('active')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Auto-open menu items with active submenu or active parent link
            document.querySelectorAll('.sidebar-item').forEach(item => {
                const activeSubLink = item.querySelector('.sidebar-sublink.active');
                const activeParentLink = item.querySelector('.sidebar-link.active');

                if (activeSubLink || activeParentLink) {
                    item.classList.add('open');
                }
            });

            // Also check if parent link should be active based on route
            document.querySelectorAll('.sidebar-item[data-group]').forEach(item => {
                const link = item.querySelector('.sidebar-link');
                if (link && link.classList.contains('active')) {
                    item.classList.add('open');
                }
            });

            // Collapsible groups
            document.querySelectorAll('.admin-sidebar .sidebar-item[data-group] > .sidebar-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    const parent = this.closest('.sidebar-item');
                    const submenu = parent.querySelector('.sidebar-submenu');

                    if (submenu) {
                        e.preventDefault();
                        const isOpen = parent.classList.contains('open');

                        // Close all other open menus (optional - remove if you want multiple open)
                        // document.querySelectorAll('.sidebar-item.open').forEach(openItem => {
                        //     if (openItem !== parent) {
                        //         openItem.classList.remove('open');
                        //     }
                        // });

                        parent.classList.toggle('open');
                    }
                });
            });

            // Close sidebar on resize up
            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });

            // Add smooth scroll to active menu item
            const activeLink = document.querySelector('.sidebar-link.active, .sidebar-sublink.active');
            if (activeLink) {
                setTimeout(() => {
                    activeLink.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 300);
            }

            // Add loading screen for navigation
            const adminNavLinks = document.querySelectorAll('.nav-link[href], .dropdown-link[href]');
            adminNavLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const href = this.getAttribute('href');
                    if (href && !href.startsWith('#')) {
                        if (window.showLoadingForAction) {
                            window.showLoadingForAction('navigate', 'Navigating...');
                        }
                    }
                });
            });
        });

        // Loader otomatis pada navigasi admin dinonaktifkan atas permintaan user
        // Agar loading hanya muncul sekali saat pertama kali login

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effects to cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = 'var(--shadow-xl)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'var(--shadow-md)';
            });
        });

        // Form animations
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('focus', function () {
                if (this.parentElement) {
                    this.parentElement.classList.add('focused');
                }
            });

            input.addEventListener('blur', function () {
                if (this.parentElement && !this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>

</html>