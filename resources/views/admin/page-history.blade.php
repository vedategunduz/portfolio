@extends('layouts.admin')

@section('title', 'Sayfa Ziyaretleri - Admin - ' . config('app.name'))
@section('page-title', 'Sayfa Ziyaretleri')

@section('content')
    <!-- Stats Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Toplam Ziyaret</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $history->total() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Bu Sayfada</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $history->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Sayfa</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $history->currentPage() }} / {{ $history->lastPage() }}</div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tarih
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    IP Adresi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Sayfa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Metod
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tarayıcı
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($history as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    <a href="{{ route('admin.page-history', ['ip' => $log->ip_address]) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        {{ $log->ip_address }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                    {{ $log->path }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($log->method == 'GET') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($log->method == 'POST') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @endif">
                                        {{ $log->method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 50) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Henüz ziyaret kaydı bulunmuyor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($history->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $history->links() }}
                </div>
                @endif
            </div>
        </div>
@endsection
