<div>
    {{-- İstatistik kartları (kompakt) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <x-admin.stat-card label="Toplam İstek" :value="number_format($this->stats['total_requests'])" iconColor="red" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Ort. Yanıt Süresi" :value="$this->stats['avg_response_ms'] !== null ? number_format($this->stats['avg_response_ms'], 0) . ' ms' : '—'" iconColor="violet" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Hata Oranı" :value="$this->stats['error_rate_percent'] . '%'" iconColor="amber" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="En Çok İstek Alan Endpoint" iconColor="emerald" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></x-slot:icon>
            <x-slot:valueSlot>
                @if($this->stats['top_endpoint'])
                    <span class="block truncate text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]" title="{{ $this->stats['top_endpoint']->path }}">{{ $this->stats['top_endpoint']->path ?: '—' }}</span>
                    <span class="text-xs font-semibold text-[#706f6c] dark:text-[#8F8F8B] mt-1 tabular-nums">{{ number_format($this->stats['top_endpoint']->c) }} istek</span>
                @else
                    —
                @endif
            </x-slot:valueSlot>
        </x-admin.stat-card>
    </div>

    {{-- Filtreler --}}
    <x-admin.card class="p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">Filtreler</h3>
            <a href="{{ $this->exportUrl }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FAFAF9] dark:bg-[#111110] text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#e3e3e0] dark:hover:bg-[#3E3E3A] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                CSV İndir
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            <x-admin.form.input label="Tarih (başlangıç)" type="date" name="date_from" wire:model.live="date_from" />
            <x-admin.form.input label="Tarih (bitiş)" type="date" name="date_to" wire:model.live="date_to" />
            <x-admin.form.input label="IP" name="ip" placeholder="192.168.1.1" wire:model.live.debounce.500ms="ip" />
            <x-admin.form.select label="Metod" name="method" :options="['' => 'Tümü', 'GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE', 'HEAD' => 'HEAD', 'OPTIONS' => 'OPTIONS']" :selected="$method" wire:model.live="method" />
            <x-admin.form.input label="Status code" type="number" name="status_code" placeholder="200" wire:model.live="status_code" min="100" max="599" />
            <div class="lg:col-span-2">
                <x-admin.form.input label="Path / URL (içeren)" name="path" placeholder="/about" wire:model.live.debounce.500ms="path" />
            </div>
            <div class="lg:col-span-2">
                <x-admin.form.input label="User-Agent (içeren)" name="user_agent" placeholder="Chrome" wire:model.live.debounce.500ms="user_agent" />
            </div>
            <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4 xl:col-span-5">
                <button type="button" wire:click="resetPage" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-sm bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    Filtrele
                </button>
                <button type="button" wire:click="clearFilters" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] transition-colors">
                    Temizle
                </button>
            </div>
        </div>
    </x-admin.card>

    {{-- Tablo --}}
    <x-admin.card class="overflow-hidden relative">
        <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/80 dark:bg-[#1a1a18]/90 rounded-sm">
            <div class="flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-[#D62113]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-sm font-medium text-[#6b7280] dark:text-[#9ca3af]">Yükleniyor...</span>
            </div>
        </div>

        @if($this->logs->isEmpty())
            <x-admin.empty-state title="Kayıt bulunamadı" description="Seçilen filtrelerle eşleşen istek yok. Tarih aralığını veya filtreleri değiştirmeyi deneyin.">
                <x-slot:icon><i data-lucide="inbox" class="mx-auto w-12 h-12 text-[#6b7280] dark:text-[#9ca3af]"></i></x-slot:icon>
            </x-admin.empty-state>
        @else
            <div class="overflow-x-auto max-h-[calc(100vh-28rem)] overflow-y-auto">
                <x-admin.ui.table-wrapper>
                    <x-slot:header>
                        <tr>
                            <x-admin.ui.table-th class="w-[130px]">Tarih</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-[120px]">IP</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-[72px]">Metod</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="min-w-[160px] max-w-[240px]">URL / Path</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-[64px]">Status</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-[72px]">Süre</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-[64px]">Asset</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="min-w-[120px]">Tarayıcı / Cihaz</x-admin.ui.table-th>
                        </tr>
                    </x-slot:header>
                    @foreach($this->logs as $index => $log)
                        <x-admin.ui.table-row wire:key="log-row-{{ $log->id }}" :zebra="$index % 2 === 1" class="cursor-pointer" wire:click.prevent="openLogDetail({{ $log->id }})">
                            <x-admin.ui.table-td class="whitespace-nowrap tabular-nums">{{ $log->visited_at?->format('d/m/Y H:i:s') }}</x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <span class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.page-history.raw', ['ip' => $log->ip_address]) }}" class="text-[#6b7280] dark:text-[#9ca3af] hover:text-[#D62113] dark:hover:text-[#e85c4d] hover:underline text-sm transition-colors" wire:click.stop>{{ $log->ip_address }}</a>
                                    <button type="button" data-copy-ip="{{ $log->ip_address }}" class="p-1 rounded hover:bg-[#e5e5e5] dark:hover:bg-[#333333] text-[#6b7280] dark:text-[#9ca3af] hover:text-[#374151] dark:hover:text-[#f3f4f6] transition-colors" title="IP adresini kopyala" wire:click.stop>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                    </button>
                                </span>
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <x-admin.method-badge :method="$log->method" />
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="max-w-[240px]">
                                <span class="block truncate" title="{{ $log->full_url }}">{{ $log->path ?: '—' }}</span>
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <x-admin.status-badge :code="$log->status_code" />
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td variant="secondary" class="whitespace-nowrap tabular-nums">{{ $log->response_time_ms !== null ? number_format($log->response_time_ms) . ' ms' : '—' }}</x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                @if($log->is_asset_request)
                                    <x-admin.ui.badge variant="default">Evet</x-admin.ui.badge>
                                @else
                                    <span class="text-[#6b7280] dark:text-[#9ca3af]">—</span>
                                @endif
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="min-w-0">
                                <span class="block truncate" title="{{ $log->user_agent }}">{{ \App\Helpers\UserAgentParser::format($log->user_agent) }}</span>
                            </x-admin.ui.table-td>
                        </x-admin.ui.table-row>
                    @endforeach
                </x-admin.ui.table-wrapper>
            </div>

            <x-admin.ui.pagination :paginator="$this->logs" :wire="true" />
        @endif
    </x-admin.card>

    {{-- Detay modal (component) --}}
    <x-admin.request-log-detail-modal :log="$selectedLog" close-action="closeLogDetail" />
</div>

@push('scripts')
<script>
(function () {
    function showCopiedFeedback(btn, ip) {
        if (window.Dialog && typeof window.Dialog.success === 'function') {
            window.Dialog.success('IP adresi panoya kopyalandı: ' + ip, 'Kopyalandı');
        }
    }

    document.body.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-copy-ip]');
        if (!btn) return;
        var ip = btn.getAttribute('data-copy-ip');
        if (!ip) return;
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(ip).then(function () {
                showCopiedFeedback(btn, ip);
            }).catch(function () {
                showCopiedFeedback(btn, ip);
            });
        } else {
            var ta = document.createElement('textarea');
            ta.value = ip;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.left = '-9999px';
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback(btn, ip);
            } finally {
                document.body.removeChild(ta);
            }
        }
    }, true);
})();
</script>
@endpush
