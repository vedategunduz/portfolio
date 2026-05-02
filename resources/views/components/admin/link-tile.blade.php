@props([
    'href',
    'title',
    'description' => null,
])

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group']) }}
>
    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">{{ $title }}</span>
    @if($description)
        <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">{{ $description }}</p>
    @endif
</a>
