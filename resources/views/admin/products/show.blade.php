@extends('layouts.admin')

@section('content')
<div class="product-detail-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-box"
        :title="$product->name"
        subtitle="Detail Produk"
        description="Informasi lengkap dan statistik penjualan produk"
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
        <a href="{{ route('admin.products.index') }}" class="action-btn back">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
        <div class="action-group">
            <a href="{{ route('admin.products.edit', $product) }}" class="action-btn edit">
                <i class="fas fa-edit"></i>
                <span>Edit Produk</span>
            </a>
            @if($product->trashed())
                <form action="{{ route('admin.products.restore', $product) }}" method="post" class="inline-form">
                    @csrf
                    <button type="submit" class="action-btn restore" onclick="return confirm('Restore produk ini?')">
                        <i class="fas fa-undo"></i>
                        <span>Restore</span>
                    </button>
                </form>
            @else
                <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete" onclick="return confirm('Hapus produk ini?')">
                        <i class="fas fa-trash"></i>
                        <span>Hapus</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Left Column: Product Info -->
        <div class="main-content-left">
            <!-- Product Image & Basic Info -->
            <x-admin-content-card 
                title="Informasi Produk" 
                icon="fas fa-info-circle" 
                :delay="300"
            >
                <div class="product-detail-grid">
                    <div class="product-image-section">
                        @if($product->image_path)
                            <div class="product-image-wrapper">
                                <img src="{{ Storage::url($product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-main-image">
                            </div>
                        @else
                            <div class="product-image-placeholder">
                                <i class="fas fa-image"></i>
                                <span>No Image</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="product-basic-info">
                        <div class="info-row">
                            <span class="info-label">Nama Produk</span>
                            <span class="info-value">{{ $product->name }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Slug</span>
                            <span class="info-value slug">{{ $product->slug }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Kategori</span>
                            <span class="info-value">
                                <span class="category-badge">
                                    {{ $product->category->name ?? 'No Category' }}
                                </span>
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Harga</span>
                            <div class="price-section">
                                <span class="price-main {{ $product->has_active_discount ? 'text-gray-400 line-through' : '' }}">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                @if($product->has_active_discount)
                                    <span class="price-sale" style="color: #ef4444; font-weight: 700;">
                                        Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                    </span>
                                    <span class="discount-badge">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @elseif($product->sale_price && $product->sale_price < $product->price)
                                    <div class="sale-inactive-warning" style="font-size: 0.75rem; color: #f59e0b; margin-top: 0.25rem;">
                                        <i class="fas fa-exclamation-triangle"></i> Harga diskon Rp {{ number_format($product->sale_price, 0, ',', '.') }} sudah diatur, tapi <strong>Status Diskon</strong> belum aktif.
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <div class="status-badges">
                                @if($product->trashed())
                                    <span class="status-badge trashed">
                                        <i class="fas fa-trash"></i>Trashed
                                    </span>
                                @elseif($product->is_active)
                                    <span class="status-badge active">
                                        <i class="fas fa-check-circle"></i>Aktif
                                    </span>
                                @else
                                    <span class="status-badge draft">
                                        <i class="fas fa-edit"></i>Draft
                                    </span>
                                @endif
                                
                                @if($product->is_best_seller)
                                    <span class="status-badge best-seller">
                                        <i class="fas fa-star"></i>Best Seller
                                    </span>
                                @endif
                                
                                @if($product->is_featured)
                                    <span class="status-badge featured">
                                        <i class="fas fa-gem"></i>Featured
                                    </span>
                                @endif
                                
                                @if($product->is_on_sale)
                                    <span class="status-badge on-sale">
                                        <i class="fas fa-tag"></i>On Sale
                                    </span>
                                @endif
                                
                                @if($product->is_new)
                                    <span class="status-badge new">
                                        <i class="fas fa-sparkles"></i>New
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($product->stock !== null)
                        <div class="info-row">
                            <span class="info-label">Stok</span>
                            <span class="info-value">
                                <span class="stock-badge {{ $product->stock <= 0 ? 'out' : ($product->stock <= ($product->min_stock ?? 10) ? 'low' : 'in') }}">
                                    {{ number_format($product->stock) }} unit
                                </span>
                            </span>
                        </div>
                        @endif
                        
                        @if($product->sku)
                        <div class="info-row">
                            <span class="info-label">SKU</span>
                            <span class="info-value">{{ $product->sku }}</span>
                        </div>
                        @endif
                        
                        <div class="info-row">
                            <span class="info-label">Dibuat</span>
                            <span class="info-value">{{ $product->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Diperbarui</span>
                            <span class="info-value">{{ $product->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </x-admin-content-card>

            <!-- Description -->
            @if($product->description || $product->short_description)
            <x-admin-content-card 
                title="Deskripsi Produk" 
                icon="fas fa-align-left" 
                :delay="400"
            >
                @if($product->short_description)
                <div class="short-description">
                    <p>{{ $product->short_description }}</p>
                </div>
                @endif
                
                @if($product->description)
                <div class="full-description">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @endif
            </x-admin-content-card>
            @endif

            <!-- Tags -->
            @if($product->tags && count($product->tags) > 0)
            <x-admin-content-card 
                title="Tags" 
                icon="fas fa-tags" 
                :delay="500"
            >
                <div class="tags-container">
                    @foreach($product->tags as $tag)
                        <span class="tag-badge">{{ $tag }}</span>
                    @endforeach
                </div>
            </x-admin-content-card>
            @endif
        </div>

        <!-- Right Column: Stats & Orders -->
        <div class="main-content-right">
            <!-- Sales Statistics -->
            <x-admin-content-card 
                title="Statistik Penjualan" 
                icon="fas fa-chart-line" 
                :delay="300"
            >
                <div class="stats-grid">
                    <x-admin-stat-card 
                        icon="fas fa-shopping-cart"
                        :value="number_format($salesStats['total_quantity_sold'] ?? 0)"
                        label="Total Terjual"
                        change="Unit"
                        changeType="info"
                        iconType="primary"
                        :delay="0"
                    />
                    
                    <x-admin-stat-card 
                        icon="fas fa-dollar-sign"
                        :value="'Rp ' . number_format($salesStats['total_sales_amount'] ?? 0, 0, ',', '.')"
                        label="Total Penjualan"
                        change="Revenue"
                        changeType="positive"
                        iconType="success"
                        :delay="0"
                    />
                    
                    <x-admin-stat-card 
                        icon="fas fa-receipt"
                        :value="number_format($salesStats['total_orders'] ?? 0)"
                        label="Total Pesanan"
                        change="Orders"
                        changeType="info"
                        iconType="warning"
                        :delay="0"
                    />
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
                    @foreach($recentOrders as $orderItem)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-header">
                                    <span class="order-id">#{{ $orderItem->order->id }}</span>
                                    <span class="order-date">{{ $orderItem->order->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="order-details">
                                    <span class="order-customer">{{ $orderItem->order->user->name ?? 'Guest' }}</span>
                                    <span class="order-quantity">{{ $orderItem->quantity }}x</span>
                                    <span class="order-total">Rp {{ number_format($orderItem->line_total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="order-status">
                                <span class="status-badge order-status-{{ $orderItem->order->status }}">
                                    {{ ucfirst($orderItem->order->status) }}
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
/* Product Detail Page */
.product-detail-page {
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

.action-btn.restore {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.action-btn.restore:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
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

/* Product Detail Grid */
.product-detail-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
}

.product-image-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.product-image-wrapper {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 16px;
    overflow: hidden;
    background: var(--gray-100);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.product-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-image-placeholder {
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

.product-image-placeholder span {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.product-basic-info {
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

.info-value.slug {
    font-family: monospace;
    font-size: 0.875rem;
    color: var(--gray-600);
    background: rgba(236, 72, 153, 0.05);
    padding: 0.5rem;
    border-radius: 6px;
}

/* Price Section */
.price-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.price-main {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
}

.price-sale {
    font-size: 1.25rem;
    font-weight: 600;
    color: #dc2626;
    text-decoration: line-through;
}

.discount-badge {
    padding: 0.25rem 0.75rem;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 700;
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

.status-badge.draft {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.trashed {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-badge.best-seller {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.status-badge.featured {
    background: rgba(147, 51, 234, 0.1);
    color: #7c3aed;
}

.status-badge.on-sale {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.status-badge.new {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.category-badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}

.stock-badge {
    display: inline-block;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
}

.stock-badge.in {
    background: rgba(34, 197, 94, 0.1);
    color: #059669;
}

.stock-badge.low {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.stock-badge.out {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Description */
.short-description {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.short-description p {
    font-size: 1.125rem;
    font-weight: 500;
    color: var(--gray-700);
    line-height: 1.6;
    margin: 0;
}

.full-description {
    font-size: 0.9375rem;
    color: var(--gray-600);
    line-height: 1.8;
}

/* Tags */
.tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tag-badge {
    padding: 0.5rem 1rem;
    background: rgba(236, 72, 153, 0.1);
    color: var(--primary-pink);
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
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
}

.order-id {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 0.875rem;
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

.order-quantity {
    color: var(--gray-600);
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

.status-badge.order-status-menunggu {
    background: rgba(107, 114, 128, 0.1);
    color: #4b5563;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 1fr;
    }
    
    .product-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .product-image-wrapper {
        max-width: 400px;
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
