@props(['active' => false])

@php
    // We add more padding (px-6) to the link itself.
    $baseClasses = 'w-full flex items-center px-6 py-3 text-left focus:outline-none transition-colors duration-200';

    // Active and hover states will now have the same background color.
    $stateClasses = $active ? 'bg-menu-hover text-white font-semibold' : 'text-gray-300 hover:text-white hover:bg-menu-hover';
@endphp

<a {{ $attributes->merge(['class' => "$baseClasses $stateClasses"]) }}>
    {{ $slot }}
</a>
