export function initThemeToggle(createIcons, icons) {
    const themeToggle = document.getElementById('theme-toggle');
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    };

    const currentTheme = localStorage.getItem('theme') || 'dark';
    applyTheme(currentTheme);

    if (!themeToggle) return;

    themeToggle.addEventListener('click', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const nextTheme = isDark ? 'light' : 'dark';
        applyTheme(nextTheme);
        localStorage.setItem('theme', nextTheme);
        if (createIcons && icons) {
            createIcons({ attrs: { width: 16, height: 16 }, icons });
        }
    });
}
