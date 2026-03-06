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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">CPU</span>
                <span id="server-stats-cpu" class="text-sm font-medium {{ $statColor($cpuP) }}">{{ $cpuP !== null ? number_format($cpuP, 1, '.', '') . '%' : '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">RAM</span>
                <span id="server-stats-ram" class="text-sm font-medium {{ $statColor($ramP) }}">
                    @if(is_array($serverStatsData['ram_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['ram_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B]">({{ $serverStatsData['ram_percent']['used_mb'] }}/{{ $serverStatsData['ram_percent']['total_mb'] }} MB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Disk</span>
                <span id="server-stats-disk" class="text-sm font-medium {{ $statColor($diskP) }}">
                    @if(is_array($serverStatsData['disk_percent'] ?? null))
                        {{ number_format((float) $serverStatsData['disk_percent']['percent'], 1, '.', '') }}% <span class="text-[#706f6c] dark:text-[#8F8F8B]">({{ $serverStatsData['disk_percent']['used_gb'] }}/{{ $serverStatsData['disk_percent']['total_gb'] }} GB)</span>
                    @else
                        —
                    @endif
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-[#e3e3e0] dark:border-[#3E3E3A] sm:border-b-0 sm:border-r pr-4">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Uptime</span>
                <span id="server-stats-uptime" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono">{{ $serverStatsData['uptime'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Load avg (1/5/15 dk)</span>
                <span id="server-stats-load" class="text-xs font-medium text-[#1b1b18] dark:text-[#EDEDEC] font-mono" title="1 dk / 5 dk / 15 dk">{{ $serverStatsData['load_average'] ?? '—' }}</span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-4 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Nginx</span>
                <span id="server-stats-nginx">@if($serverStatsData['nginx_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">MySQL</span>
                <span id="server-stats-mysql">@if($serverStatsData['mysql_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">PHP-FPM</span>
                <span id="server-stats-phpfpm">@if($serverStatsData['php_fpm_status'] === 'active')<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>@else<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>@endif</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Son deploy</span>
                <span id="server-stats-deploy" class="text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $serverStatsData['last_deploy'] ? \Carbon\Carbon::parse($serverStatsData['last_deploy'])->format('d.m.Y H:i') : '—' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-[#706f6c] dark:text-[#8F8F8B]">Failed jobs</span>
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

@push('scripts')
<script>
(function() {
    var card = document.getElementById('server-stats-card');
    var errorEl = document.getElementById('server-stats-error');
    if (!card || !errorEl) return;
    var apiUrl = card.getAttribute('data-api-url');
    if (!apiUrl) return;

    function statColor(p) {
        if (p == null) return 'text-[#1b1b18] dark:text-[#EDEDEC]';
        if (p >= 90) return 'text-red-600 dark:text-red-400';
        if (p >= 75) return 'text-amber-600 dark:text-amber-400';
        return 'text-[#1b1b18] dark:text-[#EDEDEC]';
    }
    function badgeActive() { return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">Aktif</span>'; }
    function badgeInactive() { return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">Kapalı</span>'; }
    function badgeFailed(n) {
        var num = typeof n === 'number' ? n : 0;
        if (num === 0) return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">0</span>';
        return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">' + num + '</span>';
    }

    function updateDom(d) {
        errorEl.classList.add('hidden');
        card.classList.remove('opacity-70');
        var cpuEl = document.getElementById('server-stats-cpu');
        var ramEl = document.getElementById('server-stats-ram');
        var diskEl = document.getElementById('server-stats-disk');
        if (cpuEl) {
            var cpuP = d.cpu_percent != null ? parseFloat(d.cpu_percent) : null;
            cpuEl.textContent = cpuP != null ? cpuP.toFixed(1) + '%' : '—';
            cpuEl.className = 'text-sm font-medium ' + statColor(cpuP);
        }
        if (ramEl && d.ram_percent && typeof d.ram_percent === 'object') {
            var ramP = d.ram_percent.percent != null ? parseFloat(d.ram_percent.percent) : null;
            ramEl.innerHTML = ramP != null ? ramP.toFixed(1) + '% <span class="text-[#706f6c] dark:text-[#8F8F8B]">(' + d.ram_percent.used_mb + '/' + d.ram_percent.total_mb + ' MB)</span>' : '—';
            ramEl.className = 'text-sm font-medium ' + statColor(ramP);
        }
        if (diskEl && d.disk_percent && typeof d.disk_percent === 'object') {
            var diskP = d.disk_percent.percent != null ? parseFloat(d.disk_percent.percent) : null;
            diskEl.innerHTML = diskP != null ? diskP.toFixed(1) + '% <span class="text-[#706f6c] dark:text-[#8F8F8B]">(' + d.disk_percent.used_gb + '/' + d.disk_percent.total_gb + ' GB)</span>' : '—';
            diskEl.className = 'text-sm font-medium ' + statColor(diskP);
        }
        var uptimeEl = document.getElementById('server-stats-uptime');
        if (uptimeEl) uptimeEl.textContent = d.uptime || '—';
        var loadEl = document.getElementById('server-stats-load');
        if (loadEl) loadEl.textContent = d.load_average || '—';
        var nginxEl = document.getElementById('server-stats-nginx');
        if (nginxEl) nginxEl.innerHTML = d.nginx_status === 'active' ? badgeActive() : badgeInactive();
        var mysqlEl = document.getElementById('server-stats-mysql');
        if (mysqlEl) mysqlEl.innerHTML = d.mysql_status === 'active' ? badgeActive() : badgeInactive();
        var phpfpmEl = document.getElementById('server-stats-phpfpm');
        if (phpfpmEl) phpfpmEl.innerHTML = d.php_fpm_status === 'active' ? badgeActive() : badgeInactive();
        var deployEl = document.getElementById('server-stats-deploy');
        if (deployEl) deployEl.textContent = d.last_deploy_formatted || '—';
        var failedEl = document.getElementById('server-stats-failedjobs');
        if (failedEl) failedEl.innerHTML = badgeFailed(typeof d.failed_jobs_count === 'number' ? d.failed_jobs_count : 0);
        var updatedEl = document.getElementById('server-stats-updated');
        if (updatedEl) updatedEl.textContent = d.updated_at || '—';
    }

    function fetchStats() {
        if (typeof window.getHttp !== 'function') {
            errorEl.classList.remove('hidden');
            card.classList.add('opacity-70');
            return;
        }
        window.getHttp().then(function(Http) {
            return Http.get(apiUrl);
        }).then(function(r) {
            updateDom(r.data);
        }).catch(function() {
            errorEl.classList.remove('hidden');
            card.classList.add('opacity-70');
        });
    }
    fetchStats();
    setInterval(fetchStats, 60000);
})();
</script>
@endpush
