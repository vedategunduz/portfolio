import { createIcons, icons } from 'lucide';
import Alpine from 'alpinejs';
import { initSwiper } from './swiper-helper';
import { initEditor } from './ckeditor-helper';
import { Http } from './http-helper';
import { initForm, initAction } from './form-helper';
import { Dialog } from './dialog-helper';

// 1. Alpine'i Global Yap
window.Alpine = Alpine;

// 2. Helper Fonksiyonlarını Global Yap
window.createSlider = initSwiper;
window.createEditor = initEditor;
window.Http = Http;
window.initForm = initForm;
window.initAction = initAction;
window.Dialog = Dialog;

// 3. YENİ EKLENEN: Lucide Fonksiyonlarını Global Yap
// Böylece dialog.blade.php içinden 'window.createIcons(...)' diyebileceğiz.
window.createIcons = createIcons;
window.lucideIcons = icons;

// 4. Alpine'i Başlat
Alpine.start();

// 5. Sayfa Yüklendiğinde İkonları Tara
createIcons({
    attrs: {
        width: 16,
        height: 16,
    },
    icons
});
