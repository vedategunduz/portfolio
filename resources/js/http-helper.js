import axios from 'axios';

const http = axios.create({
    baseURL: '/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    }
});

http.interceptors.request.use(config => {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

http.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        if (error.response) {
            const status = error.response.status;

            if (status === 401) {
                alert('Oturum süreniz doldu, lütfen tekrar giriş yapın.');
                window.location.href = '/login';
            }

            else if (status === 422) {
                const errors = error.response.data.errors;
                let errorMessages = '';

                Object.values(errors).forEach(err => {
                    errorMessages += `- ${err[0]}\n`;
                });

                console.warn('Validasyon Hatası:', errors);
                // alert('Lütfen bilgileri kontrol edin:\n' + errorMessages);
            }

            else if (status === 403) {
                console.error('Bu işlem için yetkiniz yok.');
            }

            else if (status >= 500) {
                console.error('Sunucu hatası oluştu.');
            }
        }

        return Promise.reject(error);
    }
);

/**
 * Yardımcı Fonksiyonlar
 * Doğrudan http.get() de kullanabilirsin ama bunları window'a açacağız.
 */
export const Http = {
    get: (url, params = {}) => http.get(url, { params }),
    post: (url, data = {}) => http.post(url, data),
    put: (url, data = {}) => http.put(url, data),
    delete: (url, params = {}) => http.delete(url, { params }),
    client: http
};
