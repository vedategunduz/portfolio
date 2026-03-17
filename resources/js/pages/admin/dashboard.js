import { getHttp } from '../../core/http.js';

const REFRESH_INTERVAL_MS = 60000;

function statColor(p) {
    if (p == null) return 'text-[#1b1b18] dark:text-[#EDEDEC]';
    if (p >= 90) return 'text-red-600 dark:text-red-400';
    if (p >= 75) return 'text-amber-600 dark:text-amber-400';
    return 'text-[#1b1b18] dark:text-[#EDEDEC]';
}

function badgeActive() {
    const text = window.translations?.['dashboard.active'] || 'Aktif';
    return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">' + text + '</span>';
}

function badgeInactive() {
    const text = window.translations?.['dashboard.inactive'] || 'Kapalı';
    return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">' + text + '</span>';
}

function badgeFailed(n) {
    const num = typeof n === 'number' ? n : 0;
    if (num === 0) {
        return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-700 dark:bg-emerald-500/25 dark:text-emerald-400">0</span>';
    }
    return '<span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-700 dark:bg-red-500/25 dark:text-red-400">' + num + '</span>';
}

function updateDom(card, errorEl, d) {
    errorEl.classList.add('hidden');
    card.classList.remove('opacity-70');

    const setText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    };
    const setHtml = (id, html) => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = html;
    };
    const setClass = (id, className) => {
        const el = document.getElementById(id);
        if (el) el.className = className;
    };

    const cpuEl = document.getElementById('server-stats-cpu');
    if (cpuEl) {
        const cpuP = d.cpu_percent != null ? parseFloat(d.cpu_percent) : null;
        cpuEl.textContent = cpuP != null ? cpuP.toFixed(1) + '%' : '—';
        cpuEl.className = 'text-sm font-medium ' + statColor(cpuP);
    }

    const ramEl = document.getElementById('server-stats-ram');
    if (ramEl && d.ram_percent && typeof d.ram_percent === 'object') {
        const ramP = d.ram_percent.percent != null ? parseFloat(d.ram_percent.percent) : null;
        ramEl.innerHTML = ramP != null
            ? ramP.toFixed(1) + '% <span class="text-[#706f6c] dark:text-[#8F8F8B]">(' + d.ram_percent.used_mb + '/' + d.ram_percent.total_mb + ' MB)</span>'
            : '—';
        ramEl.className = 'text-sm font-medium ' + statColor(ramP);
    }

    const diskEl = document.getElementById('server-stats-disk');
    if (diskEl && d.disk_percent && typeof d.disk_percent === 'object') {
        const diskP = d.disk_percent.percent != null ? parseFloat(d.disk_percent.percent) : null;
        diskEl.innerHTML = diskP != null
            ? diskP.toFixed(1) + '% <span class="text-[#706f6c] dark:text-[#8F8F8B]">(' + d.disk_percent.used_gb + '/' + d.disk_percent.total_gb + ' GB)</span>'
            : '—';
        diskEl.className = 'text-sm font-medium ' + statColor(diskP);
    }

    setText('server-stats-uptime', d.uptime || '—');
    setText('server-stats-load', d.load_average || '—');
    setHtml('server-stats-nginx', d.nginx_status === 'active' ? badgeActive() : badgeInactive());
    setHtml('server-stats-mysql', d.mysql_status === 'active' ? badgeActive() : badgeInactive());
    setHtml('server-stats-phpfpm', d.php_fpm_status === 'active' ? badgeActive() : badgeInactive());
    setText('server-stats-deploy', d.last_deploy_formatted || '—');
    setHtml('server-stats-failedjobs', badgeFailed(typeof d.failed_jobs_count === 'number' ? d.failed_jobs_count : 0));
    setText('server-stats-updated', d.updated_at || '—');
}

function showError(errorEl, card) {
    errorEl.classList.remove('hidden');
    card.classList.add('opacity-70');
}

export function initServerStats() {
    const card = document.getElementById('server-stats-card');
    const errorEl = document.getElementById('server-stats-error');
    if (!card || !errorEl) return;

    const apiUrl = card.getAttribute('data-api-url');
    if (!apiUrl) return;

    function fetchStats() {
        getHttp()
            .then((Http) => Http.get(apiUrl))
            .then((r) => updateDom(card, errorEl, r.data))
            .catch(() => showError(errorEl, card));
    }

    fetchStats();
    setInterval(fetchStats, REFRESH_INTERVAL_MS);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initServerStats, { once: true });
} else {
    initServerStats();
}
