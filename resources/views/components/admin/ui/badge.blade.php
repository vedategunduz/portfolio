@props([
    'variant' => 'default', // success | danger | warning | info | default
])

@php
    $classes = match($variant) {
        'success' => 'bg-emerald-500/20 text-emerald-800 dark:text-emerald-300',
        'danger' => 'bg-red-500/25 text-red-800 dark:text-red-300',
        'warning' => 'bg-amber-500/20 text-amber-800 dark:text-amber-300',
        'info' => 'bg-blue-500/20 text-blue-800 dark:text-blue-300',
        default => 'bg-[#706f6c]/20 text-[#4a4946] dark:text-[#b0afac]',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex px-2 py-0.5 text-xs font-medium rounded-sm ' . $classes]) }}>
    {{ $slot }}
</span>
