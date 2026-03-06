/**
 * Admin contact-messages sayfası. Sadece bu sayfada yüklenir; Blade @push('scripts') ile dahil edilir.
 * Tek giriş: initContactMessages()
 */
export function initContactMessages() {
    const root = document.getElementById('contact-messages-root');
    if (!root) return;
    // İleride: liste, filtreleme, okundu işaretleme vb.
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initContactMessages, { once: true });
} else {
    initContactMessages();
}
