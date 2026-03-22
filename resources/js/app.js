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

let Alpine = null;
const getAlpine = async () => {
    if (!Alpine) {
        const { default: AlpineModule } = await import('alpinejs');
        Alpine = AlpineModule;
    }
    return Alpine;
};

function initScrollReveal() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const scrollItems = document.querySelectorAll('.scroll-item');
    if (!scrollItems.length) return;

    if (reduceMotion) {
        scrollItems.forEach((item) => item.classList.add('is-visible'));
        return;
    }
    if (!('IntersectionObserver' in window)) {
        scrollItems.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                requestAnimationFrame(() => entry.target.classList.add('is-visible'));
                obs.unobserve(entry.target);
            }
        });
    }, { root: null, threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    scrollItems.forEach((item, index) => {
        item.style.transitionDelay = `${Math.min(index * 90, 420)}ms`;
    });
    requestAnimationFrame(() => scrollItems.forEach((item) => observer.observe(item)));
}

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

document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.contains('max-h-[500px]');
            mobileMenu.classList.toggle('max-h-[500px]', !isOpen);
            mobileMenu.classList.toggle('opacity-100', !isOpen);
            mobileMenu.classList.toggle('py-4', !isOpen);
            mobileMenu.classList.toggle('max-h-0', isOpen);
            mobileMenu.classList.toggle('opacity-0', isOpen);
            mobileMenuButton.setAttribute('aria-expanded', !isOpen);
        });
        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('max-h-[500px]', 'opacity-100', 'py-4');
                mobileMenu.classList.add('max-h-0', 'opacity-0');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            });
        });
    }

    const navbar = document.getElementById('navbar');
    const homeSection = document.getElementById('home');
    if (navbar) {
        const setNavbarVisible = (visible) => {
            navbar.style.transform = visible ? 'translateY(0)' : 'translateY(-100%)';
        };
        if (homeSection && 'IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach((entry) => setNavbarVisible(!entry.isIntersecting));
            }, { root: null, threshold: 0, rootMargin: '-100px 0px 0px 0px' });
            obs.observe(homeSection);
        } else {
            let isVisible = false;
            const update = () => {
                const should = (window.pageYOffset || document.documentElement.scrollTop) > 100;
                if (should !== isVisible) {
                    setNavbarVisible(should);
                    isVisible = should;
                }
            };
            window.addEventListener('scroll', update, { passive: true });
            update();
        }
    }

    initForm('#contact-form', { reset: true });
    initScrollReveal();
}, { once: true });
