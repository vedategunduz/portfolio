import { createIcons } from 'lucide';
import { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, AlertCircle, Info, X, Activity, Clock, TrendingUp, Download, RefreshCw, Search, Copy } from 'lucide';
import { initThemeToggle } from './core/theme-toggle.js';
import { Dialog } from './ui/dialog.js';
import { getHttp } from './core/http.js';

const adminIcons = { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, AlertCircle, Info, X, Activity, Clock, TrendingUp, Download, RefreshCw, Search, Copy };

window.getHttp = getHttp;
window.Dialog = Dialog;
window.createIcons = createIcons;
window.lucideIcons = adminIcons;

initThemeToggle(createIcons, adminIcons);

createIcons({
    attrs: { width: 16, height: 16 },
    icons: adminIcons,
});

// Alpine: toast vb. bileşenler için (admin layout'ta <x-toast /> kullanılıyor)
let Alpine = null;
const getAlpine = async () => {
    if (!Alpine) {
        const { default: AlpineModule } = await import('alpinejs');
        Alpine = AlpineModule;
    }
    return Alpine;
};
function loadAlpineIfNeeded() {
    const hasAlpine = document.querySelector('[x-data]') || document.querySelector('[x-show]') || document.querySelector('[x-for]') || document.querySelector('[x-cloak]');
    if (hasAlpine) {
        getAlpine().then((AlpineModule) => {
            window.Alpine = AlpineModule;
            AlpineModule.start();
        });
    }
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAlpineIfNeeded, { once: true });
} else {
    loadAlpineIfNeeded();
}
