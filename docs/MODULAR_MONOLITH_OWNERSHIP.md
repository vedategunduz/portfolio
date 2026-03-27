# Modular Monolith Ownership Map

Bu dokuman, proje icindeki modullerin sahiplik sinirlarini ve bagimlilik kurallarini netlestirir.

## Module Ownership

- `Modules/Blog`
  - Blog public/admin HTTP flow
  - Blog modelleri: `Post`, `PostTranslation`, `PostGalleryImage`
  - Blog route'lari: `Modules/Blog/Routes/web.php`

- `Modules/Contact`
  - Iletisim formu ve admin mesaj yonetimi
  - Contact modeli: `ContactMessage`
  - Contact route'lari: `Modules/Contact/Routes/web.php`, `Modules/Contact/Routes/api.php`

- `Modules/Analytics`
  - Visitor tracking, classification, suspicious events
  - Analytics modelleri: `RawRequestLog`, `ClassifiedVisitLog`, `ExploitSuspiciousEvent`
  - Analytics route'lari: `Modules/Analytics/Routes/web.php`

- `Modules/Admin`
  - Admin dashboard/profile/login history
  - Admin auth/login flow
  - Admin modeli: `LoginHistory`
  - Admin route'lari: `Modules/Admin/Routes/web.php`

- `Modules/PublicSite`
  - Public site orchestration (home, locale, sitemap, error pages)
  - Public route'lari: `Modules/PublicSite/Routes/web.php`, `Modules/PublicSite/Routes/api.php`

## Shared / Core

- `App/Models/User` ortak kimlik modeli olarak kalir.
- Framework-level ve altyapi odakli servisler (`App/Services/*`, middleware wiring, app bootstrapping) `App/*` altinda kalabilir.

## Route Ownership Rule

- Yeni route'lar sadece modullerin kendi `Routes/web.php` veya `Routes/api.php` dosyalarina eklenir.
- `routes/web.php` ve `routes/api.php` sadece bootstrap/entrypoint gorevi gorur.

## Dependency Rules

- Moduller varsayilan olarak baska bir modul namespace'ini direkt import etmez.
- Istisna:
  - `Admin` modulunun orchestration amacli diger modulleri compose etmesine izin verilir.
  - `PublicSite` modulunun public composition amaciyla diger modulleri (ornegin Blog) kullanmasina izin verilir.
- Modul kodu icinde `App\Models\*` kullanimi yasaktir; sadece `App\Models\User` paylasilan model olarak izinlidir.

Bu kurallar `tests/Architecture/ModuleDependencyRulesTest.php` ile otomatik olarak kontrol edilir.
