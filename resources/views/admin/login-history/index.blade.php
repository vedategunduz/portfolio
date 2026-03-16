@extends('layouts.admin')

@section('title', 'Giriş Geçmişi - Admin - ' . config('app.name'))
@section('page-title', 'Giriş Geçmişi')

@section('content')
    <x-admin.card class="p-6 mb-6">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Filtreler</h3>
        <form method="get" action="{{ route('admin.login-history.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (başlangıç)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tarih (bitiş)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">Tip</label>
                <select name="type" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
                    <option value="">Tümü</option>
                    <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Başarılı</option>
                    <option value="failed" {{ request('type') === 'failed' ? 'selected' : '' }}>Başarısız</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">E-posta (içeren)</label>
                <input type="text" name="email" value="{{ request('email') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs text-[#706f6c] dark:text-[#8F8F8B] mb-1">IP</label>
                <input type="text" name="ip" value="{{ request('ip') }}" class="w-full rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] bg-white dark:bg-[#1a1a18] px-3 py-2 text-sm">
            </div>
            <div class="flex flex-wrap items-end gap-2 sm:col-span-2 lg:col-span-4">
                <button type="submit" class="px-4 py-2 bg-[#D62113] text-white text-sm font-medium rounded-sm hover:bg-[#b81a0f]">Filtrele</button>
                <a href="{{ route('admin.login-history.index') }}" class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-sm rounded-sm">Temizle</a>
            </div>
        </form>
    </x-admin.card>

    <x-admin.card>
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50">
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tarih</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tip</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">E-posta</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">IP</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Sebep</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">User-Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->attempted_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 lg:px-6 py-3 whitespace-nowrap">
                                @if($log->isSuccess())
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-sm bg-emerald-500/15 text-emerald-700 dark:text-emerald-400">Başarılı</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-sm bg-red-500/20 text-red-700 dark:text-red-400">Başarısız</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-3 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->email }}</td>
                            <td class="px-4 lg:px-6 py-3 text-sm text-[#706f6c] dark:text-[#8F8F8B]">{{ $log->ip_address ?? '—' }}</td>
                            <td class="px-4 lg:px-6 py-3 text-sm text-[#706f6c] dark:text-[#8F8F8B]">
                                {{ $log->getFailureReasonLabel() ?? '—' }}
                            </td>
                            <td class="px-4 lg:px-6 py-3 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 35) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">Kayıt bulunamadı.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
            @forelse($logs as $log)
                <div class="p-4 hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">{{ $log->attempted_at?->format('d/m/Y H:i') }}</span>
                        @if($log->isSuccess())
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-sm bg-emerald-500/15 text-emerald-700 dark:text-emerald-400">Başarılı</span>
                        @else
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-sm bg-red-500/20 text-red-700 dark:text-red-400">Başarısız</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->email }}</p>
                    <dl class="mt-2 space-y-1 text-xs">
                        @if($log->ip_address)<div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">IP:</dt> <dd class="inline">{{ $log->ip_address }}</dd></div>@endif
                        @if($log->failure_reason)<div><dt class="inline text-[#706f6c] dark:text-[#8F8F8B]">Sebep:</dt> <dd class="inline">{{ $log->getFailureReasonLabel() }}</dd></div>@endif
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
