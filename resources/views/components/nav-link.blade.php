@props(['active'])

@php
$classes = ($active ?? false)
            ? 'relative inline-flex items-center px-2 pt-1 text-sm font-medium leading-5 text-brand-800 transition'
            : 'relative inline-flex items-center px-2 pt-1 text-sm font-medium leading-5 text-brand-600 hover:text-brand-800 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="relative">
        {{ $slot }}
        <span class="absolute -bottom-2 left-0 w-full h-0.5 bg-brand-600 origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300" aria-hidden="true"></span>
    </span>
</a>
