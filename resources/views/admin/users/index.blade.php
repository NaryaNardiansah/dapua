@extends('layouts.admin')

@section('content')
    <div class="luxury-user-management-page">
        <!-- Hero Section -->
        <x-admin-hero icon="fas fa-users" title="Manajemen Pengguna" subtitle="Kelola pengguna Dapur Sakura"
            description="Pantau dan kelola semua pengguna dengan mudah dan efisien" :showCircle="true" />

        <!-- Status Alert -->
        @if(session('status'))
            <div class="status-alert fade-in-up delay-100" data-aos="fade-down">
                <div class="alert-content">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <span class="alert-text">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <x-admin-responsive-grid class="stats-section auto-fit" :delay="200">
            <x-admin-stat-card icon="fas fa-users" :value="$totalUsers" label="Total Pengguna"
                change="+{{ $newUsersToday }} hari ini" changeType="positive" iconType="primary" :delay="300" />

            <x-admin-stat-card icon="fas fa-user-check" :value="$activeUsers" label="Pengguna Aktif" change="Aktif"
                changeType="positive" iconType="success" :delay="400" />

            <x-admin-stat-card icon="fas fa-user-times" :value="$blockedUsers" label="Pengguna Diblokir" change="Perhatian"
                changeType="warning" iconType="warning" :delay="500" />

            <x-admin-stat-card icon="fas fa-user-shield" :value="$adminUsers" label="Admin" change="Administrator"
                changeType="info" iconType="info" :delay="600" />

            <x-admin-stat-card icon="fas fa-user-plus" :value="$newUsersToday" label="Pengguna Baru" change="Hari ini"
                changeType="positive" iconType="success" :delay="700" />
        </x-admin-responsive-grid>

        <!-- Simple Modern Filter & Search -->
        <div class="modern-filter-section" data-aos="fade-up" data-aos-delay="400">
            <form method="get" class="modern-filter-form">
                <div class="filter-row">
                    <!-- Search Input -->
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" value="{{ $search }}" class="modern-search-input"
                            placeholder="Cari pengguna..." />
                        @if($search)
                            <button type="button" class="clear-search"
                                onclick="this.previousElementSibling.value=''; this.closest('form').submit();">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Quick Filters -->
                    <div class="quick-filters">
                        <select name="status" class="modern-select">
                            <option value="">Semua Status</option>
                            <option value="active" @selected($status === 'active')>Aktif</option>
                            <option value="blocked" @selected($status === 'blocked')>Diblokir</option>
                        </select>

                        <select name="role" class="modern-select">
                            <option value="all">Semua Role</option>
                            @foreach($roles as $roleItem)
                                <option value="{{ $roleItem->slug }}" @selected($role === $roleItem->slug)>
                                    {{ ucfirst($roleItem->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="filter-actions">
                        <button type="submit" class="modern-btn primary">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="modern-btn secondary" title="Reset">
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

        <!-- Users Table -->
        <x-admin-content-card title="Daftar Pengguna" icon="fas fa-table" :delay="900">
            <div class="table-scroll-container">
                <div class="scroll-indicator scroll-left" id="scrollLeft">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="scroll-indicator scroll-right" id="scrollRight">
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="table-wrapper" id="tableWrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th class="table-header-cell">Avatar</th>
                                <th class="table-header-cell">Nama</th>
                                <th class="table-header-cell">Email</th>
                                <th class="table-header-cell">Role</th>
                                <th class="table-header-cell">Status</th>
                                <th class="table-header-cell">Pesanan</th>
                                <th class="table-header-cell">Bergabung</th>
                                <th class="table-header-cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="user-avatar">
                                            @if($user->avatar)
                                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                                    class="avatar-img">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="user-info">
                                            <span class="user-name">{{ $user->name }}</span>
                                            <span class="user-id"><i class="fas fa-phone-alt"></i>
                                                {{ $user->phone ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="user-email">
                                            <span class="email-text">{{ $user->email }}</span>
                                            @if($user->email_verified_at)
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
                                        <div class="user-roles">
                                            @forelse($user->roles as $role)
                                                <span class="role-badge {{ $role->slug }}">
                                                    <i
                                                        class="fas fa-{{ $role->slug === 'admin' ? 'crown' : ($role->slug === 'driver' ? 'truck' : 'user') }}"></i>
                                                    {{ ucfirst($role->name) }}
                                                </span>
                                            @empty
                                                <span class="role-badge customer">
                                                    <i class="fas fa-user"></i> Customer
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        @if($user->is_blocked)
                                            <span class="status-badge blocked">
                                                <i class="fas fa-circle"></i> Diblokir
                                            </span>
                                        @else
                                            <span class="status-badge active">
                                                <i class="fas fa-circle"></i> Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        <div class="orders-info">
                                            <span class="orders-count">{{ $user->orders_count ?? 0 }}</span>
                                            <span class="orders-label">pesanan</span>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="joined-date">{{ $user->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="action-buttons">
                                            <button class="action-btn role role-trigger" title="Ganti Role"
                                                data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                data-current-role="{{ $user->roles->first()?->slug }}">
                                                <i class="fas fa-user-cog"></i>
                                            </button>

                                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn view"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($user->is_blocked)
                                                <form action="{{ route('admin.users.unblock', $user) }}" method="post"
                                                    class="inline-form">
                                                    @csrf
                                                    <button type="submit" class="action-btn unblock" title="Buka Blokir"
                                                        onclick="return confirm('Buka blokir pengguna ini?')">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.block', $user) }}" method="post"
                                                    class="inline-form">
                                                    @csrf
                                                    <button type="submit" class="action-btn block" title="Blokir"
                                                        onclick="return confirm('Blokir pengguna ini?')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="post"
                                                class="inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete" title="Hapus"
                                                    onclick="return confirm('Hapus pengguna ini?')">
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
                                            <i class="fas fa-users empty-icon"></i>
                                            <h3 class="empty-title">Belum Ada Pengguna</h3>
                                            <p class="empty-description">Belum ada pengguna yang terdaftar dalam sistem.</p>
                                            <a href="{{ route('register') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus"></i>Daftar Pengguna Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
                <div class="pagination-container mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        </x-admin-content-card>

        <!-- Centered Role Modal (Restored) -->
        <div id="roleChangeModal" class="luxury-role-modal">
            <div class="modal-backdrop"></div>
            <div class="modal-box" data-aos="zoom-in">
                <div class="modal-header">
                    <div class="modal-title-area">
                        <i class="fas fa-user-shield modal-icon"></i>
                        <div class="modal-titles">
                            <h3>Ganti Role Pengguna</h3>
                            <p id="targetUserName">Nama Pengguna</p>
                        </div>
                    </div>
                    <button class="close-modal-btn">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="role-selection-grid">
                        @foreach($roles as $roleItem)
                            <form id="roleForm_{{ $roleItem->slug }}" action="" method="post">
                                @csrf
                                <input type="hidden" name="role" value="{{ $roleItem->slug }}">
                                <button type="submit" class="role-selection-btn {{ $roleItem->slug }}"
                                    data-role="{{ $roleItem->slug }}">
                                    <div class="role-icon-circle">
                                        <i
                                            class="fas fa-{{ $roleItem->slug === 'admin' ? 'crown' : ($roleItem->slug === 'driver' ? 'truck' : 'user') }}"></i>
                                    </div>
                                    <div class="role-info">
                                        <span class="role-name">{{ ucfirst($roleItem->name) }}</span>
                                        <span class="role-desc">Pilih untuk menjadikan {{ $roleItem->name }}</span>
                                    </div>
                                    <i class="fas fa-check-circle active-indicator"></i>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Luxury User Management Page Styles */
        .luxury-user-management-page {
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
        }

        .scroll-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(236, 72, 153, 0.9);
            backdrop-filter: blur(10px);
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

        /* Table Styles */
        .users-table {
            min-width: 1000px;
            width: 100%;
            border-collapse: collapse;
            background: var(--pure-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .users-table th.table-header-cell {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%) !important;
            color: white !important;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none !important;
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
            color: var(--primary-pink);
            font-size: 0.75rem;
            font-weight: 600;
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

        /* User Roles */
        .user-roles {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.15rem 0.4rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            white-space: nowrap;
            width: fit-content;
        }

        .role-badge.admin {
            background: rgba(147, 51, 234, 0.1);
            color: #7c3aed;
        }

        .role-badge.driver {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .role-badge.customer {
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
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

        /* Joined Date */
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

        .action-btn.unblock {
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
        }

        .action-btn.unblock:hover {
            background: rgba(34, 197, 94, 0.2);
            transform: scale(1.1);
        }

        .action-btn.block {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .action-btn.block:hover {
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

        /* Luxury Role Modal Styles - Above the content Area */
        .luxury-role-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .luxury-role-modal.active {
            display: flex;
        }

        .modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(17, 24, 39, 0.4);
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s ease;
        }

        .modal-box {
            position: relative;
            background: #ffffff !important;
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            z-index: 2;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--pure-white) 0%, var(--gray-50) 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--gray-100);
        }

        .modal-title-area {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modal-icon {
            font-size: 1.5rem;
            color: var(--primary-pink);
            background: var(--light-pink);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .modal-titles h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .modal-titles p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--primary-pink);
            font-weight: 600;
        }

        .close-modal-btn {
            background: var(--gray-100);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--gray-500);
            cursor: pointer;
            transition: all 0.2s;
        }

        .close-modal-btn:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .role-selection-grid {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .role-selection-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem;
            background: #ffffff;
            border: 2px solid var(--gray-100);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            text-align: left;
        }

        .role-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var(--gray-50);
            color: var(--gray-400);
            transition: all 0.3s;
        }

        .role-info {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .role-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .role-desc {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .active-indicator {
            margin-left: auto;
            font-size: 1.25rem;
            color: #10b981;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.3s;
        }

        /* Hover & Active States */
        .role-selection-btn:hover {
            border-color: var(--primary-pink);
            background: var(--light-pink);
            transform: translateX(8px);
        }

        .role-selection-btn.active {
            border-color: var(--primary-pink);
            background: #fff1f2;
        }

        .role-selection-btn.active .role-icon-circle {
            background: var(--primary-pink);
            color: #ffffff;
        }

        .role-selection-btn.active .active-indicator {
            opacity: 1;
            transform: scale(1);
        }

        .role-selection-btn.admin:hover .role-icon-circle {
            color: #7c3aed;
        }

        .role-selection-btn.driver:hover .role-icon-circle {
            color: #2563eb;
        }

        .role-selection-btn.customer:hover .role-icon-circle {
            color: #059669;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .users-table {
                min-width: 800px;
            }

            .scroll-indicator {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 768px) {
            .users-table {
                min-width: 700px;
            }

            .table-cell {
                padding: 0.75rem 0.5rem;
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

            .scroll-indicator {
                width: 30px;
                height: 30px;
            }

            .dropdown-menu {
                right: -50px;
                min-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .users-table {
                min-width: 600px;
            }

            .table-cell {
                padding: 0.5rem 0.25rem;
            }

            .user-name {
                font-size: 0.8rem;
            }

            .user-id {
                font-size: 0.7rem;
            }

            .role-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .status-badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }

            .scroll-indicator {
                width: 25px;
                height: 25px;
            }
        }
    </style>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                mirror: false
            });

            // Modern Filter Auto-Submit
            const filterForm = document.querySelector('.modern-filter-form');
            const searchInput = document.querySelector('.modern-search-input');
            const modernSelects = document.querySelectorAll('.modern-select');

            // Auto-submit on select change
            modernSelects.forEach(select => {
                select.addEventListener('change', function () {
                    filterForm.submit();
                });
            });

            // Search with debounce
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay
                });
            }

            // Table scroll functionality
            const tableWrapper = document.getElementById('tableWrapper');
            const scrollLeft = document.getElementById('scrollLeft');
            const scrollRight = document.getElementById('scrollRight');

            function updateScrollIndicators() {
                if (tableWrapper.scrollLeft > 0) {
                    scrollLeft.classList.add('visible');
                } else {
                    scrollLeft.classList.remove('visible');
                }

                if (tableWrapper.scrollLeft < tableWrapper.scrollWidth - tableWrapper.clientWidth) {
                    scrollRight.classList.add('visible');
                } else {
                    scrollRight.classList.remove('visible');
                }
            }

            // Initial check
            updateScrollIndicators();

            // Scroll event listener
            tableWrapper.addEventListener('scroll', updateScrollIndicators);

            // Scroll button functionality
            scrollLeft.addEventListener('click', function () {
                tableWrapper.scrollBy({ left: -200, behavior: 'smooth' });
            });

            scrollRight.addEventListener('click', function () {
                tableWrapper.scrollBy({ left: 200, behavior: 'smooth' });
            });

            // Touch/swipe support for mobile
            let startX = 0;
            let scrollLeftStart = 0;

            tableWrapper.addEventListener('touchstart', function (e) {
                startX = e.touches[0].clientX;
                scrollLeftStart = tableWrapper.scrollLeft;
            });

            tableWrapper.addEventListener('touchmove', function (e) {
                e.preventDefault();
                const currentX = e.touches[0].clientX;
                const diff = startX - currentX;
                tableWrapper.scrollLeft = scrollLeftStart + diff;
            });

            // Keyboard navigation
            tableWrapper.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    tableWrapper.scrollBy({ left: -100, behavior: 'smooth' });
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    tableWrapper.scrollBy({ left: 100, behavior: 'smooth' });
                }
            });

            // Make table focusable for keyboard navigation
            tableWrapper.setAttribute('tabindex', '0');

            // Auto-hide scroll indicators after inactivity
            let scrollTimeout;
            tableWrapper.addEventListener('scroll', function () {
                clearTimeout(scrollTimeout);
                scrollLeft.style.opacity = '1';
                scrollRight.style.opacity = '1';

                scrollTimeout = setTimeout(function () {
                    if (tableWrapper.scrollLeft === 0) {
                        scrollLeft.style.opacity = '0';
                    }
                    if (tableWrapper.scrollLeft >= tableWrapper.scrollWidth - tableWrapper.clientWidth) {
                        scrollRight.style.opacity = '0';
                    }
                }, 2000);
            });

            // Role Modal functionality
            const roleModal = document.getElementById('roleChangeModal');
            const roleTriggers = document.querySelectorAll('.role-trigger');
            const closeRoleModal = document.querySelector('.close-modal-btn');
            const modalBackdrop = roleModal.querySelector('.modal-backdrop');
            const modalUserName = document.getElementById('targetUserName');
            const roleSelectionBtns = document.querySelectorAll('.role-selection-btn');

            roleTriggers.forEach(btn => {
                btn.addEventListener('click', function () {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    const currentRole = this.getAttribute('data-current-role');

                    // Set user name
                    modalUserName.textContent = userName;

                    // Update forms actions and active states
                    roleSelectionBtns.forEach(roleBtn => {
                        const roleSlug = roleBtn.getAttribute('data-role');
                        const form = document.getElementById('roleForm_' + roleSlug);
                        form.action = `/admin/users/${userId}/assign-role`;

                        if (roleSlug === currentRole) {
                            roleBtn.classList.add('active');
                        } else {
                            roleBtn.classList.remove('active');
                        }
                    });

                    // Open modal
                    roleModal.classList.add('active');
                    document.body.style.overflow = 'hidden';

                    // Re-trigger AOS for the box
                    const modalBox = roleModal.querySelector('.modal-box');
                    modalBox.style.animation = 'none';
                    modalBox.offsetHeight; // force reflow
                    modalBox.style.animation = null;
                });
            });

            const closeModal = () => {
                roleModal.classList.remove('active');
                document.body.style.overflow = '';
            };

            if (closeRoleModal) closeRoleModal.addEventListener('click', closeModal);
            if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

            // Prevent modal close when clicking inside
            const modalBox = roleModal ? roleModal.querySelector('.modal-box') : null;
            if (modalBox) {
                modalBox.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            }
        });
    </script>
@endsection