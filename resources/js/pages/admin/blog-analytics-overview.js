function i18n() {
    return window.blogAnalyticsI18n ?? {};
}

function numberFormat(value) {
    const n = Number(value || 0);
    const locale = i18n().number_locale ?? 'en-US';
    return Number.isFinite(n) ? n.toLocaleString(locale) : '0';
}

function formatRowLabel(label) {
    if (label === 'other') {
        return i18n().other_bucket ?? 'Other';
    }
    return label;
}

function secondsAbbr() {
    return i18n().seconds_abbr ?? 's';
}

function setText(id, value) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = value;
    }
}

function createTableRow(cells, zebra = false) {
    const tr = document.createElement('tr');
    tr.className = [
        'transition-colors',
        'duration-150',
        zebra ? 'bg-[#fafafa] dark:bg-[#1e1e1e]' : 'bg-white dark:bg-[#1a1a18]',
        'hover:bg-[#f5f5f5] dark:hover:bg-[#252525]',
    ].join(' ');

    cells.forEach(({ value, variant = 'primary' }) => {
        const td = document.createElement('td');
        td.className = [
            'px-3',
            'lg:px-4',
            'py-2.5',
            'text-sm',
            'leading-normal',
            variant === 'secondary'
                ? 'text-[#6b7280] dark:text-[#9ca3af]'
                : 'text-[#111827] dark:text-[#f3f4f6]',
        ].join(' ');
        td.textContent = value;
        tr.appendChild(td);
    });

    return tr;
}

function setDefaultDates() {
    const from = document.getElementById('analytics-date-from');
    const to = document.getElementById('analytics-date-to');
    if (!from || !to) return;

    const today = new Date();
    const fromDate = new Date(today);
    fromDate.setDate(today.getDate() - 29);

    const iso = (d) => d.toISOString().slice(0, 10);
    from.value = from.value || iso(fromDate);
    to.value = to.value || iso(today);
}

async function fetchOverview() {
    const card = document.getElementById('blog-analytics-overview');
    if (!card) return;

    const endpoint = card.dataset.endpoint;
    const from = document.getElementById('analytics-date-from')?.value;
    const to = document.getElementById('analytics-date-to')?.value;
    const includeBots = document.getElementById('analytics-include-bots')?.checked ? '1' : '0';
    const params = new URLSearchParams({
        date_from: from || '',
        date_to: to || '',
        include_bots: includeBots,
    });

    try {
        const response = await fetch(`${endpoint}?${params.toString()}`, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Failed to load analytics');
        }

        const data = await response.json();
        const totals = data?.totals ?? {};
        const sec = secondsAbbr();
        setText('analytics-total-views', numberFormat(totals.total_views));
        setText('analytics-unique-visitors', numberFormat(totals.unique_visitors));
        setText('analytics-completed-rate', `${totals.completed_read_rate ?? 0}%`);
        setText('analytics-engaged-rate', `${totals.engaged_read_rate ?? 0}%`);
        setText('analytics-returning-rate', `${totals.returning_visitor_rate ?? 0}%`);
        setText('analytics-returning-visitors', numberFormat(totals.returning_visitors));
        setText('analytics-avg-active', `${numberFormat(totals.avg_active_time_seconds)} ${sec}`);
        setText('analytics-avg-total', `${numberFormat(totals.avg_total_time_seconds)} ${sec}`);
        setText('analytics-avg-scroll', `${numberFormat(totals.avg_scroll_percent)}%`);

        const trendBody = document.getElementById('analytics-trend-body');
        if (trendBody) {
            trendBody.innerHTML = '';
            (data?.trend || []).forEach((row, index) => {
                trendBody.appendChild(createTableRow([
                    { value: row.date ?? '' },
                    { value: numberFormat(row.views) },
                    { value: numberFormat(row.engaged) },
                    { value: numberFormat(row.completed) },
                ], index % 2 === 1));
            });
        }

        const sourcesBody = document.getElementById('analytics-sources-body');
        if (sourcesBody) {
            sourcesBody.innerHTML = '';
            (data?.sources || []).forEach((row, index) => {
                const lbl = formatRowLabel(String(row.label ?? ''));
                sourcesBody.appendChild(createTableRow([
                    { value: lbl },
                    { value: numberFormat(row.views) },
                    { value: numberFormat(row.unique_visitors) },
                ], index % 2 === 1));
            });
        }

        const devicesBody = document.getElementById('analytics-devices-body');
        if (devicesBody) {
            devicesBody.innerHTML = '';
            (data?.devices || []).forEach((row, index) => {
                const lbl = formatRowLabel(String(row.label ?? ''));
                devicesBody.appendChild(createTableRow([
                    { value: lbl },
                    { value: numberFormat(row.views) },
                    { value: numberFormat(row.unique_visitors) },
                ], index % 2 === 1));
            });
        }
    } catch (error) {
        window.Dialog?.error(i18n().load_error ?? 'Could not load analytics data.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('blog-analytics-overview')) {
        return;
    }

    setDefaultDates();
    fetchOverview();

    document.getElementById('analytics-refresh')?.addEventListener('click', fetchOverview);
    document.getElementById('analytics-date-from')?.addEventListener('change', fetchOverview);
    document.getElementById('analytics-date-to')?.addEventListener('change', fetchOverview);
    document.getElementById('analytics-include-bots')?.addEventListener('change', fetchOverview);
});
