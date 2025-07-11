@props([
    'href' => '#',
    'active' => false,
])

@php
    $classes = $active
        ? 'w-full text-start px-4 py-2 bg-primary text-white'
        : 'w-full text-start px-4 py-2 text-gray-700 hover:bg-light';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
