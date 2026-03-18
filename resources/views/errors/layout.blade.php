<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicons/android-chrome-512x512.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    @php $statusCode = $code ?? (isset($exception) ? $exception->getStatusCode() : 500); @endphp
    <title>@yield('title', $statusCode . ' - ' . config('app.name'))</title>
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
    @vite(['resources/css/app.css', 'resources/css/error-page.css', 'resources/js/error-page-init.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased min-h-screen flex flex-col">
    @php
        $statusCode = $statusCode ?? ($code ?? (isset($exception) ? $exception->getStatusCode() : 500));
        $showCountdown = in_array($statusCode, [500, 503]);
    @endphp
    <x-sections.background>
        <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-3xl">
                <div data-error-page data-status-code="{{ $statusCode }}" class="error-card relative overflow-hidden rounded-xl border border-black/10 dark:border-white/15 bg-white/45 dark:bg-[#1a1a18]/45 backdrop-blur-2xl shadow-xl dark:shadow-2xl dark:shadow-black/55 p-7 sm:p-10 md:p-12 opacity-0 translate-y-4 transition-all duration-700 ease-out" style="animation: fadeInUp 0.7s ease-out forwards;">
                    <div class="error-blob-1 pointer-events-none absolute -top-16 -right-16 h-52 w-52 rounded-full bg-[#D62113]/10 blur-3xl"></div>
                    <div class="error-blob-2 pointer-events-none absolute -bottom-20 -left-16 h-60 w-60 rounded-full bg-black/5 dark:bg-white/5 blur-3xl"></div>
                    <p class="pointer-events-none absolute right-4 sm:right-8 top-10 text-7xl sm:text-8xl md:text-9xl font-bold tracking-tighter text-[#D62113]/30 dark:text-[#D62113]/40 leading-none select-none error-code-bg">
                        {{ $statusCode }}
                    </p>

                    <div class="relative flex items-center justify-end">
                        <button type="button" id="theme-toggle" class="inline-flex items-center justify-center p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="Temayı Değiştir" aria-label="Açık ve koyu tema arasında geçiş yap">
                            <span class="dark:hidden inline-flex"><i data-lucide="sun" class="w-4 h-4"></i></span>
                            <span class="hidden dark:inline-flex"><i data-lucide="moon" class="w-4 h-4"></i></span>
                        </button>
                    </div>

                    <div class="relative mt-10 text-center sm:text-left sm:max-w-2xl">
                        <h1 class="text-3xl sm:text-4xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] leading-tight">
                            @yield('heading', $title ?? 'Bir hata oluştu')
                        </h1>
                        <p class="mt-3 text-sm sm:text-base text-[#706f6c] dark:text-[#8F8F8B] max-w-xl sm:mx-0 mx-auto">
                            @yield('message', $message ?? 'Lütfen daha sonra tekrar deneyin.')
                        </p>
                        @if($showCountdown)
                            <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-[#D62113]/10 dark:bg-[#D62113]/20 px-4 py-2 text-sm font-medium text-[#D62113]">
                                <i data-lucide="refresh-cw" class="w-4 h-4 animate-spin"></i>
                                <span>Sayfa <span id="countdown-timer" class="font-bold">10</span> saniye içinde yenilenecek</span>
                            </div>
                        @endif
                    </div>

                    <div class="relative mt-10 flex flex-col sm:flex-row items-center sm:items-start sm:justify-start gap-3 sm:gap-4">
                        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-6 py-3 rounded-sm font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-all hover:scale-105 active:scale-95">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Ana Sayfaya Dön
                        </a>
                        <button onclick="history.back()" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-6 py-3 rounded-sm font-medium border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] hover:border-[#D62113] hover:text-[#D62113] transition-all hover:scale-105 active:scale-95">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Geri Dön
                        </button>
                        @hasSection('extra-link')
                            @yield('extra-link')
                        @endif
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-center gap-6 text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                    <p>{{ config('app.name') }}</p>
                    <span class="hidden sm:inline opacity-50">·</span>
                    <p class="hidden sm:inline-flex items-center gap-2">
                        <kbd class="px-2 py-1 rounded bg-black/5 dark:bg-white/10 font-mono text-xs">ESC</kbd>
                        Ana sayfa
                        <span class="opacity-50">·</span>
                        <kbd class="px-2 py-1 rounded bg-black/5 dark:bg-white/10 font-mono text-xs">R</kbd>
                        Yenile
                    </p>
                </div>
                <p class="mt-3 text-center text-xs text-[#706f6c]/50 dark:text-[#8F8F8B]/50 font-mono konami-hint opacity-0 transition-opacity">
                    ↑ ↑ ↓ ↓ ← → ← → B A
                </p>
            </div>
        </div>
    </x-sections.background>
</body>
</html>
