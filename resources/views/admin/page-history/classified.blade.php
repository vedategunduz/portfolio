@extends('layouts.admin')

@section('title', 'Sınıflandırılmış Trafik - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Sınıflandırılmış Trafik')

@php
    $trafficTypeOptions = ['' => 'Tümü'] + collect(\App\Enums\TrafficType::cases())->mapWithKeys(fn ($t) => [$t->value => $t->label()])->all();
    $riskLevelOptions = ['' => 'Tümü'] + collect(\App\Enums\RiskLevel::cases())->mapWithKeys(fn ($r) => [$r->value => $r->label()])->all();
@endphp

@section('content')
    <x-admin.ui.filter-card title="Filtreler" :action="route('admin.page-history.classified')" method="get">
        <x-admin.form.input label="Tarih (başlangıç)" type="date" name="date_from" value="{{ request('date_from') }}" />
        <x-admin.form.input label="Tarih (bitiş)" type="date" name="date_to" value="{{ request('date_to') }}" />
        <x-admin.form.select label="Trafik tipi" name="traffic_type" :options="$trafficTypeOptions" :selected="request('traffic_type')" />
        <x-admin.form.select label="Risk seviyesi" name="risk_level" :options="$riskLevelOptions" :selected="request('risk_level')" />
        <div class="sm:col-span-2 lg:col-span-1">
            <x-admin.form.input label="IP" name="ip" value="{{ request('ip') }}" placeholder="192.168.1.1" />
        </div>
        <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
            <x-admin.ui.button variant="primary" type="submit">Filtrele</x-admin.ui.button>
            <x-admin.ui.button variant="secondary" :href="route('admin.page-history.classified')">Temizle</x-admin.ui.button>
        </div>
    </x-admin.ui.filter-card>

    <x-admin.card>
        <div class="hidden md:block overflow-x-auto">
            <x-admin.ui.table-wrapper>
                <x-slot:header>
                    <tr>
                        <x-admin.ui.table-th>Tarih</x-admin.ui.table-th>
                        <x-admin.ui.table-th>IP</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Trafik tipi</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Risk</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Kural</x-admin.ui.table-th>
                        <x-admin.ui.table-th>Bot / Sebep</x-admin.ui.table-th>
                    </tr>
                </x-slot:header>
                @forelse($logs as $log)
                    @php
                        $t = $log->traffic_type;
                        $trafficVariant = match($t->value) { 'human' => 'success', 'suspicious_bot' => 'warning', default => 'default' };
                    @endphp
                    <x-admin.ui.table-row :zebra="$loop->iteration % 2 === 0">
                        <x-admin.ui.table-td class="whitespace-nowrap">{{ $log->visited_at?->format('d/m/Y H:i:s') }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">
                            <a href="{{ route('admin.page-history.classified', ['ip' => $log->ip_address]) }}" class="text-[#6b7280] dark:text-[#9ca3af] hover:text-[#D62113] dark:hover:text-[#e85c4d] hover:underline transition-colors">{{ $log->ip_address }}</a>
                        </x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">
                            <x-admin.ui.badge :variant="$trafficVariant">{{ $t->label() }}</x-admin.ui.badge>
                        </x-admin.ui.table-td>
                        <x-admin.ui.table-td class="whitespace-nowrap">{{ $log->risk_level->label() }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="max-w-xs truncate" title="{{ $log->matched_rule }}">{{ Str::limit($log->matched_rule, 25) }}</x-admin.ui.table-td>
                        <x-admin.ui.table-td variant="secondary" class="max-w-xs truncate" title="{{ $log->suspicion_reason ?? $log->bot_name }}">{{ Str::limit($log->suspicion_reason ?? $log->bot_name ?? '—', 35) }}</x-admin.ui.table-td>
                    </x-admin.ui.table-row>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 lg:px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </x-admin.ui.table-wrapper>
        </div>

        <div class="md:hidden divide-y divide-[#e5e5e5] dark:divide-[#333333]">
            @forelse($logs as $log)
                @php $t = $log->traffic_type; $trafficVariant = match($t->value) { 'human' => 'success', 'suspicious_bot' => 'warning', default => 'default' }; @endphp
                <div class="p-4 hover:bg-[#f5f5f5] dark:hover:bg-[#252525] transition-colors duration-150">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs text-[#6b7280] dark:text-[#9ca3af]">{{ $log->visited_at?->format('d/m/Y H:i') }}</span>
                        <x-admin.ui.badge :variant="$trafficVariant">{{ $t->label() }}</x-admin.ui.badge>
                        <span class="text-xs text-[#6b7280] dark:text-[#9ca3af]">{{ $log->risk_level->label() }}</span>
                    </div>
                    <dl class="space-y-1 text-xs text-[#6b7280] dark:text-[#9ca3af]">
                        <div><dt class="inline">IP:</dt> <dd class="inline"><a href="{{ route('admin.page-history.classified', ['ip' => $log->ip_address]) }}" class="text-[#D62113] dark:text-[#e85c4d] hover:underline">{{ $log->ip_address }}</a></dd></div>
                        @if($log->matched_rule)<div><dt class="inline">Kural:</dt> <dd class="inline break-all">{{ Str::limit($log->matched_rule, 50) }}</dd></div>@endif
                        @if($log->suspicion_reason ?? $log->bot_name)<div><dt class="inline">Bot/Sebep:</dt> <dd class="inline break-all">{{ Str::limit($log->suspicion_reason ?? $log->bot_name ?? '—', 50) }}</dd></div>@endif
                    </dl>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">Kayıt bulunamadı.</div>
            @endforelse
        </div>

        @if($logs->hasPages())
            <x-admin.ui.pagination :paginator="$logs" />
        @endif
    </x-admin.card>
@endsection
