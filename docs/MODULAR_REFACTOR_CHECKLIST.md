# Modular Refactor Checklist

Bu checklist, moduler monolith gecisinin tamamlanma durumunu tek yerde izlemek icin tutulur.

## Foundation

- [x] `Modules/` namespace Composer autoload'a eklendi.
- [x] `ModuleServiceProvider` ile modullerin route/migration/view yuklemeleri aktif.
- [x] Tum yeni modul route dosyalari (`Routes/web.php`, `Routes/api.php`) otomatik yukleniyor.

## Blog Module

- [x] Blog public route ownership: `Modules/Blog/Routes/web.php`
- [x] Blog admin route ownership: `Modules/Blog/Routes/web.php`
- [x] Blog controller ownership: `Modules/Blog/Http/Controllers/*`
- [x] Blog request/action/service/viewmodel ayrimi yapildi.
- [x] Blog model ownership: `Modules/Blog/Models/*`
- [x] `App\Models\Post*` shimleri kaldirildi.

## Contact Module

- [x] Contact web route ownership: `Modules/Contact/Routes/web.php`
- [x] Contact api route ownership: `Modules/Contact/Routes/api.php`
- [x] Contact controller ownership: `Modules/Contact/Http/Controllers/*`
- [x] Contact action ownership: `Modules/Contact/Application/Actions/*`
- [x] Contact model ownership: `Modules/Contact/Models/ContactMessage.php`
- [x] `App\Models\ContactMessage` shim kaldirildi.

## Analytics Module

- [x] Analytics web route ownership: `Modules/Analytics/Routes/web.php`
- [x] Analytics middleware ownership: `Modules/Analytics/Http/Middleware/LogPageHistory.php`
- [x] Analytics controller ownership: `Modules/Analytics/Http/Controllers/*`
- [x] Analytics service/action ownership: `Modules/Analytics/Application/*`
- [x] Analytics model ownership: `Modules/Analytics/Models/*`
- [x] Legacy analytics shimleri kaldirildi (`App\Services/*`, eski middleware/controller).

## Admin Module

- [x] Admin web route ownership: `Modules/Admin/Routes/web.php`
- [x] Admin auth route ownership: `Modules/Admin/Routes/web.php`
- [x] Admin controller ownership: `Modules/Admin/Http/Controllers/*`
- [x] LoginHistory model ownership: `Modules/Admin/Models/LoginHistory.php`
- [x] `App\Http\Controllers\AdminController`, `App\Http\Controllers\Auth\AdminAuthController`, `App\Models\LoginHistory` kaldirildi.

## PublicSite Module

- [x] Public web route ownership: `Modules/PublicSite/Routes/web.php`
- [x] Public api route ownership: `Modules/PublicSite/Routes/api.php`
- [x] Locale controller ownership: `Modules/PublicSite/Http/Controllers/LocaleController.php`
- [x] Sitemap controller ownership: `Modules/PublicSite/Http/Controllers/SitemapController.php`
- [x] Root/home/error route closure'lari controller action'lara tasindi.

## Route Layer Cleanup

- [x] `routes/web/*` placeholder dosyalari kaldirildi.
- [x] `routes/api/*` placeholder dosyalari kaldirildi.
- [x] `routes/web.php` ve `routes/api.php` module-first bootstrap notuna indirildi.

## Guardrails

- [x] Modul bagimlilik kurallari testi eklendi: `tests/Architecture/ModuleDependencyRulesTest.php`
- [x] Architecture guard testi passing.
- [x] Ana regresyon test setleri passing (Blog/Public/Sitemap/Admin panel).

## Documentation

- [x] Ownership map dokumani eklendi: `docs/MODULAR_MONOLITH_OWNERSHIP.md`
- [x] Analytics architecture dokumani modul path'lerine guncellendi.
- [x] README'ye modul mimari referanslari eklendi.

## Optional Next Steps

- [ ] CI pipeline'a `ModuleDependencyRulesTest` zorunlu check olarak eklenmesi.
- [ ] Modul bazli test suite ayrimi (or. `tests/Feature/Blog`, `tests/Feature/Contact` vb.).
- [ ] Cross-module entegrasyon olaylari icin explicit event contract dokumani.
