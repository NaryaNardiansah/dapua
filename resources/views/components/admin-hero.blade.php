@props([
    'icon' => 'fas fa-star',
    'title' => 'Title',
    'subtitle' => 'Subtitle',
    'description' => 'Description',
    'showCircle' => false
])

<div class="admin-hero-section" data-aos="fade-down">
    <div class="hero-content-wrapper">
        @if($showCircle)
        <div class="hero-circle-decoration"></div>
        @endif
        <div class="hero-icon-wrapper">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="hero-text-content">
            <h1 class="hero-title">
                {{ $title }}
            </h1>
            <p class="hero-subtitle">{{ $subtitle }}</p>
            @if($description)
            <p class="hero-description">{{ $description }}</p>
            @endif
        </div>
    </div>
</div>

<style>
.admin-hero-section {
    background: linear-gradient(135deg, #ec4899 0%, #f472b6 50%, #f9a8d4 100%);
    padding: 3rem 2rem;
    margin: -2rem -2rem 2rem -2rem;
    border-radius: 0 0 20px 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(236, 72, 153, 0.2);
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

.hero-circle-decoration {
    position: absolute;
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    z-index: 1;
}

.hero-icon-wrapper {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.hero-icon-wrapper i {
    font-size: 2.5rem;
    color: white;
}

.hero-text-content {
    flex: 1;
    color: white;
    text-align: left;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    color: white;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.hero-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: rgba(255, 255, 255, 0.95);
}

.hero-description {
    font-size: 1rem;
    margin: 0;
    color: rgba(255, 255, 255, 0.85);
    line-height: 1.6;
}

@media (max-width: 768px) {
    .admin-hero-section {
        padding: 2rem 1.5rem;
    }
    
    .hero-content-wrapper {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .hero-icon-wrapper {
        width: 60px;
        height: 60px;
    }
    
    .hero-icon-wrapper i {
        font-size: 2rem;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .hero-description {
        font-size: 0.875rem;
    }
}
</style>

