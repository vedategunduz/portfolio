// resources/js/dialog-helper.js

// 1. Tema Tanımları (Burada merkezi olarak yönetiyoruz)
const THEMES = {
    // Info (Mavi)
    info: {
        icon: 'info',
        iconColor: 'text-blue-600',
        iconBg: 'bg-blue-100',
        btnClass: 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500'
    },
    // Success (Yeşil)
    success: {
        icon: 'check-circle',
        iconColor: 'text-green-600',
        iconBg: 'bg-green-100',
        btnClass: 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500'
    },
    // Danger (Kırmızı - Silme İşlemleri İçin)
    danger: {
        icon: 'alert-triangle',
        iconColor: 'text-red-600',
        iconBg: 'bg-red-100',
        btnClass: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500'
    },
    // Warning (Turuncu)
    warning: {
        icon: 'alert-octagon',
        iconColor: 'text-orange-600',
        iconBg: 'bg-orange-100',
        btnClass: 'bg-orange-600 text-white hover:bg-orange-700 focus:ring-orange-500'
    }
};

const triggerModal = (options) => {
    return new Promise((resolve) => {
        // Tipe göre temayı seç
        const type = options.type || 'info';
        const themeConfig = THEMES[type]; // <-- Rengi buradan alıyoruz

        window.dispatchEvent(new CustomEvent('dialog:open', {
            detail: {
                ...options,
                themeConfig, // <-- Blade'e gönderiyoruz
                resolve
            }
        }));
    });
};

const triggerToast = (type, message, title) => {
    window.dispatchEvent(new CustomEvent('toast:show', {
        detail: { type, title, message }
    }));
};

export const Dialog = {
    success: (message, title = 'Başarılı') => triggerToast('success', message, title),
    error: (message, title = 'Hata') => triggerToast('error', message, title),
    alert: (message, title = 'Bilgi') => triggerToast('info', message, title),
    warning: (message, title = 'Dikkat') => triggerToast('warning', message, title),

    confirm: (message, title = 'Emin misiniz?', confirmText = 'Evet, Onaylıyorum', type = 'danger') =>
        triggerModal({ title, message, type, showCancel: true, cancelText: 'Vazgeç', confirmText }),
};
