@props([
    'method' => 'POST',
    'action' => '#',
    'enctype' => 'application/x-www-form-urlencoded',
    'delay' => 200,
    'title' => null,
    'icon' => null,
    'description' => null,
    'actions' => null
])

{{-- Admin Responsive Form Component --}}
<div class="form-container fade-in-up" data-aos="fade-up" data-aos-delay="{{ $delay }}">
    @if(isset($title))
        <div class="form-header">
            <h2 class="form-title">
                @if(isset($icon))
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $title }}
            </h2>
            @if(isset($description))
                <p class="form-description">{{ $description }}</p>
            @endif
        </div>
    @endif
    <form method="{{ $method }}" action="{{ $action }}" 
          class="responsive-form {{ $attributes->get('class', '') }}" 
          id="{{ $attributes->get('id') }}"
          enctype="{{ $enctype }}"
          {{ $attributes->except(['class', 'id']) }}>
        @csrf
        @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
            @method(strtoupper($method))
        @endif
        
        <div class="form-grid">
            {{ $slot }}
        </div>
        
        @if(isset($actions))
            <div class="form-actions">
                {!! $actions !!}
            </div>
        @endif
    </form>
</div>

<style>
    .form-container {
        background: var(--pure-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-200);
        margin-bottom: 2rem;
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .form-title i {
        color: var(--primary-pink);
    }

    .form-description {
        color: var(--gray-600);
        font-size: 0.875rem;
        margin: 0;
    }

    .responsive-form {
        width: 100%;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1rem;
        border-top: 1px solid var(--gray-200);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: var(--pure-white);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-pink);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        font-size: 0.875rem;
        background: var(--pure-white);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary-pink);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: var(--pure-white);
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-pink);
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.1);
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-pink);
    }

    .form-radio {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-radio input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary-pink);
    }

    .form-error {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-help {
        color: var(--gray-500);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column;
            gap: 0.75rem;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .form-container {
            padding: 1rem;
        }

        .form-header {
            margin-bottom: 1.5rem;
        }

        .form-title {
            font-size: 1.125rem;
        }

        .form-grid {
            gap: 0.75rem;
        }

        .form-actions {
            gap: 0.5rem;
        }
    }
</style>










