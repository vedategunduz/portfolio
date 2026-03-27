# Vedat Egündüz - Portfolio Website

Modern ve responsive portfolio websitesi. Laravel ile geliştirilmiştir.

## 🚀 Özellikler

- ✨ Modern ve minimalist tasarım
- 🌓 Dark/Light mode desteği
- 📱 Responsive (Mobil uyumlu)
- 🎭 Smooth animasyonlar
- 📧 İletişim formu
- 🔍 SEO optimize edilmiş
- 🗺️ XML Sitemap
- 📊 Structured Data (Schema.org)

## 🛠️ Teknolojiler

- **Backend:** Laravel 12.x
- **Frontend:** Tailwind CSS, Alpine.js
- **Icons:** Lucide Icons
- **Database:** MySQL
- **Build Tool:** Vite

## 🧩 Moduler Mimari Notlari

- Moduller ve sahiplik sinirlari icin: `docs/MODULAR_MONOLITH_OWNERSHIP.md`
- Analytics detay mimarisi icin: `docs/PAGE_HISTORY_ARCHITECTURE.md`

## 📦 Kurulum

### Gereksinimler

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL

### Adımlar

1. Repository'yi klonlayın:
```bash
git clone https://github.com/vedategunduz/portfolio.git
cd portfolio
```

2. Composer bağımlılıklarını yükleyin:
```bash
composer install
```

3. NPM bağımlılıklarını yükleyin:
```bash
npm install
```

4. `.env` dosyasını oluşturun:
```bash
cp .env.example .env
```

5. Uygulama anahtarını oluşturun:
```bash
php artisan key:generate
```

6. Veritabanı ayarlarını yapın (`.env` dosyasında):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Veritabanı migration'larını çalıştırın:
```bash
php artisan migrate
```

8. Asset'leri derleyin:
```bash
npm run build
```

9. Geliştirme sunucusunu başlatın:
```bash
php artisan serve
```

## 🌐 Production Deployment

### SEO Ayarları

Production'a deploy etmeden önce:

1. **`.env.production` dosyasını düzenleyin:**
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://vedategunduz.dev`
   - Database bilgilerini güncelleyin

2. **SEO Config güncelleyin (`config/seo.php`):**
   - Sosyal medya linkleri ✅ (Zaten ayarlandı)
   - Meta description ve keywords ✅

3. **Robots.txt kontrolü:**
   - Domain: `https://vedategunduz.dev` ✅

4. **Open Graph görseli ekleyin:**
   - `/public/images/og-image.jpg` konumuna 1200x630px boyutunda görsel ekleyin

5. **Cache'leri temizleyin ve optimize edin:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### SSL Sertifikası

SSL sertifikası zaten mevcut ✅

### Sitemap

- Sitemap otomatik oluşturulur: `https://vedategunduz.dev/sitemap.xml`
- Google Search Console'a eklenmelidir

## 📝 Lisans

Bu proje özel bir projedir ve tüm hakları saklıdır.

## 👤 İletişim

- **Website:** [vedategunduz.dev](https://vedategunduz.dev)
- **Email:** vedat.bilisim@outlook.com
- **GitHub:** [@vedategunduz](https://github.com/vedategunduz)
- **LinkedIn:** [vedategunduz](https://www.linkedin.com/in/vedategunduz)
- **Instagram:** [@vedategunduz](https://www.instagram.com/vedategunduz)

---

© 2026 Vedat Egündüz. Tüm hakları saklıdır.


In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
