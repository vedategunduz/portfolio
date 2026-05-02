@props([
    'variant' => 'default', // success | danger | warning | info | default
    'title' => null,
])

@php
    $classes = match($variant) {
        'success' => 'border-emerald-200 dark:border-emerald-800 bg-emerald-50/90 dark:bg-emerald-900/20',
        'danger' => 'border-red-200 dark:border-red-900/50 bg-red-50/90 dark:bg-red-900/20',
        'warning' => 'border-amber-200 dark:border-amber-900/50 bg-amber-50/90 dark:bg-amber-900/20',
        'info' => 'border-sky-200 dark:border-sky-900/50 bg-sky-50/90 dark:bg-sky-900/20',
        default => 'border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#f8f8f7] dark:bg-[#111110]',
    };

    $titleClasses = match($variant) {
        'success' => 'text-emerald-800 dark:text-emerald-200',
        'danger' => 'text-red-700 dark:text-red-300',
        'warning' => 'text-amber-900 dark:text-amber-200',
        'info' => 'text-sky-900 dark:text-sky-200',
        default => 'text-[#1b1b18] dark:text-[#EDEDEC]',
    };

    $bodyClasses = match($variant) {
        'success' => 'text-emerald-800 dark:text-emerald-200',
        'danger' => 'text-red-700 dark:text-red-300',
        'warning' => 'text-amber-900/90 dark:text-amber-100/90',
        'info' => 'text-sky-900/90 dark:text-sky-100/90',
        default => 'text-[#706f6c] dark:text-[#8F8F8B]',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-sm border px-4 py-3']) }} class="{{ $classes }}">
    @if($title)
        <p class="text-xs font-semibold {{ $titleClasses }}">{{ $title }}</p>
    @endif
    <div class="{{ $title ? 'mt-2' : '' }} text-sm {{ $bodyClasses }}">
        {{ $slot }}
    </div>
</div>
