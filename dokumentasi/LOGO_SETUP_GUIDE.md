# Panduan Setup Logo dan Icon - SIAG NEKAS

## 📁 File Logo yang Dibutuhkan

Semua file logo menggunakan `logonekas.png` sebagai base. Berikut adalah ukuran yang perlu disiapkan:

### 1. Logo Utama Aplikasi

**File:** `logonekas.png`

- **Ukuran:** Bebas (Rekomendasi: 512x512px atau lebih)
- **Format:** PNG dengan background transparan
- **Lokasi:** `/public/assets/images/logonekas.png`
- **Kegunaan:**
  - Logo di header aplikasi
  - Logo di halaman login
  - Logo di footer
  - Watermark untuk PDF

### 2. PWA Icons

#### Icon 192x192

**File:** `logonekas-192.png`

- **Ukuran:** 192x192 pixels
- **Format:** PNG
- **Lokasi:** `/public/assets/images/logonekas-192.png`
- **Kegunaan:** Icon untuk PWA di device Android/iOS

#### Icon 512x512

**File:** `logonekas-512.png`

- **Ukuran:** 512x512 pixels
- **Format:** PNG
- **Lokasi:** `/public/assets/images/logonekas-512.png`
- **Kegunaan:** Icon untuk PWA high-resolution devices

### 3. Favicon

**File:** `logonekas-favicon.ico` atau gunakan `logonekas.png`

- **Ukuran:** 32x32 atau 16x16 pixels
- **Format:** ICO atau PNG
- **Lokasi:** `/public/assets/images/logonekas-favicon.ico`
- **Kegunaan:** Favicon browser tab

---

## 🎨 Cara Membuat dari File Logo Asli

Jika Anda memiliki file `logonekas.png` dengan ukuran besar (misalnya 1024x1024), ikuti langkah berikut:

### Menggunakan Online Tools

#### 1. PWA Icon Generator

Kunjungi: https://www.pwabuilder.com/imageGenerator

1. Upload file `logonekas.png`
2. Download hasil generate (akan otomatis membuat berbagai ukuran)
3. Ambil file ukuran 192x192 dan 512x512

#### 2. Favicon Generator

Kunjungi: https://favicon.io/

1. Upload file `logonekas.png`
2. Download package favicon
3. Gunakan favicon.ico yang dihasilkan

### Menggunakan ImageMagick (Command Line)

Jika memiliki ImageMagick terinstall:

```bash
# Resize untuk PWA Icons
convert logonekas.png -resize 192x192 logonekas-192.png
convert logonekas.png -resize 512x512 logonekas-512.png

# Buat favicon
convert logonekas.png -resize 32x32 logonekas-favicon.ico
```

### Menggunakan Photoshop/GIMP

1. Buka file `logonekas.png`
2. Image > Image Size
3. Ubah ukuran ke 192x192 (untuk icon 192)
4. Save As > `logonekas-192.png`
5. Ulangi untuk ukuran 512x512

---

## 📂 Struktur Direktori Final

```
public/
└── assets/
    └── images/
        ├── logonekas.png           # Logo utama (512x512 atau lebih)
        ├── logonekas-192.png       # PWA Icon 192x192
        ├── logonekas-512.png       # PWA Icon 512x512
        └── logonekas-favicon.ico   # Favicon (opsional, bisa pakai .png)
```

---

## ⚙️ Konfigurasi di Aplikasi

### 1. Update .env

Pastikan file `.env` sudah dikonfigurasi:

```env
APP_LOGO=/assets/images/logonekas.png
APP_LOGO_SEKOLAH=/assets/images/logonekas.png
APP_FAVICON=/assets/images/logonekas.png
APP_WATERMARK=/assets/images/logonekas.png
```

### 2. Update PWA Config

File: `config/pwa.php`

```php
'icons' => [
    [
        'src' => '/assets/images/logonekas-192.png',
        'sizes' => '192x192',
        'type' => 'image/png',
    ],
    [
        'src' => '/assets/images/logonekas-512.png',
        'sizes' => '512x512',
        'type' => 'image/png',
    ],
],
```

### 3. Update Layout Blade

File: `resources/views/layouts/app.blade.php`

```blade
<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset(config('app.favicon', '/assets/images/logonekas.png')) }}">

<!-- PWA Manifest -->
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" href="{{ asset('/assets/images/logonekas-192.png') }}">
```

---

## ✅ Checklist Setup

- [ ] File `logonekas.png` (logo utama) sudah disiapkan
- [ ] File `logonekas-192.png` sudah di-generate dan ditempatkan
- [ ] File `logonekas-512.png` sudah di-generate dan ditempatkan
- [ ] File favicon sudah disiapkan (ico atau png)
- [ ] Semua file ditempatkan di `/public/assets/images/`
- [ ] File `.env` sudah dikonfigurasi dengan path yang benar
- [ ] File `config/pwa.php` sudah update dengan icon yang benar
- [ ] Layout Blade sudah menggunakan logo yang benar
- [ ] Test PWA install - logo tampil dengan benar
- [ ] Test favicon di browser tab - icon tampil dengan benar

---

## 🧪 Testing

### Test Logo di Aplikasi

1. Jalankan aplikasi: `php artisan serve`
2. Buka browser: `http://localhost:8000`
3. Check:
   - Logo di header (kiri atas)
   - Logo di halaman login
   - Favicon di browser tab

### Test PWA Icon

1. Buka aplikasi di browser
2. Klik "Add to Home Screen" / "Install App"
3. Check icon di home screen device
4. Buka app dari home screen
5. Check splash screen dan app icon

### Test Watermark PDF

1. Generate laporan PDF dari aplikasi
2. Check watermark muncul di dokumen PDF
3. Pastikan logo tidak pecah/blur

---

## 🎨 Spesifikasi Desain Logo (Rekomendasi)

### Logo Utama

- **Background:** Transparan (recommended) atau solid color
- **Format:** PNG atau SVG (jika tersedia)
- **Resolusi:** Minimal 512x512px (untuk quality)
- **Aspect Ratio:** 1:1 (square) atau landscape

### Color Guidelines

- Pastikan logo terlihat jelas di background putih
- Jika logo berwarna terang, siapkan versi dengan outline
- Hindari gradient kompleks untuk ukuran kecil (favicon)

### Best Practices

- Logo harus legible di ukuran kecil (32x32px)
- Hindari detail yang terlalu halus
- Gunakan vector format jika memungkinkan
- Simpan master file dalam format yang dapat diedit (AI, PSD, SVG)

---

## 📞 Support

Jika ada kendala dengan setup logo/icon:

- Hubungi Tim IT: info@smknkasomalang.sch.id
- Atau gunakan placeholder sementara hingga logo final siap

---

**Note:** Pastikan file logo memiliki quality yang baik agar tidak pecah saat ditampilkan di berbagai ukuran layar.
