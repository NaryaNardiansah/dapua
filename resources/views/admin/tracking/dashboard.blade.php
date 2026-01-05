@extends('layouts.admin')

@section('content')
<div class="luxury-tracking-dashboard-page">
    <!-- Hero Section -->
    <x-admin-hero 
        icon="fas fa-chart-line"
        title="Tracking Dashboard"
        subtitle="Real-time order tracking and management"
        description="Pantau dan kelola semua pesanan secara real-time dengan dashboard yang canggih"
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

    <!-- Quick Actions Bar -->
    <div class="quick-actions-bar fade-in-up delay-200" data-aos="fade-up">
        <div class="actions-container">
            <div class="action-buttons">
                <button onclick="processNotifications()" class="action-btn info">
                    <i class="fas fa-bell mr-2"></i>Process Notifications
                </button>
            </div>
        </div>
    </div>
    <!-- Statistics Cards -->
    <x-admin-responsive-grid class="stats-section auto-fit" :delay="300">
        <x-admin-stat-card 
            icon="fas fa-shopping-cart"
            :value="$stats['total_orders']"
            label="Total Orders"
            change="+{{ $stats['today_orders'] }} today"
            changeType="positive"
            iconType="primary"
            :delay="400"
        />
        <x-admin-stat-card 
            icon="fas fa-clock"
            :value="$stats['pending_orders'] + $stats['processing_orders'] + $stats['shipping_orders']"
            label="Active Orders"
            change="{{ $stats['pending_orders'] }} pending"
            changeType="warning"
            iconType="warning"
            :delay="500"
        />
        <x-admin-stat-card 
            icon="fas fa-truck"
            :value="$stats['active_drivers']"
            label="Active Drivers"
            change="{{ $stats['active_drivers'] }}/{{ $stats['total_drivers'] }} available"
            changeType="positive"
            iconType="success"
            :delay="600"
        />
        <x-admin-stat-card 
            icon="fas fa-check-circle"
            :value="$stats['on_time_delivery_rate'] . '%'"
            label="On-Time Rate"
            change="{{ $stats['avg_delivery_time'] }} min avg"
            changeType="positive"
            iconType="info"
            :delay="700"
        />
    </x-admin-responsive-grid>

    <div class="tracking-dashboard-grid">
        <!-- Recent Orders -->
        <x-admin-content-card 
            title="Recent Orders" 
            icon="fas fa-list" 
            :delay="800"
            class="recent-orders-section"
        >
            <div class="orders-filter-section">
                <select id="status-filter" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="diproses">Processing</option>
                    <option value="dikirim">Shipping</option>
                    <option value="selesai">Completed</option>
                    <option value="dibatalkan">Cancelled</option>
                </select>
            </div>
            
            <div class="orders-container" id="recent-orders-container">
                @foreach($recentOrders as $order)
                    <div class="order-item fade-in-up delay-{{ $loop->index * 100 }}" data-status="{{ $order->status }}">
                        <div class="order-header">
                            <div class="order-info">
                                <h4 class="order-code">#{{ $order->order_code }}</h4>
                                <span class="status-badge status-{{ $order->status }}">
                                    @if($order->status === 'pending')
                                        <i class="fas fa-clock"></i>Pending
                                    @elseif($order->status === 'diproses')
                                        <i class="fas fa-cog"></i>Processing
                                    @elseif($order->status === 'dikirim')
                                        <i class="fas fa-truck"></i>Shipping
                                    @elseif($order->status === 'selesai')
                                        <i class="fas fa-check-circle"></i>Completed
                                    @else
                                        <i class="fas fa-times-circle"></i>Cancelled
                                    @endif
                                </span>
                            </div>
                            <div class="order-actions">
                                <button onclick="viewOrderTimeline({{ $order->id }})" class="action-btn-icon timeline" title="View Timeline">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="updateOrderStatus({{ $order->id }})" class="action-btn-icon update" title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="customer-info">
                                <span class="customer-name"><i class="fas fa-user mr-1"></i>{{ $order->recipient_name }}</span>
                                <span class="customer-phone"><i class="fas fa-phone mr-1"></i>{{ $order->recipient_phone }}</span>
                                <span class="order-time"><i class="fas fa-clock mr-1"></i>{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            @if($order->driver)
                                <div class="driver-info">
                                    <span class="driver-name">
                                        <i class="fas fa-truck mr-1"></i>Driver: {{ $order->driver->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </x-admin-content-card>

        <!-- Sidebar -->
        <div class="tracking-sidebar">
            <!-- Active Drivers -->
            <x-admin-content-card 
                title="Active Drivers" 
                icon="fas fa-truck" 
                :delay="900"
            >
                <div class="drivers-container" id="active-drivers-container">
                    @foreach($activeDrivers as $driver)
                        <div class="driver-item fade-in-up delay-{{ $loop->index * 100 }}">
                            <div class="driver-avatar">
                                @if($driver->photo)
                                    <img src="{{ Storage::url($driver->photo) }}" alt="Driver Photo" class="avatar-image">
                                @else
                                    <i class="fas fa-user avatar-placeholder-icon"></i>
                                @endif
                            </div>
                            <div class="driver-info">
                                <span class="driver-name">{{ $driver->name }}</span>
                                <span class="vehicle-info">{{ $driver->vehicle_type }} {{ $driver->vehicle_number }}</span>
                            </div>
                            <div class="driver-stats">
                                <span class="orders-count">{{ $driver->active_orders_count }}</span>
                                <span class="orders-label">orders</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin-content-card>

            <!-- Recent Activity -->
            <x-admin-content-card 
                title="Recent Activity" 
                icon="fas fa-history" 
                :delay="1000"
            >
                <div class="activity-container" id="recent-activity-container">
                    @foreach($recentTimeline as $activity)
                        <div class="activity-item fade-in-up delay-{{ $loop->index * 100 }}">
                            <div class="activity-icon" style="background-color: {{ $activity->color }}">
                                <i class="{{ $activity->icon }}"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title">{{ $activity->title }}</p>
                                <p class="activity-order">{{ $activity->order->order_code }}</p>
                                <p class="activity-time">{{ $activity->timestamp->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin-content-card>

            <!-- Performance Metrics -->
            <x-admin-content-card 
                title="Performance Metrics" 
                icon="fas fa-chart-bar" 
                :delay="1100"
            >
                <div class="metrics-container">
                    <div class="metric-item">
                        <span class="metric-label">Orders (7 days)</span>
                        <span class="metric-value">{{ $performanceMetrics['orders_last_7_days'] }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Revenue (7 days)</span>
                        <span class="metric-value">Rp {{ number_format($performanceMetrics['revenue_last_7_days']) }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Avg Order Value</span>
                        <span class="metric-value">Rp {{ number_format($performanceMetrics['avg_order_value']) }}</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Customer Rating</span>
                        <span class="metric-value">{{ number_format($performanceMetrics['customer_satisfaction'], 1) }}/5</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Cancellation Rate</span>
                        <span class="metric-value error">{{ $performanceMetrics['cancellation_rate'] }}%</span>
                    </div>
                </div>
            </x-admin-content-card>

            <!-- Notification Stats -->
            <x-admin-content-card 
                title="Notification Stats" 
                icon="fas fa-bell" 
                :delay="1200"
            >
                <div class="notification-stats-container" id="notification-stats-container">
                    <div class="notification-stat-item">
                        <span class="stat-label">Total</span>
                        <span class="stat-value" id="notification-total">{{ $notificationStats['total'] ?? 0 }}</span>
                    </div>
                    <div class="notification-stat-item">
                        <span class="stat-label">Sent</span>
                        <span class="stat-value success" id="notification-sent">{{ $notificationStats['sent'] ?? 0 }}</span>
                    </div>
                    <div class="notification-stat-item">
                        <span class="stat-label">Delivered</span>
                        <span class="stat-value info" id="notification-delivered">{{ $notificationStats['delivered'] ?? 0 }}</span>
                    </div>
                    <div class="notification-stat-item">
                        <span class="stat-label">Failed</span>
                        <span class="stat-value error" id="notification-failed">{{ $notificationStats['failed'] ?? 0 }}</span>
                    </div>
                    <div class="notification-stat-item">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value warning" id="notification-pending">{{ $notificationStats['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </x-admin-content-card>
        </div>
    </div>
    </div>
</div>

    <!-- Order Status Update Modal -->
    <div id="statusModal" class="modal-overlay hidden">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Update Order Status
                </h3>
                <button onclick="hideStatusModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="statusForm">
                <input type="hidden" id="order-id" name="order_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Status:</label>
                        <select name="status" id="status-select" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="diproses">Processing</option>
                            <option value="dikirim">Shipping</option>
                            <option value="selesai">Completed</option>
                            <option value="dibatalkan">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Driver:</label>
                        <select name="driver_id" id="driver-select" class="form-select">
                            <option value="">Select Driver</option>
                            @foreach($activeDrivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} - {{ $driver->vehicle_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes:</label>
                        <textarea name="notes" rows="3" class="form-textarea" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="hideStatusModal()" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Order Timeline Modal -->
    <div id="timelineModal" class="modal-overlay hidden">
        <div class="modal-container timeline-modal">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-history"></i>
                    Order Timeline
                </h3>
                <button onclick="hideTimelineModal()" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="timeline-content">
                    <!-- Timeline content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

@endsection

<style>
/* Luxury Tracking Dashboard Page Styles */
.luxury-tracking-dashboard-page {
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

/* Tracking Dashboard Grid */
.tracking-dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.tracking-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Recent Orders Section */
.recent-orders-section {
    grid-column: 1;
}

.orders-filter-section {
    margin-bottom: 1.5rem;
}

.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
    cursor: pointer;
}

.form-select:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.orders-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.order-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
    border-color: rgba(236, 72, 153, 0.2);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.order-code {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
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

.status-badge.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.status-badge.status-diproses {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.status-badge.status-dikirim {
    background: rgba(168, 85, 247, 0.1);
    color: #9333ea;
    border: 1px solid rgba(168, 85, 247, 0.2);
}

.status-badge.status-selesai {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-badge.status-dibatalkan {
    background: rgba(220, 38, 38, 0.1);
    color: #dc2626;
    border: 1px solid rgba(220, 38, 38, 0.2);
}

.status-badge i {
    font-size: 0.625rem;
}

.order-actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.action-btn-icon.timeline {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.action-btn-icon.timeline:hover {
    background: rgba(59, 130, 246, 0.2);
}

.action-btn-icon.update {
    background: rgba(236, 72, 153, 0.1);
    color: var(--primary-pink);
}

.action-btn-icon.update:hover {
    background: rgba(236, 72, 153, 0.2);
}

.order-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.customer-info {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.customer-name,
.customer-phone,
.order-time {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.driver-info {
    margin-top: 0.5rem;
}

.driver-name {
    font-size: 0.875rem;
    color: #2563eb;
    font-weight: 500;
}

/* Driver Items */
.drivers-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.driver-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.driver-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.15);
}

.driver-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    background: rgba(236, 72, 153, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-icon {
    color: var(--primary-pink);
    font-size: 1.25rem;
}

.driver-info {
    flex: 1;
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

.driver-stats {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.orders-count {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 1rem;
}

.orders-label {
    font-size: 0.75rem;
    color: var(--gray-500);
}

/* Activity Items */
.activity-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-title {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.875rem;
    margin: 0 0 0.25rem 0;
}

.activity-order {
    font-size: 0.75rem;
    color: var(--gray-600);
    margin: 0 0 0.25rem 0;
}

.activity-time {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin: 0;
}

/* Metrics Container */
.metrics-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.metric-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
}

.metric-item:last-child {
    border-bottom: none;
}

.metric-label {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.metric-value {
    font-weight: 600;
    color: var(--gray-900);
}

.metric-value.error {
    color: #dc2626;
}

/* Notification Stats */
.notification-stats-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
}

.notification-stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--gray-600);
}

.stat-value {
    font-weight: 600;
    color: var(--gray-900);
}

.stat-value.success {
    color: #16a34a;
}

.stat-value.info {
    color: #2563eb;
}

.stat-value.error {
    color: #dc2626;
}

.stat-value.warning {
    color: #d97706;
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

.modal-container.timeline-modal {
    max-width: 800px;
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

.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: var(--pure-white);
    resize: vertical;
    min-height: 80px;
}

.form-textarea:focus {
    outline: none;
    border-color: var(--primary-pink);
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
}

.btn {
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

.btn-primary {
    background: linear-gradient(135deg, var(--primary-pink) 0%, var(--secondary-pink) 100%);
    color: white;
    border: 1px solid var(--primary-pink);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--secondary-pink) 0%, var(--primary-pink) 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.3);
}

.btn-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.btn-secondary:hover {
    background: rgba(107, 114, 128, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.2);
}

/* Real-time update indicator */
.realtime-indicator {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .tracking-dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .tracking-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-actions {
        align-self: flex-end;
    }
    
    .customer-info {
        flex-direction: column;
        gap: 0.5rem;
    }
    
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

@media (max-width: 480px) {
    .order-item {
        padding: 1rem;
    }
    
    .driver-item {
        padding: 0.75rem;
    }
    
    .modal-container {
        width: 100%;
        margin: 0.5rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 0.75rem;
    }
}
</style>

<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
let realtimeInterval;

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        mirror: false
    });

    // Start real-time updates
    startRealTimeUpdates();
    
    // Initialize notification stats on page load
    updateNotificationStats();
    
    // Initialize status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        filterOrdersByStatus(this.value);
    });
    
    // Initialize status form
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateOrderStatus();
    });
});

function startRealTimeUpdates() {
    // Update every 30 seconds
    realtimeInterval = setInterval(function() {
        fetch('/admin/tracking/realtime-updates')
            .then(response => response.json())
            .then(data => {
                updateDashboard(data);
                // Update notification stats if included in response
                if (data.notification_stats) {
                    updateNotificationStats(data.notification_stats);
                } else {
                    updateNotificationStats();
                }
            })
            .catch(error => {
                console.error('Error fetching real-time updates:', error);
            });
    }, 30000);
}

function updateDashboard(data) {
    // Update statistics
    if (document.getElementById('total-orders')) {
    document.getElementById('total-orders').textContent = data.stats.total_orders;
    }
    if (document.getElementById('active-orders')) {
    document.getElementById('active-orders').textContent = 
        data.stats.pending_orders + data.stats.processing_orders + data.stats.shipping_orders;
    }
    if (document.getElementById('active-drivers')) {
    document.getElementById('active-drivers').textContent = data.stats.active_drivers;
    }
    if (document.getElementById('on-time-rate')) {
    document.getElementById('on-time-rate').textContent = data.stats.on_time_delivery_rate + '%';
    }
    
    // Update recent orders
    if (data.recent_orders) {
    updateRecentOrders(data.recent_orders);
    }
    
    // Update recent activity
    if (data.recent_timeline) {
    updateRecentActivity(data.recent_timeline);
    }
}

function updateRecentOrders(orders) {
    const container = document.getElementById('recent-orders-container');
    container.innerHTML = '';
    
    orders.forEach(order => {
        const orderElement = document.createElement('div');
        orderElement.className = 'order-item fade-in-up';
        orderElement.setAttribute('data-status', order.status);
        
        orderElement.innerHTML = `
            <div class="order-header">
                <div class="order-info">
                    <h4 class="order-code">#${order.order_code}</h4>
                    <span class="status-badge status-${order.status}">
                        ${getStatusIcon(order.status)}${capitalizeFirst(order.status)}
                    </span>
                </div>
                <div class="order-actions">
                    <button onclick="viewOrderTimeline(${order.id})" class="action-btn-icon timeline" title="View Timeline">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="updateOrderStatus(${order.id})" class="action-btn-icon update" title="Update Status">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
            <div class="order-details">
                <div class="customer-info">
                    <span class="customer-name"><i class="fas fa-user mr-1"></i>${order.recipient_name}</span>
                    <span class="customer-phone"><i class="fas fa-phone mr-1"></i>${order.recipient_phone}</span>
                    <span class="order-time"><i class="fas fa-clock mr-1"></i>${new Date(order.created_at).toLocaleTimeString()}</span>
                </div>
                ${order.driver ? `
                    <div class="driver-info">
                        <span class="driver-name">
                            <i class="fas fa-truck mr-1"></i>Driver: ${order.driver.name}
                        </span>
                    </div>
                ` : ''}
            </div>
        `;
        
        container.appendChild(orderElement);
    });
}

function updateRecentActivity(activities) {
    const container = document.getElementById('recent-activity-container');
    container.innerHTML = '';
    
    activities.forEach(activity => {
        const activityElement = document.createElement('div');
        activityElement.className = 'activity-item fade-in-up';
        
        activityElement.innerHTML = `
            <div class="activity-icon" style="background-color: ${activity.color}">
                <i class="${activity.icon}"></i>
            </div>
            <div class="activity-content">
                <p class="activity-title">${activity.title}</p>
                <p class="activity-order">${activity.order.order_code}</p>
                <p class="activity-time">${new Date(activity.timestamp).toLocaleString()}</p>
            </div>
        `;
        
        container.appendChild(activityElement);
    });
}

function updateNotificationStats(stats = null) {
    if (stats) {
        // Update with provided stats
        if (document.getElementById('notification-total')) {
            document.getElementById('notification-total').textContent = stats.total || 0;
        }
        if (document.getElementById('notification-sent')) {
            document.getElementById('notification-sent').textContent = stats.sent || 0;
        }
        if (document.getElementById('notification-delivered')) {
            document.getElementById('notification-delivered').textContent = stats.delivered || 0;
        }
        if (document.getElementById('notification-failed')) {
            document.getElementById('notification-failed').textContent = stats.failed || 0;
        }
        if (document.getElementById('notification-pending')) {
            document.getElementById('notification-pending').textContent = stats.pending || 0;
        }
    } else {
        // Fetch notification stats from API
        fetch('/admin/tracking/notifications/stats')
            .then(response => response.json())
            .then(data => {
                if (document.getElementById('notification-total')) {
                    document.getElementById('notification-total').textContent = data.total || 0;
                }
                if (document.getElementById('notification-sent')) {
                    document.getElementById('notification-sent').textContent = data.sent || 0;
                }
                if (document.getElementById('notification-delivered')) {
                    document.getElementById('notification-delivered').textContent = data.delivered || 0;
                }
                if (document.getElementById('notification-failed')) {
                    document.getElementById('notification-failed').textContent = data.failed || 0;
                }
                if (document.getElementById('notification-pending')) {
                    document.getElementById('notification-pending').textContent = data.pending || 0;
                }
            })
            .catch(error => {
                console.error('Error fetching notification stats:', error);
            });
    }
}

function filterOrdersByStatus(status) {
    const orderItems = document.querySelectorAll('.order-item');
    
    orderItems.forEach(item => {
        if (status === '' || item.getAttribute('data-status') === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function updateOrderStatus(orderId) {
    document.getElementById('order-id').value = orderId;
    document.getElementById('statusModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function viewOrderTimeline(orderId) {
    fetch(`/admin/tracking/orders/${orderId}/timeline`)
        .then(response => response.json())
        .then(data => {
            displayTimeline(data.timeline);
            document.getElementById('timelineModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            console.error('Error fetching timeline:', error);
        });
}

function hideTimelineModal() {
    document.getElementById('timelineModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function displayTimeline(timeline) {
    const container = document.getElementById('timeline-content');
    container.innerHTML = '';
    
    timeline.forEach(item => {
        const timelineElement = document.createElement('div');
        timelineElement.className = 'flex items-start space-x-4 mb-6';
        
        timelineElement.innerHTML = `
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white" 
                 style="background-color: ${item.color}">
                <i class="${item.icon}"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900 mb-1">${item.title}</h4>
                <p class="text-gray-600 mb-2">${item.description}</p>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    ${new Date(item.timestamp).toLocaleString()}
                    ${item.user ? `
                        <span class="ml-2 px-2 py-1 bg-gray-100 rounded text-xs">
                            ${item.user.name} (${item.user.is_admin ? 'Admin' : item.user.is_driver ? 'Driver' : 'Customer'})
                        </span>
                    ` : ''}
                </div>
            </div>
        `;
        
        container.appendChild(timelineElement);
    });
}

function updateOrderStatus() {
    const formData = new FormData(document.getElementById('statusForm'));
    const orderId = formData.get('order_id');
    
    fetch(`/admin/tracking/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: formData.get('status'),
            driver_id: formData.get('driver_id'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideStatusModal();
            showNotification('Order status updated successfully', 'success');
            refreshDashboard();
        } else {
            showNotification(data.message || 'Failed to update order status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function processNotifications() {
    fetch('/admin/tracking/notifications/process', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Update notification stats after processing
            updateNotificationStats();
            // Refresh dashboard
            refreshDashboard();
        } else {
            showNotification(data.message || 'Failed to process notifications', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function refreshDashboard() {
    fetch('/admin/tracking/realtime-updates')
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
            showNotification('Dashboard refreshed', 'success');
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showNotification('Failed to refresh dashboard', 'error');
        });
}

function getStatusIcon(status) {
    const icons = {
        'pending': '<i class="fas fa-clock"></i>',
        'diproses': '<i class="fas fa-cog"></i>',
        'dikirim': '<i class="fas fa-truck"></i>',
        'selesai': '<i class="fas fa-check-circle"></i>',
        'dibatalkan': '<i class="fas fa-times-circle"></i>'
    };
    return icons[status] || '<i class="fas fa-circle"></i>';
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modals when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideStatusModal();
    }
});

document.getElementById('timelineModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideTimelineModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideStatusModal();
        hideTimelineModal();
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (realtimeInterval) {
        clearInterval(realtimeInterval);
    }
});
</script>

@push('scripts')
<script>
let realtimeInterval;

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Start real-time updates
    startRealTimeUpdates();
    
    // Initialize status filter
    document.getElementById('status-filter').addEventListener('change', function() {
        filterOrdersByStatus(this.value);
    });
    
    // Initialize status form
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateOrderStatus();
    });
});

function startRealTimeUpdates() {
    // Update every 30 seconds
    realtimeInterval = setInterval(function() {
        fetch('/admin/tracking/realtime-updates')
            .then(response => response.json())
            .then(data => {
                updateDashboard(data);
                // Update notification stats if included in response
                if (data.notification_stats) {
                    updateNotificationStats(data.notification_stats);
                } else {
                    updateNotificationStats();
                }
            })
            .catch(error => {
                console.error('Error fetching real-time updates:', error);
            });
    }, 30000);
}

function updateDashboard(data) {
    // Update statistics
    if (document.getElementById('total-orders')) {
    document.getElementById('total-orders').textContent = data.stats.total_orders;
    }
    if (document.getElementById('active-orders')) {
    document.getElementById('active-orders').textContent = 
        data.stats.pending_orders + data.stats.processing_orders + data.stats.shipping_orders;
    }
    if (document.getElementById('active-drivers')) {
    document.getElementById('active-drivers').textContent = data.stats.active_drivers;
    }
    if (document.getElementById('on-time-rate')) {
    document.getElementById('on-time-rate').textContent = data.stats.on_time_delivery_rate + '%';
    }
    
    // Update recent orders
    if (data.recent_orders) {
    updateRecentOrders(data.recent_orders);
    }
    
    // Update recent activity
    if (data.recent_timeline) {
    updateRecentActivity(data.recent_timeline);
    }
}

function updateRecentOrders(orders) {
    const container = document.getElementById('recent-orders-container');
    container.innerHTML = '';
    
    orders.forEach(order => {
        const orderElement = document.createElement('div');
        orderElement.className = 'border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow order-item';
        orderElement.setAttribute('data-status', order.status);
        
        orderElement.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <h4 class="font-semibold text-gray-900">#${order.order_code}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full ${getStatusClass(order.status)}">
                            ${capitalizeFirst(order.status)}
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <div class="flex items-center space-x-4">
                            <span><i class="fas fa-user mr-1"></i> ${order.recipient_name}</span>
                            <span><i class="fas fa-phone mr-1"></i> ${order.recipient_phone}</span>
                            <span><i class="fas fa-clock mr-1"></i> ${new Date(order.created_at).toLocaleTimeString()}</span>
                        </div>
                        ${order.driver ? `
                            <div class="mt-1">
                                <span class="text-blue-600">
                                    <i class="fas fa-truck mr-1"></i> Driver: ${order.driver.name}
                                </span>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="viewOrderTimeline(${order.id})" 
                            class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye mr-1"></i>Timeline
                    </button>
                    <button onclick="updateOrderStatus(${order.id})" 
                            class="text-pink-600 hover:text-pink-800 text-sm">
                        <i class="fas fa-edit mr-1"></i>Update
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(orderElement);
    });
}

function updateRecentActivity(activities) {
    const container = document.getElementById('recent-activity-container');
    container.innerHTML = '';
    
    activities.forEach(activity => {
        const activityElement = document.createElement('div');
        activityElement.className = 'flex items-start space-x-3';
        
        activityElement.innerHTML = `
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm" 
                 style="background-color: ${activity.color}">
                <i class="${activity.icon}"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">${activity.title}</p>
                <p class="text-xs text-gray-600">${activity.order.order_code}</p>
                <p class="text-xs text-gray-500">${new Date(activity.timestamp).toLocaleString()}</p>
            </div>
        `;
        
        container.appendChild(activityElement);
    });
}

function filterOrdersByStatus(status) {
    const orderItems = document.querySelectorAll('.order-item');
    
    orderItems.forEach(item => {
        if (status === '' || item.getAttribute('data-status') === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function updateOrderStatus(orderId) {
    document.getElementById('order-id').value = orderId;
    document.getElementById('statusModal').classList.remove('hidden');
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function viewOrderTimeline(orderId) {
    fetch(`/admin/tracking/orders/${orderId}/timeline`)
        .then(response => response.json())
        .then(data => {
            displayTimeline(data.timeline);
            document.getElementById('timelineModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching timeline:', error);
        });
}

function hideTimelineModal() {
    document.getElementById('timelineModal').classList.add('hidden');
}

function displayTimeline(timeline) {
    const container = document.getElementById('timeline-content');
    container.innerHTML = '';
    
    timeline.forEach(item => {
        const timelineElement = document.createElement('div');
        timelineElement.className = 'flex items-start space-x-4 mb-6';
        
        timelineElement.innerHTML = `
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white" 
                 style="background-color: ${item.color}">
                <i class="${item.icon}"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900 mb-1">${item.title}</h4>
                <p class="text-gray-600 mb-2">${item.description}</p>
                <div class="flex items-center text-sm text-gray-500">
                    <i class="fas fa-clock mr-1"></i>
                    ${new Date(item.timestamp).toLocaleString()}
                    ${item.user ? `
                        <span class="ml-2 px-2 py-1 bg-gray-100 rounded text-xs">
                            ${item.user.name} (${item.user.is_admin ? 'Admin' : item.user.is_driver ? 'Driver' : 'Customer'})
                        </span>
                    ` : ''}
                </div>
            </div>
        `;
        
        container.appendChild(timelineElement);
    });
}

function updateOrderStatus() {
    const formData = new FormData(document.getElementById('statusForm'));
    const orderId = formData.get('order_id');
    
    fetch(`/admin/tracking/orders/${orderId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: formData.get('status'),
            driver_id: formData.get('driver_id'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideStatusModal();
            showNotification('Order status updated successfully', 'success');
            refreshDashboard();
        } else {
            showNotification(data.message || 'Failed to update order status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function processNotifications() {
    fetch('/admin/tracking/notifications/process', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Update notification stats after processing
            updateNotificationStats();
            // Refresh dashboard
            refreshDashboard();
        } else {
            showNotification(data.message || 'Failed to process notifications', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function refreshDashboard() {
    fetch('/admin/tracking/realtime-updates')
        .then(response => response.json())
        .then(data => {
            updateDashboard(data);
            showNotification('Dashboard refreshed', 'success');
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showNotification('Failed to refresh dashboard', 'error');
        });
}

function getStatusClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'diproses': 'bg-blue-100 text-blue-800',
        'dikirim': 'bg-purple-100 text-purple-800',
        'selesai': 'bg-green-100 text-green-800',
        'dibatalkan': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (realtimeInterval) {
        clearInterval(realtimeInterval);
    }
});
</script>
@endpush















