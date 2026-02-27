import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, Grid, Scrollbar } from 'swiper/modules';

// CSS dosyalarını burada bir kere çağırmak yeterli
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import 'swiper/css/grid';

/**
 * Özelleştirilebilir Swiper Başlatıcı
 * @param {string} selector - Slider'ın class veya id'si (örn: '.ana-slider')
 * @param {object} options - Swiper ayarları (override etmek için)
 */
export const initSwiper = (selector, options = {}) => {

    // Varsayılan ayarlar (Her slider'da olmasını istediklerin)
    const defaultOptions = {
        modules: [Navigation, Pagination, Autoplay, EffectFade, Grid, Scrollbar],
        loop: true,
        // effect: 'fade',
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: selector + ' .swiper-pagination', // Dinamik seçim
            clickable: true,
        },
        navigation: {
            nextEl: selector + ' .swiper-button-next', // Dinamik seçim
            prevEl: selector + ' .swiper-button-prev',
        },
        scrollbar: {
            el: selector + ' .swiper-scrollbar',
            draggable: true,
        },
    };

    // Varsayılan ayarlarla gelen ayarları birleştir (Merge)
    const finalOptions = { ...defaultOptions, ...options };

    // Swiper'ı başlat ve instance'ı döndür
    return new Swiper(selector, finalOptions);
};
