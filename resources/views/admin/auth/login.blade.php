@extends('layouts.empty')

@section('title', __('messages.auth.login_title') . ' - ' . config('app.name'))

@section('content')
    <x-sections.background>
        <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-10">
            <div class="w-full max-w-md">
                <div class="rounded-2xl border border-black/10 dark:border-white/15 bg-white/35 dark:bg-[#1a1a18]/35 backdrop-blur-2xl shadow-lg dark:shadow-2xl dark:shadow-black/50 p-6 sm:p-8">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.22em] text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.auth.admin_panel') }}</p>
                            <h1 class="mt-2 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ __('messages.auth.login_title') }}</h1>
                            <p class="mt-2 text-sm text-[#706f6c] dark:text-[#D4D3D0]">{{ __('messages.auth.login_subtitle') }}</p>
                        </div>

                        <button type="button" id="theme-toggle" class="inline-flex items-center justify-center leading-none p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/60 dark:bg-[#1a1a18]/50 backdrop-blur-sm text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors" title="{{ __('messages.theme.toggle') }}" aria-label="{{ __('messages.theme.light_dark') }}">
                            <span class="dark:hidden inline-flex">
                                <i data-lucide="sun" class="w-4 h-4"></i>
                            </span>
                            <span class="hidden dark:inline-flex">
                                <i data-lucide="moon" class="w-4 h-4"></i>
                            </span>
                        </button>
                    </div>

                    @if($errors->any())
                        <x-admin.notice variant="danger" class="mb-6">
                            {{ __('messages.auth.login_failed') }}
                        </x-admin.notice>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                        @csrf

                        <x-admin.form.input
                            label="{{ __('messages.auth.email_label') }}"
                            id="email"
                            name="email"
                            type="email"
                            :value="old('email')"
                            placeholder="admin@example.com"
                            :error="$errors->first('email')"
                            class="[&_input]:bg-white/80 dark:[&_input]:bg-[#0a0a0a]/70"
                            required
                            autofocus
                        />

                        <x-admin.form.input
                            label="{{ __('messages.auth.password_label') }}"
                            id="password"
                            name="password"
                            type="password"
                            :placeholder="__('messages.auth.password_label')"
                            :error="$errors->first('password')"
                            class="[&_input]:bg-white/80 dark:[&_input]:bg-[#0a0a0a]/70"
                            required
                        />

                        <label for="remember" class="inline-flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#D4D3D0] cursor-pointer">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                class="rounded-sm border-[#e3e3e0] dark:border-[#3E3E3A] text-[#D62113] focus:ring-[#D62113]/20 cursor-pointer"
                            >
                            {{ __('messages.auth.remember_me') }}
                        </label>

                        <x-admin.ui.button variant="primary" type="submit" class="w-full">
                            {{ __('messages.auth.login_button') }}
                        </x-admin.ui.button>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-[#e3e3e0] dark:border-[#3E3E3A]"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="px-2 bg-white/90 dark:bg-[#1a1a18]/85 text-[#706f6c] dark:text-[#8F8F8B]">{{ __('messages.or') }}</span>
                            </div>
                        </div>

                        <x-admin.ui.button variant="secondary" :href="route('home', ['locale' => app()->getLocale()])" class="w-full bg-white/50 dark:bg-[#1a1a18]/35">
                            {{ __('messages.auth.back_to_portfolio') }}
                        </x-admin.ui.button>
                    </form>
                </div>

                <p class="mt-6 text-center text-xs text-[#706f6c] dark:text-[#8F8F8B]">
                    © {{ date('Y') }} {{ config('app.name') }}. {{ __('messages.all_rights_reserved') }}
                </p>
            </div>
        </div>
    </x-sections.background>
@endsection
