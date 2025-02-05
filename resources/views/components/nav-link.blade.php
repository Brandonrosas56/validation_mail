@props(['active'])

@php
$classes = ($active ?? false)
            ? 'w-32 justify-center inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-medium leading-5 text-primary focus:outline-none focus:border-primary transition duration-150 ease-in-out'
            : 'w-32 justify-center inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-color-info hover:text-color-info hover:border-color-info focus:outline-none focus:text-colo-info focus:border-color-info transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
