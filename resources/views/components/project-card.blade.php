@props([
    'title',
    'description',
    'tags' => [],
    'gradient' => 'from-[#FF2D20] to-[#FF6B6B]',
    'color' => '#FF2D20',
    'initial' => 'P',
])

<div
    x-data="{ expanded: false }"
    class="group border-2 rounded-sm overflow-hidden transition-all duration-500"
    :class="expanded ? 'border-[#FF2D20] shadow-2xl shadow-[#FF2D20]/50 scale-[1.02]' : 'border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#FF2D20] hover:shadow-xl hover:shadow-[#FF2D20]/30'"
>
    <!-- Project Image/Placeholder -->
    <div class="aspect-video flex items-center justify-center cursor-pointer relative overflow-hidden"
         style="background-color: {{ $color }};"
         @click="expanded = !expanded">
        <div class="flex items-center justify-center gap-4">
            <span class="text-white text-4xl md:text-6xl font-black transition-transform duration-500 drop-shadow-2xl"
                  :class="expanded ? 'scale-125 rotate-180' : 'scale-100'">
                {{ $initial }}
            </span>
            <div class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <i data-lucide="chevron-down" class="w-6 h-6 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
            </div>
        </div>
        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    <!-- Project Content -->
    <div class="p-6">
        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-3 group-hover:text-[#FF2D20] transition-colors">
            {{ $title }}
        </h3>
        <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm mb-4"
           x-show="!expanded || expanded"
           x-transition>
            {{ $description }}
        </p>

        <!-- Tags -->
        @if(count($tags) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <span class="px-3 py-1 text-xs bg-[#f8f8f7] dark:bg-[#161615] text-[#706f6c] dark:text-[#A1A09A] rounded-full hover:bg-[#FF2D20] hover:text-white hover:shadow-lg transition-all duration-300 cursor-pointer">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Additional Content Slot -->
        @if($slot->isNotEmpty())
            <div x-show="expanded" x-transition class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
