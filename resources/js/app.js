import { createIcons } from 'lucide';
import {
    Github,
    Linkedin,
    Instagram,
    Mail,
    Sun,
    Moon,
    Menu,
    XCircle,
    CheckCircle,
    Send,
    Check,
    Ban,
    AlertTriangle,
    AlertOctagon,
    Info,
    X,
    ChevronDown,
    Server,
    Code,
    Database,
    Wrench,
    ArrowLeft,
    ArrowRight,
} from 'lucide';
import { initForm, initAction } from './helpers/form.js';
import { Dialog } from './ui/dialog.js';
import { getHttp } from './core/http.js';
import { initThemeToggle } from './core/theme-toggle.js';
import { initEditor, initAllEditors } from './editor.js';
import { initBlogAnalytics } from './modules/blog-analytics.js';
import { loadAlpineIfNeeded } from './public/alpine.js';
import { initMobileMenu, initNavbarVisibility } from './public/navigation.js';
import { initScrollReveal } from './public/scroll-reveal.js';

const lucideIcons = {
    Github,
    Linkedin,
    Instagram,
    Mail,
    Sun,
    Moon,
    Menu,
    XCircle,
    CheckCircle,
    Send,
    Check,
    Ban,
    AlertTriangle,
    AlertOctagon,
    Info,
    X,
    ChevronDown,
    Server,
    Code,
    Database,
    Wrench,
    ArrowLeft,
    ArrowRight,
};

window.initForm = initForm;
window.initAction = initAction;
window.getHttp = getHttp;
window.Dialog = Dialog;
window.createIcons = createIcons;
window.lucideIcons = lucideIcons;
window.initEditor = initEditor;
window.initAllEditors = initAllEditors;

initThemeToggle(createIcons, lucideIcons);

createIcons({
    attrs: { width: 16, height: 16 },
    icons: lucideIcons,
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAlpineIfNeeded, { once: true });
} else {
    loadAlpineIfNeeded();
}

document.addEventListener('DOMContentLoaded', function () {
    initMobileMenu();
    initNavbarVisibility();
    initForm('#contact-form', { reset: true });
    initScrollReveal();
    initBlogAnalytics();
}, { once: true });
