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
} from 'lucide';

// GSAP - Scroll animasyonları critical, hemen yükle
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// // (commented out - lazy loaded on demand)
// import { initSwiper } from './swiper-helper';
// import { initEditor } from './ckeditor-helper';
// Http is lazy-loaded in form-helper.js to reduce bundle size
import { initForm, initAction } from './form-helper';
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
};
// 1. Helper Fonksiyonlarını Global Yap
// window.createSlider = initSwiper;
// window.createEditor = initEditor;
window.initForm = initForm;
window.initAction = initAction;
window.Dialog = Dialog;

// 2. YENİ EKLENEN: Lucide Fonksiyonlarını Global Yap
// Böylece dialog.blade.php içinden 'window.createIcons(...)' diyebileceğiz.
window.createIcons = createIcons;
window.lucideIcons = lucideIcons;

const initGSAPScrollDriven = () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduceMotion) {
        return;
    }

    const scrollItems = document.querySelectorAll('.scroll-item');
    if (scrollItems.length > 0) {
        scrollItems.forEach((item, idx) => {
            gsap.fromTo(
                item,
                {
                    opacity: 0,
                    y: 24,
                },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 85%',
                        end: 'top 40%',
                        scrub: 1.2,
                        markers: false,
                    },
                }
            );
        });
    }

    const scrollCards = document.querySelectorAll('[data-scroll-card]');
    if (scrollCards.length > 0) {
        scrollCards.forEach((card, idx) => {
            const delay = idx * 0.15;
            gsap.fromTo(
                card,
                {
                    opacity: 0,
                    y: 24,
                    scale: 0.95,
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 1.2,
                    delay: delay,
                    scrollTrigger: {
                        trigger: card.closest('[data-scroll-section]'),
                        start: 'top 80%',
                        end: 'top 20%',
                        scrub: 1,
                        markers: false,
                    },
                }
            );
        });
    }

    const parallaxBg = document.querySelector('.scroll-parallax-bg');
    if (parallaxBg) {
        gsap.fromTo(
            parallaxBg,
            {
                y: 0,
            },
            {
                y: -40,
                scrollTrigger: {
                    trigger: 'body',
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: 0.5,
                    markers: false,
                },
            }
        );
    }
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

    // 5. Navbar Show/Hide on Scroll (optimized with RAF)
    const navbar = document.getElementById('navbar');
    let lastScrollTop = 0;
    let isNavbarVisible = false;
    let rafId = null;

    const updateNavbarVisibility = () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const shouldBeVisible = scrollTop > 100;

        // Only update DOM if visibility state changed
        if (shouldBeVisible !== isNavbarVisible) {
            navbar.style.transform = shouldBeVisible ? 'translateY(0)' : 'translateY(-100%)';
            isNavbarVisible = shouldBeVisible;
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        rafId = null;
    };

    window.addEventListener('scroll', function () {
        // Debounce with RAF - only schedule one update per frame
        if (rafId === null) {
            rafId = requestAnimationFrame(updateNavbarVisibility);
        }
    }, { passive: true });

    // (moved to early init above)

    // 7. AJAX Contact Form
    initForm('#contact-form', {
        reset: true,
    });

    // 8. GSAP scroll-driven animations (scrub-based timeline)
    initGSAPScrollDriven();

});
