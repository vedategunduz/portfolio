@extends('layouts.admin')

@section('title', 'Şüpheli / Exploit - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Şüpheli İstekler')

@section('content')
    <x-admin.card class="p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Filtreler</h3>
        <form method="get" action="{{ route('admin.page-history.suspicious') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (başlangıç)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (bitiş)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">IP</label>
                <input type="text" name="ip" value="{{ request('ip') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Olay tipi</label>
                <select name="event_type" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    <option value="suspicious_pattern" {{ request('event_type') === 'suspicious_pattern' ? 'selected' : '' }}>Şüpheli pattern</option>
                    <option value="rate_abuse" {{ request('event_type') === 'rate_abuse' ? 'selected' : '' }}>Rate abuse</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Önem</label>
                <select name="severity" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Düşük</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Orta</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>Yüksek</option>
                    <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Kritik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">URL (içeren)</label>
                <input type="text" name="url" value="{{ request('url') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-[#D62113] text-white text-sm font-medium rounded-sm hover:bg-[#b81a0f]">Filtrele</button>
                <a href="{{ route('admin.page-history.suspicious') }}" class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-sm rounded-sm">Temizle</a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Olay tipi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Başlık</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Eşleşen kural</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Önem</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">User-Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.page-history.suspicious', ['ip' => $log->ip_address]) }}" class="text-[#D62113] hover:underline">{{ $log->ip_address }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->event_type }}</td>
                            <td class="px-6 py-4 text-sm max-w-xs" title="{{ $log->title }}">{{ Str::limit($log->title, 40) }}</td>
                            <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->matched_rule }}">{{ Str::limit($log->matched_rule, 25) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-sm
                                    @if($log->severity === 'critical') bg-red-500/20 text-red-700 dark:text-red-400
                                    @elseif($log->severity === 'high') bg-amber-500/20 text-amber-700 dark:text-amber-400
                                    @elseif($log->severity === 'medium') bg-[#706f6c]/15 text-[#706f6c] dark:text-[#8F8F8B]
                                    @else bg-emerald-500/15 text-emerald-700 dark:text-emerald-400
                                    @endif">{{ $log->severity }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm max-w-xs truncate" title="{{ $log->full_url }}">{{ Str::limit($log->full_url, 40) }}</td>
                            <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 35) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">Kayıt bulunamadı.</td>
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
