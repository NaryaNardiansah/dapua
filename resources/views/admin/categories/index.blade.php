@extends('layouts.admin')

@section('content')
    <div class="luxury-categories-page">
        <!-- Hero Section -->
        <x-admin-hero icon="fas fa-tags" title="Manajemen Kategori"
            subtitle="Kelola dan organisasi kategori produk Dapur Sakura"
            description="Kelola kategori dengan mudah dan efisien" :showCircle="true" />

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
            <x-admin-stat-card icon="fas fa-tags" :value="$stats['total']" label="Total Kategori" change="Semua Kategori"
                changeType="info" iconType="primary" :delay="300" />

            <x-admin-stat-card icon="fas fa-check-circle" :value="$stats['active']" label="Kategori Aktif" change="Active"
                changeType="positive" iconType="success" :delay="400" />

            <x-admin-stat-card icon="fas fa-star" :value="$stats['featured']" label="Featured" change="Highlighted"
                changeType="positive" iconType="warning" :delay="500" />

            <x-admin-stat-card icon="fas fa-box" :value="$stats['total_products']" label="Total Produk" change="Products"
                changeType="info" iconType="info" :delay="600" />

            <x-admin-stat-card icon="fas fa-dollar-sign" :value="'Rp ' . number_format($stats['total_sales'], 0, ',', '.')"
                label="Total Sales" change="Revenue" changeType="positive" iconType="success" :delay="700" />
        </x-admin-responsive-grid>

        <!-- Simple Modern Filter & Search -->
        <div class="modern-filter-section" data-aos="fade-up" data-aos-delay="400">
            <form method="get" class="modern-filter-form">
                <div class="filter-row">
                    <!-- Search Input -->
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ $search }}" class="modern-search-input"
                            placeholder="Cari kategori..." />
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
                            <option value="all" @selected($status === 'all')>Semua Status</option>
                            <option value="active" @selected($status === 'active')>Aktif</option>
                            <option value="inactive" @selected($status === 'inactive')>Tidak Aktif</option>
                            <option value="featured" @selected($status === 'featured')>Featured</option>
                            <option value="trending" @selected($status === 'trending')>Trending</option>
                            <option value="empty" @selected($status === 'empty')>Kosong</option>
                            <option value="with_products" @selected($status === 'with_products')>Berisi Produk</option>
                        </select>

                        <select name="sort" class="modern-select">
                            <option value="sort_order" @selected($sort === 'sort_order')>Urutan</option>
                            <option value="name" @selected($sort === 'name')>Nama A-Z</option>
                            <option value="created_at" @selected($sort === 'created_at')>Terbaru</option>
                            <option value="sales" @selected($sort === 'sales')>Penjualan</option>
                            <option value="quantity" @selected($sort === 'quantity')>Kuantitas</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="filter-actions">
                        <button type="submit" class="modern-btn primary">
                            <i class="fas fa-filter"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="modern-btn secondary" title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                        <a href="{{ route('admin.categories.export', ['format' => 'pdf']) }}" class="modern-btn info">
                            <i class="fas fa-file-pdf"></i>
                            <span>Export PDF</span>
                        </a>
                        <a href="{{ route('admin.categories.create') }}" class="modern-btn success">
                            <i class="fas fa-plus"></i>
                            <span>Tambah</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Categories Table -->
        <x-admin-content-card title="Daftar Kategori" icon="fas fa-table" :delay="900">
            <div class="table-scroll-container">
                <div class="scroll-indicator scroll-left" id="scrollLeft">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="scroll-indicator scroll-right" id="scrollRight">
                    <i class="fas fa-chevron-right"></i>
                </div>

                <div class="table-wrapper" id="tableWrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-header-cell">Gambar</th>
                                <th class="table-header-cell">Nama Kategori</th>
                                <th class="table-header-cell">Warna</th>
                                <th class="table-header-cell">Produk</th>
                                <th class="table-header-cell">Status</th>
                                <th class="table-header-cell">Sales</th>
                                <th class="table-header-cell">Dibuat</th>
                                <th class="table-header-cell">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="category-image">
                                            @if($category->image_path)
                                                <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}"
                                                    class="category-img">
                                            @else
                                                <div class="no-image" style="background-color: {{ $category->color ?? '#e5e7eb' }}">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="category-info">
                                            <div class="category-name">{{ $category->name }}</div>
                                            <div class="category-slug">{{ $category->slug }}</div>
                                            @if($category->is_featured)
                                                <span class="badge featured">
                                                    <i class="fas fa-star"></i>Featured
                                                </span>
                                            @endif
                                            @if($category->is_trending)
                                                <span class="badge trending">
                                                    <i class="fas fa-fire"></i>Trending
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="color-display">
                                            <div class="color-preview"
                                                style="background-color: {{ $category->color ?? '#e5e7eb' }}"></div>
                                            <span class="color-code">{{ $category->color ?? '#e5e7eb' }}</span>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <div class="products-count">
                                            <span class="count-number">{{ $category->products_count }}</span>
                                            <span class="count-label">produk</span>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        @if($category->is_active)
                                            <span class="status-badge active">
                                                <i class="fas fa-check-circle"></i>Aktif
                                            </span>
                                        @else
                                            <span class="status-badge inactive">
                                                <i class="fas fa-times-circle"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-cell">
                                        <div class="sales-info">
                                            <div class="sales-amount">
                                                Rp {{ number_format($category->total_sales ?? 0, 0, ',', '.') }}
                                            </div>
                                            <div class="sales-quantity">
                                                {{ number_format($category->total_quantity_sold ?? 0) }} terjual
                                            </div>
                                        </div>
                                    </td>
                                    <td class="table-cell">
                                        <span class="created-date">
                                            {{ $category->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="table-cell">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.categories.show', $category) }}" class="action-btn view"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit"
                                                title="Edit Kategori">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post"
                                                class="inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="action-btn delete" title="Hapus"
                                                    onclick="return confirm('Hapus kategori ini?')">
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
                                            <i class="fas fa-tags empty-icon"></i>
                                            <h3 class="empty-title">Belum Ada Kategori</h3>
                                            <p class="empty-description">Belum ada kategori yang terdaftar dalam sistem.</p>
                                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i>Tambah Kategori Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
                <div class="pagination-container mt-6">
                    {{ $categories->links() }}
                </div>
            @endif
        </x-admin-content-card>
    </div>

    <style>
        /* Luxury Categories Page Styles */
        .luxury-categories-page {
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

        .modern-btn.info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: var(--pure-white);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .modern-btn.info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
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
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
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
            padding: 1rem;
            vertical-align: middle;
        }

        /* Category Image */
        .category-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Category Info */
        .category-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .category-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .category-slug {
            color: var(--gray-500);
            font-size: 0.75rem;
            font-family: monospace;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .badge.featured {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .badge.trending {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Color Display */
        .color-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .color-preview {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--gray-300);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .color-code {
            font-family: monospace;
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        /* Products Count */
        .products-count {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }

        .count-number {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
        }

        .count-label {
            color: var(--gray-600);
            font-size: 0.75rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Sales Info */
        .sales-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .sales-amount {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.875rem;
        }

        .sales-quantity {
            color: var(--gray-600);
            font-size: 0.75rem;
        }

        /* Created Date */
        .created-date {
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .action-btn.view:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.1);
        }

        .action-btn.edit {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .action-btn.edit:hover {
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

        /* Responsive Design */
        @media (max-width: 1024px) {
            .table {
                min-width: 800px;
            }

            .scroll-indicator {
                width: 35px;
                height: 35px;
            }
        }

        @media (max-width: 768px) {
            .table {
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
        }

        @media (max-width: 480px) {
            .table {
                min-width: 600px;
            }

            .table-cell {
                padding: 0.5rem 0.25rem;
            }

            .category-name {
                font-size: 0.8rem;
            }

            .category-slug {
                font-size: 0.7rem;
            }

            .badge {
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
        });
    </script>
@endsection