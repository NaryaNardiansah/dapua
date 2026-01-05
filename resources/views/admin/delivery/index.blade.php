@extends('layouts.admin')

@section('content')
<div class="luxury-delivery-management-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-truck"
        title="Manajemen Pengiriman"
        subtitle="Kelola pengiriman Dapur Sakura"
        description="Pantau dan kelola semua pengiriman dengan mudah dan efisien"
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

    @if(session('error'))
        <div class="error-alert fade-in-up delay-100" data-aos="fade-down">
            <div class="alert-content">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <span class="alert-text">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <x-admin-responsive-grid class="stats-section auto-fit" :delay="300">
        <x-admin-stat-card 
            icon="fas fa-box"
            :value="$orders->total()"
            label="Total Pesanan"
            iconType="primary"
            :delay="400"
        />
        <x-admin-stat-card 
            icon="fas fa-clock"
            :value="$orders->where('status', 'diproses')->count()"
            label="Diproses"
            iconType="warning"
            :delay="500"
        />
        <x-admin-stat-card 
            icon="fas fa-truck"
            :value="$orders->where('status', 'dikirim')->count()"
            label="Dikirim"
            iconType="info"
            :delay="600"
        />
        <x-admin-stat-card 
            icon="fas fa-user-check"
            :value="$availableDrivers->count()"
            label="Kurir Tersedia"
            iconType="success"
            :delay="700"
        />
    </x-admin-responsive-grid>

    <!-- Orders Table -->
    <x-admin-content-card 
        title="Daftar Pesanan" 
        icon="fas fa-list" 
        :delay="800"
    >
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
                            <th class="table-header-cell">Pesanan</th>
                            <th class="table-header-cell">Customer</th>
                            <th class="table-header-cell">Kurir</th>
                            <th class="table-header-cell">Status</th>
                            <th class="table-header-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="table-row fade-in-up delay-{{ $loop->index * 100 }}">
                            <td class="table-cell">
                                <div class="order-info">
                                    <span class="order-code">{{ $order->order_code }}</span>
                                    <span class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="customer-info">
                                    <span class="customer-name">{{ $order->recipient_name }}</span>
                                    <span class="customer-phone">{{ $order->recipient_phone }}</span>
                                </div>
                            </td>
                            <td class="table-cell">
                                @if($order->driver)
                                    <div class="driver-info">
                                        <span class="driver-name">{{ $order->driver->name }}</span>
                                        @if($order->driver->vehicle_type)
                                            <span class="vehicle-info">{{ $order->driver->vehicle_type }} {{ $order->driver->vehicle_number }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="no-driver">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td class="table-cell">
                                <span class="status-badge status-{{ $order->status }}">
                                    @if($order->status === 'diproses')
                                        <i class="fas fa-clock"></i>Diproses
                                    @elseif($order->status === 'dikirim')
                                        <i class="fas fa-truck"></i>Dikirim
                                    @else
                                        <i class="fas fa-circle"></i>{{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="table-cell actions">
                                @if(!$order->driver_id)
                                    <button onclick="openAssignModal({{ $order->id }}, '{{ $order->order_code }}')" 
                                            class="action-btn-icon assign" title="Assign Driver">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                @elseif($order->status === 'diproses')
                                    <form action="{{ route('admin.delivery.mark-picked-up', $order) }}" method="POST" class="inline-form" onsubmit="return confirm('Mark pesanan sebagai diambil?')">
                                        @csrf
                                        <button type="submit" class="action-btn-icon pickup" title="Ambil Pesanan">
                                            <i class="fas fa-hand-holding"></i>
                                        </button>
                                    </form>
                                @elseif($order->status === 'dikirim')
                                    <button onclick="openDeliverModal({{ $order->id }}, '{{ $order->order_code }}')" 
                                            class="action-btn-icon deliver" title="Selesai">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.orders.show', $order) }}" class="action-btn-icon view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="table-cell text-center">Tidak ada pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($orders->hasPages())
            <div class="pagination-container mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </x-admin-content-card>
    </div>

    <!-- Assign Driver Modal -->
    <div id="assignModal" class="modal-overlay hidden">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-user-plus"></i>
                    Assign Driver
                </h3>
                <button onclick="closeAssignModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Pilih Driver</label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">-- Pilih Driver --</option>
                            @foreach($availableDrivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->name }} 
                                @if($driver->vehicle_type)
                                    - {{ $driver->vehicle_type }} {{ $driver->vehicle_number }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeAssignModal()" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i>Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deliver Modal -->
    <div id="deliverModal" class="modal-overlay hidden">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Konfirmasi Pengiriman
                </h3>
                <button onclick="closeDeliverModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="deliverForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Foto Bukti Pengiriman</label>
                        <input type="file" name="delivery_photo" accept="image/*" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="delivery_notes" rows="3" class="form-textarea" placeholder="Catatan pengiriman..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeDeliverModal()" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
/* Luxury Delivery Management Page Styles */
.luxury-delivery-management-page {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--light-pink) 0%, var(--pure-white) 100%);
}

/* Error Alert */
.error-alert {
    background: rgba(220, 38, 38, 0.1);
    border: 1px solid rgba(220, 38, 38, 0.2);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin: 1rem 0;
    backdrop-filter: blur(10px);
}

.error-alert .alert-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.error-alert .alert-icon {
    color: #dc2626;
    font-size: 1.25rem;
}

.error-alert .alert-text {
    color: #dc2626;
    font-weight: 500;
}

/* Action Button Success */
.action-btn.success {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.action-btn.success:hover {
    background: rgba(34, 197, 94, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.2);
}

/* Table Specific Styles */
.order-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.order-code {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
}

.order-date {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
}

.customer-phone {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.driver-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.driver-name {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
}

.vehicle-info {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.no-driver {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-style: italic;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.status-diproses {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-badge.status-dikirim {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-badge i {
    font-size: 0.625rem;
}

/* Action Button Icons */
.action-btn-icon.assign {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.action-btn-icon.assign:hover {
    background: rgba(59, 130, 246, 0.2);
}

.action-btn-icon.pickup {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
}

.action-btn-icon.pickup:hover {
    background: rgba(34, 197, 94, 0.2);
}

.action-btn-icon.deliver {
    background: rgba(168, 85, 247, 0.1);
    color: #9333ea;
}

.action-btn-icon.deliver:hover {
    background: rgba(168, 85, 247, 0.2);
}

.action-btn-icon.view {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
}

.action-btn-icon.view:hover {
    background: rgba(107, 114, 128, 0.2);
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay:not(.hidden) {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: var(--pure-white);
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    transform: scale(0.9);
    transition: all 0.3s ease;
}

.modal-overlay:not(.hidden) .modal-container {
    transform: scale(1);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.modal-title i {
    color: var(--primary-pink);
    font-size: 1.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--gray-400);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(107, 114, 128, 0.1);
    color: var(--gray-600);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--gray-200);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.form-select,
.form-input,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
}

.form-select:focus,
.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.btn-success {
    background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
    color: white;
    border: 1px solid #16a34a;
}

.btn-success:hover {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
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

    // Modal functions
    function openAssignModal(orderId, orderCode) {
        document.getElementById('assignForm').action = `/admin/orders/${orderId}/assign-driver`;
        document.getElementById('assignModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openDeliverModal(orderId, orderCode) {
        document.getElementById('deliverForm').action = `/admin/orders/${orderId}/mark-delivered`;
        document.getElementById('deliverModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeliverModal() {
        document.getElementById('deliverModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Make functions global
    window.openAssignModal = openAssignModal;
    window.closeAssignModal = closeAssignModal;
    window.openDeliverModal = openDeliverModal;
    window.closeDeliverModal = closeDeliverModal;

    // Close modals when clicking outside
    document.getElementById('assignModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAssignModal();
        }
    });

    document.getElementById('deliverModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeliverModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAssignModal();
            closeDeliverModal();
        }
    });

    // Table horizontal scroll functionality
    const tableWrapper = document.getElementById('tableWrapper');
    const scrollLeft = document.getElementById('scrollLeft');
    const scrollRight = document.getElementById('scrollRight');

    if (tableWrapper && scrollLeft && scrollRight) {
        function updateScrollButtons() {
            const isScrollable = tableWrapper.scrollWidth > tableWrapper.clientWidth;
            const isAtStart = tableWrapper.scrollLeft === 0;
            const isAtEnd = tableWrapper.scrollLeft >= tableWrapper.scrollWidth - tableWrapper.clientWidth - 1;

            scrollLeft.style.display = isScrollable && !isAtStart ? 'flex' : 'none';
            scrollRight.style.display = isScrollable && !isAtEnd ? 'flex' : 'none';
        }

        scrollLeft.addEventListener('click', () => {
            tableWrapper.scrollBy({ left: -200, behavior: 'smooth' });
        });

        scrollRight.addEventListener('click', () => {
            tableWrapper.scrollBy({ left: 200, behavior: 'smooth' });
        });

        tableWrapper.addEventListener('scroll', updateScrollButtons);
        window.addEventListener('resize', updateScrollButtons);
        
        // Initial check
        updateScrollButtons();
    }
});
</script>
@endsection















