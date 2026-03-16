@extends('layouts.admin')

@section('title', 'Ham İstekler - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Geçmişi — Ham İstekler')

@section('content')
    <x-admin.card class="p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Filtreler</h3>
        <form method="get" action="{{ route('admin.page-history.raw') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                <input type="text" name="ip" value="{{ request('ip') }}" placeholder="192.168.1.1" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Metod</label>
                <select name="method" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    @foreach(['GET','POST','PUT','PATCH','DELETE','HEAD','OPTIONS'] as $m)
                        <option value="{{ $m }}" {{ request('method') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Path / URL (içeren)</label>
                <input type="text" name="path" value="{{ request('path') }}" placeholder="/about" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">User-Agent (içeren)</label>
                <input type="text" name="user_agent" value="{{ request('user_agent') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Status code</label>
                <input type="number" name="status_code" value="{{ request('status_code') }}" placeholder="200" min="100" max="599" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
                <button type="submit" class="px-4 py-2 bg-[#D62113] text-white text-sm font-medium rounded-sm hover:bg-[#b81a0f]">Filtrele</button>
                <a href="{{ route('admin.page-history.raw') }}" class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-sm rounded-sm">Temizle</a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card>
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50">
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tarih</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">IP</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Metod</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">URL / Path</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Süre</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Asset</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">User-Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->visited_at?->format('d/m/Y H:i:s') ?? $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.page-history.raw', ['ip' => $log->ip_address]) }}" class="text-[#D62113] hover:underline">{{ $log->ip_address }}</a>
                            </td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap">
                                <x-admin.method-badge :method="$log->method" />
                            </td>
                            <td class="px-4 lg:px-6 py-3 text-sm max-w-xs truncate" title="{{ $log->full_url }}">{{ Str::limit($log->path, 50) }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm">{{ $log->status_code ?? '—' }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm">{{ $log->response_time_ms !== null ? number_format($log->response_time_ms) . ' ms' : '—' }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm">{{ $log->is_asset_request ? 'Evet' : 'Hayır' }}</td>
                            <td class="px-4 lg:px-6 py-3 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 40) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
            @forelse($logs as $log)
                <div class="p-4 hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ ($log->visited_at ?? $log->created_at)?->format('d/m/Y H:i') }}</span>
                        <x-admin.method-badge :method="$log->method" />
                        @if($log->status_code)<span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ $log->status_code }}</span>@endif
                    </div>
                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] break-all">{{ Str::limit($log->path, 55) }}</p>
                    <dl class="mt-2 space-y-1 text-xs">
                        <div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">IP:</dt> <dd class="inline"><a href="{{ route('admin.page-history.raw', ['ip' => $log->ip_address]) }}" class="text-[#D62113] hover:underline">{{ $log->ip_address }}</a></dd></div>
                        @if($log->response_time_ms !== null)<div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">Süre:</dt> <dd class="inline">{{ number_format($log->response_time_ms) }} ms</dd></div>@endif
                        <div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">Asset:</dt> <dd class="inline">{{ $log->is_asset_request ? 'Evet' : 'Hayır' }}</dd></div>
                        @if($log->user_agent)<div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">UA:</dt> <dd class="inline break-all">{{ Str::limit($log->user_agent, 45) }}</dd></div>@endif
                    </dl>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">Kayıt bulunamadı.</div>
            @endforelse
        </div>

        @if($logs->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">{{ $logs->links() }}</div>
        @endif
    </x-admin.card>
@endsection
