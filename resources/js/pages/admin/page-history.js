/**
 * Admin page-history sayfası. Sadece bu sayfada yüklenir; Blade @push('scripts') ile dahil edilir.
 * Tek giriş: initPageHistory()
 */
export function initPageHistory() {
    const root = document.getElementById('page-history-root');
    if (!root) return;
    // İleride: tablo, filtreleme, sayfalama vb.
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageHistory, { once: true });
} else {
    initPageHistory();
}
