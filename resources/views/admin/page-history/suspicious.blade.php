@extends('layouts.admin')

@section('title', 'Şüpheli / Exploit - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Şüpheli İstekler')

@php
    $eventTypeOptions = ['' => 'Tümü', 'suspicious_pattern' => 'Şüpheli pattern', 'rate_abuse' => 'Rate abuse'];
    $severityOptions = ['' => 'Tümü', 'low' => 'Düşük', 'medium' => 'Orta', 'high' => 'Yüksek', 'critical' => 'Kritik'];
@endphp

@section('content')
    <x-admin.ui.filter-card title="Filtreler" :action="route('admin.page-history.suspicious')" method="get">
        <x-admin.form.input label="Tarih (başlangıç)" type="date" name="date_from" value="{{ request('date_from') }}" />
        <x-admin.form.input label="Tarih (bitiş)" type="date" name="date_to" value="{{ request('date_to') }}" />
        <x-admin.form.input label="IP" name="ip" value="{{ request('ip') }}" />
        <x-admin.form.select label="Olay tipi" name="event_type" :options="$eventTypeOptions" :selected="request('event_type')" />
        <x-admin.form.select label="Önem" name="severity" :options="$severityOptions" :selected="request('severity')" />
        <div class="sm:col-span-2 lg:col-span-1">
            <x-admin.form.input label="URL (içeren)" name="url" value="{{ request('url') }}" />
        </div>
        <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
            <x-admin.ui.button variant="primary" type="submit">Filtrele</x-admin.ui.button>
            <x-admin.ui.button variant="secondary" :href="route('admin.page-history.suspicious')">Temizle</x-admin.ui.button>
        </div>
    </x-admin.ui.filter-card>

    <x-admin.card>
        <div class="hidden md:block overflow-x-auto">
            <x-admin.ui.table-wrapper>
                <x-slot:header>
                    <tr>
                        <x-admin.ui.table-th>Tarih</x-admin.ui.table-th>
                        <x-admin.ui.table-th>IP</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Olay tipi</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Başlık</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Eşleşen kural</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Önem</x-admin.ui.table-th>
                        <x-admin.ui.table-th>URL</x-admin.ui.table-th>
                        <x-admin.ui.table-th>User-Agent</x-admin.ui.table-th>
                    </tr>
                </x-slot:header>
                @forelse($logs as $log)
                    <x-admin.ui.table-row :zebra="$loop->iteration % 2 === 0">
                        <x-admin.ui.table-td class="whitespace-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">
                            <a href="{{ route('admin.page-history.suspicious', ['ip' => $log->ip_address]) }}" class="text-[#6b7280] dark:text-[#9ca3af] hover:text-[#D62113] dark:hover:text-[#e85c4d] hover:underline transition-colors">{{ $log->ip_address }}</a>
                        </x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">{{ $log->event_type }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td class="max-w-xs truncate" title="{{ $log->title }}">{{ Str::limit($log->title, 40) }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="max-w-xs truncate" title="{{ $log->matched_rule }}">{{ Str::limit($log->matched_rule, 25) }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">
                            @php
                                $sev = $log->severity;
                                $badgeVariant = match($sev) { 'critical' => 'danger', 'high' => 'warning', 'medium' => 'default', default => 'success' };
                            @endphp
                            <x-admin.ui.badge :variant="$badgeVariant">{{ $sev ?? 'low' }}</x-admin.ui.badge>
                        </x-admin.ui.table-td>
                        <x-admin.ui.table-td class="max-w-xs truncate" title="{{ $log->full_url }}">{{ Str::limit($log->full_url, 40) }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 35) }}</x-admin.ui.table-td>
                    </x-admin.ui.table-row>
                @empty
                    <tr>
                        <td colspan="8" class="px-3 lg:px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </x-admin.ui.table-wrapper>
        </div>

        <div class="md:hidden divide-y divide-[#e5e5e5] dark:divide-[#333333]">
            @forelse($logs as $log)
                <div class="p-4 hover:bg-[#f5f5f5] dark:hover:bg-[#252525] transition-colors duration-150">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs text-[#6b7280] dark:text-[#9ca3af]">{{ $log->created_at?->format('d/m/Y H:i') }}</span>
                        @php $badgeVariant = match($log->severity) { 'critical' => 'danger', 'high' => 'warning', 'medium' => 'default', default => 'success' }; @endphp
                        <x-admin.ui.badge :variant="$badgeVariant">{{ $log->severity ?? 'low' }}</x-admin.ui.badge>
                        <span class="text-xs text-[#6b7280] dark:text-[#9ca3af]">{{ $log->event_type }}</span>
                    </div>
                    <p class="text-sm font-medium text-[#111827] dark:text-[#f3f4f6] wrap-break-word">{{ Str::limit($log->title, 60) }}</p>
                    <dl class="mt-2 space-y-1 text-xs text-[#6b7280] dark:text-[#9ca3af]">
                        <div><dt class="inline">IP:</dt> <dd class="inline"><a href="{{ route('admin.page-history.suspicious', ['ip' => $log->ip_address]) }}" class="text-[#D62113] dark:text-[#e85c4d] hover:underline">{{ $log->ip_address }}</a></dd></div>
                        @if($log->matched_rule)<div><dt class="inline">Kural:</dt> <dd class="inline break-all">{{ Str::limit($log->matched_rule, 50) }}</dd></div>@endif
                        @if($log->full_url)<div><dt class="inline">URL:</dt> <dd class="inline break-all">{{ Str::limit($log->full_url, 50) }}</dd></div>@endif
                        @if($log->user_agent)<div><dt class="inline">UA:</dt> <dd class="inline break-all">{{ Str::limit($log->user_agent, 45) }}</dd></div>@endif
                    </dl>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">Kayıt bulunamadı.</div>
            @endforelse
        </div>

        @if($logs->hasPages())
            <x-admin.ui.pagination-wrap>{{ $logs->links() }}</x-admin.ui.pagination-wrap>
        @endif
    </x-admin.card>
@endsection
