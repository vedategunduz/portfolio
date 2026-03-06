import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade, Grid, Scrollbar } from 'swiper/modules';

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
    const defaultOptions = {
        modules: [Navigation, Pagination, Autoplay, EffectFade, Grid, Scrollbar],
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: selector + ' .swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: selector + ' .swiper-button-next',
            prevEl: selector + ' .swiper-button-prev',
        },
        scrollbar: {
            el: selector + ' .swiper-scrollbar',
            draggable: true,
        },
    };

    const finalOptions = { ...defaultOptions, ...options };

    return new Swiper(selector, finalOptions);
};
