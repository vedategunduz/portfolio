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
        setText('analytics-avg-active', `${numberFormat(totals.avg_active_time_seconds)} ${sec}`);
        setText('analytics-avg-total', `${numberFormat(totals.avg_total_time_seconds)} ${sec}`);
        setText('analytics-avg-scroll', `${numberFormat(totals.avg_scroll_percent)}%`);

        const trendBody = document.getElementById('analytics-trend-body');
        if (trendBody) {
            trendBody.innerHTML = '';
            (data?.trend || []).forEach((row) => {
                const tr = document.createElement('tr');
                tr.className = 'border-t border-[#e3e3e0] dark:border-[#3E3E3A]';
                tr.innerHTML = `
                    <td class="px-4 py-3">${row.date}</td>
                    <td class="px-4 py-3">${numberFormat(row.views)}</td>
                    <td class="px-4 py-3">${numberFormat(row.engaged)}</td>
                    <td class="px-4 py-3">${numberFormat(row.completed)}</td>
                `;
                trendBody.appendChild(tr);
            });
        }

        const sourcesBody = document.getElementById('analytics-sources-body');
        if (sourcesBody) {
            sourcesBody.innerHTML = '';
            (data?.sources || []).forEach((row) => {
                const tr = document.createElement('tr');
                tr.className = 'border-t border-[#e3e3e0] dark:border-[#3E3E3A]';
                const lbl = formatRowLabel(String(row.label ?? ''));
                tr.innerHTML = `
                    <td class="px-4 py-3">${lbl}</td>
                    <td class="px-4 py-3">${numberFormat(row.views)}</td>
                    <td class="px-4 py-3">${numberFormat(row.unique_visitors)}</td>
                `;
                sourcesBody.appendChild(tr);
            });
        }

        const devicesBody = document.getElementById('analytics-devices-body');
        if (devicesBody) {
            devicesBody.innerHTML = '';
            (data?.devices || []).forEach((row) => {
                const tr = document.createElement('tr');
                tr.className = 'border-t border-[#e3e3e0] dark:border-[#3E3E3A]';
                const lbl = formatRowLabel(String(row.label ?? ''));
                tr.innerHTML = `
                    <td class="px-4 py-3">${lbl}</td>
                    <td class="px-4 py-3">${numberFormat(row.views)}</td>
                    <td class="px-4 py-3">${numberFormat(row.unique_visitors)}</td>
                `;
                devicesBody.appendChild(tr);
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
