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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">@yield('page-title', 'Admin Panel')</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</span>
                        <a href="/" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">Ana Sayfa</a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                Çıkış Yap
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }} py-4 px-1 text-sm font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.page-history') }}" class="border-b-2 {{ request()->routeIs('admin.page-history') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }} py-4 px-1 text-sm font-medium">
                        Sayfa Ziyaretleri
                    </a>
                    <a href="{{ route('admin.contact-messages') }}" class="border-b-2 {{ request()->routeIs('admin.contact-messages') || request()->routeIs('admin.message.mark-read') ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }} py-4 px-1 text-sm font-medium">
                        İletişim Mesajları
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>
    </div>

    <x-toast />

    @stack('scripts')
</body>
</html>

