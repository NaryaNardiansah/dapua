@extends('layouts.admin')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="driver-detail-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-truck"
        :title="$driver->name"
        subtitle="Detail Driver"
        description="Informasi lengkap dan statistik performa driver"
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

    <!-- Action Buttons -->
    <div class="action-bar" data-aos="fade-up" data-aos-delay="200">
        <a href="{{ route('admin.driver.index') }}" class="action-btn back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <div class="action-group">
            <a href="{{ route('admin.driver.edit', $driver) }}" class="action-btn edit">
                <i class="fas fa-edit"></i>
                <span>Edit Driver</span>
            </a>
            <form action="{{ route('admin.driver.destroy', $driver) }}" method="post" class="inline-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn delete" onclick="return confirm('Hapus driver ini?')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Left Column: Driver Info -->
        <div class="main-content-left">
            <!-- Driver Basic Info -->
            <x-admin-content-card 
                title="Informasi Driver" 
                icon="fas fa-user" 
                :delay="300"
            >
                <div class="driver-detail-grid">
                    <div class="driver-photo-section">
                        @if($driver->photo)
                            <div class="driver-photo-wrapper">
                                <img src="{{ Storage::url($driver->photo) }}" 
                                     alt="{{ $driver->name }}" 
                                     class="driver-photo">
                            </div>
                        @else
                            <div class="driver-photo-placeholder">
                                <i class="fas fa-user"></i>
                                <span>No Photo</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="driver-basic-info">
                        <div class="info-row">
                            <span class="info-label">Nama Driver</span>
                            <span class="info-value">{{ $driver->name }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $driver->email }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Telepon</span>
                            <span class="info-value">{{ $driver->phone ?? '-' }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <div class="status-badges">
                                @if($driver->is_blocked)
                                    <span class="status-badge blocked">
                                        <i class="fas fa-ban"></i>Diblokir
                                    </span>
                                @else
                                    <span class="status-badge active">
                                        <i class="fas fa-check-circle"></i>Aktif
                                    </span>
                                @endif
                                
                                @if($driver->is_available)
                                    <span class="status-badge available">
                                        <i class="fas fa-circle"></i>Tersedia
                                    </span>
                                @else
                                    <span class="status-badge unavailable">
                                        <i class="fas fa-circle"></i>Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($driver->vehicle_type)
                        <div class="info-row">
                            <span class="info-label">Jenis Kendaraan</span>
                            <span class="info-value">{{ $driver->vehicle_type }}</span>
                        </div>
                        @endif
                        
                        @if($driver->vehicle_number)
                        <div class="info-row">
                            <span class="info-label">Nomor Kendaraan</span>
                            <span class="info-value vehicle-number">{{ $driver->vehicle_number }}</span>
                        </div>
                        @endif
                        
                        @if($driver->driver_license || $driver->license_number)
                        <div class="info-row">
                            <span class="info-label">Nomor SIM</span>
                            <span class="info-value">{{ $driver->license_number ?? $driver->driver_license ?? '-' }}</span>
                        </div>
                        @endif
                        
                        @if($driver->current_latitude && $driver->current_longitude)
                        <div class="info-row">
                            <span class="info-label">Lokasi Terakhir</span>
                            <div class="location-info">
                                <span class="info-value">{{ $driver->current_latitude }}, {{ $driver->current_longitude }}</span>
                                @if($driver->last_location_update)
                                    <span class="location-time">{{ $driver->last_location_update->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-row">
                            <span class="info-label">Terdaftar</span>
                            <span class="info-value">{{ $driver->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Terakhir Diperbarui</span>
                            <span class="info-value">{{ $driver->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </x-admin-content-card>
        </div>

        <!-- Right Column: Stats & Orders -->
        <div class="main-content-right">
            <!-- Statistics -->
            <x-admin-content-card 
                title="Statistik Performa" 
                icon="fas fa-chart-line" 
                :delay="300"
            >
                <div class="stats-grid">
                    <x-admin-stat-card 
                        icon="fas fa-shopping-bag"
                        :value="number_format($stats['total_orders'] ?? 0)"
                        label="Total Pesanan"
                        change="Orders"
                        changeType="info"
                        iconType="primary"
                        :delay="0"
                    />
                    
                    <x-admin-stat-card 
                        icon="fas fa-check-circle"
                        :value="number_format($stats['completed_orders'] ?? 0)"
                        label="Pesanan Selesai"
                        change="Completed"
                        changeType="positive"
                        iconType="success"
                        :delay="0"
                    />
                    
                    <x-admin-stat-card 
                        icon="fas fa-clock"
                        :value="number_format($stats['active_orders'] ?? 0)"
                        label="Pesanan Aktif"
                        change="Active"
                        changeType="warning"
                        iconType="warning"
                        :delay="0"
                    />
                    
                    <x-admin-stat-card 
                        icon="fas fa-dollar-sign"
                        :value="'Rp ' . number_format($stats['total_revenue'] ?? 0, 0, ',', '.')"
                        label="Total Revenue"
                        change="Revenue"
                        changeType="positive"
                        iconType="info"
                        :delay="0"
                    />
                    
                    @if($stats['avg_rating'] > 0)
                    <x-admin-stat-card 
                        icon="fas fa-star"
                        :value="number_format($stats['avg_rating'], 1)"
                        label="Rating Rata-rata"
                        change="Stars"
                        changeType="positive"
                        iconType="warning"
                        :delay="0"
                    />
                    @endif
                </div>
            </x-admin-content-card>

            <!-- Recent Orders -->
            @if($recentOrders && $recentOrders->count() > 0)
            <x-admin-content-card 
                title="Pesanan Terkini" 
                icon="fas fa-list" 
                :delay="400"
            >
                <div class="orders-list">
                    @foreach($recentOrders as $order)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-header">
                                    <span class="order-id">#{{ $order->id }}</span>
                                    <span class="order-code">{{ $order->order_code }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="order-details">
                                    <span class="order-customer">{{ $order->user->name ?? $order->recipient_name ?? 'Guest' }}</span>
                                    <span class="order-total">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="order-status">
                                <span class="status-badge order-status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin-content-card>
            @endif
        </div>
    </div>
</div>

<style>
/* Driver Detail Page */
.driver-detail-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
    padding-bottom: 2rem;
}

/* Action Bar */
.action-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2rem 0;
    padding: 1rem 0;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.action-btn.back {
    background: rgba(107, 114, 128, 0.1);
    color: #4b5563;
}

.action-btn.back:hover {
    background: rgba(107, 114, 128, 0.2);
    transform: translateY(-2px);
}

.action-group {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.action-btn.edit {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.action-btn.edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.action-btn.delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.action-btn.delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.inline-form {
    display: inline;
    margin: 0;
}

/* Main Content Grid */
.main-content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.main-content-left,
.main-content-right {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Driver Detail Grid */
.driver-detail-grid {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 2rem;
}

.driver-photo-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.driver-photo-wrapper {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 16px;
    overflow: hidden;
    background: var(--gray-100);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.driver-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.driver-photo-placeholder {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 16px;
    background: var(--gray-100);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--gray-400);
    font-size: 3rem;
}

.driver-photo-placeholder span {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.driver-basic-info {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-row {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
}

.info-value.vehicle-number {
    font-family: monospace;
    font-size: 0.875rem;
    color: var(--gray-600);
    background: rgba(236, 72, 153, 0.05);
    padding: 0.5rem;
    border-radius: 6px;
}

.location-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.location-time {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-style: italic;
}

/* Status Badges */
.status-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.status-badge.blocked {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-badge.available {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.status-badge.unavailable {
    background: rgba(107, 114, 128, 0.1);
    color: #4b5563;
}

/* Stats Grid */
.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Orders List */
.orders-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(236, 72, 153, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.order-item:hover {
    background: rgba(236, 72, 153, 0.1);
    transform: translateX(4px);
}

.order-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.order-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.order-id {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 0.875rem;
}

.order-code {
    font-family: monospace;
    font-size: 0.75rem;
    color: var(--gray-600);
    background: rgba(236, 72, 153, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.order-date {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.order-details {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
}

.order-customer {
    color: var(--gray-700);
    font-weight: 500;
}

.order-total {
    font-weight: 700;
    color: var(--primary-pink);
}

.order-status {
    display: flex;
    align-items: center;
}

.status-badge.order-status-selesai {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.status-badge.order-status-dikirim {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.status-badge.order-status-diproses {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.order-status-menunggu,
.status-badge.order-status-pending {
    background: rgba(107, 114, 128, 0.1);
    color: #4b5563;
}

.status-badge.order-status-dibatalkan {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 1fr;
    }
    
    .driver-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .driver-photo-wrapper {
        max-width: 300px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .action-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-group {
        flex-direction: column;
        width: 100%;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-status {
        width: 100%;
    }
}
</style>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });
});
</script>
@endsection

