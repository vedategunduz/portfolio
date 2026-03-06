@props([
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] p-12 text-center']) }}>
    @if(isset($icon))
        <div class="flex justify-center text-[#706f6c] dark:text-[#8F8F8B]">{{ $icon }}</div>
    @endif
    <h3 class="mt-4 text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ $description }}</p>
    @endif
</div>
