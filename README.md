# Vedat Egunduz Portfolio

Laravel tabanli portfolio, blog ve admin paneli uygulamasi.

## Ozellikler

- Cok dilli public site (`tr`, `en`)
- Blog listeleme ve detay sayfalari
- Admin panelinden yazi yonetimi
- Otomatik kaydetme destekli editor akisi
- Iletisim formu ve admin mesaj takibi
- Sitemap, SEO ve Open Graph altyapisi
- Ziyaret siniflandirma ve blog analytics toplama

## Teknolojiler

- PHP 8.2+
- Laravel 12
- Livewire 4
- Tailwind CSS 4
- Vite 7
- Alpine.js
- MySQL

## Proje Yapisi

- `Modules/Admin`: admin auth, dashboard, profil, login gecmisi
- `Modules/Blog`: public blog, admin yazi paneli, post domain modeli
- `Modules/Contact`: iletisim formu ve mesaj yonetimi
- `Modules/Analytics`: request logging, traffic classification, blog analytics
- `Modules/PublicSite`: ana sayfa, locale, sitemap, error page akislari

Mimari sahiplik kurallari icin:

- `docs/MODULAR_MONOLITH_OWNERSHIP.md`
- `docs/PAGE_HISTORY_ARCHITECTURE.md`
- `docs/FRONTEND_ASSET_ARCHITECTURE.md`

## Kurulum

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Gelistirme icin:

```bash
composer run dev
```

Bu komut Laravel server, queue listener, log izleme ve Vite dev server'i birlikte calistirir.

## Test

```bash
php artisan test
```

Test paketi su alanlari kapsar:

- public blog akislari
- sitemap uretimi
- admin post paneli
- analytics overview
- moduller arasi bagimlilik kurallari

## Production Notlari

- `APP_DEBUG=false` kullanin
- `APP_URL` degerini dogru domain ile ayarlayin
- `public/images/og-image.jpg` dosyasini gercek paylasim gorseli ile guncelleyin
- deploy sonrasi cache komutlarini calistirin:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Iletisim

- Website: `https://vedategunduz.dev`
- Email: `vedat.bilisim@outlook.com`
- GitHub: `https://github.com/vedategunduz`
