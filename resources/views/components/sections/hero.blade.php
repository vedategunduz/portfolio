<section id="home" data-scroll-section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center">
        {{-- LCP Element: Hero başlığı - animasyonsuz, hemen görünür --}}
        <h1 class="text-5xl md:text-7xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-6" style="content-visibility: auto;">
            {{ __('messages.home.hero.greeting') }}
            <span class="text-[#D62113]">Vedat</span>
        </h1>
        <p class="scroll-item text-xl md:text-2xl text-[#706f6c] dark:text-[#D4D3D0] max-w-3xl mx-auto mb-8 transition-all duration-500">
            {{ __('messages.home.hero.description') }}
        </p>
        <div class="scroll-item flex gap-4 justify-center flex-wrap transition-all duration-500">
            <x-button href="#projects" variant="primary">
                {{ __('messages.home.hero.cta_projects') }}
            </x-button>
            <x-button href="#contact" variant="secondary">
                {{ __('messages.home.hero.cta_contact') }}
            </x-button>
        </div>
    </div>
</section>
