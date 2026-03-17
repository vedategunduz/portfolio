@props([
    'zebra' => false,
])

@php
    $base = 'transition-colors duration-150';
    $bg = $zebra
        ? 'bg-[#fafafa] dark:bg-[#1e1e1e]'
        : 'bg-white dark:bg-[#1a1a18]';
    $hover = 'hover:bg-[#f5f5f5] dark:hover:bg-[#252525]';
    $rowClass = $base . ' ' . $bg . ' ' . $hover;
@endphp

<tr {{ $attributes->merge(['class' => $rowClass]) }}>
    {{ $slot }}
</tr>
