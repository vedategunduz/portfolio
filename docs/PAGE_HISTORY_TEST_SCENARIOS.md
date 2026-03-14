# Sayfa Geçmişi – Test Senaryoları

## Ortam

1. `php artisan migrate` ile `raw_request_logs`, `classified_visit_logs`, `exploit_suspicious_events` tablolarını oluştur.
2. `config('page_history.enabled')` = true (varsayılan; env: `PAGE_HISTORY_ENABLED`).

---

## 1. Normal insan trafiği

- Tarayıcıdan ana sayfayı aç: `GET /`
- **Beklenen:** `raw_request_logs` ve `classified_visit_logs`’ta kayıt; `traffic_type` = human. `exploit_suspicious_events`’ta yeni kayıt yok.

## 2. Şüpheli path – exploit event

- İstek at: `GET /.env`, `GET /wp-admin`, `GET /phpmyadmin` vb.
- **Beklenen:** `traffic_type` = suspicious_bot, `exploit_suspicious_events`’ta bir satır; `event_type` = suspicious_pattern.

## 3. Bilinen bot

- User-Agent: `Googlebot/2.1` ile `GET /`
- **Beklenen:** `traffic_type` = known_bot, `bot_name` = Googlebot.

## 4. Rate abuse

- Aynı IP’den config’deki pencerede max istekten fazla istek at.
- **Beklenen:** `traffic_type` = suspicious_bot, `exploit_suspicious_events`’ta `event_type` = rate_abuse.

## 5. Admin atlanıyor

- `GET /admin/dashboard` (giriş sonrası).
- **Beklenen:** `raw_request_logs` ve `classified_visit_logs`’ta bu path için kayıt yok.

## 6. Admin panel

- Dashboard: Trafik özeti kartları (toplam hit, insan, bot, şüpheli, benzersiz insan, bugün, son 24h şüpheli, top IP/URL/pattern).
- Sayfa Geçmişi: Ham İstekler (`/admin/page-history/raw`), Sınıflandırılmış (`/admin/page-history/classified`), Şüpheli (`/admin/page-history/suspicious`). Filtreler çalışmalı.
- Eski kayıtlar: `/admin/legacy-page-history` (page_history tablosu, artık güncellenmiyor).
