import { Http } from './http-helper';
import { Dialog } from './dialog-helper';

// --- YARDIMCI: Hata Mesajını Analiz Et ---
const parseErrorMessage = (error) => {
    let message = 'Beklenmedik bir hata oluştu.';

    if (error.response) {
        const data = error.response.data;

        // 1. Laravel Validasyon Hataları (422)
        if (error.response.status === 422 && data.errors) {
            // İlk hatayı bulup göster (Örn: "message" alanındaki ilk hata)
            const firstKey = Object.keys(data.errors)[0];
            if (firstKey && data.errors[firstKey][0]) {
                message = data.errors[firstKey][0];
            } else {
                message = data.message || 'Lütfen bilgileri kontrol edin.';
            }
        }
        // 2. Genel Sunucu Mesajı
        else if (data && data.message) {
            message = data.message;
        }
    } else if (error.message) {
        // 3. Ağ Hatası vb.
        message = error.message;
    }

    return message;
};

/**
 * Otomatik Form Gönderici
 */
export const initForm = (selector, options = {}) => {
    const form = document.querySelector(selector);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Ayarlar
        const url = options.url || form.action;
        let method = (options.method || form.getAttribute('method') || 'post').toLowerCase();
        const shouldReset = options.reset || false;

        // Buton Loading
        const submitBtn = form.querySelector('[type="submit"]') || form.querySelector('.form-submit');
        let originalBtnContent = '';
        if (submitBtn) {
            originalBtnContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = options.loadingText || '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...</span>';
        }

        // Veri Hazırlığı & Method Spoofing
        const formData = new FormData(form);
        if (['put', 'patch', 'delete'].includes(method)) {
            formData.append('_method', method.toUpperCase());
            method = 'post';
        }

        try {
            let response;
            if (method === 'get') {
                const params = Object.fromEntries(formData);
                response = await Http.get(url, params);
            } else {
                response = await Http[method](url, formData);
            }

            if (shouldReset) form.reset();

            // Başarılı
            if (options.onSuccess) {
                options.onSuccess(response);
            } else if (response.data && response.data.message) {
                Dialog.success(response.data.message);
            }

        } catch (error) {
            const errorMsg = parseErrorMessage(error);

            if (options.onError) {
                options.onError(error);
            } else {
                Dialog.error(errorMsg, 'İşlem Başarısız');
            }

        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            }
        }
    });
};

/**
 * Tekil Buton Aksiyonu
 */
export const initAction = (selectorOrElement, options = {}) => {
    const btn = typeof selectorOrElement === 'string'
        ? document.querySelector(selectorOrElement)
        : selectorOrElement;
    if (!btn) return;

    btn.addEventListener('click', async (e) => {
        e.preventDefault();

        // Onay Mekanizması
        if (options.confirm) {
            const isConfirmed = await Dialog.confirm(
                options.confirm,
                options.confirmTitle || 'Onay Gerekiyor',
                options.confirmButtonText || 'Evet, Devam Et',
                options.confirmType || 'danger'
            );
            if (!isConfirmed) return;
        }

        // Buton Loading
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = options.loadingText || '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> İşleniyor...</span>';

        const url = options.url || btn.getAttribute('href') || btn.getAttribute('data-url');
        const method = (options.method || 'get').toLowerCase();

        try {
            const response = await Http[method](url);

            if (options.onSuccess) {
                options.onSuccess(response);
            } else if (response.data && response.data.message) {
                Dialog.success(response.data.message);
            }

        } catch (error) {
            console.error('Action Hatası:', error);

            const errorMsg = parseErrorMessage(error);

            if (options.onError) {
                options.onError(error);
            } else {
                Dialog.error(errorMsg, 'Hata');
            }
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    });
};
