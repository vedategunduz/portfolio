<header id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <nav class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 mb-4">
        <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white/80 dark:bg-[#161615]/80 backdrop-blur-md">
            <div class="flex justify-between items-center h-14 px-6">
                <!-- Logo -->
                <div class="shrink-0">
                    <a href="#home" class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] hover:text-[#FF2D20] transition-colors duration-300">
                        {{ config('app.name', 'Portfolio') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="#home" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors duration-300 relative group">
                        Ana Sayfa
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#FF2D20] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#about" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors duration-300 relative group">
                        Hakkımda
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#FF2D20] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#projects" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors duration-300 relative group">
                        Projeler
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#FF2D20] group-hover:w-full transition-all duration-300"></span>
                    </a>
                    <a href="#contact" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors duration-300 relative group">
                        İletişim
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#FF2D20] group-hover:w-full transition-all duration-300"></span>
                    </a>
                </div>

                <!-- Right Section: Social & Theme Toggle -->
                <div class="flex items-center gap-3">
                    <!-- Social Icons -->
                    <div class="hidden sm:flex items-center gap-2">
                        <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="GitHub">
                            <i data-lucide="github" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="LinkedIn">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="Instagram">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:vedat.bilisim@outlook.com" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="Email">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="Tema Değiştir">
                        <span class="dark:hidden inline-flex">
                            <i data-lucide="sun" class="w-4 h-4"></i>
                        </span>
                        <span class="hidden dark:inline-flex">
                            <i data-lucide="moon" class="w-4 h-4"></i>
                        </span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button type="button" class="md:hidden p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] focus:outline-none" id="mobile-menu-button">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="hidden md:hidden pb-4 px-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]" id="mobile-menu">
                <div class="flex flex-col gap-4 pt-4">
                    <a href="#home" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors">
                        Ana Sayfa
                    </a>
                    <a href="#about" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors">
                        Hakkımda
                    </a>
                    <a href="#projects" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors">
                        Projeler
                    </a>
                    <a href="#contact" class="text-xs text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors">
                        İletişim
                    </a>

                    <!-- Social Icons (Mobile) -->
                    <div class="flex items-center gap-3 pt-2 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="GitHub">
                            <i data-lucide="github" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="LinkedIn">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="Instagram">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="mailto:vedat.bilisim@outlook.com" class="p-1.5 text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] transition-colors" title="Email">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
