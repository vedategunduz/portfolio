<div class="max-w-3xl mx-auto w-full">
    <!-- Error Message -->
    @if($showError)
        <div x-data x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-sm">
            <p class="text-red-800 dark:text-red-200 text-sm flex items-center gap-2">
                <i data-lucide="x-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ $errorMessage }}</span>
            </p>
        </div>
    @endif

    <!-- Success Message -->
    @if($showSuccess)
        <div x-data x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-sm">
            <p class="text-green-800 dark:text-green-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağım.</span>
            </p>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                Ad Soyad <span class="text-[#FF2D20]">*</span>
            </label>
            <input
                type="text"
                id="name"
                wire:model="name"
                @blur="$wire.validateOnly('name')"
                placeholder="Adınız ve Soyadınız"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] {{ $errors->has('name') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }}"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                E-posta <span class="text-[#FF2D20]">*</span>
            </label>
            <input
                type="email"
                id="email"
                wire:model="email"
                @blur="$wire.validateOnly('email')"
                placeholder="ornek@email.com"
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] {{ $errors->has('email') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }}"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                Mesaj <span class="text-[#FF2D20]">*</span>
            </label>
            <textarea
                id="message"
                rows="6"
                wire:model="message"
                @blur="$wire.validateOnly('message')"
                placeholder="Mesajınızı buraya yazın..."
                class="w-full px-4 py-3 border rounded-sm bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] resize-none {{ $errors->has('message') ? 'border-red-500' : 'border-[#e3e3e0] dark:border-[#3E3E3A]' }}"
            ></textarea>
            @error('message')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full px-8 py-4 bg-[#FF2D20] text-white rounded-sm text-sm font-medium transition-all duration-300 hover:bg-[#e02915] hover:shadow-2xl hover:shadow-[#FF2D20]/50 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
            <span wire:loading.remove class="flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Mesaj Gönder
            </span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                Gönderiliyor...
            </span>
        </button>
    </form>
</div>
