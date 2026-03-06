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
}, error => Promise.reject(error));

http.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            const status = error.response.status;
            // 401: path /admin ile başlıyorsa admin login, değilse public login sayfasına yönlendir
            if (status === 401) {
                alert('Oturum süreniz doldu, lütfen tekrar giriş yapın.');
                const isAdmin = window.location.pathname.startsWith('/admin');
                window.location.href = isAdmin ? '/admin/login' : '/login';
            } else if (status === 422) {
                console.warn('Validasyon Hatası:', error.response.data?.errors);
            } else if (status === 403) {
                console.error('Bu işlem için yetkiniz yok.');
            } else if (status >= 500) {
                console.error('Sunucu hatası oluştu.');
            }
        }
        return Promise.reject(error);
    }
);

export const Http = {
    get: (url, params = {}) => http.get(url, { params }),
    post: (url, data = {}) => http.post(url, data),
    put: (url, data = {}) => http.put(url, data),
    delete: (url, params = {}) => http.delete(url, { params }),
    client: http
};

export const getHttp = async () => Http;
