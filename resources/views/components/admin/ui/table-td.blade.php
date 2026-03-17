@props([
    'variant' => 'primary', // primary | secondary
])

@php
    $padding = 'px-3 lg:px-4 py-2.5';
    $text = $variant === 'secondary'
        ? 'text-[#6b7280] dark:text-[#9ca3af]'
        : 'text-[#111827] dark:text-[#f3f4f6]';
@endphp

<td {{ $attributes->merge(['class' => $padding . ' text-sm leading-normal ' . $text]) }}>
    {{ $slot }}
</td>
