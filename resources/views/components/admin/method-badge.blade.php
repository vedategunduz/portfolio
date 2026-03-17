@props([
    'method', // GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS
])

@php
    $m = strtoupper((string) $method);
    $classes = match($m) {
        'GET' => 'bg-emerald-500/20 text-emerald-800 dark:text-emerald-300',
        'POST' => 'bg-[#D62113]/20 text-[#D62113]',
        'PUT', 'PATCH' => 'bg-blue-500/20 text-blue-800 dark:text-blue-300',
        'DELETE' => 'bg-red-500/20 text-red-800 dark:text-red-300',
        'HEAD', 'OPTIONS' => 'bg-[#706f6c]/20 text-[#4a4946] dark:text-[#b0afac]',
        default => 'bg-[#706f6c]/20 text-[#4a4946] dark:text-[#b0afac]',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex px-2 py-0.5 text-xs font-medium rounded-sm ' . $classes]) }}>
    {{ $m ?: '—' }}
</span>
