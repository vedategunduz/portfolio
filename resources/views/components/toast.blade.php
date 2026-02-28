<div x-data="{
    toasts: [],
    add(type, title, message) {
        const id = Date.now();
        const theme = this.getTheme(type);
        this.toasts.push({ id, type, title, message, ...theme });
        setTimeout(() => { this.remove(id); }, 4000);
    },
    remove(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    },
    getTheme(type) {
        return {
            success: { icon: 'check', color: 'text-emerald-600', bgIcon: 'bg-emerald-100', bar: 'bg-emerald-500' },
            error: { icon: 'ban', color: 'text-rose-600', bgIcon: 'bg-rose-100', bar: 'bg-rose-500' },
            warning: { icon: 'alert-triangle', color: 'text-amber-600', bgIcon: 'bg-amber-100', bar: 'bg-amber-500' },
            info: { icon: 'info', color: 'text-blue-600', bgIcon: 'bg-blue-100', bar: 'bg-blue-500' },
        } [type] || { icon: 'info', color: 'text-zinc-600', bgIcon: 'bg-zinc-100', bar: 'bg-zinc-500' };
    }
}" @toast:show.window="add($event.detail.type, $event.detail.title, $event.detail.message)"
    class="fixed bottom-4 right-4 z-[90] flex flex-col-reverse gap-3 w-11/12 lg:w-full max-w-sm pointer-events-none items-end"
    x-cloak>

    <template x-for="toast in toasts" :key="toast.id">

        {{--
            SADELEŞTİRİLMİŞ YAPI:
            x-transition satırlarını sildik.
            Yerine 'animate-fade-in-up' sınıfını ekledik.
        --}}
        <div x-show="true"
            class="animate-fade-in-up pointer-events-auto relative w-full overflow-hidden rounded bg-white border border-zinc-200 shadow group">
            <div class="p-4 flex items-start gap-4">
                {{-- İkon --}}
                <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center transition-colors"
                    :class="toast.bgIcon">
                    <div x-html="`<i data-lucide='${toast.icon}' class='h-5 w-5 ${toast.color}'></i>`"></div>
                </div>

                {{-- İçerik --}}
                <div class="flex-1 pt-0.5 min-w-0"> {{-- min-w-0: Flex içinde truncate çalışması için şart --}}

                    {{-- Başlık: Tek satır, sığmazsa üç nokta --}}
                    <p class="text-sm font-bold text-zinc-900 leading-tight truncate" x-text="toast.title">
                    </p>

                    {{-- Mesaj: Maksimum 3 satır, fazlası kesilir --}}
                    {{-- break-words: Uzun kelimeleri (url gibi) alt satıra atar --}}
                    <p class="mt-1 text-sm text-zinc-500 leading-snug line-clamp-3 wrap-break-word" x-text="toast.message">
                    </p>

                </div>

                {{-- Kapat --}}
                <button @click="remove(toast.id)"
                    class="text-zinc-400 hover:text-zinc-600 transition-colors p-1 rounded-md hover:bg-zinc-100"
                    aria-label="Bildirimi kapat">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>

            {{-- Progress Bar (CSS'te tanımladığımız animate-toast-progress) --}}
            <div class="absolute bottom-0 left-0 h-1 w-full animate-toast-progress" :class="toast.bar"></div>

            <div x-init="$nextTick(() => window.createIcons({ icons: window.lucideIcons, nameAttr: 'data-lucide' }))"></div>
        </div>

    </template>
</div>
