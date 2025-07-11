@props(['align' => 'left', 'width' => '48'])

<div {{ $attributes->merge(['class' => 'dropdown']) }}>
    {{ $trigger ?? '' }}
    <div class="dropdown-menu">
        {{ $content ?? $slot }}
    </div>
</div>
