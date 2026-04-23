export function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (!mobileMenuButton || !mobileMenu) {
        return;
    }

    mobileMenuButton.addEventListener('click', () => {
        const isOpen = mobileMenu.classList.contains('max-h-[500px]');
        mobileMenu.classList.toggle('max-h-[500px]', !isOpen);
        mobileMenu.classList.toggle('opacity-100', !isOpen);
        mobileMenu.classList.toggle('py-4', !isOpen);
        mobileMenu.classList.toggle('max-h-0', isOpen);
        mobileMenu.classList.toggle('opacity-0', isOpen);
        mobileMenuButton.setAttribute('aria-expanded', String(!isOpen));
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('max-h-[500px]', 'opacity-100', 'py-4');
            mobileMenu.classList.add('max-h-0', 'opacity-0');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        });
    });
}

export function initNavbarVisibility() {
    const navbar = document.getElementById('navbar');

    if (!navbar) {
        return;
    }

    const homeSection = document.getElementById('home');
    const setNavbarVisible = (visible) => {
        navbar.style.transform = visible ? 'translateY(0)' : 'translateY(-100%)';
    };

    if (homeSection && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => setNavbarVisible(!entry.isIntersecting));
        }, { root: null, threshold: 0, rootMargin: '-100px 0px 0px 0px' });

        observer.observe(homeSection);
        return;
    }

    let isVisible = false;
    const update = () => {
        const shouldBeVisible = (window.pageYOffset || document.documentElement.scrollTop) > 100;
        if (shouldBeVisible !== isVisible) {
            setNavbarVisible(shouldBeVisible);
            isVisible = shouldBeVisible;
        }
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
}
