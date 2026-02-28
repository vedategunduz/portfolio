@props([
    'title',
    'subtitle' => null,
    'align' => 'center', // left, center, right
])

<div class="mb-12 text-{{ $align }}">
    <h2 class="text-4xl md:text-5xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 relative inline-block">
        {{ $title }}
        <span class="absolute -bottom-2 left-0 w-full h-1 bg-[#FF2D20] rounded-full"></span>
    </h2>
    @if($subtitle)
        <p class="text-lg text-[#706f6c] dark:text-[#D4D3D0] max-w-2xl {{ $align === 'center' ? 'mx-auto' : '' }} mt-6">
            {{ $subtitle }}
        </p>
    @endif
</div>
