<div class="d-flex align-items-center">
    <!-- Logo Image -->
    <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name', 'Job Board') }} Logo" class="h-8 w-auto">
    
    <!-- Logo Text (Optional) -->
    <span class="ms-2 text-xl font-bold text-gray-800 dark:text-gray-200">
        {{ config('app.name', 'Job Board') }}
    </span>
</div>

@push('styles')
<style>
    .h-8 {
        height: 2rem;
    }
    .w-auto {
        width: auto;
    }
    .ms-2 {
        margin-left: 0.5rem;
    }
    .text-xl {
        font-size: 1.25rem;
        line-height: 1.75rem;
    }
    .font-bold {
        font-weight: 700;
    }
    .text-gray-800 {
        --tw-text-opacity: 1;
        color: rgb(31 41 55 / var(--tw-text-opacity));
    }
    .dark .dark\:text-gray-200 {
        --tw-text-opacity: 1;
        color: rgb(229 231 235 / var(--tw-text-opacity));
    }
</style>
@endpush
