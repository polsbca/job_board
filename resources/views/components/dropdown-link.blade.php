@props(['href' => '#'])

<a {{ $attributes->merge(['class' => 'dropdown-item']) }} href="{{ $href }}">
    {{ $slot }}
</a>
