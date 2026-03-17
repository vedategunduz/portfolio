@extends('layouts.empty')

@section('title', 'Admin Giriş - ' . config('app.name'))

@section('content')
    <x-sections.background>
        <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10">
            <div class="w-full max-w-md">
                <div class="rounded-2xl border border-black/10 dark:border-white/15 bg-white/35 dark:bg-[#1a1a18]/35 backdrop-blur-2xl shadow-lg dark:shadow-2xl dark:shadow-black/50 p-8 sm:p-10">
                    <div class="mb-8 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.auth.admin_panel') }}</p>
                            <h1 class="mt-2 text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.auth.login_title') }}</h1>
                            <p class="mt-2 text-[#706f6c] dark:text-[#D4D3D0]">{{ __('messages.auth.login_subtitle') }}</p>
                        </div>

                        <button type="button" id="theme-toggle" class="inline-flex items-center justify-center leading-none p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.theme.toggle') }}" aria-label="{{ __('messages.theme.light_dark') }}">
                            <span class="dark:hidden inline-flex">
                                <i data-lucide="sun" class="w-4 h-4"></i>
                            </span>
                            <span class="hidden dark:inline-flex">
                                <i data-lucide="moon" class="w-4 h-4"></i>
                            </span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-50/90 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm p-4">
                                <p class="text-sm font-medium text-red-800 dark:text-red-300">
                                    {{ __('messages.auth.login_failed') }}
                                </p>
                            </div>
                        @endif

                        <div>
                            <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                {{ __('messages.auth.email_label') }}
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full px-4 py-3 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#8F8F8B] focus:outline-none focus:border-[#D62113] focus:ring-2 focus:ring-[#D62113]/20 transition-all duration-200"
                                placeholder="admin@example.com"
                            >
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                                {{ __('messages.auth.password_label') }}
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="w-full px-4 py-3 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] placeholder-[#706f6c] dark:placeholder-[#8F8F8B] focus:outline-none focus:border-[#D62113] focus:ring-2 focus:ring-[#D62113]/20 transition-all duration-200"
                                placeholder="{{ __('messages.auth.password_label') }} girin"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="rounded-sm border-[#e3e3e0] dark:border-[#3E3E3A] text-[#D62113] focus:ring-[#D62113]/20 cursor-pointer"
                            >
                            <label for="remember" class="text-sm text-[#706f6c] dark:text-[#D4D3D0] cursor-pointer">
                                {{ __('messages.auth.remember_me') }}
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#D62113] text-white py-3 rounded-sm font-medium transition-all duration-300 hover:bg-[#b81a0f] hover:shadow-2xl hover:shadow-[#D62113]/50"
                        >
                            {{ __('messages.auth.login_button') }}
                        </button>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="px-2 bg-white/90 dark:bg-[#1a1a18]/85 text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.or') }}</span>
                            </div>
                        </div>

                        <a
                            href="/"
                            class="w-full block text-center px-8 py-3 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] font-medium transition-all duration-300 hover:border-[#D62113] hover:text-[#D62113]"
                        >
                            {{ __('messages.auth.back_to_portfolio') }}
                        </a>
                    </form>
                </div>

                <p class="mt-8 text-center text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                    © {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </x-sections.background>
@endsection
