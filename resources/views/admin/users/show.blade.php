@extends('layouts.admin')

@section('content')
<div class="luxury-user-detail-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-user"
        title="Detail Pengguna"
        subtitle="{{ $user->name }}"
        description="Informasi lengkap dan statistik pengguna"
        :showCircle="true"
    />

    <!-- Status Alert -->
    @if(session('status'))
        <div class="status-alert fade-in-up delay-100" data-aos="fade-down">
            <div class="alert-content">
                <i class="fas fa-check-circle alert-icon"></i>
                <span class="alert-text">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="quick-actions-bar fade-in-up delay-200" data-aos="fade-up">
        <div class="actions-container">
            <a href="{{ route('admin.users.index') }}" class="action-btn secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pengguna
            </a>
            
            <div class="action-buttons">
                @if($user->is_blocked)
                    <form action="{{ route('admin.users.unblock', $user) }}" method="post" class="inline-form">
                        @csrf
                        <button class="action-btn success" onclick="return confirm('Aktifkan pengguna ini?')">
                            <i class="fas fa-unlock mr-2"></i>Aktifkan Pengguna
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.block', $user) }}" method="post" class="inline-form">
                        @csrf
                        <button class="action-btn warning" onclick="return confirm('Blokir pengguna ini?')">
                            <i class="fas fa-ban mr-2"></i>Blokir Pengguna
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('admin.users.destroy', $user) }}" method="post" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button class="action-btn danger" onclick="return confirm('Hapus pengguna ini?')">
                        <i class="fas fa-trash mr-2"></i>Hapus Pengguna
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid fade-in-up delay-300" data-aos="fade-up">
        <!-- User Profile Card -->
        <x-admin-content-card 
            title="Profil Pengguna" 
            icon="fas fa-user-circle" 
            :delay="400"
        >
            <div class="user-profile-section">
                <div class="profile-header">
                    <div class="user-avatar-large">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 alt="{{ $user->name }}" 
                                 class="avatar-img-large">
                        @else
                            <div class="avatar-placeholder-large">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <h3 class="user-name-large">{{ $user->name }}</h3>
                        <p class="user-email-large">{{ $user->email }}</p>
                        <div class="user-status-badges">
                            @if($user->email_verified_at)
                                <span class="status-badge verified">
                                    <i class="fas fa-check-circle"></i>Email Terverifikasi
                                </span>
                            @else
                                <span class="status-badge unverified">
                                    <i class="fas fa-exclamation-circle"></i>Email Belum Terverifikasi
                                </span>
                            @endif
                            
                            @if($user->is_blocked)
                                <span class="status-badge blocked">
                                    <i class="fas fa-ban"></i>Diblokir
                                </span>
                            @else
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>Aktif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label class="detail-label">ID Pengguna</label>
                            <span class="detail-value">{{ $user->id }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Role</label>
                            <div class="role-badges">
                                @foreach($user->roles as $role)
                                    <span class="role-badge {{ $role->slug }}">
                                        <i class="fas fa-{{ $role->slug === 'admin' ? 'crown' : ($role->slug === 'driver' ? 'truck' : 'user') }}"></i>
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Tanggal Registrasi</label>
                            <span class="detail-value">{{ $user->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <label class="detail-label">Login Terakhir</label>
                            <span class="detail-value">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin-content-card>

        <!-- Statistics Card -->
        <x-admin-content-card 
            title="Statistik Pengguna" 
            icon="fas fa-chart-bar" 
            :delay="500"
        >
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->orders->count() }}</div>
                        <div class="stat-label">Total Pesanan</div>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->wishlist->count() }}</div>
                        <div class="stat-label">Wishlist</div>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">Rp {{ number_format($user->orders->sum('grand_total'), 0, ',', '.') }}</div>
                        <div class="stat-label">Total Belanja</div>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $user->created_at->diffForHumans() }}</div>
                        <div class="stat-label">Bergabung</div>
                    </div>
                </div>
            </div>
        </x-admin-content-card>

        <!-- Recent Orders Card -->
        <x-admin-content-card 
            title="Pesanan Terbaru" 
            icon="fas fa-receipt" 
            :delay="600"
        >
            @if($user->orders->count() > 0)
                <div class="orders-list">
                    @foreach($user->orders->take(5) as $order)
                        <div class="order-item">
                            <div class="order-header">
                                <div class="order-info">
                                    <div class="order-code">#{{ $order->order_code ?? $order->id }}</div>
                                    <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <div class="order-amount">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="order-status">
                                <span class="status-badge {{ $order->status }}">
                                    @if($order->status === 'selesai')
                                        <i class="fas fa-check-circle"></i>Selesai
                                    @elseif($order->status === 'diproses')
                                        <i class="fas fa-clock"></i>Diproses
                                    @elseif($order->status === 'dikirim')
                                        <i class="fas fa-truck"></i>Dikirim
                                    @elseif($order->status === 'dibatalkan')
                                        <i class="fas fa-times-circle"></i>Dibatalkan
                                    @else
                                        <i class="fas fa-hourglass-half"></i>{{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($user->orders->count() > 5)
                    <div class="view-all-section">
                        <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" class="view-all-btn">
                            <i class="fas fa-eye mr-2"></i>Lihat Semua Pesanan
                        </a>
                    </div>
                @endif
            @else
                <div class="empty-orders">
                    <div class="empty-content">
                        <i class="fas fa-shopping-cart empty-icon"></i>
                        <h3 class="empty-title">Belum Ada Pesanan</h3>
                        <p class="empty-description">Pengguna ini belum melakukan pesanan apapun.</p>
                    </div>
                </div>
            @endif
        </x-admin-content-card>
    </div>
</div>

<style>
/* Luxury User Detail Page Styles */
.luxury-user-detail-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Quick Actions Bar */
.quick-actions-bar {
    margin: 2rem 0;
}

.actions-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    border-radius: 16px;
    padding: 1.5rem 2rem;
    box-shadow: 0 8px 32px rgba(236, 72, 153, 0.1);
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.action-btn.secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.action-btn.secondary:hover {
    background: rgba(107, 114, 128, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.2);
}

.action-btn.success {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.action-btn.success:hover {
    background: rgba(34, 197, 94, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.2);
}

.action-btn.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.action-btn.warning:hover {
    background: rgba(245, 158, 11, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
}

.action-btn.danger {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.action-btn.danger:hover {
    background: rgba(239, 68, 68, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.2);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.inline-form {
    display: inline-block;
    margin: 0;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.content-grid > *:last-child {
    grid-column: 1 / -1;
}

/* User Profile Section */
.user-profile-section {
    padding: 0;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--gray-200);
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.2);
}

.avatar-img-large {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-large {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    font-size: 2rem;
}

.profile-info {
    flex: 1;
}

.user-name-large {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 0.5rem 0;
}

.user-email-large {
    font-size: 1rem;
    color: var(--gray-600);
    margin: 0 0 1rem 0;
}

.user-status-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.verified {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.status-badge.unverified {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.active {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.status-badge.blocked {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Profile Details */
.profile-details {
    margin-top: 1rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 1rem;
    color: var(--gray-800);
    font-weight: 500;
}

.role-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
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

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(236, 72, 153, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(236, 72, 153, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-600);
    font-weight: 500;
}

/* Orders List */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    padding: 1.5rem;
    background: rgba(236, 72, 153, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.order-item:hover {
    background: rgba(236, 72, 153, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-info {
    flex: 1;
}

.order-code {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.order-date {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.order-amount {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary-pink);
}

.order-status {
    display: flex;
    justify-content: flex-end;
}

.status-badge.selesai {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.status-badge.diproses {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.status-badge.dikirim {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.dibatalkan {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-badge.pending {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

/* View All Section */
.view-all-section {
    margin-top: 1.5rem;
    text-align: center;
}

.view-all-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: rgba(236, 72, 153, 0.1);
    color: var(--primary-pink);
    border: 1px solid rgba(236, 72, 153, 0.2);
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.view-all-btn:hover {
    background: rgba(236, 72, 153, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.2);
}

/* Empty Orders */
.empty-orders {
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
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-container {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .action-buttons {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-status {
        justify-content: flex-start;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    .actions-container {
        padding: 1rem;
    }
    
    .action-btn {
        padding: 0.625rem 1.25rem;
        font-size: 0.8rem;
    }
    
    .user-name-large {
        font-size: 1.25rem;
    }
    
    .user-email-large {
        font-size: 0.875rem;
    }
    
    .stat-item {
        padding: 1rem;
    }
    
    .order-item {
        padding: 1rem;
    }
}
</style>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
});
</script>
@endsection







