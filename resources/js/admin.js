import { createIcons } from 'lucide';
import { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, AlertCircle, Info, X, Activity, Clock, TrendingUp, Download, RefreshCw, Search, Copy, ChevronLeft, ChevronRight, ArrowLeft, ArrowRight } from 'lucide';
import { initThemeToggle } from './core/theme-toggle.js';
import { Dialog } from './ui/dialog.js';
import { getHttp } from './core/http.js';
import { initEditor, initAllEditors } from './editor.js';

const adminIcons = { Sun, Moon, Eye, Users, Mail, Inbox, Check, Ban, AlertTriangle, AlertCircle, Info, X, Activity, Clock, TrendingUp, Download, RefreshCw, Search, Copy, ChevronLeft, ChevronRight, ArrowLeft, ArrowRight };

window.getHttp = getHttp;
window.Dialog = Dialog;
window.createIcons = createIcons;
window.lucideIcons = adminIcons;
window.initEditor = initEditor;
window.initAllEditors = initAllEditors;

initThemeToggle(createIcons, adminIcons);

function runCreateIcons() {
    createIcons({ attrs: { width: 16, height: 16 }, icons: adminIcons, nameAttr: 'data-lucide' });
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', runCreateIcons, { once: true });
} else {
    runCreateIcons();
}

function initEditorsIfPresent() {
    if (!document.querySelector('[data-editor-content]')) {
        return;
    }

    initAllEditors();

    // In some admin forms Alpine renders a moment later; retry once.
    window.setTimeout(() => {
        initAllEditors();
    }, 120);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditorsIfPresent, { once: true });
} else {
    initEditorsIfPresent();
}

// Livewire DOM güncellemesi bittikten sonra ikonları çiz (morph tamamlanana kadar bekle)
function runCreateIconsDeferred() {
    requestAnimationFrame(() => {
        requestAnimationFrame(runCreateIcons);
    });
}

document.addEventListener('livewire:navigated', runCreateIconsDeferred);
document.addEventListener('livewire:initialized', () => {
    if (window.Livewire && typeof window.Livewire.hook === 'function') {
        window.Livewire.hook('request', ({ succeed }) => {
            succeed(() => runCreateIconsDeferred());
        });
    }
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
