# Frontend Asset Mimarisi (Laravel + Vite)

## Admin page modülleri – standartlar

Bu üç kural tüm admin sayfa modüllerinde geçerli:

1. **Dosya ismi:** `pages/admin/` altında **kebab-case** kullan.
   - Örnek: `page-history.js`, `contact-messages.js`. Tek kelime ise `dashboard.js` gibi kalır.

2. **Tek giriş fonksiyonu:** Her modülde yalnızca **bir** init fonksiyonu export et; adı tutarlı olsun.
   - `dashboard.js` → `initServerStats()`
   - `page-history.js` → `initPageHistory()`
   - `contact-messages.js` → `initContactMessages()`

3. **Root selector:** Dynamic import için Blade’de **tek bir root element id’si** kullan. Aynı convention:
   - Dashboard: `#server-stats-card`
   - Diğer sayfalar: `#<modül>-root` (örn. `#page-history-root`, `#contact-messages-root`).
   - `admin.js` bu id’lere göre ilgili modülü yükler.

---

## Klasör yapısı

```
resources/js/
├── app.js                      # Public entry – genel/ortak davranışlar
├── admin.js                    # Admin entry – admin layout ortak + sayfa yönlendirici
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
        ├── page-history.js     # (ileride)
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
| **pages/admin/dashboard.js** | Sadece dashboard: getHttp import eder, API + DOM güncelleme. |
| **admin.js** | Admin entry: tema, Lucide, getHttp/Dialog (gerekirse window), sayfa modülü yönlendirici. |

## Window globals

Mümkün olduğunca az global kullan. Şu an:

- **getHttp, Dialog**: Blade/toast ve eski kullanımlar için entry’ler (app.js / admin.js) window’a yazıyor; **yeni modüller birbirini import ile kullansın** (ör: dashboard.js `getHttp`’u core/http’tan import eder).
- **createIcons, lucideIcons**: Toast ve layout’taki Lucide ikonları için Blade tarafında gerekli; ileride component tabanlı yapıda azaltılabilir.

## Blade entegrasyonu

- **layouts/app.blade.php**: `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- **layouts/admin.blade.php**: `@vite(['resources/css/app.css', 'resources/js/admin.js'])`
- **layouts/empty.blade.php**: (admin login) `app.js` – tema + toast.
- Sayfa özel JS Blade’de değil; ilgili sayfa modülü (pages/admin/…) dynamic import ile yüklenir.

## Dynamic import (admin)

`admin.js` DOMContentLoaded sonrası, sayfadaki root id’ye göre ilgili modülü yükler:

| Root selector        | Dosya                    | Giriş fonksiyonu      |
|----------------------|--------------------------|------------------------|
| `#server-stats-card` | `pages/admin/dashboard.js` | `initServerStats()`   |
| `#page-history-root` | `pages/admin/page-history.js` | `initPageHistory()` |
| `#contact-messages-root` | `pages/admin/contact-messages.js` | `initContactMessages()` |

Slider veya editör kullanacak sayfada: `import('./features/slider/swiper.js')` / `import('./features/editor/ckeditor.js')` ile lazy yükle.

## Vite

- `input`: `['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js']`
- Sayfa ve feature modülleri dynamic import ile ayrı chunk’lara gider.

## Eski / kaldırılan

- `lib/` → yerine `ui/` (dialog) ve `helpers/` (form).
- Root: `swiper-helper.js`, `ckeditor-helper.js` → `features/slider/swiper.js`, `features/editor/ckeditor.js`.
