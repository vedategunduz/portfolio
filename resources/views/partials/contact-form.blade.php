<div class="max-w-3xl mx-auto w-full">
    @if (session('contact_error'))
        <div x-data x-show="true"
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
        <div x-data x-show="true"
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
                Ad Soyadınız <span class="text-[#FF2D20]">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                data-field-input="name"
                value="{{ old('name') }}"
                placeholder="Adınız ve Soyadınız"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('name') ? 'border-red-500' : '' }}"
            >
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('name') ? '' : 'hidden' }}" data-field-error="name">{{ $errors->first('name') }}</p>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                E-posta <span class="text-[#FF2D20]">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                data-field-input="email"
                value="{{ old('email') }}"
                placeholder="ornek@eposta.com"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('email') ? 'border-red-500' : '' }}"
            >
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('email') ? '' : 'hidden' }}" data-field-error="email">{{ $errors->first('email') }}</p>
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                Mesaj <span class="text-[#FF2D20]">*</span>
            </label>
            <textarea
                id="message"
                name="message"
                data-field-input="message"
                rows="6"
                placeholder="Mesajınızı buraya yazın..."
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] resize-none border-[#e3e3e0] dark:border-[#3E3E3A] {{ $errors->has('message') ? 'border-red-500' : '' }}"
            >{{ old('message') }}</textarea>
            <p class="mt-1 text-sm text-red-500 {{ $errors->has('message') ? '' : 'hidden' }}" data-field-error="message">{{ $errors->first('message') }}</p>
        </div>

        <button
            type="submit"
            class="w-full px-8 py-4 bg-[#FF2D20] text-white rounded-sm text-sm font-medium transition-all duration-300 hover:bg-[#e02915] hover:shadow-2xl hover:shadow-[#FF2D20]/50 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            <span class="flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Mesaj Gönder
            </span>
        </button>
    </form>
</div>
