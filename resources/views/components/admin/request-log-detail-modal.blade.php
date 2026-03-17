@props([
    'log', // \App\Models\RawRequestLog
    'closeAction' => 'closeLogDetail',
])

@if($log)
    <div wire:key="request-log-modal-{{ $log->id }}"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        wire:click="{{ $closeAction }}">
        <div class="w-full max-w-2xl max-h-[85vh] overflow-hidden rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] shadow-xl" wire:click.stop>
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#F8F8F7] dark:bg-[#141413]">
                <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">İstek Detayı</h3>
                <button type="button" wire:click="{{ $closeAction }}" class="p-2 rounded-sm hover:bg-[#e3e3e0] dark:hover:bg-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(85vh-8rem)] text-sm space-y-3">
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] items-center"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Metod</span><span class="w-fit"><x-admin.method-badge :method="$log->method" /></span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Path</span><span class="break-all">{{ $log->path }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Tam URL</span><span class="break-all">{{ $log->full_url }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Query</span><span class="break-all">{{ $log->query_string ?? '—' }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Referer</span><span class="break-all">{{ $log->referer ?? '—' }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">IP</span><span>{{ $log->ip_address }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] items-center"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Status</span><span class="w-fit"><x-admin.status-badge :code="$log->status_code" /></span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Süre</span><span>{{ $log->response_time_ms !== null ? $log->response_time_ms . ' ms' : '—' }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Tarih</span><span>{{ $log->visited_at?->format('d/m/Y H:i:s') }}</span></div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] items-center"><span class="text-[#706f6c] dark:text-[#8F8F8B]">Asset</span>@if($log->is_asset_request)<span class="w-fit"><x-admin.ui.badge variant="default">Evet</x-admin.ui.badge></span>@else<span class="text-[#706f6c] dark:text-[#8F8F8B]">Hayır</span>@endif</div>
                <div class="grid grid-cols-[100px_1fr] gap-2 py-2"><span class="text-[#706f6c] dark:text-[#8F8F8B]">User-Agent</span><span class="break-all">{{ $log->user_agent ?? '—' }}</span></div>
            </div>
        </div>
    </div>
@endif
