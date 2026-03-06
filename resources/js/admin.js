import { createIcons } from 'lucide';
import { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, Info, X } from 'lucide';
import { initThemeToggle } from './core/theme-toggle.js';
import { Dialog } from './ui/dialog.js';
import { getHttp } from './core/http.js';

const adminIcons = { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, Info, X };

window.getHttp = getHttp;
window.Dialog = Dialog;
window.createIcons = createIcons;
window.lucideIcons = adminIcons;

initThemeToggle(createIcons, adminIcons);

createIcons({
    attrs: { width: 16, height: 16 },
    icons: adminIcons,
});

// Admin sayfa modülleri: root id’ye göre tek giriş fonksiyonu (init*) ile dynamic import.
// Standart: #server-stats-card → dashboard.js → initServerStats();
//          #page-history-root → page-history.js → initPageHistory();
//          #contact-messages-root → contact-messages.js → initContactMessages();
function runPageModules() {
    if (document.getElementById('server-stats-card')) {
        import('./pages/admin/dashboard.js').then((m) => m.initServerStats());
    }
    if (document.getElementById('page-history-root')) {
        import('./pages/admin/page-history.js').then((m) => m.initPageHistory());
    }
    if (document.getElementById('contact-messages-root')) {
        import('./pages/admin/contact-messages.js').then((m) => m.initContactMessages());
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runPageModules, { once: true });
} else {
    runPageModules();
}
