@props([
    'code', // 200, 404, 500, null
])

@php
    $code = (int) $code;
    $label = $code ? (string) $code : '—';
    $classes = match(true) {
        $code >= 200 && $code < 300 => 'bg-emerald-500/20 text-emerald-800 dark:text-emerald-300',
        $code >= 300 && $code < 400 => 'bg-blue-500/20 text-blue-800 dark:text-blue-300',
        $code >= 400 && $code < 500 => 'bg-amber-500/20 text-amber-800 dark:text-amber-300',
        $code >= 500 => 'bg-red-500/25 text-red-800 dark:text-red-300',
        default => 'bg-[#706f6c]/20 text-[#4a4946] dark:text-[#b0afac]',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex px-2 py-0.5 text-xs font-medium rounded-sm tabular-nums ' . $classes]) }}>
    {{ $label }}
</span>
