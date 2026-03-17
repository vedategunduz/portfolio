<div class="max-w-3xl mx-auto w-full">
    @if (session('contact_error'))
        <div x-cloak x-data x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm">
            <p class="text-red-800 dark:text-red-200 text-sm flex items-center gap-2">
                <i data-lucide="x-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('contact_error') }}</span>
            </p>
        </div>
    @endif

    @if (session('contact_success'))
        <div x-cloak x-data x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-sm">
            <p class="text-green-800 dark:text-green-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('contact_success') }}</span>
            </p>
        </div>
    @endif

    <div
        data-form-error
        class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm hidden"
    >
        <p class="text-red-800 dark:text-red-200 text-sm flex items-center gap-2">
            <i data-lucide="x-circle" class="w-5 h-5 shrink-0"></i>
            <span data-form-error-message></span>
        </p>
    </div>

    <div
        data-form-success
        class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-sm hidden"
    >
        <p class="text-green-800 dark:text-green-200 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
            <span data-form-success-message></span>
        </p>
    </div>

    <form id="contact-form" method="POST" action="{{ route('contact.submit') }}" class="space-y-6" novalidate>
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                {{ __('messages.home.contact.form.name_label') }} <span class="text-[#D62113]">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                data-field-input="name"
                value="{{ old('name') }}"
                placeholder="{{ __('messages.home.contact.form.name_placeholder') }}"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#D62113] border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('name') ? 'border-red-500' : '' }}"
            >
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('name') ? '' : 'hidden' }}" data-field-error="name">{{ $errors->first('name') }}</p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                {{ __('messages.home.contact.form.email_label') }} <span class="text-[#D62113]">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                data-field-input="email"
                value="{{ old('email') }}"
                placeholder="{{ __('messages.home.contact.form.email_placeholder') }}"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#D62113] border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('email') ? 'border-red-500' : '' }}"
            >
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('email') ? '' : 'hidden' }}" data-field-error="email">{{ $errors->first('email') }}</p>
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                {{ __('messages.home.contact.form.message_label') }} <span class="text-[#D62113]">*</span>
            </label>
            <textarea
                id="message"
                name="message"
                data-field-input="message"
                rows="6"
                placeholder="{{ __('messages.home.contact.form.message_placeholder') }}"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#D62113] resize-none border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('message') ? 'border-red-500' : '' }}"
            >{{ old('message') }}</textarea>
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('message') ? '' : 'hidden' }}" data-field-error="message">{{ $errors->first('message') }}</p>
        </div>

        <button
            type="submit"
            class="w-full px-8 py-4 bg-[#D62113] text-white rounded-sm text-sm font-medium transition-all duration-300 hover:bg-[#b81a0f] hover:shadow-2xl hover:shadow-[#D62113]/50 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            <span class="flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                {{ __('messages.home.contact.form.submit') }}
            </span>
        </button>
    </form>
</div>

