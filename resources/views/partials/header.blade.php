@php
    $homeUrl = route('home', ['locale' => app()->getLocale()]);
@endphp

<header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <nav class="max-w-2xl md:max-w-3xl lg:max-w-3xl mx-auto px-4 sm:px-0 lg:px-0 mt-4 mb-4">
        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white/80 dark:bg-[#161615]/80 backdrop-blur-md">
            <div class="flex justify-between items-center h-14 px-6">
                <div class="shrink-0">
                    <a href="{{ $homeUrl }}#home" class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#D62113] transition-colors duration-300">
                        {{ config('app.name', 'Portfolio') }}
                    </a>
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ $homeUrl }}#home" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors duration-300 relative group">
                        {{ __('messages.nav.home') }}
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#D62113] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ $homeUrl }}#about" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors duration-300 relative group">
                        {{ __('messages.nav.about') }}
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#D62113] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ $homeUrl }}#projects" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors duration-300 relative group">
                        {{ __('messages.nav.projects') }}
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#D62113] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="{{ $homeUrl }}#contact" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors duration-300 relative group">
                        {{ __('messages.nav.contact') }}
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#D62113] group-hover:w-full transition-all duration-300"></span>
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="hidden sm:inline-flex items-center justify-center px-3 py-1.5 rounded-sm text-xs font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] hover:border-[#D62113]/40 transition-colors"
                        >
                            {{ __('messages.nav.admin_panel') }}
                        </a>
                    @endauth
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.github') }}" aria-label="{{ __('messages.social.aria.github') }}">
                            <i data-lucide="github" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.linkedin') }}" aria-label="{{ __('messages.social.aria.linkedin') }}">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.instagram') }}" aria-label="{{ __('messages.social.aria.instagram') }}">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:vedat.bilisim@outlook.com" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.email') }}" aria-label="{{ __('messages.social.aria.email') }}">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>

                    <button type="button" class="md:hidden inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] focus:outline-none" id="mobile-menu-button" aria-label="{{ __('messages.menu.open_close') }}" aria-expanded="false" aria-controls="mobile-menu">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div class="md:hidden max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-out px-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]" id="mobile-menu">
                <div class="flex flex-col gap-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors">
                            {{ __('messages.nav.admin_panel') }}
                        </a>
                    @endauth
                    <a href="{{ $homeUrl }}#home" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors">
                        {{ __('messages.nav.home') }}
                    </a>
                    <a href="{{ $homeUrl }}#about" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors">
                        {{ __('messages.nav.about') }}
                    </a>
                    <a href="{{ $homeUrl }}#projects" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors">
                        {{ __('messages.nav.projects') }}
                    </a>
                    <a href="{{ $homeUrl }}#contact" class="text-xs text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors">
                        {{ __('messages.nav.contact') }}
                    </a>

                    <div class="flex items-center gap-3 pt-2 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.github') }}" aria-label="{{ __('messages.social.aria.github') }}">
                            <i data-lucide="github" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.linkedin') }}" aria-label="{{ __('messages.social.aria.linkedin') }}">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.instagram') }}" aria-label="{{ __('messages.social.aria.instagram') }}">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:vedat.bilisim@outlook.com" class="inline-flex items-center justify-center leading-none p-1.5 text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.social.email') }}" aria-label="{{ __('messages.social.aria.email') }}">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </nav>
</header>

