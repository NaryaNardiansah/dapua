@extends('layouts.admin')

@section('content')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="luxury-orders-page">
        <!-- Hero Section -->
        <x-admin-hero icon="fas fa-shopping-bag" title="Manajemen Pesanan" subtitle="Kelola semua pesanan pelanggan"
            description="Pantau dan kelola pesanan dengan mudah dan efisien" :showCircle="true" />

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
            <x-admin-stat-card icon="fas fa-receipt" :value="$totalOrders" label="Total Pesanan"
                change="+{{ $todayOrders }} hari ini" changeType="positive" iconType="primary" :delay="400" />

            <x-admin-stat-card icon="fas fa-clock" :value="$pendingOrders" label="Pending" change="Menunggu"
                changeType="warning" iconType="warning" :delay="500" />

            <x-admin-stat-card icon="fas fa-check-circle" :value="$completedOrders" label="Selesai" change="Completed"
                changeType="positive" iconType="success" :delay="600" />

            <x-admin-stat-card icon="fas fa-truck" :value="$shippedOrders" label="Dikirim" change="On Delivery"
                changeType="info" iconType="info" :delay="700" />

            <x-admin-stat-card icon="fas fa-dollar-sign" :value="'Rp ' . number_format($todayRevenue, 0, ',', '.')"
                label="Revenue Hari Ini" change="Today" changeType="positive" iconType="success" :delay="800" />
        </x-admin-responsive-grid>


        <!-- Orders Table Section -->
        <x-admin-content-card title="Daftar Pesanan" icon="fas fa-list-alt" :delay="500">
            @slot('actions')
            <x-admin-button-group>
                <button class="btn btn-secondary" onclick="refreshOrders()" data-tooltip="Refresh Data">
                    <i class="fas fa-sync-alt"></i>Refresh
                </button>
                <button class="btn btn-primary" onclick="exportOrders()" data-tooltip="Export Data">
                    <i class="fas fa-download"></i>Export
                </button>
            </x-admin-button-group>
            @endslot

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
                    <table class="orders-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-hashtag mr-2"></i>
                                        ID Pesanan
                                    </div>
                                </th>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Tanggal
                                    </div>
                                </th>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-user mr-2"></i>
                                        Pelanggan
                                    </div>
                                </th>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-tag mr-2"></i>
                                        Status
                                    </div>
                                </th>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-dollar-sign mr-2"></i>
                                        Total
                                    </div>
                                </th>
                                <th class="table-header-cell">
                                    <div class="header-content">
                                        <i class="fas fa-cogs mr-2"></i>
                                        Aksi
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($orders as $order)
                                <tr class="order-row" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}"
                                    data-order-id="{{ $order->id }}">
                                    <td class="order-cell">
                                        <div class="order-id">
                                            <span class="order-code">{{ $order->order_code ?? ('#' . $order->id) }}</span>
                                        </div>
                                    </td>
                                    <td class="order-cell">
                                        <div class="order-date">
                                            <span class="date-text">{{ $order->created_at->format('d M Y') }}</span>
                                            <span class="time-text">{{ $order->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="order-cell">
                                        <div class="customer-info">
                                            <div class="customer-name">{{ $order->recipient_name }}</div>
                                            <div class="customer-phone">{{ $order->recipient_phone ?? 'No Phone' }}</div>
                                        </div>
                                    </td>
                                    <td class="order-cell">
                                        <div class="status-container">
                                            <span class="status-badge status-{{ Str::slug($order->status) }}">
                                                <i class="fas fa-circle status-dot"></i>
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="order-cell">
                                        <div class="order-total">
                                            <span class="total-amount">Rp
                                                {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                        </div>
                                    </td>
                                    <td class="order-cell">
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="action-btn detail-btn"
                                                data-tooltip="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $order->whatsapp_link }}" target="_blank"
                                                class="action-btn whatsapp-btn" data-tooltip="Chat WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>

                                            <form id="delete-order-{{ $order->id }}"
                                                action="{{ route('admin.orders.destroy', $order) }}" method="post"
                                                class="no-loading" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" class="action-btn delete-btn"
                                                onclick="deleteOrder({{ $order->id }}, '{{ $order->order_code ?? $order->id }}')"
                                                data-tooltip="Hapus Pesanan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">
                                        <div class="empty-content">
                                            <i class="fas fa-shopping-bag empty-icon"></i>
                                            <h3 class="empty-title">Belum Ada Pesanan</h3>
                                            <p class="empty-description">Belum ada pesanan yang masuk ke sistem</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="pagination-container mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </x-admin-content-card>

    </div>

    <style>
        /* Luxury Orders Page Styles */
        .luxury-orders-page {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
        }

        /* Table Scroll Container */
        .table-scroll-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: var(--pure-white);
            box-shadow: var(--shadow-lg);
        }

        /* Scroll Indicators */
        .scroll-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(236, 72, 153, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
        }

        .scroll-indicator:hover {
            background: rgba(255, 255, 255, 1);
            border-color: var(--primary-pink);
            box-shadow: 0 12px 35px rgba(236, 72, 153, 0.25);
            transform: translateY(-50%) scale(1.1);
        }

        .scroll-indicator.active {
            opacity: 1;
            visibility: visible;
        }

        .scroll-indicator i {
            color: var(--primary-pink);
            font-size: 0.875rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .scroll-left {
            left: 10px;
        }

        .scroll-right {
            right: 10px;
        }

        /* Table Wrapper */
        .table-wrapper {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-pink) transparent;
        }

        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: rgba(236, 72, 153, 0.1);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary-pink);
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-pink);
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            min-width: 800px;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--pure-white);
        }

        /* Table Responsive Enhancements */
        .table-header-cell {
            white-space: nowrap;
            min-width: 120px;
            padding: 1rem;
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
            border-bottom: 2px solid var(--primary-pink);
            font-weight: 600;
            color: var(--gray-800);
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .order-cell {
            white-space: nowrap;
            min-width: 100px;
            padding: 1rem;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            transition: background-color 0.3s ease;
        }

        .order-row:hover .order-cell {
            background: rgba(236, 72, 153, 0.05);
        }

        .order-code {
            font-weight: 600;
            color: var(--primary-pink);
        }

        .customer-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .customer-phone {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-badge.status-diproses {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .status-badge.status-dikirim {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-badge.status-selesai {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-badge.status-dibatalkan {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .status-dot {
            font-size: 0.5rem;
        }

        .total-amount {
            font-weight: 700;
            color: var(--gray-900);
        }

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
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .action-btn.detail-btn {
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .action-btn.detail-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.whatsapp-btn {
            background: rgba(37, 211, 102, 0.1);
            color: #25D366;
        }

        .action-btn.whatsapp-btn:hover {
            background: #25D366;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.edit-btn {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .action-btn.edit-btn:hover {
            background: #d97706;
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .action-btn.delete-btn:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
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
            color: var(--gray-300);
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 0;
        }

        .empty-description {
            color: var(--gray-500);
            margin: 0;
        }

        /* Responsive Enhancements */
        @media (max-width: 1024px) {

            /* Tablet adjustments */
            .orders-table {
                min-width: 700px;
            }

            .table-header-cell {
                min-width: 100px;
                padding: 0.75rem;
            }

            .order-cell {
                min-width: 80px;
                padding: 0.75rem;
            }

            .scroll-indicator {
                width: 35px;
                height: 35px;
            }

            .scroll-indicator i {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 768px) {

            /* Mobile adjustments */
            .orders-table {
                min-width: 600px;
            }

            .table-header-cell {
                min-width: 80px;
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .order-cell {
                min-width: 60px;
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .scroll-indicator {
                width: 32px;
                height: 32px;
                opacity: 1;
                visibility: visible;
            }

            .scroll-indicator i {
                font-size: 0.75rem;
            }

            .scroll-left {
                left: 5px;
            }

            .scroll-right {
                right: 5px;
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

            .status-badge {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }

            .customer-phone {
                display: none;
            }

            .date-text {
                display: block;
            }

            .time-text {
                display: none;
            }
        }

        @media (max-width: 480px) {

            /* Small mobile adjustments */
            .orders-table {
                min-width: 500px;
            }

            .table-header-cell {
                min-width: 60px;
                padding: 0.4rem;
                font-size: 0.8rem;
            }

            .order-cell {
                min-width: 50px;
                padding: 0.4rem;
                font-size: 0.8rem;
            }

            .scroll-indicator {
                width: 28px;
                height: 28px;
            }

            .scroll-indicator i {
                font-size: 0.7rem;
            }

            .scroll-left {
                left: 3px;
            }

            .scroll-right {
                right: 3px;
            }

            .action-buttons {
                flex-direction: row;
                gap: 0.25rem;
            }

            .action-btn {
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
            }

            .status-badge {
                padding: 0.25rem 0.4rem;
                font-size: 0.65rem;
            }

            .total-amount {
                font-size: 0.8rem;
            }
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
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.8) 100%);
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

        /* Orders Table Section */
        .orders-table-section {
            background: var(--pure-white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            margin: 2rem 0;
            border: 1px solid rgba(236, 72, 153, 0.1);
            overflow: hidden;
        }

        .table-container {
            padding: 0;
        }

        .table-header {
            background: linear-gradient(135deg, var(--light-pink) 0%, rgba(255, 255, 255, 0.8) 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .table-icon {
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

        .table-title-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .table-title-content p {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin: 0.25rem 0 0 0;
        }

        .table-actions {
            display: flex;
            gap: 0.75rem;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header-cell {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
            font-size: 0.875rem;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-row {
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.3s ease;
        }

        .order-row:hover {
            background: var(--light-pink);
        }

        .order-cell {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }

        .order-id-container {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .order-id {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 1rem;
        }

        .order-code {
            font-family: 'Courier New', monospace;
            background: var(--gray-100);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .order-date {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date-text {
            font-weight: 500;
            color: var(--gray-800);
        }

        .time-text {
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .customer-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .customer-name {
            font-weight: 500;
            color: var(--gray-800);
        }

        .customer-phone {
            font-size: 0.75rem;
            color: var(--gray-600);
        }

        .status-container {
            display: flex;
            align-items: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-dot {
            font-size: 0.5rem;
            animation: pulse 2s infinite;
        }

        .order-total {
            font-weight: 600;
            color: var(--primary-pink);
            font-size: 1.1rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .action-btn {
            padding: 0.5rem;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .refresh-btn,
        .export-btn {
            background: var(--gray-200);
            color: var(--gray-700);
            border-color: var(--gray-200);
        }

        .refresh-btn:hover,
        .export-btn:hover {
            background: var(--gray-300);
            color: var(--gray-800);
        }

        .detail-btn {
            background: #dbeafe;
            color: #1e40af;
            border-color: #dbeafe;
        }

        .detail-btn:hover {
            background: #1e40af;
            color: white;
        }

        .whatsapp-btn {
            background: #dcfce7;
            color: #166534;
            border-color: #dcfce7;
        }

        .whatsapp-btn:hover {
            background: #166534;
            color: white;
        }

        .edit-btn {
            background: #fef3c7;
            color: #92400e;
            border-color: #fef3c7;
        }

        .edit-btn:hover {
            background: #92400e;
            color: white;
        }

        .delete-btn {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fee2e2;
        }

        .delete-btn:hover {
            background: #991b1b;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--gray-300);
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-600);
            margin: 0;
        }

        .empty-description {
            color: var(--gray-500);
            margin: 0;
        }

        /* Pagination Section */
        .pagination-section {
            background: var(--pure-white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            margin: 2rem 0;
            padding: 1.5rem 2rem;
            border: 1px solid rgba(236, 72, 153, 0.1);
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination-info {
            display: flex;
            align-items: center;
        }

        .pagination-text {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .pagination-links {
            display: flex;
            align-items: center;
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

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .filters-actions {
                justify-content: center;
            }

            .table-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .table-actions {
                justify-content: center;
            }

            .action-buttons {
                flex-wrap: wrap;
            }

            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
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

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .modal-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(236, 72, 153, 0.1);
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .modal-title i {
            color: var(--primary-pink);
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-500);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-size: 1rem;
            background: white;
            color: var(--gray-800);
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-300);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global functions accessible from HTML onclick
        function exportOrders() {
            // Build export URL with current filters
            const url = new URL(window.location);
            url.searchParams.set('export', 'csv');

            // Create temporary link and click it
            const link = document.createElement('a');
            link.href = url.toString();
            link.download = 'orders-export.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function refreshOrders() {
            window.location.reload();
        }


        function deleteOrder(orderId, orderCode) {
            Swal.fire({
                title: 'Hapus Pesanan?',
                text: `Pesanan ${orderCode} akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ec4899',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '16px',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-order-' + orderId);
                    if (form) {
                        form.submit();
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true,
                mirror: false,
            });

            // Horizontal scroll functionality
            const tableWrapper = document.getElementById('tableWrapper');
            const scrollLeft = document.getElementById('scrollLeft');
            const scrollRight = document.getElementById('scrollRight');
            const ordersTable = document.getElementById('ordersTable');

            if (tableWrapper && scrollLeft && scrollRight && ordersTable) {
                // Scroll functionality
                function updateScrollIndicators() {
                    const scrollLeft = tableWrapper.scrollLeft;
                    const maxScrollLeft = tableWrapper.scrollWidth - tableWrapper.clientWidth;

                    // Show/hide scroll indicators based on scroll position
                    if (scrollLeft > 0) {
                        document.getElementById('scrollLeft').classList.add('active');
                    } else {
                        document.getElementById('scrollLeft').classList.remove('active');
                    }

                    if (scrollLeft < maxScrollLeft - 1) {
                        document.getElementById('scrollRight').classList.add('active');
                    } else {
                        document.getElementById('scrollRight').classList.remove('active');
                    }
                }

                // Scroll left
                scrollLeft.addEventListener('click', function () {
                    tableWrapper.scrollBy({
                        left: -200,
                        behavior: 'smooth'
                    });
                });

                // Scroll right
                scrollRight.addEventListener('click', function () {
                    tableWrapper.scrollBy({
                        left: 200,
                        behavior: 'smooth'
                    });
                });

                // Update indicators on scroll
                tableWrapper.addEventListener('scroll', updateScrollIndicators);

                // Update indicators on resize
                window.addEventListener('resize', function () {
                    setTimeout(updateScrollIndicators, 100);
                });

                // Initial check
                setTimeout(updateScrollIndicators, 200);

                // Touch/swipe support for mobile
                let startX = 0;
                let startY = 0;
                let isScrolling = false;

                tableWrapper.addEventListener('touchstart', function (e) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    isScrolling = false;
                });

                tableWrapper.addEventListener('touchmove', function (e) {
                    if (!isScrolling) {
                        const deltaX = Math.abs(e.touches[0].clientX - startX);
                        const deltaY = Math.abs(e.touches[0].clientY - startY);

                        if (deltaX > deltaY) {
                            isScrolling = true;
                            e.preventDefault();
                        }
                    }
                });

                // Keyboard navigation
                tableWrapper.addEventListener('keydown', function (e) {
                    if (e.target.closest('table')) {
                        switch (e.key) {
                            case 'ArrowLeft':
                                e.preventDefault();
                                tableWrapper.scrollBy({ left: -100, behavior: 'smooth' });
                                break;
                            case 'ArrowRight':
                                e.preventDefault();
                                tableWrapper.scrollBy({ left: 100, behavior: 'smooth' });
                                break;
                        }
                    }
                });

                // Make table focusable for keyboard navigation
                tableWrapper.setAttribute('tabindex', '0');

                // Auto-hide scroll indicators after inactivity
                let hideTimeout;
                function hideScrollIndicators() {
                    hideTimeout = setTimeout(function () {
                        if (window.innerWidth > 768) {
                            scrollLeft.classList.remove('active');
                            scrollRight.classList.remove('active');
                        }
                    }, 3000);
                }

                function showScrollIndicators() {
                    clearTimeout(hideTimeout);
                    updateScrollIndicators();
                    hideScrollIndicators();
                }

                // Show indicators on hover
                tableWrapper.addEventListener('mouseenter', showScrollIndicators);
                tableWrapper.addEventListener('mouseleave', function () {
                    if (window.innerWidth > 768) {
                        hideScrollIndicators();
                    }
                });

                // Show indicators on touch
                tableWrapper.addEventListener('touchstart', showScrollIndicators);

                // Force show indicators on mobile
                if (window.innerWidth <= 768) {
                    setTimeout(function () {
                        scrollLeft.classList.add('active');
                        scrollRight.classList.add('active');
                    }, 500);
                }
            }

            // Show success message if exists
            @if(session('status'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('status') }}",
                    icon: 'success',
                    confirmButtonColor: '#ec4899',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif


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

            // WhatsApp link handler
            document.querySelectorAll('a[href*="wa.me"]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    const whatsappUrl = this.href;

                    // Try to open WhatsApp app first
                    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                    if (isMobile) {
                        // For mobile devices, try to open WhatsApp app
                        window.location.href = whatsappUrl;
                    } else {
                        // For desktop, open in new tab
                        const newWindow = window.open(whatsappUrl, '_blank', 'noopener,noreferrer');

                        // If popup blocked, show fallback message
                        if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                            // Show notification
                            const notification = document.createElement('div');
                            notification.style.cssText = `
                                        position: fixed;
                                        top: 20px;
                                        right: 20px;
                                        background: #25D366;
                                        color: white;
                                        padding: 1rem 1.5rem;
                                        border-radius: 8px;
                                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                        z-index: 10000;
                                        font-size: 0.875rem;
                                        max-width: 300px;
                                    `;
                            notification.innerHTML = `
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fab fa-whatsapp" style="font-size: 1.25rem;"></i>
                                            <div>
                                                <div style="font-weight: 600;">WhatsApp Link</div>
                                                <div style="font-size: 0.75rem; opacity: 0.9;">Klik untuk membuka WhatsApp</div>
                                            </div>
                                        </div>
                                    `;

                            notification.addEventListener('click', function () {
                                window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
                                document.body.removeChild(notification);
                            });

                            document.body.appendChild(notification);

                            // Auto remove after 5 seconds
                            setTimeout(() => {
                                if (document.body.contains(notification)) {
                                    document.body.removeChild(notification);
                                }
                            }, 5000);
                        }
                    }
                });
            });
        });
    </script>
@endsection