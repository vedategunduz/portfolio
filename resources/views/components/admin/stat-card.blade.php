@props([
    'label',
    'value' => null,
    'iconColor' => 'red', // red, emerald, violet, amber
    'compact' => false,
])

@php
    $iconClasses = match($iconColor) {
        'red' => 'bg-[#D62113]/10 dark:bg-[#D62113]/20 text-[#D62113]',
        'emerald' => 'bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400',
        'violet' => 'bg-violet-500/10 dark:bg-violet-500/20 text-violet-600 dark:text-violet-400',
        'amber' => 'bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400',
        default => 'bg-[#D62113]/10 dark:bg-[#D62113]/20 text-[#D62113]',
    };
    $padding = $compact ? 'p-4' : 'p-6';
    $iconBox = $compact ? 'w-9 h-9' : 'w-12 h-12';
    $valueSize = $compact ? 'text-lg' : 'text-2xl';
    $contentAlign = $compact ? 'items-start' : 'items-center';
@endphp

<div {{ $attributes->merge(['class' => 'h-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] ' . $padding]) }}>
    <div class="flex {{ $contentAlign }} gap-3">
        @if(isset($icon))
            <div class="shrink-0 {{ $iconBox }} rounded-sm flex items-center justify-center {{ $iconClasses }} {{ $compact ? '[&_svg]:w-4 [&_svg]:h-4' : '[&_svg]:w-5 [&_svg]:h-5' }} [&_svg]:shrink-0">
                {{ $icon }}
            </div>
        @endif
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">{{ $label }}</p>
            @if(isset($valueSlot))
                <div class="mt-0.5 {{ $valueSize }} font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $valueSlot }}</div>
            @else
                <p class="{{ $valueSize }} font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ $value ?? '—' }}</p>
            @endif
        </div>
    </div>
</div>
