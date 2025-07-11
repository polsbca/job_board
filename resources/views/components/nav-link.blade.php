@props(['active' => false, 'href' => '#'])

@php
$classes = 'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200';
$classes .= $active 
    ? ' bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-100' 
    : ' text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
