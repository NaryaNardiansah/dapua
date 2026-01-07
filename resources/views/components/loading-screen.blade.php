@props(['title', 'subtitle'])

<div id="luxuryLoader" class="luxury-loader">
    <div class="loader-container">
        <!-- Floating Particles -->
        <div class="loader-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>

        <!-- Decorative Elements -->
        <div class="loader-decoration">
            <div class="decoration-line"></div>
            <div class="decoration-line"></div>
            <div class="decoration-line"></div>
        </div>

        <!-- Animated Logo -->
        <div class="loader-logo">
            <div class="logo-ring"></div>
            <div class="logo-inner">
                <i class="fas fa-utensils logo-icon"></i>
            </div>
        </div>

        <!-- Loading Text -->
        <div class="loader-text">{{ $title ?? config('app.name', 'Dapur Sakura') }}</div>
        @if(isset($subtitle))
            <div class="loader-subtitle">{{ $subtitle }}</div>
        @endif

        <!-- Progress Bar -->
        <div class="loader-progress">
            <div class="progress-bar"></div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('luxuryLoader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 800);
            }, 1000);
        }
    });
</script>