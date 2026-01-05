@extends('layouts.admin')

@section('content')
<div class="luxury-category-detail-page">
    <!-- Hero Section -->
    <div class="hero-section fade-in-up">
        <div class="hero-content">
            <div class="hero-title-container">
                <div class="hero-breadcrumb">
                    <a href="{{ route('admin.categories.index') }}" class="breadcrumb-link">
                        <i class="fas fa-tags mr-1"></i>Kategori
                    </a>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <span class="breadcrumb-current">{{ $category->name }}</span>
                </div>
                <h1 class="hero-title fade-in-up delay-200">
                    <div class="category-icon-wrapper">
                        @if($category->color)
                            <div class="category-color-icon" style="background-color: {{ $category->color }}"></div>
                        @else
                            <i class="fas fa-tag hero-icon"></i>
                        @endif
                    </div>
                    <div class="hero-title-text">
                        <span class="hero-title-main">{{ $category->name }}</span>
                        <span class="hero-title-sub">Detail Kategori</span>
                    </div>
                </h1>
                <p class="hero-subtitle fade-in-up delay-300">{{ $category->description ?: 'Informasi lengkap kategori Dapur Sakura' }}</p>
            </div>
            <div class="hero-decorative-elements">
                <div class="decorative-line fade-in-up delay-400"></div>
                <div class="decorative-dots fade-in-up delay-500"></div>
                <div class="decorative-circle fade-in-up delay-600"></div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Bar -->
    <div class="quick-stats-bar fade-in-up delay-300" data-aos="fade-up">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $category->products->count() }}</span>
                    <span class="stat-label">Produk</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $category->products->where('is_active', true)->count() }}</span>
                    <span class="stat-label">Aktif</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $category->children->count() }}</span>
                    <span class="stat-label">Subkategori</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $category->products->sum('view_count') }}</span>
                    <span class="stat-label">Views</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons-section fade-in-up delay-400" data-aos="fade-up">
        <div class="action-buttons-container">
            <a href="{{ route('admin.categories.index') }}" class="action-btn secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn primary">
                <i class="fas fa-edit mr-2"></i>Edit Kategori
            </a>
            @if($category->canBeDeleted())
                <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn danger">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="main-content-grid">
        <!-- Left Column - Main Information -->
        <div class="main-content-left">
            <!-- Basic Information Card -->
            <div class="info-card fade-in-up delay-500" data-aos="fade-up">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="card-title">
                        <h3>Informasi Dasar</h3>
                        <p>Data utama kategori yang akan ditampilkan</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <label class="info-label">Nama Kategori</label>
                            <div class="info-value">{{ $category->name }}</div>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Slug</label>
                            <div class="info-value-secondary">{{ $category->slug }}</div>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Warna</label>
                            <div class="info-value">
                                @if($category->color)
                                    <div class="color-display">
                                        <div class="color-swatch" style="background-color: {{ $category->color }}"></div>
                                        <span class="color-code">{{ $category->color }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">Tidak ada warna</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-item">
                            <label class="info-label">Urutan</label>
                            <div class="info-value">{{ $category->sort_order }}</div>
                        </div>
                        @if($category->description)
                            <div class="info-item full-width">
                                <label class="info-label">Deskripsi</label>
                                <div class="info-description">{{ $category->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hierarchy Information Card -->
            @if($category->parent || $category->children->count() > 0)
                <div class="info-card fade-in-up delay-600" data-aos="fade-up">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div class="card-title">
                            <h3>Hierarki Kategori</h3>
                            <p>Struktur kategori dan hubungannya</p>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="hierarchy-content">
                            @if($category->parent)
                                <div class="hierarchy-item">
                                    <label class="info-label">Parent Kategori</label>
                                    <div class="parent-link">
                                        <a href="{{ route('admin.categories.show', $category->parent) }}" class="category-link">
                                            <i class="fas fa-level-up-alt mr-2"></i>
                                            {{ $category->parent->name }}
                                        </a>
                                        <span class="level-badge">Level {{ $category->level }}</span>
                                    </div>
                                </div>
                            @endif
                            @if($category->children->count() > 0)
                                <div class="hierarchy-item">
                                    <label class="info-label">Subkategori ({{ $category->children->count() }})</label>
                                    <div class="children-list">
                                        @foreach($category->children as $child)
                                            <div class="child-item">
                                                <a href="{{ route('admin.categories.show', $child) }}" class="category-link">
                                                    <i class="fas fa-level-down-alt mr-2"></i>
                                                    {{ $child->name }}
                                                </a>
                                                <span class="product-count">{{ $child->products_count }} produk</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Products Card -->
            <div class="info-card fade-in-up delay-700" data-aos="fade-up">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="card-title">
                        <h3>Produk dalam Kategori</h3>
                        <p>Daftar produk yang termasuk dalam kategori ini</p>
                    </div>
                </div>
                <div class="card-content">
                    @if($category->products->count() > 0)
                        <div class="products-grid">
                            @foreach($category->products as $product)
                                <div class="product-item">
                                    <div class="product-image">
                                        @if($product->image_path)
                                            <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="product-img">
                                        @else
                                            <div class="product-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <h4 class="product-name">{{ $product->name }}</h4>
                                        <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        <div class="product-status">
                                            @if($product->is_active)
                                                <span class="status-badge active">Aktif</span>
                                            @else
                                                <span class="status-badge inactive">Draft</span>
                                            @endif
                                            @if($product->is_featured)
                                                <span class="status-badge featured">Featured</span>
                                            @endif
                                            @if($product->is_best_seller)
                                                <span class="status-badge best-seller">Best Seller</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box-open empty-icon"></i>
                            <p class="empty-text">Belum ada produk dalam kategori ini</p>
                            <a href="{{ route('admin.products.create') }}" class="empty-action">
                                <i class="fas fa-plus mr-2"></i>Tambah Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Statistics & System Info -->
        <div class="main-content-right">
            <!-- Status & Settings Card -->
            <div class="status-card fade-in-up delay-500" data-aos="fade-left">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="card-title">
                        <h3>Status & Pengaturan</h3>
                        <p>Konfigurasi kategori</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="status-grid">
                        <div class="status-item">
                            <label class="status-label">Status</label>
                            <div class="status-value">
                                @if($category->is_active)
                                    <span class="status-badge active">Aktif</span>
                                @else
                                    <span class="status-badge inactive">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="status-item">
                            <label class="status-label">Featured</label>
                            <div class="status-value">
                                @if($category->is_featured)
                                    <span class="status-badge featured">Ya</span>
                                @else
                                    <span class="status-badge inactive">Tidak</span>
                                @endif
                            </div>
                        </div>
                        <div class="status-item">
                            <label class="status-label">Trending</label>
                            <div class="status-value">
                                @if($category->is_trending)
                                    <span class="status-badge trending">Ya</span>
                                @else
                                    <span class="status-badge inactive">Tidak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="system-card fade-in-up delay-600" data-aos="fade-left">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-info"></i>
                    </div>
                    <div class="card-title">
                        <h3>Informasi Sistem</h3>
                        <p>Data teknis kategori</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="system-info">
                        <div class="system-item">
                            <label class="system-label">Dibuat</label>
                            <div class="system-value">{{ $category->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="system-item">
                            <label class="system-label">Diperbarui</label>
                            <div class="system-value">{{ $category->updated_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="system-item">
                            <label class="system-label">Level</label>
                            <div class="system-value">{{ $category->level }}</div>
                        </div>
                        @if($category->path)
                            <div class="system-item">
                                <label class="system-label">Path</label>
                                <div class="system-value path-value">{{ $category->path }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Luxury Category Detail Styles */
.luxury-category-detail-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    padding: 4rem 0;
    margin: -2rem -2rem 0 -2rem;
    position: relative;
    overflow: hidden;
}

.hero-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    position: relative;
    z-index: 2;
}

.hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: var(--pure-white);
}

.breadcrumb-separator {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.8rem;
}

.breadcrumb-current {
    color: var(--pure-white);
    font-weight: 600;
}

.hero-title-container {
    text-align: center;
    color: var(--pure-white);
}

.hero-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.category-icon-wrapper {
    position: relative;
}

.category-color-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    animation: float 3s ease-in-out infinite;
}

.hero-icon {
    font-size: 3rem;
    animation: bounce 2s infinite;
}

.hero-title-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.hero-title-main {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1;
}

.hero-title-sub {
    font-size: 1.2rem;
    font-weight: 300;
    opacity: 0.9;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.hero-decorative-elements {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
}

.decorative-line {
    position: absolute;
    top: 50%;
    left: 10%;
    width: 3px;
    height: 120px;
    background: rgba(255,255,255,0.3);
    transform: translateY(-50%) rotate(15deg);
    border-radius: 2px;
}

.decorative-dots {
    position: absolute;
    top: 30%;
    right: 15%;
    width: 24px;
    height: 24px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.decorative-circle {
    position: absolute;
    bottom: 20%;
    left: 20%;
    width: 40px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.2);
    border-radius: 50%;
    animation: rotate 10s linear infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 0.2; }
    50% { transform: scale(1.2); opacity: 0.4; }
    100% { transform: scale(1); opacity: 0.2; }
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Quick Stats Bar */
.quick-stats-bar {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    margin: 2rem 0;
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255,255,255,0.8) 100%);
    border-radius: 12px;
    transition: all 0.3s ease;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.stat-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pure-white);
    font-size: 1.2rem;
    box-shadow: var(--shadow-sm);
}

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--gray-800);
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Action Buttons */
.action-buttons-section {
    margin: 2rem 0;
}

.action-buttons-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    position: relative;
    overflow: hidden;
    font-size: 0.95rem;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn.secondary {
    background: var(--gray-200);
    color: var(--gray-700);
}

.action-btn.secondary:hover {
    background: var(--gray-300);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.action-btn.primary {
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    color: var(--pure-white);
}

.action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.action-btn.danger {
    background: linear-gradient(45deg, #ef4444, #dc2626);
    color: var(--pure-white);
}

.action-btn.danger:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

/* Main Content Grid */
.main-content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

@media (min-width: 1024px) {
    .main-content-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.main-content-left {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.main-content-right {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Info Cards */
.info-card, .status-card, .system-card {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(236, 72, 153, 0.1);
}

.info-card:hover, .status-card:hover, .system-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-2xl);
}

.card-header {
    background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255,255,255,0.8) 100%);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pure-white);
    font-size: 1.2rem;
    box-shadow: var(--shadow-md);
}

.card-title h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
}

.card-title p {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin: 0.25rem 0 0 0;
}

.card-content {
    padding: 1.5rem;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-value {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--gray-800);
}

.info-value-secondary {
    font-size: 1rem;
    font-weight: 500;
    color: var(--gray-600);
    font-family: 'Courier New', monospace;
    background: var(--gray-100);
    padding: 0.5rem;
    border-radius: 6px;
}

.info-description {
    font-size: 1rem;
    color: var(--gray-700);
    line-height: 1.6;
    background: var(--gray-50);
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-pink);
}

/* Color Display */
.color-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.color-swatch {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 2px solid var(--gray-200);
    box-shadow: var(--shadow-sm);
}

.color-code {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    background: var(--gray-100);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

/* Hierarchy */
.hierarchy-content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.hierarchy-item {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.parent-link {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.category-link {
    display: inline-flex;
    align-items: center;
    color: var(--primary-pink);
    text-decoration: none;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background: var(--light-pink);
    transition: all 0.3s ease;
}

.category-link:hover {
    background: var(--primary-pink);
    color: var(--pure-white);
    transform: translateX(4px);
}

.level-badge {
    background: var(--gray-200);
    color: var(--gray-700);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.children-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.child-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: var(--gray-50);
    border-radius: 8px;
    border-left: 4px solid var(--secondary-pink);
    transition: all 0.3s ease;
}

.child-item:hover {
    background: var(--light-pink);
    transform: translateX(4px);
}

.product-count {
    background: var(--primary-pink);
    color: var(--pure-white);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
}

.product-item:hover {
    background: var(--light-pink);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-400);
    font-size: 1.5rem;
}

.product-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 0;
}

.product-price {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--primary-pink);
}

.product-status {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--gray-500);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-text {
    font-size: 1.125rem;
    margin-bottom: 1.5rem;
}

.empty-action {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(45deg, var(--primary-pink), var(--secondary-pink));
    color: var(--pure-white);
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.empty-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* Status Grid */
.status-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 8px;
}

.status-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
}

.status-badge.featured {
    background: #fce7f3;
    color: #be185d;
}

.status-badge.trending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.best-seller {
    background: #dbeafe;
    color: #1e40af;
}

/* System Info */
.system-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.system-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 8px;
}

.system-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.system-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
}

.path-value {
    font-family: 'Courier New', monospace;
    background: var(--gray-100);
    padding: 0.5rem;
    border-radius: 4px;
    word-break: break-all;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-title-main {
        font-size: 2.5rem;
    }
    
    .hero-icon {
        font-size: 2.5rem;
    }
    
    .category-color-icon {
        width: 60px;
        height: 60px;
    }
    
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons-container {
        flex-direction: column;
    }
    
    .action-btn {
        justify-content: center;
    }
    
    .main-content-grid {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .hero-title-main {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
}

/* Animations */
.fade-in-up {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.6s ease-out forwards;
}

.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.3s; }
.delay-400 { animation-delay: 0.4s; }
.delay-500 { animation-delay: 0.5s; }
.delay-600 { animation-delay: 0.6s; }
.delay-700 { animation-delay: 0.7s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
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
        mirror: false,
    });

    // Add hover effects to cards
    document.querySelectorAll('.info-card, .stats-card, .status-card, .system-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add click effects to action buttons
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple effect styles
const style = document.createElement('style');
style.textContent = `
    .action-btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection







