<div>
    {{-- İstatistik kartları (kompakt) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <x-admin.stat-card label="{{ __('messages.log.stats.total') }}" :value="number_format($this->stats['total_requests'])" iconColor="red" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="{{ __('messages.log.stats.avg_response') }}" :value="$this->stats['avg_response_ms'] !== null ? number_format($this->stats['avg_response_ms'], 0) . ' ms' : '—'" iconColor="violet" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="{{ __('messages.log.stats.error_rate') }}" :value="$this->stats['error_rate_percent'] . '%'" iconColor="amber" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="{{ __('messages.log.stats.top_endpoint') }}" iconColor="emerald" compact>
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></x-slot:icon>
            <x-slot:valueSlot>
                @if($this->stats['top_endpoint'])
                    <span class="block truncate text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC]" title="{{ $this->stats['top_endpoint']->path }}">{{ $this->stats['top_endpoint']->path ?: '—' }}</span>
                @else
                    —
                @endif
            </x-slot:valueSlot>
        </x-admin.stat-card>
    </div>

    {{-- Filtreler --}}
    @php
        $activeFilterCount = collect([
            $date_from,
            $date_to,
            $ip,
            $method,
            $path,
            $user_agent,
            $status_code,
        ])->filter(fn ($value) => (string) $value !== '')->count();
    @endphp
    <x-admin.card class="mb-6">
        <details class="group" {{ $activeFilterCount > 0 ? 'open' : '' }}>
            <summary class="list-none cursor-pointer px-4 py-3 [&::-webkit-details-marker]:hidden">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] uppercase tracking-wider">{{ __('messages.log.filters') }}</h3>
                        @if($activeFilterCount > 0)
                            <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ $activeFilterCount }} {{ __('messages.log.filters.active') }}</span>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#706f6c] dark:text-[#8F8F8B] transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </summary>

            <div class="border-t border-[#e3e3e0] dark:border-[#3E3E3A] p-4">
                <div class="flex flex-wrap items-center justify-end gap-3 mb-3">
                    <a href="{{ $this->exportUrl }}" class="group inline-flex items-center gap-2 rounded-sm border border-[#d9d9d6] dark:border-[#3E3E3A] bg-linear-to-b from-white to-[#f7f7f5] dark:from-[#171716] dark:to-[#111110] px-3.5 py-2 text-sm font-semibold text-[#2b2b28] dark:text-[#f5f5f4] transition-colors duration-200 hover:border-[#D62113]/40 hover:text-[#D62113] dark:hover:text-[#ff7569] hover:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D62113]/35">
                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-sm bg-[#D62113]/10 text-[#D62113] dark:bg-[#D62113]/20 dark:text-[#ff7569]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        </span>
                        {{ __('messages.log.export') }}
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    <x-admin.form.input label="{{ __('messages.log.date_from') }}" type="date" name="date_from" wire:model.live="date_from" />
                    <x-admin.form.input label="{{ __('messages.log.date_to') }}" type="date" name="date_to" wire:model.live="date_to" />
                    <x-admin.form.input label="{{ __('messages.log.ip') }}" name="ip" placeholder="192.168.1.1" wire:model.live.debounce.500ms="ip" />
                    <x-admin.form.select label="{{ __('messages.log.method') }}" name="method" :options="['' => __('messages.log.all_methods'), 'GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT', 'PATCH' => 'PATCH', 'DELETE' => 'DELETE', 'HEAD' => 'HEAD', 'OPTIONS' => 'OPTIONS']" :selected="$method" wire:model.live="method" />
                    <x-admin.form.select
                        label="{{ __('messages.log.status_code') }}"
                        name="status_code"
                        :options="[
                            '' => __('messages.log.all_status'),
                            '200' => __('messages.status.200'),
                            '201' => __('messages.status.201'),
                            '204' => __('messages.status.204'),
                            '301' => __('messages.status.301'),
                            '302' => __('messages.status.302'),
                            '304' => __('messages.status.304'),
                            '400' => __('messages.status.400'),
                            '401' => __('messages.status.401'),
                            '403' => __('messages.status.403'),
                            '404' => __('messages.status.404'),
                            '405' => __('messages.status.405'),
                            '419' => __('messages.status.419'),
                            '422' => __('messages.status.422'),
                            '429' => __('messages.status.429'),
                            '500' => __('messages.status.500'),
                            '502' => __('messages.status.502'),
                            '503' => __('messages.status.503'),
                            '504' => __('messages.status.504'),
                        ]"
                        :selected="$status_code"
                        wire:model.live="status_code"
                    />
                    <div class="lg:col-span-2">
                        <x-admin.form.input label="{{ __('messages.log.path') }}" name="path" placeholder="/about" wire:model.live.debounce.500ms="path" />
                    </div>
                    <div class="lg:col-span-2">
                        <x-admin.form.input label="{{ __('messages.log.user_agent') }}" name="user_agent" placeholder="Chrome" wire:model.live.debounce.500ms="user_agent" />
                    </div>
                    <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4 xl:col-span-5">
                        <button type="button" wire:click="resetPage" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-sm bg-[#D62113] text-white hover:bg-[#b81a0f] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            {{ __('messages.log.filter_btn') }}
                        </button>
                        <button type="button" wire:click="clearFilters" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#8F8F8B] hover:bg-[#e3e3e0]/80 dark:hover:bg-[#3E3E3A] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            {{ __('messages.log.clear_filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </details>
    </x-admin.card>

    {{-- Tablo --}}
    <x-admin.card class="overflow-hidden relative">
        <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/80 dark:bg-[#1a1a18]/90 rounded-sm">
            <div class="flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-[#D62113]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-sm font-medium text-[#6b7280] dark:text-[#9ca3af]">{{ __('messages.log.loading') }}</span>
            </div>
        </div>

        @if($this->logs->isEmpty())
            <x-admin.empty-state title="{{ __('messages.log.no_records') }}" description="{{ __('messages.log.no_records_desc') }}">
                <x-slot:icon><i data-lucide="inbox" class="mx-auto w-12 h-12 text-[#6b7280] dark:text-[#9ca3af]"></i></x-slot:icon>
            </x-admin.empty-state>
        @else
            <div class="overflow-x-auto max-h-[calc(100vh-28rem)] overflow-y-auto">
                <x-admin.ui.table-wrapper tableClass="min-w-full table-fixed text-sm">
                    <x-slot:header>
                        <tr>
                            <x-admin.ui.table-th class="w-32.5">{{ __('messages.log.date_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-30">{{ __('messages.log.ip_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-18">{{ __('messages.log.method_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="min-w-40 max-w-60">{{ __('messages.log.url_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-16">{{ __('messages.log.status_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-18">{{ __('messages.log.duration_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-16">{{ __('messages.log.asset_column') }}</x-admin.ui.table-th>
                            <x-admin.ui.table-th class="w-44 max-w-44">{{ __('messages.log.browser_column') }}</x-admin.ui.table-th>
                        </tr>
                    </x-slot:header>
                    @foreach($this->logs as $index => $log)
                        <x-admin.ui.table-row wire:key="log-row-{{ $log->id }}" :zebra="$index % 2 === 1" class="cursor-pointer" wire:click.prevent="openLogDetail({{ $log->id }})">
                            <x-admin.ui.table-td class="whitespace-nowrap tabular-nums">{{ $log->visited_at?->format('d/m/Y H:i:s') }}</x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <span class="inline-flex items-center gap-1">
                                    <button type="button" data-copy-ip="{{ $log->ip_address }}" class="p-1 rounded hover:bg-[#e5e5e5] dark:hover:bg-[#333333] text-[#6b7280] dark:text-[#9ca3af] hover:text-[#374151] dark:hover:text-[#f3f4f6] transition-colors" title="{{ __('messages.log.copy_ip') }}" wire:click.stop>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                    </button>
                                    <a href="{{ route('admin.page-history.raw', ['ip' => $log->ip_address]) }}" class="text-[#6b7280] dark:text-[#9ca3af] hover:text-[#D62113] dark:hover:text-[#e85c4d] hover:underline text-sm transition-colors" wire:click.stop>{{ $log->ip_address }}</a>
                                </span>
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <x-admin.method-badge :method="$log->method" />
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="max-w-60">
                                <span class="block truncate" title="{{ $log->full_url }}">{{ $log->path ?: '—' }}</span>
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                <x-admin.status-badge :code="$log->status_code" />
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td variant="secondary" class="whitespace-nowrap tabular-nums">{{ $log->response_time_ms !== null ? number_format($log->response_time_ms) . ' ms' : '—' }}</x-admin.ui.table-td>
                            <x-admin.ui.table-td class="whitespace-nowrap">
                                @if($log->is_asset_request)
                                    <x-admin.ui.badge variant="default">{{ __('messages.log.yes') }}</x-admin.ui.badge>
                                @else
                                    <span class="text-[#6b7280] dark:text-[#9ca3af]">—</span>
                                @endif
                            </x-admin.ui.table-td>
                            <x-admin.ui.table-td class="w-44 max-w-44 min-w-0">
                                <span class="block truncate" title="{{ $log->user_agent }}">{{ \App\Helpers\UserAgentParser::format($log->user_agent) }}</span>
                            </x-admin.ui.table-td>
                        </x-admin.ui.table-row>
                    @endforeach
                </x-admin.ui.table-wrapper>
            </div>

            <x-admin.ui.pagination :paginator="$this->logs" :wire="true" />
        @endif
    </x-admin.card>

    {{-- Detailı modal (component) --}}
    <x-admin.request-log-detail-modal :log="$selectedLog" close-action="closeLogDetail" />
</div>

@push('scripts')
<script>
(function () {
    function showCopiedFeedback(btn, ip) {
        if (window.Dialog && typeof window.Dialog.success === 'function') {
            window.Dialog.success('{{ __('messages.log.copy_ip') }}: ' + ip, 'Copied');
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
