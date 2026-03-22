export function initThemeToggle(createIcons, icons) {
    const selector = '[data-theme-toggle], #theme-toggle';
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    };

    const toggleTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        const nextTheme = isDark ? 'light' : 'dark';
        applyTheme(nextTheme);
        localStorage.setItem('theme', nextTheme);
        if (createIcons && icons) {
            createIcons({ attrs: { width: 16, height: 16 }, icons });
        }
    };

    const currentTheme = localStorage.getItem('theme') || 'dark';
    applyTheme(currentTheme);

    // Delegate click so toggle keeps working even if element is rendered later.
    if (!document.body?.dataset.themeToggleBound) {
        document.addEventListener('click', (event) => {
            const target = event.target instanceof Element
                ? event.target.closest(selector)
                : null;

            if (!target) {
                return;
            }

            event.preventDefault();
            toggleTheme();
        });

        if (document.body) {
            document.body.dataset.themeToggleBound = '1';
        }
    }
}
