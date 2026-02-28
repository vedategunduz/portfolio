@props([
    'href' => '#',
    'variant' => 'primary', // primary, secondary
    'size' => 'md', // sm, md, lg
])

@php
    $baseClasses = 'inline-block rounded-sm font-medium transition-all duration-300';

    $sizeClasses = [
        'sm' => 'px-4 py-2 text-xs',
        'md' => 'px-8 py-4 text-sm',
        'lg' => 'px-10 py-5 text-base',
    ];

    $variantClasses = [
        'primary' => 'bg-[#D62113] text-white hover:bg-[#b81a0f] hover:shadow-2xl hover:shadow-[#D62113]/50 hover:scale-105',
        'secondary' => 'border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#D62113] hover:text-[#D62113] hover:shadow-lg',
        'outline' => 'border-2 border-[#D62113] text-[#D62113] hover:bg-[#D62113] hover:text-white hover:scale-105',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant]]) }}>
    {{ $slot }}
</a>

