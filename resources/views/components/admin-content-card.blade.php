@props([
    'title' => 'Title',
    'icon' => 'fas fa-star',
    'delay' => 0
])

<div class="admin-content-card" data-aos="fade-up" data-aos-delay="{{ $delay }}">
    <div class="content-card-header">
        <div class="content-card-title-wrapper">
            <div class="content-card-icon">
                <i class="{{ $icon }}"></i>
            </div>
            <h3 class="content-card-title">{{ $title }}</h3>
        </div>
        @if(isset($actions))
        <div class="content-card-actions">
            {{ $actions }}
        </div>
        @endif
    </div>
    <div class="content-card-body">
        {{ $slot }}
    </div>
</div>

<style>
.admin-content-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(236, 72, 153, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.content-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
    border-bottom: 1px solid rgba(236, 72, 153, 0.1);
}

.content-card-title-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.content-card-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.125rem;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.content-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.content-card-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.content-card-body {
    padding: 2rem;
}

@media (max-width: 768px) {
    .content-card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .content-card-actions {
        justify-content: center;
    }
    
    .content-card-body {
        padding: 1.5rem;
    }
}
</style>

