<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Portfolio'))</title>

    <!-- SEO Meta Tags -->
    @hasSection('seo')
        @yield('seo')
    @else
        <x-seo />
    @endif

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

    <script>
        window.translations = {
            'form.processing': "{{ __('messages.form.processing') }}",
            'form.unexpected_error': "{{ __('messages.form.unexpected_error') }}",
            'form.check_fields': "{{ __('messages.form.check_fields') }}",
            'form.action_failed': "{{ __('messages.form.action_failed') }}",
            'form.error': "{{ __('messages.form.error') }}",
            'form.confirm_required': "{{ __('messages.form.confirm_required') }}",
            'form.confirm_continue': "{{ __('messages.form.confirm_continue') }}",
            'dialog.success': "{{ __('messages.dialog.success') }}",
            'dialog.error': "{{ __('messages.dialog.error') }}",
            'dialog.info': "{{ __('messages.dialog.info') }}",
            'dialog.warning': "{{ __('messages.dialog.warning') }}",
            'dialog.confirm_title': "{{ __('messages.dialog.confirm_title') }}",
            'dialog.confirm_button': "{{ __('messages.dialog.confirm_button') }}",
            'dialog.cancel_button': "{{ __('messages.dialog.cancel_button') }}"
        };
    </script>

    <style>
        /* Scroll margin for navbar */
        section[id] {
            scroll-margin-top: 6rem;
        }

        /* LCP Optimization: Hero başlığı için ilk render optimizasyonu */
        #home h1 {
            contain: layout style paint;
        }
    </style>
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased">
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <x-toast />

    @stack('scripts')
</body>
</html>

