@props([
    'method', // GET, POST, etc.
])

@php
    $classes = match(strtoupper($method)) {
        'GET' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-400',
        'POST' => 'bg-[#D62113]/15 text-[#D62113]',
        default => 'bg-[#706f6c]/15 text-[#706f6c] dark:text-[#8F8F8B]',
    };
@endphp

<span {{ $attributes->merge(['class' => 'px-2 py-0.5 text-xs font-medium rounded-sm ' . $classes]) }}>
    {{ $method }}
</span>
