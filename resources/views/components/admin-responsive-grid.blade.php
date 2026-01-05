@props([
    'delay' => 0,
    'class' => ''
])

<div class="admin-responsive-grid {{ $class }}" data-aos="fade-up" data-aos-delay="{{ $delay }}">
    {{ $slot }}
</div>

<style>
.admin-responsive-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.admin-responsive-grid.auto-fit {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
}

@media (max-width: 768px) {
    .admin-responsive-grid.auto-fit {
        grid-template-columns: 1fr;
    }
}
</style>

