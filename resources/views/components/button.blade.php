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
        'primary' => 'bg-[#FF2D20] text-white hover:bg-[#e02915] hover:shadow-2xl hover:shadow-[#FF2D20]/50 hover:scale-105',
        'secondary' => 'border border-[#19140035] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#FF2D20] hover:text-[#FF2D20] hover:shadow-lg',
        'outline' => 'border-2 border-[#FF2D20] text-[#FF2D20] hover:bg-[#FF2D20] hover:text-white hover:scale-105',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant]]) }}>
    {{ $slot }}
</a>
