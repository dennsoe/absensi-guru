# PERUBAHAN KONFIGURASI - SIAG NEKAS

## SMK Negeri Kasomalang

**Tanggal Update:** 16 November 2025

---

## 📝 Ringkasan Perubahan

Berikut adalah perubahan yang telah dilakukan untuk menyesuaikan aplikasi dengan identitas **SMK Negeri Kasomalang** dan nama aplikasi **SIAG NEKAS**:

---

## 🏫 Informasi Sekolah yang Diperbarui

### Data Sekolah

- **Nama:** SMK Negeri Kasomalang
- **NPSN:** 20219345
- **Alamat:** Jl. Raya Kasomalang, Kasomalang Kulon, Kec. Kasomalang, Kabupaten Subang, Jawa Barat 41281
- **Email:** info@smknkasomalang.sch.id
- **Telepon:** (0260) 520xxx
- **Website:** https://smknkasomalang.sch.id

### Data Kepala Sekolah

- **Nama:** [Perlu diisi sesuai data aktual]
- **NIP:** [Perlu diisi sesuai data aktual]

> **Note:** Silakan update nama dan NIP Kepala Sekolah di file `.env` setelah mendapat data yang akurat.

---

## 📱 Informasi Aplikasi yang Diperbarui

### Nama Aplikasi

- **Sebelumnya:** Aplikasi Absensi Guru / Sistem Absensi Guru
- **Sekarang:** **SIAG NEKAS**

### Kepanjangan

**SIAG NEKAS** = **Sistem Informasi Absensi Guru SMK Negeri Kasomalang**

### Deskripsi

"Sistem Informasi Absensi Guru SMK Negeri Kasomalang dengan QR Code dan Selfie"

### Tagline

"Sistem Informasi Absensi Guru SMK Negeri Kasomalang"

### Footer Text

"© 2025 SMK Negeri Kasomalang. All rights reserved."

---

## 🖼️ Logo & Branding Assets

### File Logo Utama

**Nama File:** `logonekas.png`

### Penggunaan Logo

Logo `logonekas.png` digunakan untuk:

- Logo aplikasi utama
- Logo sekolah
- Favicon
- Watermark PDF

### File yang Perlu Disiapkan

1. `logonekas.png` (512x512px atau lebih) - Logo utama
2. `logonekas-192.png` (192x192px) - PWA Icon
3. `logonekas-512.png` (512x512px) - PWA Icon Hi-res
4. `logonekas-favicon.ico` (32x32px) - Favicon browser (opsional)

### Lokasi Penempatan

```
/public/assets/images/
├── logonekas.png
├── logonekas-192.png
├── logonekas-512.png
└── logonekas-favicon.ico (opsional)
```

> **Panduan Lengkap:** Lihat file `LOGO_SETUP_GUIDE.md` untuk instruksi detail setup logo dan icon.

---

## 📄 File yang Telah Diubah

### 1. DEVELOPMENT_NOTES.md

**Perubahan:**

- Header aplikasi diupdate menjadi "SIAG NEKAS - LARAVEL"
- Informasi project: SIAG NEKAS - Sistem Informasi Absensi Guru SMK Negeri Kasomalang
- Section Branding: APP_NAME="SIAG NEKAS" dengan logo logonekas.png
- Section Sekolah Info: Data SMK Negeri Kasomalang lengkap
- Section PWA Settings: PWA_NAME="SIAG NEKAS"
- Contoh settings categories dengan data SMK Negeri Kasomalang

### 2. SKEMA_LARAVEL_IMPLEMENTATION.md

**Perubahan:**

- Header: "SKEMA SIAG NEKAS - LARAVEL VERSION"
- Subtitle: "Sistem Informasi Absensi Guru SMK Negeri Kasomalang"
- Informasi Umum: Nama aplikasi, sekolah, dan NPSN ditambahkan
- PWA Config: name="SIAG NEKAS", description sesuai SIAG NEKAS
- PWA Icons: logonekas-192.png dan logonekas-512.png

### 3. README.md (BARU)

**File baru dibuat dengan konten:**

- Header: SIAG NEKAS dengan logo
- Informasi sekolah lengkap SMK Negeri Kasomalang
- Fitur utama aplikasi
- Teknologi stack
- Panduan instalasi
- Default login credentials
- Instalasi PWA
- Konfigurasi system
- Branding assets information

### 4. .env.example (BARU)

**File baru dibuat dengan konten:**

- APP_NAME="SIAG NEKAS"
- Database: siag_nekas
- Branding settings dengan logonekas.png
- Sekolah info: SMK Negeri Kasomalang lengkap
- PWA settings: SIAG NEKAS
- Semua konfigurasi sistem lengkap (GPS, QR, Absensi, Notifikasi, dll)

### 5. LOGO_SETUP_GUIDE.md (BARU)

**File panduan lengkap untuk:**

- Daftar file logo yang dibutuhkan
- Cara membuat/resize logo berbagai ukuran
- Struktur direktori untuk logo
- Konfigurasi di aplikasi
- Checklist setup
- Testing logo dan icon
- Spesifikasi desain rekomendasi

---

## ⚙️ Konfigurasi Environment Variables

### Perubahan Utama di .env

```env
# Aplikasi
APP_NAME="SIAG NEKAS"
DB_DATABASE=siag_nekas

# Branding
APP_TAGLINE="Sistem Informasi Absensi Guru SMK Negeri Kasomalang"
APP_LOGO=/assets/images/logonekas.png
APP_LOGO_SEKOLAH=/assets/images/logonekas.png
APP_FAVICON=/assets/images/logonekas.png
APP_FOOTER_TEXT="© 2025 SMK Negeri Kasomalang. All rights reserved."
APP_WATERMARK=/assets/images/logonekas.png

# Sekolah
SEKOLAH_NAMA="SMK Negeri Kasomalang"
SEKOLAH_NPSN=20219345
SEKOLAH_ALAMAT="Jl. Raya Kasomalang, Kasomalang Kulon, Kec. Kasomalang, Kabupaten Subang, Jawa Barat 41281"
SEKOLAH_EMAIL=info@smknkasomalang.sch.id
SEKOLAH_TELEPON=(0260) 520xxx
SEKOLAH_WEBSITE=https://smknkasomalang.sch.id
SEKOLAH_KEPALA_SEKOLAH="[Nama Kepala Sekolah]"
SEKOLAH_KEPALA_SEKOLAH_NIP=[NIP Kepala Sekolah]

# PWA
PWA_NAME="SIAG NEKAS"
PWA_SHORT_NAME="SIAG NEKAS"
PWA_DESCRIPTION="Sistem Informasi Absensi Guru SMK Negeri Kasomalang dengan QR Code dan Selfie"
```

---

## 📋 Checklist Langkah Berikutnya

### Segera Dilakukan

- [ ] Siapkan file logo `logonekas.png` dengan quality tinggi
- [ ] Generate PWA icons (192x192 dan 512x512)
- [ ] Tempatkan semua file logo di `/public/assets/images/`
- [ ] Update nama Kepala Sekolah dan NIP di `.env`
- [ ] Verifikasi nomor telepon sekolah yang benar
- [ ] Test aplikasi dengan branding baru

### Sebelum Production

- [ ] Update koordinat GPS sekolah yang akurat
- [ ] Konfigurasi email settings (jika menggunakan email notifikasi)
- [ ] Konfigurasi WhatsApp integration (jika menggunakan WhatsApp)
- [ ] Setup backup otomatis
- [ ] Test semua fitur dengan data sekolah yang benar
- [ ] Training untuk admin dan user

### Dokumentasi

- [ ] Screenshot aplikasi dengan branding baru
- [ ] User manual untuk guru dan admin
- [ ] Video tutorial penggunaan aplikasi
- [ ] SOP penggunaan sistem

---

## 📍 Koordinat GPS Sekolah

**Current Setting (Perkiraan):**

- Latitude: -6.4167
- Longitude: 107.7667
- Radius: 100 meter

> **PENTING:** Koordinat di atas adalah perkiraan untuk wilayah Kasomalang, Subang. Untuk akurasi GPS validation, silakan:
>
> 1. Buka Google Maps di lokasi sekolah
> 2. Klik kanan di titik lokasi sekolah
> 3. Salin koordinat yang muncul
> 4. Update di Admin Panel > Pengaturan > GPS atau di file `.env`

---

## 🎨 Panduan Branding Konsistensi

### Penggunaan Nama

- **Formal:** SIAG NEKAS atau Sistem Informasi Absensi Guru SMK Negeri Kasomalang
- **Informal:** SIAG NEKAS
- **Short:** SIAG NEKAS (jangan disingkat lebih lanjut)

### Warna Tema (Rekomendasi)

- **Primary:** #2563eb (Biru)
- **Background:** #ffffff (Putih)
- **Text:** #1f2937 (Dark Gray)

> Warna dapat disesuaikan dengan identitas visual SMK Negeri Kasomalang.

### Font

- **Primary:** Inter (Google Fonts)
- **Fallback:** System UI fonts

---

## 📞 Kontak & Support

Untuk informasi lebih lanjut atau bantuan terkait setup aplikasi:

**SMK Negeri Kasomalang**

- Email: info@smknkasomalang.sch.id
- Telepon: (0260) 520xxx
- Alamat: Jl. Raya Kasomalang, Kasomalang Kulon, Kec. Kasomalang, Kabupaten Subang, Jawa Barat 41281
- Website: https://smknkasomalang.sch.id

---

## 📚 Referensi Dokumentasi

1. **DEVELOPMENT_NOTES.md** - Catatan pengembangan lengkap dengan semua keputusan teknis
2. **SKEMA_LARAVEL_IMPLEMENTATION.md** - Struktur implementasi Laravel detail
3. **LOGO_SETUP_GUIDE.md** - Panduan lengkap setup logo dan icon
4. **README.md** - Overview aplikasi dan panduan instalasi
5. **.env.example** - Template konfigurasi environment variables

---

## ✅ Status Perubahan

**Status:** ✅ Dokumentasi selesai diupdate  
**Next Action:** Setup logo files dan test implementasi  
**Priority:** HIGH - Perlu siapkan file logo segera

---

**Dokumen ini dibuat pada:** 16 November 2025  
**Last Updated:** 16 November 2025  
**Version:** 1.0

---

**SIAG NEKAS** - Sistem Informasi Absensi Guru yang Modern untuk SMK Negeri Kasomalang
