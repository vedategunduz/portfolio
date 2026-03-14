@extends('layouts.admin')

@section('title', 'Sınıflandırılmış Trafik - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Sınıflandırılmış Trafik')

@section('content')
    <x-admin.card class="p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Filtreler</h3>
        <form method="get" action="{{ route('admin.page-history.classified') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (başlangıç)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (bitiş)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Trafik tipi</label>
                <select name="traffic_type" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    @foreach(\App\Enums\TrafficType::cases() as $t)
                        <option value="{{ $t->value }}" {{ request('traffic_type') === $t->value ? 'selected' : '' }}>{{ $t->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Risk seviyesi</label>
                <select name="risk_level" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    @foreach(\App\Enums\RiskLevel::cases() as $r)
                        <option value="{{ $r->value }}" {{ request('risk_level') === $r->value ? 'selected' : '' }}>{{ $r->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">IP</label>
                <input type="text" name="ip" value="{{ request('ip') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-[#D62113] text-white text-sm font-medium rounded-sm hover:bg-[#b81a0f]">Filtrele</button>
                <a href="{{ route('admin.page-history.classified') }}" class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-sm rounded-sm">Temizle</a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Trafik tipi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Risk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Kural</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Bot / Sebep</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->visited_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.page-history.classified', ['ip' => $log->ip_address]) }}" class="text-[#D62113] hover:underline">{{ $log->ip_address }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php $t = $log->traffic_type; @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded-sm
                                    @if($t->value === 'human') bg-emerald-500/15 text-emerald-700 dark:text-emerald-400
                                    @elseif($t->value === 'known_bot') bg-[#706f6c]/15 text-[#706f6c] dark:text-[#8F8F8B]
                                    @elseif($t->value === 'suspicious_bot') bg-amber-500/15 text-amber-700 dark:text-amber-400
                                    @else bg-[#706f6c]/15 text-[#706f6c] dark:text-[#8F8F8B]
                                    @endif">{{ $t->label() }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->risk_level->label() }}</td>
                            <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->matched_rule }}">{{ Str::limit($log->matched_rule, 25) }}</td>
                            <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->suspicion_reason ?? $log->bot_name }}">{{ Str::limit($log->suspicion_reason ?? $log->bot_name ?? '—', 35) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">{{ $logs->links() }}</div>
        @endif
    </x-admin.card>
@endsection
