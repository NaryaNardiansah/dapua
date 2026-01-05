@props([
    'icon' => 'fas fa-star',
    'value' => '0',
    'label' => 'Label',
    'change' => '',
    'changeType' => 'neutral',
    'iconType' => 'primary',
    'delay' => 0
])

<div class="admin-stat-card" data-aos="fade-up" data-aos-delay="{{ $delay }}">
    <div class="stat-icon-wrapper stat-icon-{{ $iconType }}">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-content-wrapper">
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
        @if($change)
        <div class="stat-change stat-change-{{ $changeType }}">
            {{ $change }}
        </div>
        @endif
    </div>
</div>

<style>
.admin-stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(236, 72, 153, 0.1);
    transition: all 0.3s ease;
}

.admin-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(236, 72, 153, 0.15);
}

.stat-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon-primary {
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
}

.stat-icon-success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.stat-icon-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.stat-icon-info {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
}

.stat-content-wrapper {
    flex: 1;
    min-width: 0;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-change {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    display: inline-block;
}

.stat-change-positive {
    background: rgba(16, 185, 129, 0.1);
    color: #059669;
}

.stat-change-negative {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.stat-change-warning {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
}

.stat-change-info {
    background: rgba(59, 130, 246, 0.1);
    color: #2563eb;
}

.stat-change-neutral {
    background: rgba(107, 114, 128, 0.1);
    color: #4b5563;
}

@media (max-width: 768px) {
    .admin-stat-card {
        padding: 1rem;
    }
    
    .stat-icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
}
</style>

