# Favicons

Tüm site ikonları bu klasörde tutulur. Layout'larda `asset('favicons/...')` ile referans edilir.

## Dosyalar

| Dosya | Boyut | Kullanım |
|-------|--------|----------|
| `favicon-16x16.png` | 16×16 | Sekme (küçük) |
| `favicon-32x32.png` | 32×32 | Sekme / bookmark |
| `apple-touch-icon.png` | 180×180 | iOS "Ana Ekrana Ekle" |
| `android-chrome-192x192.png` | 192×192 | Android Chrome, PWA manifest |
| `android-chrome-512x512.png` | 512×512 | Android Chrome splash, PWA manifest |

## Manifest

`public/manifest.json` Android Chrome ve PWA için 192 ve 512 ikonlarına referans verir. Theme rengi marka rengi (`#D62113`) ile ayarlı.

## İsteğe bağlı

- `favicon.ico` – Eski tarayıcılar için (çoklu boyut tek .ico).
