@props([
    'variant' => 'primary', // primary | secondary
    'type' => 'button',
    'href' => null,
    'tag' => null,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-sm transition-colors';
    $classes = match($variant) {
        'primary' => $base . ' bg-[#D62113] text-white hover:bg-[#b81a0f]',
        'secondary' => $base . ' border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]',
        default => $base,
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
