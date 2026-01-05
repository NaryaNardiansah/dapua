@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp

    <style>
        /* Wishlist Page - Home Theme (Luxury Pink) */
        :root {
            --primary-pink: #ec4899;
            --secondary-pink: #f472b6;
            --dark-pink: #db2777;
            --light-pink: #fce7f3;
            --pure-white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        body {
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 50%, var(--gray-50) 100%) !important;
            min-height: 100vh !important;
        }

        .wishlist-page {
            position: relative;
            z-index: 10;
            padding-bottom: 5rem;
        }

        /* Background Decorations matching Home */
        .bg-decoration-wishlist {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            top: 0;
            left: 0;
            pointer-events: none;
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

        /* Hero Section - Matching Home vibe */
        .wishlist-hero {
            animation: slideUp 0.8s ease-out;
            margin-bottom: 4rem;
            text-align: center;
        }

        .wishlist-hero-badge {
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

        .wishlist-hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--gray-900), var(--primary-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .wishlist-hero-subtitle {
            font-size: 1.25rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Card embedded in Hero Vibe */
        .wishlist-stats {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1rem 2rem;
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.1);
        }

        .wishlist-stats-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .wishlist-stats-text {
            font-weight: 800;
            font-size: 1.125rem;
            color: var(--gray-900);
        }

        /* Status Alert - Matching Home success colors */
        .wishlist-alert {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            padding: 1.25rem 2rem;
            border-radius: 16px;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
            font-weight: 600;
            animation: slideUp 0.5s ease-out;
        }

        /* Products Grid */
        .wishlist-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2.5rem;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .wishlist-product-wrapper {
            position: relative;
            transition: transform 0.3s ease;
        }

        .wishlist-product-wrapper:hover {
            transform: translateY(-5px);
        }

        .wishlist-product-remove {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 20;
        }

        .wishlist-product-remove-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: white;
            border: 2px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            font-size: 0.875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .wishlist-product-remove-btn:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
        }

        /* Empty State - Matching Home Feature Card */
        .wishlist-empty {
            max-width: 600px;
            margin: 4rem auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 5rem 3rem;
            text-align: center;
            border: 2px solid rgba(236, 72, 153, 0.1);
            box-shadow: 0 25px 60px rgba(236, 72, 153, 0.1);
            animation: slideUp 0.8s ease-out;
        }

        .wishlist-empty-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2.5rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .wishlist-empty-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.25rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .wishlist-empty-text {
            font-size: 1.125rem;
            color: var(--gray-600);
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        .wishlist-empty-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.125rem 2.5rem;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            font-weight: 700;
            font-size: 1.0625rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
        }

        .wishlist-empty-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(236, 72, 153, 0.4);
        }

        /* Pagination - Consistent with overall theme */
        .wishlist-pagination {
            margin-top: 5rem;
            display: flex;
            justify-content: center;
        }

        .wishlist-pagination nav svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .wishlist-hero-title {
                font-size: 2.5rem;
            }

            .wishlist-hero-subtitle {
                font-size: 1.125rem;
            }

            .wishlist-empty {
                padding: 3rem 2rem;
                margin: 2rem 1rem;
            }

            .wishlist-products-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>

    <div class="wishlist-page">
        <!-- Matching Home Background Circles -->
        <div class="bg-decoration-wishlist">
            <div class="decoration-circle decoration-circle-1"></div>
            <div class="decoration-circle decoration-circle-2"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="wishlist-hero">
                <div class="wishlist-hero-badge">
                    <i class="fas fa-heart"></i>
                    <span>Ruang Favorit Anda</span>
                </div>
                <h1 class="wishlist-hero-title">Wishlist Saya</h1>
                <p class="wishlist-hero-subtitle">
                    Simpan semua produk yang Anda sukai di satu tempat dan pesan kapan saja Anda inginkan.
                </p>

                @if($items->count() > 0)
                    <div class="wishlist-stats">
                        <div class="wishlist-stats-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="wishlist-stats-text">
                            {{ $items->total() }} Produk Tersimpan
                        </div>
                    </div>
                @endif
            </div>

            <!-- Status Alert -->
            @if(session('status'))
                <div class="wishlist-alert" id="statusAlert">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('statusAlert');
                        if (alert) {
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(10px)';
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 4000);
                </script>
            @endif

            <!-- Products Section -->
            @if($items->count())
                <div class="wishlist-products-section">
                    <div class="wishlist-products-grid">
                        @foreach($items as $product)
                            <div class="wishlist-product-wrapper">
                                <!-- Remove Button -->
                                <div class="wishlist-product-remove">
                                    <form action="{{ route('wishlist.remove', $product) }}" method="post">
                                        @csrf
                                        <button type="submit" class="wishlist-product-remove-btn" title="Hapus dari Wishlist"
                                            onclick="event.stopPropagation(); event.preventDefault(); if(confirm('Hapus produk ini dari wishlist?')) { this.closest('form').submit(); }">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Product Card Component -->
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($items->hasPages())
                        <div class="wishlist-pagination">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="wishlist-empty" data-aos="zoom-in">
                    <div class="wishlist-empty-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h2 class="wishlist-empty-title">Wishlist Anda Masih Kosong</h2>
                    <p class="wishlist-empty-text">
                        Sepertinya Anda belum menemukan menu favorit. Mari jelajahi koleksi masakan rumah terbaik kami sekarang!
                    </p>
                    <a href="{{ route('products.index') }}" class="wishlist-empty-btn">
                        <i class="fas fa-utensils"></i>
                        <span>Mulai Menjelajah</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection