import {
    createIcons,
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
    Eye,
    Users,
    Inbox,
} from 'lucide';

// // (commented out - lazy loaded on demand)
// import { initSwiper } from './swiper-helper';
// import { initEditor } from './ckeditor-helper';
// Http is lazy-loaded in form-helper.js to reduce bundle size
import { initForm, initAction, getHttp } from './form-helper';
import { Dialog } from './dialog-helper';
import { initThemeToggle } from './theme-toggle';

// Lazy load Alpine.js - only when needed
let Alpine = null;
const getAlpine = async () => {
    if (!Alpine) {
        const { default: AlpineModule } = await import('alpinejs');
        Alpine = AlpineModule;
    }
    return Alpine;
};

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
    Eye,
    Users,
    Inbox,
};
// 1. Helper Fonksiyonlarını Global Yap
// window.createSlider = initSwiper;
// window.createEditor = initEditor;
window.initForm = initForm;
window.initAction = initAction;
window.getHttp = getHttp;
window.Dialog = Dialog;

// 2. YENİ EKLENEN: Lucide Fonksiyonlarını Global Yap
// Böylece dialog.blade.php içinden 'window.createIcons(...)' diyebileceğiz.
window.createIcons = createIcons;
window.lucideIcons = lucideIcons;

const initScrollReveal = () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const scrollItems = document.querySelectorAll('.scroll-item');
    if (scrollItems.length === 0) {
        return;
    }

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
                requestAnimationFrame(() => {
                    entry.target.classList.add('is-visible');
                });
                obs.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.12,
        rootMargin: '0px 0px -8% 0px',
    });

    scrollItems.forEach((item, index) => {
        item.style.transitionDelay = `${Math.min(index * 90, 420)}ms`;
    });

    requestAnimationFrame(() => {
        scrollItems.forEach((item) => observer.observe(item));
    });
};

// Alpine.js - smart lazy load (only load if Alpine components exist)
const LoadAlpineIfNeeded = () => {
    // Check for Alpine components
    const hasAlpineComponents = document.querySelector('[x-data]') ||
        document.querySelector('[x-show]') ||
        document.querySelector('[x-for]') ||
        document.querySelector('[x-cloak]');

    if (hasAlpineComponents) {
        getAlpine().then(AlpineModule => {
            window.Alpine = AlpineModule;
            AlpineModule.start();
        });
    }
};

// Load when DOM is ready (ensures all HTML is parsed)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', LoadAlpineIfNeeded, { once: true });
} else {
    // DOM already loaded
    LoadAlpineIfNeeded();
}

// Theme Toggle (early init)
initThemeToggle(createIcons, lucideIcons);

// 3. Sayfa Yüklendiğinde İkonları Tara
createIcons({
    attrs: {
        width: 16,
        height: 16,
    },
    icons: lucideIcons,
});

// 4. Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.contains('max-h-[500px]');
            if (isOpen) {
                // Close menu
                mobileMenu.classList.remove('max-h-[500px]', 'opacity-100', 'py-4');
                mobileMenu.classList.add('max-h-0', 'opacity-0');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            } else {
                // Open menu
                mobileMenu.classList.remove('max-h-0', 'opacity-0');
                mobileMenu.classList.add('max-h-[500px]', 'opacity-100', 'py-4');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
            }
        });

        // Mobile menüdeki linklere tıklandığında menüyü kapat
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('max-h-[500px]', 'opacity-100', 'py-4');
                mobileMenu.classList.add('max-h-0', 'opacity-0');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // 5. Navbar Show/Hide (IntersectionObserver - forced reflow riskini azaltır)
    const navbar = document.getElementById('navbar');
    const homeSection = document.getElementById('home');

    if (navbar) {
        const setNavbarVisible = (visible) => {
            navbar.style.transform = visible ? 'translateY(0)' : 'translateY(-100%)';
        };

        if (homeSection && 'IntersectionObserver' in window) {
            const navbarObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    // Hero görünürdeyse navbar gizli, çıkınca görünür
                    setNavbarVisible(!entry.isIntersecting);
                });
            }, {
                root: null,
                threshold: 0,
                rootMargin: '-100px 0px 0px 0px',
            });

            navbarObserver.observe(homeSection);
        } else {
            // Fallback: eski davranış, sadece gerekli olduğunda style yaz
            let isNavbarVisible = false;
            const updateNavbarVisibility = () => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const shouldBeVisible = scrollTop > 100;

                if (shouldBeVisible !== isNavbarVisible) {
                    setNavbarVisible(shouldBeVisible);
                    isNavbarVisible = shouldBeVisible;
                }
            };

            window.addEventListener('scroll', updateNavbarVisibility, { passive: true });
            updateNavbarVisibility();
        }
    }

    // (moved to early init above)

    // 7. AJAX Contact Form
    initForm('#contact-form', {
        reset: true,
    });

    // 8. Lightweight scroll reveal (GSAP yerine)
    initScrollReveal();

});
