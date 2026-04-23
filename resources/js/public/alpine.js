let Alpine = null;

async function getAlpine() {
    if (!Alpine) {
        const { default: AlpineModule } = await import('alpinejs');
        Alpine = AlpineModule;
    }

    return Alpine;
}

export function loadAlpineIfNeeded() {
    const hasAlpine =
        document.querySelector('[x-data]') ||
        document.querySelector('[x-show]') ||
        document.querySelector('[x-for]') ||
        document.querySelector('[x-cloak]');

    if (!hasAlpine) {
        return;
    }

    getAlpine().then((AlpineModule) => {
        window.Alpine = AlpineModule;
        AlpineModule.start();
    });
}
