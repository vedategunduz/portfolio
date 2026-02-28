<footer class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About Section -->
            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    {{ config('app.name', 'Portfolio') }}
                </h3>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Profesyonel web geliştirme ve tasarım hizmetleri.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Hızlı Bağlantılar
                </h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#home" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Ana Sayfa
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Hakkımda
                        </a>
                    </li>
                    <li>
                        <a href="#projects" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            Projeler
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] transition-colors">
                            İletişim
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Social Links -->
            <div>
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Sosyal Medya
                </h3>
                <div class="flex gap-4">
                    <a href="https://github.com/vedategunduz" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] dark:hover:text-[#FF2D20] transition-colors" title="GitHub">
                        <i data-lucide="github" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/vedategunduz/" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] dark:hover:text-[#FF2D20] transition-colors" title="LinkedIn">
                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                    </a>
                    <a href="https://www.instagram.com/vedategunduz/" target="_blank" rel="noopener noreferrer" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] dark:hover:text-[#FF2D20] transition-colors" title="Instagram">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="mailto:vedat.bilisim@outlook.com" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#FF2D20] dark:hover:text-[#FF2D20] transition-colors" title="Email">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] text-center">
                &copy; {{ date('Y') }} {{ config('app.name', 'Portfolio') }}. Tüm hakları saklıdır.
            </p>
        </div>

        <!-- Name -->
        <div class="mt-16 text-center">
            <p class="text-[6rem] md:text-[12rem] lg:text-[16rem] font-black text-[#FF2D20] leading-none tracking-tighter select-none">
                VEDAT
            </p>
        </div>
    </div>
</footer>
