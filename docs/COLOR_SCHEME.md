# Proje Renk Şeması

Bu belge, portföy projesinde kullanılan renk paletini ve kullanım yerlerini tanımlar. Tüm renkler **light** ve **dark** tema ile uyumludur; dark modda `dark:` öneki ile alternatif değerler kullanılır.

---

## 1. Marka / Aksan (Primary)

| Ad           | Hex       | Kullanım |
|-------------|-----------|----------|
| **Primary** | `#D62113` | Ana marka rengi: CTA butonları, link hover, vurgu, aktif sayfa, focus ring |
| **Primary hover** | `#b81a0f` | Primary buton hover |
| **Primary gradient (to)** | `#FF6B6B` | Proje kartı gradient (from Primary) |

**Kullanıldığı yerler:** Hero’da “Vedat” vurgusu, nav link hover alt çizgi, primary/secondary butonlar, admin nav aktif, pagination aktif sayfa, skill/project kart hover border, checkbox/input focus, logout ve link vurguları.

---

## 2. Arka planlar (Backgrounds)

### Light mode

| Ad              | Hex       | Kullanım |
|-----------------|-----------|----------|
| **Page bg**     | `#FDFDFC` | Ana sayfa arka planı (app, empty layout body) |
| **Card / Surface** | `#ffffff` (white) | Kartlar, input arka planı, modal içi |
| **Card alt**    | `#fafafa` | Pagination bar, bazı bölüm arka planları |
| **Header/Surface muted** | `white/80`, `#F8F8F7` | Header backdrop, modal header, toast |
| **Blob/Overlay** | `white/35`, `white/90` | Login kutusu, “or” ayırıcı |

### Dark mode

| Ad              | Hex       | Kullanım |
|-----------------|-----------|----------|
| **Page bg**     | `#0a0a0a` | Ana sayfa arka planı |
| **Card / Surface** | `#1a1a18` | Kartlar, input, modal içi, buton |
| **Card alt**    | `#1e1e1e` | Pagination bar |
| **Header/Surface muted** | `#161615`, `#161615/80`, `#141413` | Header, admin header, modal header |

---

## 3. Metin (Text)

### Light mode

| Ad           | Hex       | Kullanım |
|-------------|-----------|----------|
| **Primary text** | `#1b1b18` | Başlıklar, ana metin |
| **Secondary / Muted** | `#706f6c` | Etiketler, placeholder, ikincil metin |
| **Tertiary / Disabled** | `#a3a3a0`, `#9ca3af` | Devre dışı buton, pagination ellipsis |
| **Table header** | `#374151` | Admin tablo başlıkları (th) |

### Dark mode

| Ad           | Hex       | Kullanım |
|-------------|-----------|----------|
| **Primary text** | `#EDEDEC` | Başlıklar, ana metin |
| **Secondary / Muted** | `#8F8F8B`, `#D4D3D0` | Etiketler, placeholder, ikincil metin |
| **Tertiary / Disabled** | `#525250`, `#6b7280` | Devre dışı buton, soluk metin |
| **Table header** | `#d1d5db` | Admin tablo başlıkları |

---

## 4. Çizgiler / Kenarlıklar (Borders)

### Light mode

| Ad        | Hex       | Kullanım |
|----------|-----------|----------|
| **Border default** | `#e3e3e0` | Kart, input, buton, ayırıcı çizgiler |
| **Border subtle**  | `#e5e5e5`, `#333333` (dark) | Pagination üst border |

### Dark mode

| Ad        | Hex       | Kullanım |
|----------|-----------|----------|
| **Border default** | `#3E3E3A` | Kart, input, buton, ayırıcı çizgiler |

---

## 5. Semantic (Anlamlı) Renkler

Tailwind sınıfları ile kullanılır; duruma göre metin/arka plan/border.

| Durum    | Light örnek        | Dark örnek         | Kullanım |
|----------|--------------------|--------------------|----------|
| **Başarı** | `emerald-500/20`, `emerald-600`, `emerald-700` | `emerald-500/25`, `emerald-400` | Aktif durum, GET badge, success toast |
| **Hata**   | `red-500`, `red-600`, `red-700` | `red-400`, `red-500/25` | Hata mesajı, DELETE badge, inactive, danger toast |
| **Uyarı**  | `amber-500`, `amber-600` | `amber-400`, `amber-500/20` | Uyarı toast, warning badge, şüpheli sayı |
| **Bilgi**  | `blue-500`, `blue-600` | `blue-300`, `blue-500/20` | Info toast, PUT/PATCH badge |
| **Nötr**   | `zinc-200`, `zinc-400`, `zinc-500`, `zinc-900` | — | Toast kutusu, varsayılan badge (HEAD/OPTIONS) |

**Badge/Method renkleri:**  
- GET: emerald  
- POST: `#D62113`  
- PUT/PATCH: blue  
- DELETE: red  
- HEAD/OPTIONS: `#706f6c` / `#4a4946` (light), `#b0afac` (dark)

---

## 6. Özet Tablo (Hex)

| Rol        | Light     | Dark      |
|-----------|-----------|-----------|
| Marka     | `#D62113` | `#D62113` |
| Marka hover | `#b81a0f` | `#b81a0f` |
| Sayfa arka plan | `#FDFDFC` | `#0a0a0a` |
| Kart / yüzey | `#ffffff` | `#1a1a18` |
| Ana metin | `#1b1b18` | `#EDEDEC` |
| İkincil metin | `#706f6c` | `#8F8F8B` / `#D4D3D0` |
| Border    | `#e3e3e0` | `#3E3E3A` |
| Devre dışı metin | `#a3a3a0` | `#525250` |

---

## 7. Tailwind’de Özel Renk Kullanımı

Projede hex değerler doğrudan sınıf içinde kullanılıyor; `tailwind.config.js` içinde tema genişletmesi yok. Örnekler:

- `bg-[#D62113]`, `text-[#D62113]`, `border-[#D62113]`
- `bg-[#1b1b18]` + `dark:text-[#EDEDEC]`
- `border-[#e3e3e0]` + `dark:border-[#3E3E3A]`
- Opaklık: `bg-[#D62113]/10`, `shadow-[#D62113]/50`, `ring-[#D62113]/20`

Yeni bileşen eklerken bu dokümandaki değerleri kullanmak, light/dark ve sayfalar arası tutarlılığı korur.
