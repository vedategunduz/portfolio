# Frontend Asset Mimarisi (Laravel + Vite)

## Admin page modülleri – standartlar

Bu kurallar tüm admin sayfa modüllerinde geçerli:

1. **Dosya ismi:** `pages/admin/` altında **kebab-case** kullan.
   - Örnek: `contact-messages.js`. Tek kelime ise `dashboard.js` gibi kalır.

2. **Tek giriş fonksiyonu:** Her modülde yalnızca **bir** init fonksiyonu export et.
   - `dashboard.js` → `initServerStats()`
   - `contact-messages.js` → `initContactMessages()`

3. **Yükleme:** Sayfa script’i **Blade’de** ilgili view içinde `@push('scripts')` + `@vite('resources/js/pages/admin/<sayfa>.js')` ile dahil edilir. admin.js içinde root selector / dynamic import **yok**; sayfa–script ilişkisi doğrudan Blade üzerinden kurulur.

4. **Root id (isteğe bağlı):** Sayfa modülü kendi DOM’unu bulmak için `#server-stats-card`, `#page-history-root`, `#contact-messages-root` gibi id’ler kullanabilir; script zaten sadece o sayfada yüklendiği için routing mantığı gerekmez.

---

## Klasör yapısı

```
resources/js/
├── app.js                      # Public entry – genel/ortak davranışlar
├── admin.js                    # Admin entry – sadece ortak davranışlar (tema, Lucide, Dialog, getHttp)
├── core/                       # Hem public hem admin tarafından kullanılan temel yapılar
│   ├── http.js                 # Axios instance, getHttp, CSRF, 401 yönlendirmesi
│   └── theme-toggle.js         # Tema (dark/light) toggle
├── ui/                         # Tekrar kullanılabilir UI bileşenleri
│   └── dialog.js               # Dialog + toast tetikleyicileri (Dialog.success, .confirm vb.)
├── helpers/                    # Form ve benzeri yardımcı mantık
│   └── form.js                 # initForm, initAction; core/http + ui/dialog kullanır
├── features/                   # Özellik bazlı modüller (lazy kullanım için)
│   ├── slider/
│   │   └── swiper.js           # initSwiper – dynamic import ile kullan
│   └── editor/
│       └── ckeditor.js         # initEditor – dynamic import ile kullan
└── pages/                      # Sayfa özel modüller
    ├── public/                 # (ileride) Public sayfa özel
    └── admin/
        ├── dashboard.js        # Sunucu istatistikleri
        └── contact-messages.js # (ileride)
```

## Yeni bir şey eklerken (standard)

- **Ortaksa** → `core/`, `ui/`, `helpers/` veya `services/`
- **Belirli bir özelliğe aitse** → `features/<özellik-adı>/`
- **Tek bir sayfaya özelse** → `pages/public/` veya `pages/admin/`

## Dosya sorumlulukları

| Dosya | Açıklama |
|-------|----------|
| **core/http.js** | Ortak HTTP (axios, CSRF). 401’de path `/admin` ile başlıyorsa `/admin/login`, değilse `/login`. |
| **core/theme-toggle.js** | Tema butonu; app ve admin layout’ta kullanılır. |
| **ui/dialog.js** | Toast ve confirm modal tetikleyicileri. |
| **helpers/form.js** | initForm / initAction; `core/http` ve `ui/dialog` kullanır. |
| **features/slider/swiper.js** | Slider başlatıcı; lazy import ile kullan. |
| **features/editor/ckeditor.js** | Editör başlatıcı; lazy import ile kullan. |
| **pages/admin/dashboard.js** | Sadece dashboard sayfasında yüklenir; initServerStats(). |
| **admin.js** | Admin layout ortak: tema, Lucide, getHttp/Dialog (window). Sayfa modülü yüklemez. |

## Window globals

Mümkün olduğunca az global kullan. Şu an:

- **getHttp, Dialog**: Blade/toast ve eski kullanımlar için entry’ler (app.js / admin.js) window’a yazıyor; **yeni modüller birbirini import ile kullansın** (ör: dashboard.js `getHttp`’u core/http’tan import eder).
- **createIcons, lucideIcons**: Toast ve layout’taki Lucide ikonları için Blade tarafında gerekli; ileride component tabanlı yapıda azaltılabilir.

## Blade entegrasyonu

- **layouts/app.blade.php**: `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- **layouts/admin.blade.php**: `@vite(['resources/css/app.css', 'resources/js/admin.js'])` + body kapanmadan önce `@stack('scripts')`
- **layouts/empty.blade.php**: (admin login) `app.js` – tema + toast.
- **Admin sayfa script’leri:** Her admin view kendi script’ini ekler:
  - `dashboard.blade.php` → `@push('scripts')` + `@vite('resources/js/pages/admin/dashboard.js')`
  - `contact-messages.blade.php` → `@push('scripts')` + `@vite('resources/js/pages/admin/contact-messages.js')`

## Vite

- **input:** `resources/css/app.css`, `resources/js/app.js`, `resources/js/admin.js`, ve admin sayfa entry’leri: `resources/js/pages/admin/dashboard.js`, `contact-messages.js`. Sayfa dosyaları Blade’deki `@vite()` ile ayrı entry olarak yüklenir.
- Slider veya editör kullanacak sayfada: `import('./features/slider/swiper.js')` / `import('./features/editor/ckeditor.js')` ile lazy yükle.

## Eski / kaldırılan

- `lib/` → yerine `ui/` (dialog) ve `helpers/` (form).
- Root: `swiper-helper.js`, `ckeditor-helper.js` → `features/slider/swiper.js`, `features/editor/ckeditor.js`.
