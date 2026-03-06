<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php $statusCode = $code ?? (isset($exception) ? $exception->getStatusCode() : 500); @endphp
    <title>@yield('title', $statusCode . ' - ' . config('app.name'))</title>
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased min-h-screen flex flex-col">
    <x-sections.background>
        <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
            <div class="w-full max-w-lg text-center">
                <div class="rounded-2xl border border-black/10 dark:border-white/15 bg-white/35 dark:bg-[#1a1a18]/35 backdrop-blur-2xl shadow-lg dark:shadow-2xl dark:shadow-black/50 p-8 sm:p-12">
                    <div class="flex justify-end mb-4">
                        <button type="button" id="theme-toggle" class="inline-flex items-center justify-center p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="Temayı Değiştir" aria-label="Açık ve koyu tema arasında geçiş yap">
                            <span class="dark:hidden inline-flex"><i data-lucide="sun" class="w-4 h-4"></i></span>
                            <span class="hidden dark:inline-flex"><i data-lucide="moon" class="w-4 h-4"></i></span>
                        </button>
                    </div>
                    <p class="text-6xl sm:text-7xl font-bold text-[#D62113] tracking-tight">{{ $statusCode }}</p>
                    <h1 class="mt-4 text-xl sm:text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">
                        @yield('heading', $title ?? 'Bir hata oluştu')
                    </h1>
                    <p class="mt-2 text-sm text-[#706f6c] dark:text-[#8F8F8B]">
                        @yield('message', $message ?? 'Lütfen daha sonra tekrar deneyin.')
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-sm font-medium bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors">
                            Ana Sayfaya Dön
                        </a>
                        @hasSection('extra-link')
                            @yield('extra-link')
                        @endif
                    </div>
                </div>
                <p class="mt-8 text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                    {{ config('app.name') }} · {{ $statusCode }}
                </p>
            </div>
        </div>
    </x-sections.background>
</body>
</html>
