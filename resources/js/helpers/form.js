import { Dialog } from '../ui/dialog.js';
import { getHttp } from '../core/http.js';

const t = (key, fallback) => window.translations?.[key] || fallback;

const clearFormMessages = (form) => {
    const errorBox = form.parentElement?.querySelector('[data-form-error]') || form.querySelector('[data-form-error]');
    const successBox = form.parentElement?.querySelector('[data-form-success]') || form.querySelector('[data-form-success]');
    if (errorBox) errorBox.classList.add('hidden');
    if (successBox) successBox.classList.add('hidden');
};

const showFormMessage = (form, type, message) => {
    const isError = type === 'error';
    const box = (form.parentElement?.querySelector(isError ? '[data-form-error]' : '[data-form-success]')
        || form.querySelector(isError ? '[data-form-error]' : '[data-form-success]'));
    if (!box) return false;
    const messageEl = box.querySelector(isError ? '[data-form-error-message]' : '[data-form-success-message]');
    if (messageEl) messageEl.textContent = message;
    box.classList.remove('hidden');
    return true;
};

const clearValidationErrors = (form) => {
    form.querySelectorAll('[data-field-error]').forEach((el) => {
        el.textContent = '';
        el.classList.add('hidden');
    });
    form.querySelectorAll('[data-field-input]').forEach((el) => {
        el.classList.remove('border-red-500');
    });
};

const escapeCss = (value) => {
    const str = String(value);
    if (window.CSS && typeof window.CSS.escape === 'function') return window.CSS.escape(str);
    return str.replace(/"/g, '\\"');
};

const renderValidationErrors = (form, errors) => {
    if (!errors || typeof errors !== 'object') return;
    Object.entries(errors).forEach(([field, messages]) => {
        const msg = Array.isArray(messages) ? (messages[0] || '') : String(messages || '');
        if (!msg) return;
        const errorEl = form.querySelector(`[data-field-error="${escapeCss(field)}"]`);
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
        }
        const inputEl = form.querySelector(`[data-field-input="${escapeCss(field)}"]`) || form.querySelector(`[name="${escapeCss(field)}"]`);
        if (inputEl) inputEl.classList.add('border-red-500');
    });
};

const parseErrorMessage = (error) => {
    let message = t('form.unexpected_error', 'Beklenmedik bir hata oluştu.');
    if (error.response) {
        const data = error.response.data;
        if (error.response.status === 422 && data?.errors) {
            const firstKey = Object.keys(data.errors)[0];
            message = firstKey && data.errors[firstKey]?.[0] ? data.errors[firstKey][0] : (data.message || t('form.check_fields', 'Lütfen bilgileri kontrol edin.'));
        } else if (data?.message) {
            message = data.message;
        }
    } else if (error.message) {
        message = error.message;
    }
    return message;
};

const loadingHtml = `<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${t('form.processing', 'İşleniyor...')}</span>`;

export function initForm(selector, options = {}) {
    const form = document.querySelector(selector);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFormMessages(form);
        clearValidationErrors(form);

        const url = options.url || form.action;
        let method = (options.method || form.getAttribute('method') || 'post').toLowerCase();
        const shouldReset = options.reset || false;
        const submitBtn = form.querySelector('[type="submit"]') || form.querySelector('.form-submit');
        let originalBtnContent = '';
        if (submitBtn) {
            originalBtnContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = options.loadingText || loadingHtml;
        }

        const formData = new FormData(form);
        if (['put', 'patch', 'delete'].includes(method)) {
            formData.append('_method', method.toUpperCase());
            method = 'post';
        }

        try {
            const HttpClass = await getHttp();
            const response = method === 'get'
                ? await HttpClass.get(url, Object.fromEntries(formData))
                : await HttpClass[method](url, formData);

            if (shouldReset) form.reset();
            const successMessage = response?.data?.message;
            if (successMessage) {
                if (!showFormMessage(form, 'success', successMessage)) Dialog.success(successMessage);
            }
            if (options.onSuccess) options.onSuccess(response);
        } catch (error) {
            const errorMsg = parseErrorMessage(error);
            const status = error?.response?.status;
            const data = error?.response?.data;
            if (status === 422 && data?.errors) renderValidationErrors(form, data.errors);
            if (options.onError) options.onError(error);
            else {
                if (!showFormMessage(form, 'error', errorMsg)) Dialog.error(errorMsg, t('form.action_failed', 'İşlem Başarısız'));
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            }
        }
    });
}

export function initAction(selectorOrElement, options = {}) {
    const btn = typeof selectorOrElement === 'string' ? document.querySelector(selectorOrElement) : selectorOrElement;
    if (!btn) return;

    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        if (options.confirm) {
            const isConfirmed = await Dialog.confirm(
                options.confirm,
                options.confirmTitle || t('form.confirm_required', 'Onay Gerekiyor'),
                options.confirmButtonText || t('form.confirm_continue', 'Evet, Devam Et'),
                options.confirmType || 'danger'
            );
            if (!isConfirmed) return;
        }

        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = options.loadingText || loadingHtml;
        const url = options.url || btn.getAttribute('href') || btn.getAttribute('data-url');
        const method = (options.method || 'get').toLowerCase();

        try {
            const HttpClass = await getHttp();
            const response = await HttpClass[method](url);
            if (options.onSuccess) options.onSuccess(response);
            else if (response.data?.message) Dialog.success(response.data.message);
        } catch (error) {
            console.error('Action Hatası:', error);
            const errorMsg = parseErrorMessage(error);
            if (options.onError) options.onError(error);
            else Dialog.error(errorMsg, t('form.error', 'Hata'));
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    });
}

export { getHttp } from '../core/http.js';
