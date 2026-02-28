import axios from 'axios';

// 1. Temel Ayarlar
const http = axios.create({
    baseURL: '/', // Veya '/api' (Projenin yapısına göre)
    headers: {
        'X-Requested-With': 'XMLHttpRequest', // Laravel'in AJAX olduğunu anlaması için şart
        'Accept': 'application/json',
    }
});

// 2. Request Interceptor (İstek Atılmadan Önce)
// CSRF Token'ı otomatik ekler. Blade sayfalarında meta tag'den okur.
http.interceptors.request.use(config => {
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// 3. Response Interceptor (Cevap Geldikten Sonra)
// Global hata yönetimi burada yapılır.
http.interceptors.response.use(
    response => {
        // Başarılı cevapları olduğu gibi döndür
        return response;
    },
    error => {
        // HATA YÖNETİMİ
        if (error.response) {
            const status = error.response.status;

            // 401: Oturum Süresi Dolmuş
            if (status === 401) {
                alert('Oturum süreniz doldu, lütfen tekrar giriş yapın.');
                window.location.href = '/login';
            }

            // 422: Validasyon Hatası (Laravel'den gelen form hataları)
            else if (status === 422) {
                const errors = error.response.data.errors;
                let errorMessages = '';

                // Hataları listele (İstersen burada SweetAlert / Toastify kullanabilirsin)
                Object.values(errors).forEach(err => {
                    errorMessages += `- ${err[0]}\n`;
                });

                console.warn('Validasyon Hatası:', errors);
                // alert('Lütfen bilgileri kontrol edin:\n' + errorMessages);
            }

            // 403: Yetkisiz Erişim
            else if (status === 403) {
                console.error('Bu işlem için yetkiniz yok.');
            }

            // 500: Sunucu Hatası
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
    // Raw axios instance'ına erişmek istersen:
    client: http
};
