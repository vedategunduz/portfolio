@props([
    'title',
    'description',
    'icon' => null,
])

<div
    x-data="{ hover: false }"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    class="p-6 border-2 rounded-sm transition-all duration-700 cursor-pointer"
    :class="hover ? 'border-[#D62113] shadow-2xl shadow-[#D62113]/30 transform scale-105' : 'border-[#e3e3e0] dark:border-[#3E3E3A]'"
>
    @if($icon)
        <div class="mb-4 text-[#D62113] h-8 transition-all duration-700"
             :class="hover ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-2'">
            <i data-lucide="{{ $icon }}" class="w-8 h-8"></i>
        </div>
    @endif
    <h3 class="text-xl font-semibold mb-3 transition-all duration-700"
        :class="hover ? 'text-[#D62113]' : 'text-[#1b1b18] dark:text-[#EDEDEC]'">
        {{ $title }}
    </h3>
    <p class="text-[#706f6c] dark:text-[#D4D3D0] text-sm">
        {{ $description }}
    </p>
</div>

