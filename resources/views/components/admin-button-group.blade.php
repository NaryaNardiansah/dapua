@props([
    'class' => ''
])

<div class="admin-button-group {{ $class }}">
    {{ $slot }}
</div>

<style>
.admin-button-group {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .admin-button-group {
        justify-content: center;
        width: 100%;
    }
    
    .admin-button-group .btn {
        flex: 1;
        min-width: 120px;
    }
}
</style>

