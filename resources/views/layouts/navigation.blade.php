<style>
    .global-nav {
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
        text-decoration: none;
    }

    .logo-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #ec4899, #f472b6);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
    }

    .logo-icon i {
        font-size: 1.25rem;
        color: white;
    }

    .logo-text {
        font-size: 1.25rem;
        font-weight: 900;
        background: linear-gradient(135deg, #ec4899, #db2777);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .nav-links {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .nav-link {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        color: #4b5563;
    }

    .nav-link:hover {
        background: #fce7f3;
        color: #ec4899;
    }

    .nav-link.primary {
        background: linear-gradient(135deg, #ec4899, #f472b6);
        color: white;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
    }

    .cart-link {
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 0.65rem;
        font-weight: 900;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

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
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-width: 180px;
        display: none;
        z-index: 1000;
    }

    .profile-dropdown.active .profile-dropdown-menu {
        display: block;
    }

    .profile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.25rem;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 600;
        width: 100%;
        border: none;
        background: none;
        text-align: left;
    }

    .profile-dropdown-item:hover {
        background: #f3f4f6;
        color: #ec4899;
    }
</style>

<nav class="global-nav">
    <div class="nav-container">
        <a href="{{ route('home') }}" class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="logo-text">{{ config('app.name', 'Dapur Sakura') }}</div>
        </a>
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
                        <div class="nav-link profile-dropdown-toggle" onclick="toggleGlobalProfileDropdown()">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                        </div>
                        <div class="profile-dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">
                                <i class="fas fa-user-edit"></i> Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" class="profile-dropdown-item">
                                <i class="fas fa-shopping-bag"></i> Pesanan Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="profile-dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
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

<script>
    function toggleGlobalProfileDropdown() {
        document.querySelector('.profile-dropdown').classList.toggle('active');
    }
    document.addEventListener('click', function (e) {
        const dropdown = document.querySelector('.profile-dropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });
</script>