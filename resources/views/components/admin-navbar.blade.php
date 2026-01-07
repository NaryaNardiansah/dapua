<!-- Admin Navbar -->
<nav class="admin-navbar">
    <div class="navbar-container">
        <!-- Logo Section -->
        <div class="navbar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-sakura.jpg') }}" alt="Logo Dapur Sakura" class="logo-image-navbar">
                    <div class="brand-text">
                        <h1 class="brand-title">Dapur Sakura</h1>
                        <span class="brand-subtitle">Admin Panel</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Main Navigation -->
        <div class="navbar-menu" id="navbarMenu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-shopping-cart nav-icon"></i>
                        <span class="nav-text">Pesanan</span>
                        @php
                            $pendingCount = \App\Models\Order::whereIn('status', ['pending', 'diproses'])->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="notification-badge">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                        @endif
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.orders.index') }}" class="dropdown-link">
                                <i class="fas fa-list"></i>Semua Pesanan
                            </a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="dropdown-link">
                                <i class="fas fa-clock"></i>Pending
                            </a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'diproses']) }}" class="dropdown-link">
                                <i class="fas fa-cog"></i>Diproses
                            </a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'dikirim']) }}" class="dropdown-link">
                                <i class="fas fa-shipping-fast"></i>Dikirim
                            </a></li>
                        <li><a href="{{ route('admin.orders.index', ['status' => 'selesai']) }}" class="dropdown-link">
                                <i class="fas fa-check-circle"></i>Selesai
                            </a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-box nav-icon"></i>
                        <span class="nav-text">Produk</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.products.index') }}" class="dropdown-link">
                                <i class="fas fa-list"></i>Semua Produk
                            </a></li>
                        <li><a href="{{ route('admin.products.create') }}" class="dropdown-link">
                                <i class="fas fa-plus"></i>Tambah Produk
                            </a></li>
                        <li><a href="{{ route('admin.products.export', ['format' => 'pdf']) }}" class="dropdown-link">
                                <i class="fas fa-file-pdf"></i>Export PDF
                            </a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-tags nav-icon"></i>
                        <span class="nav-text">Kategori</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.categories.index') }}" class="dropdown-link">
                                <i class="fas fa-list"></i>Semua Kategori
                            </a></li>
                        <li><a href="{{ route('admin.categories.create') }}" class="dropdown-link">
                                <i class="fas fa-plus"></i>Tambah Kategori
                            </a></li>
                        <li><a href="{{ route('admin.categories.export') }}" class="dropdown-link">
                                <i class="fas fa-download"></i>Export Data
                            </a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Pengguna</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.users.index') }}" class="dropdown-link">
                                <i class="fas fa-users"></i>Semua Pengguna
                            </a></li>
                        <li><a href="{{ route('admin.driver.index') }}" class="dropdown-link">
                                <i class="fas fa-id-card"></i>Driver
                            </a></li>
                        <li><a href="{{ route('admin.driver.create') }}" class="dropdown-link">
                                <i class="fas fa-user-plus"></i>Tambah Driver
                            </a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-truck nav-icon"></i>
                        <span class="nav-text">Pengiriman</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.delivery.index') }}" class="dropdown-link">
                                <i class="fas fa-map-marked-alt"></i>Manajemen Pengiriman
                            </a></li>
                        <li><a href="{{ route('admin.delivery.zones') }}" class="dropdown-link">
                                <i class="fas fa-shipping-fast"></i>Manajemen Ongkir
                            </a></li>
                        <li><a href="{{ route('admin.tracking.dashboard') }}" class="dropdown-link">
                                <i class="fas fa-search-location"></i>Tracking Dashboard
                            </a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle">
                        <i class="fas fa-cog nav-icon"></i>
                        <span class="nav-text">Pengaturan</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('admin.settings.index') }}" class="dropdown-link">
                                <i class="fas fa-sliders-h"></i>Pengaturan Sistem
                            </a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- User Section -->
        <div class="navbar-user">
            <div class="user-dropdown">
                <button class="user-button">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Administrator</span>
                    </div>
                    <i class="fas fa-chevron-down user-arrow"></i>
                </button>
                <ul class="user-menu">
                    <li><a href="{{ route('profile.edit') }}" class="user-menu-link">
                            <i class="fas fa-user"></i>Profil
                        </a></li>
                    <li><a href="{{ route('admin.settings.index') }}" class="user-menu-link">
                            <i class="fas fa-cog"></i>Pengaturan
                        </a></li>
                    <li>
                        <hr class="user-menu-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu-link logout-btn">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
</nav>