@extends('layouts.admin')

@section('title', 'Admin Dashboard - ' . config('app.name'))
@section('page-title', 'Admin Panel')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-admin.stat-card label="Toplam Ziyaret" :value="number_format($stats['total_visits'])" iconColor="red">
            <x-slot:icon><i data-lucide="eye" class="w-6 h-6"></i></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Benzersiz Ziyaretçi" :value="number_format($stats['unique_visitors'])" iconColor="emerald">
            <x-slot:icon><i data-lucide="users" class="w-6 h-6"></i></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Toplam Mesaj" :value="number_format($stats['total_messages'])" iconColor="violet">
            <x-slot:icon><i data-lucide="mail" class="w-6 h-6"></i></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Okunmamış Mesaj" :value="number_format($stats['unread_messages'])" iconColor="amber">
            <x-slot:icon><i data-lucide="inbox" class="w-6 h-6"></i></x-slot:icon>
        </x-admin.stat-card>
    </div>

    <!-- Server Stats -->
    <x-admin.card class="p-6 mb-8 relative">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Sunucu Durumu</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r border-[#e3e3e0] dark:border-[#3E3E3A] pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">CPU</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $serverStatsData['cpu_percent'] !== null ? number_format((float) $serverStatsData['cpu_percent'], 1, '.', '') . '%' : '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r border-[#e3e3e0] dark:border-[#3E3E3A] pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">RAM</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    @if(is_array($serverStatsData['ram_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['ram_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B]">({{ $serverStatsData['ram_percent']['used_mb'] }}/{{ $serverStatsData['ram_percent']['total_mb'] }} MB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r border-[#e3e3e0] dark:border-[#3E3E3A] pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Disk</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                    @if(is_array($serverStatsData['disk_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['disk_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B]">({{ $serverStatsData['disk_percent']['used_gb'] }}/{{ $serverStatsData['disk_percent']['total_gb'] }} GB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r border-[#e3e3e0] dark:border-[#3E3E3A] pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Uptime</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono">{{ $serverStatsData['uptime'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Load avg</span>
                <span class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono" title="1 dk / 5 dk / 15 dk">{{ $serverStatsData['load_average'] ?? '—' }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Nginx</span>
                @if($serverStatsData['nginx_status'] === 'active')
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>
                @else
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">MySQL</span>
                @if($serverStatsData['mysql_status'] === 'active')
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>
                @else
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">PHP-FPM</span>
                @if($serverStatsData['php_fpm_status'] === 'active')
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>
                @else
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Son deploy</span>
                <span class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $serverStatsData['last_deploy'] ? \Carbon\Carbon::parse($serverStatsData['last_deploy'])->format('d.m.Y H:i') : '—' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Failed jobs</span>
                @if($serverStatsData['failed_jobs_count'] === 0)
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">{{ number_format($serverStatsData['failed_jobs_count']) }}</span>
                @else
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-700 dark:bg-amber-500/25 dark:text-amber-400">{{ number_format($serverStatsData['failed_jobs_count']) }}</span>
                @endif
            </div>
        </div>
        <p class="mt-4 pt-3 border-t border-[#e3e3e0] dark:border-[#3E3E3A] text-right text-xs text-[#706f6c] dark:text-[#8F8F8B]">Son güncelleme: {{ $serverStatsData['updated_at'] }}</p>
    </x-admin.card>

    <!-- Quick Links & System Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-admin.card class="p-6">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Hızlı Erişim</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.page-history') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Sayfa Ziyaretlerini Görüntüle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Detaylı ziyaret loglarını inceleyin</p>
                </a>
                <a href="{{ route('admin.contact-messages') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">İletişim Mesajlarını Görüntüle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Gelen mesajları okuyun</p>
                </a>
            </div>
        </x-admin.card>

        <x-admin.card class="p-6">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Sistem Bilgisi</h3>
            <dl class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Laravel</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">PHP</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex justify-between items-center py-2">
                    <dt class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Ortam</dt>
                    <dd class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ config('app.env') }}</dd>
                </div>
            </dl>
        </x-admin.card>
    </div>
@endsection
