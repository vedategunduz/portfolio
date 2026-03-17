<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Portfolio'))</title>

    <!-- Preload critical fonts for LCP optimization -->
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('build/assets/instrument-sans-latin-400-normal-DRC__1Mx.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('build/assets/instrument-sans-latin-600-normal-B7fBEWYG.woff2') }}" crossorigin>

    <script>
        (() => {
            document.documentElement.classList.add('js');
            const stored = localStorage.getItem('theme');
            const theme = stored || 'dark';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/admin.js'])

    @stack('styles')
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased min-h-screen">
    <div class="min-h-screen flex flex-col overflow-x-hidden">
        <!-- Header -->
        <header class="sticky top-0 z-40 border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16 gap-2 min-w-0">
                    <h1 class="text-base sm:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] tracking-tight truncate">
                        @yield('page-title', 'Admin Panel')
                    </h1>
                    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                        <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] hidden md:inline truncate max-w-[120px] lg:max-w-[180px]">{{ Auth::user()->email }}</span>
                        <a href="/" class="text-xs font-medium text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors duration-200 whitespace-nowrap">
                            Ana Sayfa
                        </a>
                        <button type="button" id="theme-toggle" class="inline-flex items-center justify-center p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="Temayı Değiştir" aria-label="Açık ve koyu tema arasında geçiş yap">
                            <span class="dark:hidden inline-flex"><i data-lucide="sun" class="w-4 h-4"></i></span>
                            <span class="hidden dark:inline-flex"><i data-lucide="moon" class="w-4 h-4"></i></span>
                        </button>
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-[#D62113] hover:text-[#b81a0f] transition-colors duration-200 whitespace-nowrap">
                                Çıkış
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation: horizontal scroll on small screens -->
        <nav class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/50 dark:bg-[#161615]/50 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex gap-0 -mb-px overflow-x-auto overscroll-x-contain" style="-webkit-overflow-scrolling: touch;">
                    <a href="{{ route('admin.dashboard') }}" class="py-3 sm:py-4 px-3 sm:px-4 text-xs font-medium border-b-2 transition-colors duration-200 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.page-history.raw') }}" class="py-3 sm:py-4 px-3 sm:px-4 text-xs font-medium border-b-2 transition-colors duration-200 shrink-0 {{ request()->routeIs('admin.page-history.*') ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]' }}">
                        Sayfa Geçmişi
                    </a>
                    <a href="{{ route('admin.login-history.index') }}" class="py-3 sm:py-4 px-3 sm:px-4 text-xs font-medium border-b-2 transition-colors duration-200 shrink-0 {{ request()->routeIs('admin.login-history.*') ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]' }}">
                        Giriş Geçmişi
                    </a>
                    <a href="{{ route('admin.contact-messages') }}" class="py-3 sm:py-4 px-3 sm:px-4 text-xs font-medium border-b-2 transition-colors duration-200 shrink-0 {{ request()->routeIs('admin.contact-messages') || request()->routeIs('admin.message.mark-read') ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]' }}">
                        İletişim Mesajları
                    </a>
                    <a href="{{ route('admin.profile.edit') }}" class="py-3 sm:py-4 px-3 sm:px-4 text-xs font-medium border-b-2 transition-colors duration-200 shrink-0 {{ request()->routeIs('admin.profile.*') ? 'border-[#D62113] text-[#D62113]' : 'border-transparent text-[#706f6c] dark:text-[#8F8F8B] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] hover:border-[#e3e3e0] dark:hover:border-[#3E3E3A]' }}">
                        Hesap Ayarları
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <x-toast />

    @stack('scripts')
</body>
</html>
