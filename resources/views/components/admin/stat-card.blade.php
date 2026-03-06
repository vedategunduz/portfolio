@props([
    'label',
    'value',
    'iconColor' => 'red', // red, emerald, violet, amber
])

@php
    $iconClasses = match($iconColor) {
        'red' => 'bg-[#D62113]/10 dark:bg-[#D62113]/20 text-[#D62113]',
        'emerald' => 'bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400',
        'violet' => 'bg-violet-500/10 dark:bg-violet-500/20 text-violet-600 dark:text-violet-400',
        'amber' => 'bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400',
        default => 'bg-[#D62113]/10 dark:bg-[#D62113]/20 text-[#D62113]',
    };
@endphp

<div {{ $attributes->merge(['class' => 'rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-6 transition-shadow hover:shadow-lg dark:shadow-black/20']) }}>
    <div class="flex items-center gap-4">
        @if(isset($icon))
            <div class="shrink-0 w-12 h-12 rounded-sm flex items-center justify-center {{ $iconClasses }}">
                {{ $icon }}
            </div>
        @endif
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">{{ $label }}</p>
            <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-0.5">{{ $value }}</p>
        </div>
    </div>
</div>
