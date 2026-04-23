export function initScrollReveal() {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const scrollItems = document.querySelectorAll('.scroll-item');

    if (!scrollItems.length) {
        return;
    }

    if (reduceMotion || !('IntersectionObserver' in window)) {
        scrollItems.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                requestAnimationFrame(() => entry.target.classList.add('is-visible'));
                currentObserver.unobserve(entry.target);
            }
        });
    }, { root: null, threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    scrollItems.forEach((item, index) => {
        item.style.transitionDelay = `${Math.min(index * 90, 420)}ms`;
    });

    requestAnimationFrame(() => scrollItems.forEach((item) => observer.observe(item)));
}
