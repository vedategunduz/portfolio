import Alpine from 'alpinejs';
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
// import { initSwiper } from './swiper-helper';
// import { initEditor } from './ckeditor-helper';
import { Http } from './http-helper';
import { initForm, initAction } from './form-helper';
import { Dialog } from './dialog-helper';
import { initThemeToggle } from './theme-toggle';

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
window.Http = Http;
window.initForm = initForm;
window.initAction = initAction;
window.Dialog = Dialog;

// 2. YENİ EKLENEN: Lucide Fonksiyonlarını Global Yap
// Böylece dialog.blade.php içinden 'window.createIcons(...)' diyebileceğiz.
window.createIcons = createIcons;
window.lucideIcons = lucideIcons;

// Alpine.js
window.Alpine = Alpine;
Alpine.start();

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
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // Mobile menüdeki linklere tıklandığında menüyü kapat
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });
    }

    // 5. Navbar Show/Hide on Scroll
    const navbar = document.getElementById('navbar');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 100) {
            // Scroll down - show navbar
            navbar.style.transform = 'translateY(0)';
        } else {
            // At top or small scroll - hide navbar
            navbar.style.transform = 'translateY(-100%)';
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });

    // (moved to early init above)

    // 7. AJAX Contact Form
    initForm('#contact-form', {
        reset: true,
    });

});
