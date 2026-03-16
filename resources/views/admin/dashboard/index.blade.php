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

    <!-- Ziyaretçi / Trafik Özeti (sınıflandırılmış) -->
    <x-admin.card class="p-6 mb-8">
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Trafik Özeti (İnsan / Bot Ayrımı)</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Toplam hit</p>
                <p class="text-lg sm:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['total_hits'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">İnsan hit</p>
                <p class="text-lg sm:text-xl font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['human_hits'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Bilinen bot</p>
                <p class="text-lg sm:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['known_bot_hits'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Şüpheli hit</p>
                <p class="text-lg sm:text-xl font-semibold text-amber-600 dark:text-amber-400">{{ number_format($stats['suspicious_hits'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Benzersiz insan</p>
                <p class="text-lg sm:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['unique_human_visitors'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Bugünkü hit</p>
                <p class="text-lg sm:text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">{{ number_format($stats['today_hits'] ?? 0) }}</p>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Son 24h şüpheli</p>
                <p class="text-lg sm:text-xl font-semibold text-red-600 dark:text-red-400">{{ number_format($stats['suspicious_last_24h'] ?? 0) }}</p>
            </div>
            <div class="min-w-0 col-span-2 sm:col-span-1">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En çok istek atan IP</p>
                <p class="text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] break-all" title="{{ $stats['top_request_ip']?->ip_address ?? '—' }}">
                    @if(!empty($stats['top_request_ip']))
                        {{ $stats['top_request_ip']->ip_address }} ({{ number_format($stats['top_request_ip']->c) }})
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="min-w-0 col-span-2 sm:col-span-1">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En hedeflenen URL</p>
                <p class="text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] break-all" title="{{ $stats['top_target_url']?->path ?? '—' }}">
                    @if(!empty($stats['top_target_url']))
                        {{ Str::limit($stats['top_target_url']->path, 35) }} ({{ number_format($stats['top_target_url']->c) }})
                    @else
                        —
                    @endif
                </p>
            </div>
            <div class="min-w-0 col-span-2 sm:col-span-1">
                <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">En sık şüpheli pattern</p>
                <p class="text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] break-all" title="{{ $stats['top_suspicious_pattern']?->matched_rule ?? '—' }}">
                    @if(!empty($stats['top_suspicious_pattern']))
                        {{ Str::limit($stats['top_suspicious_pattern']->matched_rule, 28) }} ({{ number_format($stats['top_suspicious_pattern']->c) }})
                    @else
                        —
                    @endif
                </p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A] flex flex-wrap gap-3">
            <a href="{{ route('admin.page-history.raw') }}" class="text-xs font-medium text-[#D62113] hover:underline">Ham istekler</a>
            <a href="{{ route('admin.page-history.classified') }}" class="text-xs font-medium text-[#D62113] hover:underline">Sınıflandırılmış trafik</a>
            <a href="{{ route('admin.page-history.suspicious') }}" class="text-xs font-medium text-[#D62113] hover:underline">Şüpheli / exploit</a>
        </div>
    </x-admin.card>

    <!-- Server Stats -->
    <x-admin.card class="p-6 mb-8 relative" id="server-stats-card" data-api-url="{{ route('admin.api.server-stats') }}">
        @php
            $cpuP = $serverStatsData['cpu_percent'] !== null ? (float) $serverStatsData['cpu_percent'] : null;
            $ramP = isset($serverStatsData['ram_percent']['percent']) ? (float) $serverStatsData['ram_percent']['percent'] : null;
            $diskP = isset($serverStatsData['disk_percent']['percent']) ? (float) $serverStatsData['disk_percent']['percent'] : null;
            $statColor = function($p) {
                if ($p === null) return 'text-[#1b1b18] dark:text-[#EDEDEC]';
                if ($p >= 90) return 'text-red-600 dark:text-red-400';
                if ($p >= 75) return 'text-amber-600 dark:text-amber-400';
                return 'text-[#1b1b18] dark:text-[#EDEDEC]';
            };
        @endphp
        <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Sunucu Durumu</h3>
        <p id="server-stats-error" class="hidden text-xs text-amber-600 dark:text-amber-400 mb-2">Veri alınamadı</p>
        <div id="server-stats-content">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-2 sm:gap-4">
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 lg:border-r lg:pr-4 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">CPU</span>
                <span id="server-stats-cpu" class="text-xs sm:text-sm font-medium {{ $statColor($cpuP) }}">{{ $cpuP !== null ? number_format($cpuP, 1, '.', '') . '%' : '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 lg:border-r lg:pr-4 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">RAM</span>
                <span id="server-stats-ram" class="text-xs sm:text-sm font-medium {{ $statColor($ramP) }} min-w-0 text-right">
                    @if(is_array($serverStatsData['ram_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['ram_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B] hidden sm:inline">({{ $serverStatsData['ram_percent']['used_mb'] }}/{{ $serverStatsData['ram_percent']['total_mb'] }} MB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 lg:border-r lg:pr-4 min-w-0 col-span-2 sm:col-span-1">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">Disk</span>
                <span id="server-stats-disk" class="text-xs sm:text-sm font-medium {{ $statColor($diskP) }} min-w-0 text-right">
                    @if(is_array($serverStatsData['disk_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['disk_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B] hidden sm:inline">({{ $serverStatsData['disk_percent']['used_gb'] }}/{{ $serverStatsData['disk_percent']['total_gb'] }} GB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center gap-2 py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 lg:border-r lg:pr-4 min-w-0 col-span-2 sm:col-span-1">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">Uptime</span>
                <span id="server-stats-uptime" class="text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono truncate" title="{{ $serverStatsData['uptime'] ?? '—' }}">{{ $serverStatsData['uptime'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between items-center gap-2 py-2 min-w-0 col-span-2 lg:col-span-1">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0" title="1, 5 ve 15 dakika yük ortalaması">Load avg</span>
                <span id="server-stats-load" class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono text-right truncate" title="1 dk / 5 dk / 15 dk">{{ $serverStatsData['load_average'] ?? '—' }}</span>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-3 mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex justify-between items-center gap-2 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">Nginx</span>
                <span id="server-stats-nginx">@if($serverStatsData['nginx_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center gap-2 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">MySQL</span>
                <span id="server-stats-mysql">@if($serverStatsData['mysql_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center gap-2 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">PHP-FPM</span>
                <span id="server-stats-phpfpm">@if($serverStatsData['php_fpm_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center gap-2 min-w-0 col-span-2 sm:col-span-1">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">Son deploy</span>
                <span id="server-stats-deploy" class="text-xs sm:text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] truncate">{{ $serverStatsData['last_deploy'] ? \Carbon\Carbon::parse($serverStatsData['last_deploy'])->format('d.m.Y H:i') : '—' }}</span>
            </div>
            <div class="flex justify-between items-center gap-2 min-w-0">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B] shrink-0">Failed jobs</span>
                <span id="server-stats-failedjobs">@if($serverStatsData['failed_jobs_count'] === 0)<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">{{ number_format($serverStatsData['failed_jobs_count']) }}</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">{{ number_format($serverStatsData['failed_jobs_count']) }}</span>@endif</span>
            </div>
        </div>
        <p class="mt-4 pt-3 border-t border-[#e3e3e0] dark:border-[#3E3E3A] text-right text-xs text-[#706f6c] dark:text-[#8F8F8B]">Son güncelleme: <span id="server-stats-updated">{{ $serverStatsData['updated_at'] }}</span></p>
        </div>
    </x-admin.card>

    <!-- Quick Links & System Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-admin.card class="p-6">
            <h3 class="text-sm font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4 uppercase tracking-wider">Hızlı Erişim</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.page-history.raw') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Sayfa Geçmişi — Ham İstekler</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">raw_request_logs</p>
                </a>
                <a href="{{ route('admin.page-history.classified') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Sayfa Geçmişi — Sınıflandırılmış</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">İnsan / bot ayrımı</p>
                </a>
                <a href="{{ route('admin.page-history.suspicious') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Sayfa Geçmişi — Şüpheli / Exploit</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Güvenlik olayları</p>
                </a>
                <a href="{{ route('admin.contact-messages') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">İletişim Mesajlarını Görüntüle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Gelen mesajları okuyun</p>
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="block p-4 rounded-sm border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#D62113]/50 dark:hover:border-[#D62113]/50 hover:bg-[#D62113]/5 dark:hover:bg-[#D62113]/10 transition-all duration-200 group">
                    <span class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#D62113] transition-colors">Hesap Bilgilerini Güncelle</span>
                    <p class="text-xs text-[#706f6c] dark:text-[#8F8F8B] mt-1">Ad, e-posta ve şifreyi düzenleyin</p>
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

@push('scripts')
    @vite('resources/js/pages/admin/dashboard.js')
@endpush
