@extends('layouts.admin')

@section('title', 'Sayfa Ziyaretleri - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Ziyaretleri')

@section('content')
    <!-- Stats Summary -->
    <x-admin.card class="p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Toplam Ziyaret</p>
                <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $history->total() }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Bu Sayfada</p>
                <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $history->count() }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Sayfa</p>
                <p class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mt-1">{{ $history->currentPage() }} / {{ $history->lastPage() }}</p>
            </div>
        </div>
    </x-admin.card>

    <!-- Table -->
    <x-admin.card>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a]/50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">IP Adresi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Gidilen URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Metod</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Yanıt süresi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#706f6c] dark:text-[#8F8F8B] uppercase tracking-wider">Tarayıcı</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                    @forelse($history as $log)
                        <tr class="hover:bg-[#FDFDFC] dark:hover:bg-[#0a0a0a]/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.page-history', ['ip' => $log->ip_address]) }}" class="text-[#D62113] hover:underline">{{ $log->ip_address }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ url($log->path) }}" target="_blank" rel="noopener noreferrer" class="text-[#D62113] hover:underline break-all" title="{{ url($log->path) }}">{{ url($log->path) }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.method-badge :method="$log->method" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                @if($log->response_time_ms !== null)
                                    {{ number_format($log->response_time_ms) }} ms
                                @else
                                    <span class="text-[#706f6c] dark:text-[#8F8F8B]">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#706f6c] dark:text-[#8F8F8B] max-w-xs truncate" title="{{ $log->user_agent }}">{{ Str::limit($log->user_agent, 50) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-[#706f6c] dark:text-[#8F8F8B]">
                                Henüz ziyaret kaydı bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($history->hasPages())
            <div class="px-6 py-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                {{ $history->links() }}
            </div>
        @endif
    </x-admin.card>
@endsection
