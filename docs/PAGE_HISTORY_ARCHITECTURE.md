# Sayfa Geçmişi (Page History) Mimarisi

## Amaç
Normal insan trafiği ile bot/crawler/scanner/exploit denemelerini ayırmak; iki katmanlı (ham + sınıflandırılmış) kayıt ve şüpheli istek takibi.

## Katmanlar

1. **Raw request log**  
   Her gelen istek (admin hariç) bir satır: IP, method, URL, UA, referer, status, response_time, is_asset_request, fingerprint.  
   Tablo: `raw_request_logs`.

2. **Classification**  
   `VisitorClassificationService`: UA, path, IP sıklığı, asset mi, robots.txt, referer, method vb. sinyallere göre  
   `human | known_bot | suspicious_bot | monitoring | internal` atanır. User-Agent tek başına yeterli sayılmaz.

3. **Classified visit log**  
   Her istek için bir satır: raw_log_id, traffic_type, risk_level, suspicion_reason, matched_rule, bot_name.  
   Tablo: `classified_visit_logs`. Analytics (insan hit, benzersiz ziyaretçi) bu tablodan hesaplanır.

4. **Exploit / suspicious events**  
   Şüpheli pattern veya kısa sürede yoğun istek eşleştiğinde ayrı tabloya yazılır. Tablo: `exploit_suspicious_events`.

## Akış

- **Middleware** (`LogPageHistory`): Response’tan sonra çalışır.
  1. Admin ve `config('page_history.skip_paths')` atlanır.
  2. Raw log yazılır (`raw_request_logs`).
  3. `VisitorClassificationService->classify()` çağrılır.
  4. `ClassifiedVisitLog` yazılır.
  5. Şüpheli kural eşleşirse `ExploitSuspiciousEvent` yazılır.

## Dosya / isimlendirme tutarlılığı

- **Config:** `config/page_history.php`
- **Middleware:** `App\Http\Middleware\LogPageHistory`
- **Controller:** `App\Http\Controllers\Admin\PageHistoryController`
- **Route prefix:** `admin/page-history` → `admin.page-history.raw`, `.classified`, `.suspicious`
- **Views:** `resources/views/admin/page-history/raw.blade.php`, `classified.blade.php`, `suspicious.blade.php`
- Eski `page_history` tablosu ve legacy sayfa kaldırıldı; tüm loglar yeni tablolardan (raw, classified, suspicious) yönetilir.

## Kural yapısı (config/page_history.php)

- `suspicious_path_patterns`, `suspicious_query_patterns`, `known_bot_signatures`, `monitoring_paths`, `internal_ips`, `rate_limit_window_seconds`, `rate_limit_max_requests`
- Yeni kural eklemek: config’de ilgili diziye pattern ekleyin; kod değişikliği gerekmez.

## Log retention

- config’de `raw_retention_days`, `classified_retention_days`, `exploit_retention_days`.  
  İsteğe bağlı scheduled command ile eski kayıtlar silinebilir.
