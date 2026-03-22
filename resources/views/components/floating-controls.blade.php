<div class="fixed right-4 bottom-4 z-50 flex flex-col gap-2">
    <div class="border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm bg-white/85 dark:bg-[#161615]/85 backdrop-blur-md p-1.5 shadow-lg">
        <label for="locale-switch-public" class="sr-only">{{ __('messages.language') }}</label>
        <select
            id="locale-switch-public"
            class="text-[11px] rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/80 dark:bg-[#161615]/80 text-[#706f6c] dark:text-[#D4D3D0] px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#D62113]/30"
            onchange="window.location.href='{{ route('locale.update', ['locale' => '__LOCALE__']) }}'.replace('__LOCALE__', this.value)"
        >
            @foreach(config('app.supported_locales', ['tr', 'en']) as $supportedLocale)
                <option value="{{ $supportedLocale }}" @selected(app()->getLocale() === $supportedLocale)>
                    {{ strtoupper($supportedLocale) }}
                </option>
            @endforeach
        </select>
    </div>

    <button id="theme-toggle" data-theme-toggle="1" type="button" class="inline-flex items-center justify-center p-2 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white/85 dark:bg-[#161615]/85 backdrop-blur-md text-[#706f6c] dark:text-[#D4D3D0] hover:text-[#D62113] transition-colors shadow-lg" title="{{ __('messages.theme.toggle') }}" aria-label="{{ __('messages.theme.light_dark') }}">
        <span class="dark:hidden inline-flex">
            <i data-lucide="sun" class="w-4 h-4"></i>
        </span>
        <span class="hidden dark:inline-flex">
            <i data-lucide="moon" class="w-4 h-4"></i>
        </span>
    </button>
</div>
