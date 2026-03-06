/**
 * Premium Error Page Features
 * - 3D Tilt Effect
 * - Auto Countdown & Reload
 * - Keyboard Shortcuts
 * - Konami Code Easter Egg
 */

export function initErrorPage() {
    const errorCard = document.querySelector('[data-error-page]');
    if (!errorCard) return;

    const statusCode = parseInt(errorCard.dataset.statusCode);

    // 3D Tilt Effect
    initTiltEffect(errorCard);

    // Countdown for 500/503 errors
    if ([500, 503].includes(statusCode)) {
        initCountdown();
    }

    // Keyboard Shortcuts
    initKeyboardShortcuts();

    // Konami Code Easter Egg
    initKonamiCode();
}

/**
 * 3D Tilt Effect on mouse move
 */
function initTiltEffect(card) {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (window.innerWidth < 768) return; // Disable on mobile

    let rafId = null;

    const handleMouseMove = (e) => {
        if (rafId) return;

        rafId = requestAnimationFrame(() => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * -5; // Max 5deg
            const rotateY = ((x - centerX) / centerX) * 5;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
            card.style.transition = 'transform 0.1s ease-out';

            rafId = null;
        });
    };

    const handleMouseLeave = () => {
        card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
        card.style.transition = 'transform 0.4s ease-out';
    };

    card.addEventListener('mousemove', handleMouseMove);
    card.addEventListener('mouseleave', handleMouseLeave);
}

/**
 * Auto Countdown and Page Reload
 */
function initCountdown() {
    const timerEl = document.getElementById('countdown-timer');
    if (!timerEl) return;

    let seconds = 10;

    const interval = setInterval(() => {
        seconds--;
        timerEl.textContent = seconds;

        if (seconds <= 0) {
            clearInterval(interval);
            location.reload();
        }
    }, 1000);

    // Allow user to cancel countdown by interacting with page
    const cancelCountdown = () => {
        clearInterval(interval);
        const parent = timerEl.closest('.inline-flex');
        if (parent) {
            parent.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i><span>Otomatik yenileme iptal edildi</span>';
            // Re-init lucide icons for the new icon
            if (window.createIcons && window.lucideIcons) {
                window.createIcons({ attrs: { width: 16, height: 16 }, icons: window.lucideIcons });
            }
        }
    };

    // Cancel on any user action
    ['click', 'keydown', 'scroll'].forEach(event => {
        document.addEventListener(event, cancelCountdown, { once: true });
    });
}

/**
 * Keyboard Shortcuts
 * ESC - Go to homepage
 * R - Reload page
 */
function initKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // ESC - Go home
        if (e.key === 'Escape') {
            e.preventDefault();
            window.location.href = '/';
            return;
        }

        // R - Reload (only if not in input field)
        if (e.key === 'r' && !e.ctrlKey && !e.metaKey) {
            const target = e.target;
            if (target.tagName !== 'INPUT' && target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                location.reload();
            }
        }
    });
}

/**
 * Konami Code Easter Egg
 * ↑ ↑ ↓ ↓ ← → ← → B A
 */
function initKonamiCode() {
    const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
    let currentIndex = 0;
    let timeout = null;

    document.addEventListener('keydown', (e) => {
        const key = e.key.toLowerCase();

        if (key === konamiSequence[currentIndex].toLowerCase()) {
            currentIndex++;

            // Reset timeout
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                currentIndex = 0;
            }, 2000);

            // Show hint on progress
            if (currentIndex >= 4) {
                const hint = document.querySelector('.konami-hint');
                if (hint) {
                    hint.style.opacity = '1';
                }
            }

            // Complete!
            if (currentIndex === konamiSequence.length) {
                currentIndex = 0;
                clearTimeout(timeout);
                triggerEasterEgg();
            }
        } else if (konamiSequence.some(k => k.toLowerCase() === key)) {
            // Wrong key in sequence
            currentIndex = 0;
            clearTimeout(timeout);
        }
    });
}

/**
 * Easter Egg Activation
 */
function triggerEasterEgg() {
    const card = document.querySelector('[data-error-page]');
    if (!card) return;

    // Show celebration
    const originalBg = card.style.background;

    // Rainbow animation
    let hue = 0;
    const rainbowInterval = setInterval(() => {
        hue = (hue + 2) % 360;
        card.style.background = `linear-gradient(135deg, hsla(${hue}, 70%, 60%, 0.3), hsla(${hue + 60}, 70%, 60%, 0.3))`;
    }, 50);

    // Create confetti-like effect with emojis
    const emojis = ['🎉', '✨', '🎊', '⭐', '💫', '🌟'];
    for (let i = 0; i < 30; i++) {
        setTimeout(() => {
            createFallingEmoji(emojis[Math.floor(Math.random() * emojis.length)]);
        }, i * 80);
    }

    // Stop after 3 seconds
    setTimeout(() => {
        clearInterval(rainbowInterval);
        card.style.background = originalBg;
    }, 3000);

    // Show secret message
    setTimeout(() => {
        alert('🎮 Konami Code başarılı! Efsane bir geliştirici olduğunu kanıtladın! ⭐');
    }, 500);
}

/**
 * Create falling emoji animation
 */
function createFallingEmoji(emoji) {
    const el = document.createElement('div');
    el.textContent = emoji;
    el.style.cssText = `
        position: fixed;
        top: -20px;
        left: ${Math.random() * 100}%;
        font-size: ${20 + Math.random() * 30}px;
        pointer-events: none;
        z-index: 9999;
        animation: fall ${2 + Math.random() * 2}s linear forwards;
    `;

    document.body.appendChild(el);

    setTimeout(() => el.remove(), 4000);
}

// Add fall animation
if (!document.getElementById('emoji-fall-style')) {
    const style = document.createElement('style');
    style.id = 'emoji-fall-style';
    style.textContent = `
        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(${Math.random() * 360}deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}
