<footer class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ config('app.name', 'Portfolio') }}
                </h3>
                <p class="text-sm text-[#706f6c] dark:text-[#D4D3D0]">
                    {{ __('messages.footer.description') }}
                </p>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ __('messages.footer.quick_links') }}
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#home" class="text-sm text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            {{ __('messages.nav.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="text-sm text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            {{ __('messages.nav.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="#projects" class="text-sm text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            {{ __('messages.nav.projects') }}
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="text-sm text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            {{ __('messages.nav.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ __('messages.footer.social_media') }}
                </h3>
                <div class="flex gap-4">
                    <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] dark:hover:text-[#D62113] transition-colors" title="{{ __('messages.social.github') }}" aria-label="{{ __('messages.social.aria.github') }}">
                        <i data-lucide="github" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] dark:hover:text-[#D62113] transition-colors" title="{{ __('messages.social.linkedin') }}" aria-label="{{ __('messages.social.aria.linkedin') }}">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] dark:hover:text-[#D62113] transition-colors" title="{{ __('messages.social.instagram') }}" aria-label="{{ __('messages.social.aria.instagram') }}">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="mailto:vedat.bilisim@outlook.com" class="text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] dark:hover:text-[#D62113] transition-colors" title="{{ __('messages.social.email') }}" aria-label="{{ __('messages.social.aria.email') }}">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <p class="text-sm text-[#706f6c] dark:text-[#D4D3D0] text-center">
                &copy; {{ date('Y') }} {{ __('messages.footer.owner_name') }}. {{ __('messages.all_rights_reserved') }}
            </p>
        </div>

        <div class="mt-12 text-center">
            <p class="text-[6rem] md:text-[12rem] lg:text-[16rem] font-black text-[#D62113] leading-none tracking-tighter select-none">
                VEDAT
            </p>
        </div>
    </div>
</footer>

