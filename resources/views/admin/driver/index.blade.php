@extends('layouts.admin')

@section('content')
    <div class="luxury-drivers-page">
        <!-- Hero Section -->
        <x-admin-hero icon="fas fa-truck" title="Manajemen Driver" subtitle="Kelola semua driver pengiriman"
            description="Pantau dan kelola driver dengan mudah dan efisien" :showCircle="true" />

        <!-- Status Alert -->
        @if(session('status'))
            <div class="status-alert fade-in-up delay-100" data-aos="fade-down">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span class="alert-text">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Quick Stats Bar -->
        <x-admin-responsive-grid class="quick-stats-bar auto-fit" :delay="300">
            <x-admin-stat-card icon="fas fa-users" :value="$totalDrivers ?? 0" label="Total Driver" change="Semua Driver"
                changeType="info" iconType="primary" :delay="400" />

            <x-admin-stat-card icon="fas fa-user-check" :value="$activeDrivers ?? 0" label="Driver Aktif" change="Active"
                changeType="positive" iconType="success" :delay="500" />

            <x-admin-stat-card icon="fas fa-user-times" :value="$blockedDrivers ?? 0" label="Driver Diblokir"
                change="Blocked" changeType="warning" iconType="warning" :delay="600" />
        </x-admin-responsive-grid>

        <!-- Simple Modern Filter & Search -->
        <div class="modern-filter-section" data-aos="fade-up" data-aos-delay="400">
            <form method="get" class="modern-filter-form" action="{{ route('admin.driver.index') }}" id="filterForm">
                <div class="filter-row">
                    <!-- Search Input -->
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" value="{{ $search ?? '' }}" class="modern-search-input" id="searchInput"
                            placeholder="Cari driver..." />
                        @if($search ?? '')
                            <button type="button" class="clear-search"
                                onclick="this.previousElementSibling.value=''; this.closest('form').submit();">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Quick Filters -->
                    <div class="quick-filters">
                        <select name="status" class="modern-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="blocked" {{ ($status ?? '') == 'blocked' ? 'selected' : '' }}>Diblokir</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="filter-actions">
                        <button type="submit" class="modern-btn primary">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.driver.index') }}" class="modern-btn secondary" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                        <a href="{{ route('admin.driver.create') }}" class="modern-btn success">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Driver</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Drivers Table Section -->
        <x-admin-content-card title="Daftar Driver" icon="fas fa-list-alt" :delay="500">
            <!-- Table with Horizontal Scroll -->
            <div class="table-scroll-container">
                <!-- Scroll Indicators -->
                <div class="scroll-indicator scroll-left" id="scrollLeft">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="scroll-indicator scroll-right" id="scrollRight">
                    <i class="fas fa-chevron-right"></i>
                </div>

                <!-- Table Wrapper -->
                <div class="table-wrapper" id="tableWrapper">
                    <table class="drivers-table" id="driversTable">
                        <thead>
                            <tr>
                                <th class="table-header-cell">Avatar</th>
                                <th class="table-header-cell">Nama</th>
                                <th class="table-header-cell">Email</th>
                                <th class="table-header-cell">Kendaraan</th>
                                <th class="table-header-cell text-center">Status</th>
                                <th class="table-header-cell text-center">Pesanan</th>
                                <th class="table-header-cell text-center">Bergabung</th>
                                <th class="table-header-cell text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($drivers as $driver)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="user-avatar">
                                            @if($driver->avatar)
                                                <img src="{{ $driver->avatar }}" alt="{{ $driver->name }}" class="avatar-img">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($driver->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="user-info">
                                            <span class="user-name">{{ $driver->name }}</span>
                                            <span class="user-id"><i class="fas fa-phone-alt"></i>
                                                {{ $driver->phone ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="user-email">
                                            <span class="email-text">{{ $driver->email }}</span>
                                            @if($driver->email_verified_at)
                                                <span class="verified-badge">
                                                    <i class="fas fa-check-circle"></i> Verified
                                                </span>
                                            @else
                                                <span class="unverified-badge">
                                                    <i class="fas fa-exclamation-circle"></i> Unverified
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="driver-vehicle">
                                            <span class="vehicle-type">{{ $driver->vehicle_type ?? '-' }}</span>
                                            <span class="vehicle-number">{{ $driver->vehicle_number ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="table-cell text-center">
                                        @if($driver->is_blocked)
                                            <span class="status-badge blocked">
                                                <i class="fas fa-circle"></i> Diblokir
                                            </span>
                                        @else
                                            <span class="status-badge active">
                                                <i class="fas fa-circle"></i> Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-cell text-center">
                                        <div class="orders-info">
                                            <span class="orders-count">{{ $driver->orders_count ?? 0 }}</span>
                                            <span class="orders-label">pesanan</span>
                                        </div>
                                    </td>
                                    <td class="table-cell text-center">
                                        <span class="joined-date">{{ $driver->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.driver.show', $driver) }}" class="action-btn view"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.driver.edit', $driver) }}" class="action-btn role"
                                                title="Edit Driver">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.driver.destroy', $driver) }}" method="post"
                                                onsubmit="return confirmDelete('{{ $driver->name }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete" title="Hapus Driver">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-truck empty-icon"></i>
                                            <h3 class="empty-title">Belum Ada Driver</h3>
                                            <p class="empty-description">Belum ada driver yang terdaftar di sistem</p>
                                            <a href="{{ route('admin.driver.create') }}" class="modern-btn success mt-4">
                                                <i class="fas fa-plus"></i>
                                                <span>Tambah Driver Pertama</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($drivers->hasPages())
                <div class="pagination-container mt-6">
                    {{ $drivers->links() }}
                </div>
            @endif
        </x-admin-content-card>

    </div>

    <style>
        /* Luxury Drivers Page Styles */
        .luxury-drivers-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
        }

        /* Modern Filter Section */
        .modern-filter-section {
            margin: 2rem 0;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.08);
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .modern-filter-form {
            margin: 0;
        }

        .filter-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Search Wrapper */
        .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 250px;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            color: var(--gray-400);
            font-size: 0.875rem;
            z-index: 2;
            pointer-events: none;
        }

        .modern-search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid rgba(236, 72, 153, 0.15);
            border-radius: 12px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            color: var(--gray-800);
        }

        .modern-search-input:focus {
            outline: none;
            border-color: var(--primary-pink);
            background: var(--pure-white);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        .modern-search-input::placeholder {
            color: var(--gray-400);
        }

        .clear-search {
            position: absolute;
            right: 0.75rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: rgba(236, 72, 153, 0.1);
            border-radius: 50%;
            color: var(--primary-pink);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.75rem;
            z-index: 2;
        }

        .clear-search:hover {
            background: rgba(236, 72, 153, 0.2);
            transform: scale(1.1);
        }

        /* Quick Filters */
        .quick-filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .modern-select {
            padding: 0.875rem 2.5rem 0.875rem 1rem;
            border: 2px solid rgba(236, 72, 153, 0.15);
            border-radius: 12px;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.8);
            color: var(--gray-800);
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ec4899' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 12px;
            min-width: 140px;
        }

        .modern-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            background-color: var(--pure-white);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        .modern-select:hover {
            border-color: rgba(236, 72, 153, 0.3);
        }

        /* Filter Actions */
        .filter-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .modern-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.25rem;
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .modern-btn i {
            font-size: 0.875rem;
        }

        .modern-btn.primary {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            color: var(--pure-white);
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .modern-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .modern-btn.secondary {
            background: rgba(236, 72, 153, 0.1);
            color: var(--primary-pink);
            padding: 0.875rem;
            min-width: 40px;
            justify-content: center;
        }

        .modern-btn.secondary:hover {
            background: rgba(236, 72, 153, 0.2);
            transform: translateY(-2px);
        }

        .modern-btn.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: var(--pure-white);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .modern-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Responsive Design for Filter */
        @media (max-width: 1024px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                min-width: 100%;
            }

            .quick-filters {
                width: 100%;
            }

            .modern-select {
                flex: 1;
                min-width: 0;
            }

            .filter-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (max-width: 768px) {
            .modern-filter-section {
                padding: 1rem;
            }

            .quick-filters {
                flex-direction: column;
            }

            .modern-select {
                width: 100%;
            }

            .filter-actions {
                flex-wrap: wrap;
            }

            .modern-btn span {
                display: none;
            }

            .modern-btn {
                padding: 0.875rem;
                min-width: 40px;
                justify-content: center;
            }
        }

        /* Table Scroll Container */
        .table-scroll-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: var(--pure-white);
            box-shadow: var(--shadow-lg);
        }

        /* Luxury Table Styles */
        .table-wrapper {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-pink) var(--light-pink);
        }

        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: var(--light-pink);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary-pink);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-pink);
        }

        .table {
            min-width: 1000px;
            width: 100%;
            border-collapse: collapse;
            background: var(--pure-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table-header-cell {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            color: white;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-row {
            border-bottom: 1px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background: rgba(236, 72, 153, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 2px 10px rgba(236, 72, 153, 0.1);
        }

        .table-cell {
            padding: 0.65rem 1rem;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            color: white;
            font-size: 1rem;
        }

        /* User Info */
        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .user-id {
            color: var(--gray-500);
            font-size: 0.7rem;
            font-family: monospace;
        }

        /* User Email */
        .user-email {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .email-text {
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 1px 0.35rem;
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 500;
        }

        .unverified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 1px 0.35rem;
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 500;
        }

        /* Vehicle Info */
        .driver-vehicle {
            display: flex;
            flex-direction: column;
        }

        .vehicle-type {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-800);
        }

        .vehicle-number {
            font-size: 0.75rem;
            color: var(--primary-pink);
            font-weight: 600;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.15rem 0.4rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
        }

        .status-badge.blocked {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Orders Info */
        .orders-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .orders-count {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.9rem;
        }

        .orders-label {
            color: var(--gray-600);
            font-size: 0.7rem;
        }

        .joined-date {
            color: var(--gray-600);
            font-size: 0.8rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.35rem;
            align-items: center;
            justify-content: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .action-btn.view:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.1);
        }

        .action-btn.role {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .action-btn.role:hover {
            background: rgba(245, 158, 11, 0.2);
            transform: scale(1.1);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.2);
            transform: scale(1.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-icon {
            font-size: 3rem;
            color: var(--gray-400);
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-600);
            margin: 0;
        }

        .empty-description {
            color: var(--gray-500);
            margin: 0;
        }

        /* Status Alert */
        .status-alert {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            margin-bottom: 2rem;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-icon {
            font-size: 1.2rem;
        }

        .alert-text {
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .table {
                min-width: 800px;
            }
        }

        @media (max-width: 768px) {
            .table {
                min-width: 700px;
            }

            .table-header-cell {
                min-width: 100px;
                padding: 0.75rem;
            }

            .table-cell {
                min-width: 80px;
                padding: 0.75rem;
            }

            .scroll-indicator {
                width: 35px;
                height: 35px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }
        }

        /* Scroll Indicators */
        .scroll-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            background: var(--primary-pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        }

        .scroll-indicator:hover {
            background: rgba(236, 72, 153, 1);
            transform: translateY(-50%) scale(1.1);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        .scroll-indicator.scroll-left {
            left: 10px;
        }

        .scroll-indicator.scroll-right {
            right: 10px;
        }

        .scroll-indicator.visible {
            opacity: 1;
            visibility: visible;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                mirror: false,
            });

            // Table scroll functionality
            const tableWrapper = document.getElementById('tableWrapper');
            const scrollLeft = document.getElementById('scrollLeft');
            const scrollRight = document.getElementById('scrollRight');

            function updateScrollIndicators() {
                if (!tableWrapper || !scrollLeft || !scrollRight) return;

                // Check if table is actually scrollable
                const isScrollable = tableWrapper.scrollWidth > tableWrapper.clientWidth;

                if (!isScrollable) {
                    scrollLeft.classList.remove('visible');
                    scrollRight.classList.remove('visible');
                    return;
                }

                if (tableWrapper.scrollLeft > 20) { // A small threshold to show indicator
                    scrollLeft.classList.add('visible');
                } else {
                    scrollLeft.classList.remove('visible');
                }

                // Check if there's still content to scroll to the right
                if (tableWrapper.scrollLeft < tableWrapper.scrollWidth - tableWrapper.clientWidth - 20) { // A small threshold
                    scrollRight.classList.add('visible');
                } else {
                    scrollRight.classList.remove('visible');
                }
            }

            if (tableWrapper) {
                updateScrollIndicators(); // Initial check
                tableWrapper.addEventListener('scroll', updateScrollIndicators);
                window.addEventListener('resize', updateScrollIndicators); // Update on window resize
            }

            if (scrollLeft) {
                scrollLeft.addEventListener('click', () => {
                    tableWrapper.scrollBy({ left: -200, behavior: 'smooth' });
                });
            }

            if (scrollRight) {
                scrollRight.addEventListener('click', () => {
                    tableWrapper.scrollBy({ left: 200, behavior: 'smooth' });
                });
            }

            // Modern Filter Auto-Submit
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const modernSelects = document.querySelectorAll('.modern-select');

            // Auto-submit on select change
            modernSelects.forEach(select => {
                select.addEventListener('change', function () {
                    if (filterForm) {
                        filterForm.submit();
                    }
                });
            });

            // Search with debounce
            let searchTimeout;
            if (searchInput && filterForm) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay
                });
            }

            // Confirm delete
            function confirmDelete(driverName) {
                return confirm(`Apakah Anda yakin ingin menghapus driver ${driverName}?`);
            }

            // Tooltip functionality
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(element => {
                element.addEventListener('mouseenter', function () {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    tooltip.style.cssText = `
                    position: absolute;
                    background: #1f2937;
                    color: white;
                    padding: 0.5rem 0.75rem;
                    border-radius: 6px;
                    font-size: 0.75rem;
                    z-index: 1000;
                    pointer-events: none;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                `;

                    document.body.appendChild(tooltip);

                    const rect = this.getBoundingClientRect();
                    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';

                    setTimeout(() => tooltip.style.opacity = '1', 10);

                    this.addEventListener('mouseleave', function () {
                        tooltip.style.opacity = '0';
                        setTimeout(() => document.body.removeChild(tooltip), 300);
                    });
                });
            });
        });
    </script>
@endsection