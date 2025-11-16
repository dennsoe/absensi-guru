# SKEMA SIAG NEKAS - LARAVEL VERSION

## Sistem Informasi Absensi Guru SMK Negeri Kasomalang

## 📋 INFORMASI UMUM

**Nama Aplikasi:** SIAG NEKAS (Sistem Informasi Absensi Guru SMK Negeri Kasomalang)  
**Sekolah:** SMK Negeri Kasomalang, Kabupaten Subang, Jawa Barat  
**NPSN:** 20219345  
**Framework:** Laravel 11.x  
**Platform:** Progressive Web App (PWA)  
**Database:** MySQL 8.0+  
**PHP Version:** 8.2+  
**Frontend:** Blade Templates + Bootstrap 5 + Alpine.js  
**Deployment:** Fleksibel - Shared Hosting / VPS / Cloud Platform  
**Akses:** **WAJIB melalui PWA** (Install di device sebagai app)  
**Hari Mengajar:** Senin - Jumat  
**Metode Absensi:** Kode QR / Swafoto (Dapat Diatur)  
**Responsif:** ✅ Mobile-First Design (100% support semua device)  
**Offline Support:** ✅ Cache & Background Sync  
**Push Notification:** ✅ Real-time notification via PWA

---

## 🏗️ STRUKTUR FOLDER LARAVEL

```
absen-guru-laravel/
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   ├── GenerateSuratPeringatan.php
│   │   │   ├── AutoBackupDatabase.php
│   │   │   ├── CleanupExpiredQR.php
│   │   │   └── SendReminderNotification.php
│   │   └── Kernel.php
│   │
│   ├── Events/
│   │   ├── AbsensiCreated.php
│   │   ├── IzinApproved.php
│   │   ├── GuruPengganti Assigned.php
│   │   └── NotificationSent.php
│   │
│   ├── Exceptions/
│   │   ├── AbsensiException.php
│   │   ├── QrCodeException.php
│   │   └── GpsValidationException.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── LogoutController.php
│   │   │   │
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── GuruController.php
│   │   │   │   ├── KelasController.php
│   │   │   │   ├── MataPelajaranController.php
│   │   │   │   ├── JadwalMengajarController.php
│   │   │   │   ├── GuruPiketController.php
│   │   │   │   ├── KetuaKelasController.php
│   │   │   │   ├── ApprovalController.php
│   │   │   │   ├── LaporanController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   ├── KalenderLiburController.php
│   │   │   │   ├── BackupController.php
│   │   │   │   ├── SuratPeringatanController.php
│   │   │   │   └── BroadcastController.php
│   │   │   │
│   │   │   ├── Guru/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── QrCodeController.php
│   │   │   │   ├── SelfieController.php
│   │   │   │   ├── JadwalController.php
│   │   │   │   ├── IzinController.php
│   │   │   │   ├── RiwayatAbsensiController.php
│   │   │   │   └── ProfileController.php
│   │   │   │
│   │   │   ├── KetuaKelas/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ScanQrController.php
│   │   │   │   ├── ValidasiAbsensiController.php
│   │   │   │   └── RiwayatController.php
│   │   │   │
│   │   │   ├── GuruPiket/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MonitoringController.php
│   │   │   │   ├── AbsensiManualController.php
│   │   │   │   ├── LaporanPiketController.php
│   │   │   │   └── KontakGuruController.php
│   │   │   │
│   │   │   ├── KepalaSekolah/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MonitoringController.php
│   │   │   │   ├── ApprovalController.php
│   │   │   │   ├── LaporanEksekutifController.php
│   │   │   │   └── AnalyticsController.php
│   │   │   │
│   │   │   ├── Kurikulum/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── JadwalMengajarController.php
│   │   │   │   ├── GuruPenggantiController.php
│   │   │   │   ├── ApprovalController.php
│   │   │   │   └── LaporanAkademikController.php
│   │   │   │
│   │   │   ├── Api/
│   │   │   │   ├── NotificationController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   └── SettingsController.php
│   │   │   │
│   │   │   └── PwaController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   ├── CheckActiveUser.php
│   │   │   ├── LogActivity.php
│   │   │   ├── CheckJadwalAktif.php
│   │   │   └── ValidateAbsensiTime.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   └── LoginRequest.php
│   │   │   ├── Admin/
│   │   │   │   ├── StoreGuruRequest.php
│   │   │   │   ├── UpdateGuruRequest.php
│   │   │   │   ├── StoreJadwalRequest.php
│   │   │   │   └── UpdateSettingsRequest.php
│   │   │   ├── Guru/
│   │   │   │   ├── AbsensiQrRequest.php
│   │   │   │   ├── AbsensiSelfieRequest.php
│   │   │   │   └── IzinRequest.php
│   │   │   └── GuruPiket/
│   │   │       └── AbsensiManualRequest.php
│   │   │
│   │   └── Resources/
│   │       ├── AbsensiResource.php
│   │       ├── GuruResource.php
│   │       ├── JadwalResource.php
│   │       └── NotifikasiResource.php
│   │
│   ├── Jobs/
│   │   ├── GenerateLaporanPdf.php
│   │   ├── SendEmailNotification.php
│   │   ├── SendWhatsappNotification.php
│   │   ├── GenerateSuratPeringatan.php
│   │   ├── BackupDatabase.php
│   │   ├── ProcessBulkImport.php
│   │   └── SendReminderAbsensi.php
│   │
│   ├── Listeners/
│   │   ├── SendAbsensiNotification.php
│   │   ├── LogAbsensiActivity.php
│   │   ├── UpdateRekapJamMengajar.php
│   │   └── CheckPelanggaranGuru.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Guru.php
│   │   ├── Kelas.php
│   │   ├── MataPelajaran.php
│   │   ├── JadwalMengajar.php
│   │   ├── Absensi.php
│   │   ├── QrCode.php
│   │   ├── Notifikasi.php
│   │   ├── GuruPiket.php
│   │   ├── GuruPengganti.php
│   │   ├── IzinCuti.php
│   │   ├── Pelanggaran.php
│   │   ├── PengaturanSistem.php
│   │   ├── LogAktivitas.php
│   │   ├── Libur.php
│   │   ├── Laporan.php
│   │   ├── SuratPeringatan.php
│   │   ├── BroadcastMessage.php
│   │   ├── NotifikasiPreference.php
│   │   ├── ApiKey.php
│   │   ├── BackupHistory.php
│   │   ├── RekapJamMengajar.php
│   │   └── PushSubscription.php
│   │
│   ├── Notifications/
│   │   ├── JadwalMengajarReminder.php
│   │   ├── AbsensiManualNeedApproval.php
│   │   ├── IzinNeedApproval.php
│   │   ├── GuruBelumAbsen.php
│   │   └── GuruAlphaTanpaKeterangan.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   │
│   ├── Services/
│   │   ├── AbsensiService.php
│   │   ├── QrCodeService.php
│   │   ├── GpsService.php
│   │   ├── ImageService.php
│   │   ├── NotificationService.php
│   │   ├── ApprovalService.php
│   │   ├── LaporanService.php
│   │   ├── SettingsService.php
│   │   ├── BackupService.php
│   │   ├── WhatsappService.php
│   │   ├── EmailService.php
│   │   └── SuratPeringatanService.php
│   │
│   ├── Repositories/
│   │   ├── GuruRepository.php
│   │   ├── AbsensiRepository.php
│   │   ├── JadwalRepository.php
│   │   ├── NotifikasiRepository.php
│   │   └── SettingsRepository.php
│   │
│   └── Helpers/
│       ├── DateHelper.php
│       ├── TimeHelper.php
│       ├── FormatHelper.php
│       └── ValidationHelper.php
│
├── bootstrap/
│   ├── app.php
│   └── cache/
│
├── config/
│   ├── app.php
│   ├── database.php
│   ├── auth.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── absensi.php          # Custom config untuk absensi
│   ├── gps.php              # Custom config untuk GPS
│   └── pwa.php              # Custom config untuk PWA
│
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── GuruFactory.php
│   │   ├── KelasFactory.php
│   │   └── JadwalMengajarFactory.php
│   │
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_guru_table.php
│   │   ├── 2024_01_01_000003_create_mata_pelajaran_table.php
│   │   ├── 2024_01_01_000004_create_kelas_table.php
│   │   ├── 2024_01_01_000005_create_jadwal_mengajar_table.php
│   │   ├── 2024_01_01_000006_create_absensi_table.php
│   │   ├── 2024_01_01_000007_create_qr_codes_table.php
│   │   ├── 2024_01_01_000008_create_notifikasi_table.php
│   │   ├── 2024_01_01_000009_create_pengaturan_sistem_table.php
│   │   ├── 2024_01_01_000010_create_guru_piket_table.php
│   │   ├── 2024_01_01_000011_create_guru_pengganti_table.php
│   │   ├── 2024_01_01_000012_create_izin_cuti_table.php
│   │   ├── 2024_01_01_000013_create_pelanggaran_table.php
│   │   ├── 2024_01_01_000014_create_log_aktivitas_table.php
│   │   ├── 2024_01_01_000015_create_libur_table.php
│   │   ├── 2024_01_01_000016_create_laporan_table.php
│   │   ├── 2024_01_01_000017_create_surat_peringatan_table.php
│   │   ├── 2024_01_01_000018_create_broadcast_message_table.php
│   │   ├── 2024_01_01_000019_create_notifikasi_preferences_table.php
│   │   ├── 2024_01_01_000020_create_api_keys_table.php
│   │   ├── 2024_01_01_000021_create_backup_history_table.php
│   │   ├── 2024_01_01_000022_create_rekap_jam_mengajar_table.php
│   │   └── 2024_01_01_000023_create_push_subscriptions_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── GuruSeeder.php
│       ├── KelasSeeder.php
│       ├── MataPelajaranSeeder.php
│       ├── JadwalMengajarSeeder.php
│       ├── SettingsSeeder.php
│       └── LiburSeeder.php
│
├── public/
│   ├── index.php
│   ├── manifest.json           # PWA Manifest
│   ├── service-worker.js       # Service Worker
│   ├── offline.html           # Offline fallback page
│   ├── robots.txt
│   ├── favicon.ico
│   │
│   ├── assets/
│   │   ├── images/
│   │   │   ├── logo.png
│   │   │   ├── icon-192.png
│   │   │   ├── icon-512.png
│   │   │   └── placeholder-avatar.png
│   │   │
│   │   ├── css/
│   │   │   ├── app.css
│   │   │   ├── admin.css
│   │   │   ├── guru.css
│   │   │   └── mobile.css
│   │   │
│   │   ├── js/
│   │   │   ├── app.js
│   │   │   ├── qr-scanner.js
│   │   │   ├── camera.js
│   │   │   ├── gps.js
│   │   │   ├── notification.js
│   │   │   ├── chart.js
│   │   │   └── pwa-register.js
│   │   │
│   │   └── vendor/
│   │       ├── bootstrap/
│   │       ├── alpine/
│   │       ├── chart.js/
│   │       └── html5-qrcode/
│   │
│   └── storage/
│       ├── selfies/
│       ├── documents/
│       ├── qr-codes/
│       ├── laporan/
│       ├── backup/
│       └── surat-peringatan/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── admin.blade.php
│   │   │   ├── guru.blade.php
│   │   │   ├── guest.blade.php
│   │   │   └── components/
│   │   │       ├── navbar.blade.php
│   │   │       ├── sidebar.blade.php
│   │   │       ├── footer.blade.php
│   │   │       ├── notification-badge.blade.php
│   │   │       └── breadcrumb.blade.php
│   │   │
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── reset-password.blade.php
│   │   │
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── guru/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   ├── edit.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── kelas/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── mata-pelajaran/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── jadwal/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── guru-piket/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── assign.blade.php
│   │   │   ├── ketua-kelas/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── assign.blade.php
│   │   │   ├── approval/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── izin.blade.php
│   │   │   │   └── absensi-manual.blade.php
│   │   │   ├── laporan/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── harian.blade.php
│   │   │   │   ├── bulanan.blade.php
│   │   │   │   └── custom.blade.php
│   │   │   ├── settings/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── umum.blade.php
│   │   │   │   ├── absensi.blade.php
│   │   │   │   ├── gps.blade.php
│   │   │   │   ├── notifikasi.blade.php
│   │   │   │   ├── email.blade.php
│   │   │   │   ├── whatsapp.blade.php
│   │   │   │   └── backup.blade.php
│   │   │   ├── kalender-libur/
│   │   │   │   └── index.blade.php
│   │   │   ├── backup/
│   │   │   │   └── index.blade.php
│   │   │   ├── surat-peringatan/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── generate.blade.php
│   │   │   └── broadcast/
│   │   │       └── create.blade.php
│   │   │
│   │   ├── guru/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── absensi/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── qr-code.blade.php
│   │   │   │   ├── selfie.blade.php
│   │   │   │   └── keluar.blade.php
│   │   │   ├── jadwal/
│   │   │   │   └── index.blade.php
│   │   │   ├── izin/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── riwayat/
│   │   │   │   └── index.blade.php
│   │   │   └── profile/
│   │   │       ├── index.blade.php
│   │   │       └── edit.blade.php
│   │   │
│   │   ├── ketua-kelas/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── scan-qr.blade.php
│   │   │   ├── validasi.blade.php
│   │   │   └── riwayat.blade.php
│   │   │
│   │   ├── guru-piket/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── monitoring.blade.php
│   │   │   ├── absensi-manual/
│   │   │   │   ├── create.blade.php
│   │   │   │   └── index.blade.php
│   │   │   ├── laporan/
│   │   │   │   ├── create.blade.php
│   │   │   │   └── index.blade.php
│   │   │   └── kontak-guru.blade.php
│   │   │
│   │   ├── kepala-sekolah/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── monitoring.blade.php
│   │   │   ├── approval/
│   │   │   │   └── index.blade.php
│   │   │   ├── laporan/
│   │   │   │   ├── eksekutif.blade.php
│   │   │   │   └── detail.blade.php
│   │   │   └── analytics/
│   │   │       └── index.blade.php
│   │   │
│   │   ├── kurikulum/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── jadwal/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── guru-pengganti/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── assign.blade.php
│   │   │   ├── approval/
│   │   │   │   └── index.blade.php
│   │   │   └── laporan/
│   │   │       └── akademik.blade.php
│   │   │
│   │   ├── components/
│   │   │   ├── alert.blade.php
│   │   │   ├── modal.blade.php
│   │   │   ├── card.blade.php
│   │   │   ├── table.blade.php
│   │   │   ├── button.blade.php
│   │   │   ├── form-input.blade.php
│   │   │   ├── form-select.blade.php
│   │   │   ├── notification-item.blade.php
│   │   │   └── jadwal-card.blade.php
│   │   │
│   │   ├── pdf/
│   │   │   ├── laporan-harian.blade.php
│   │   │   ├── laporan-bulanan.blade.php
│   │   │   ├── surat-peringatan.blade.php
│   │   │   └── laporan-piket.blade.php
│   │   │
│   │   └── errors/
│   │       ├── 403.blade.php
│   │       ├── 404.blade.php
│   │       └── 500.blade.php
│   │
│   ├── lang/
│   │   └── id/
│   │       ├── auth.php
│   │       ├── validation.php
│   │       ├── pagination.php
│   │       └── messages.php
│   │
│   └── css/
│       └── app.css
│
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # API routes
│   ├── console.php          # Console commands
│   └── channels.php         # Broadcast channels
│
├── storage/
│   ├── app/
│   │   ├── public/
│   │   │   ├── selfies/
│   │   │   ├── documents/
│   │   │   ├── qr-codes/
│   │   │   └── avatars/
│   │   ├── backups/
│   │   └── exports/
│   │
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   │
│   └── logs/
│       ├── laravel.log
│       └── absensi.log
│
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   │   └── LoginTest.php
│   │   ├── Admin/
│   │   │   ├── GuruManagementTest.php
│   │   │   └── JadwalManagementTest.php
│   │   ├── Guru/
│   │   │   ├── AbsensiQrTest.php
│   │   │   └── AbsensiSelfieTest.php
│   │   └── Api/
│   │       └── NotificationTest.php
│   │
│   ├── Unit/
│   │   ├── Services/
│   │   │   ├── GpsServiceTest.php
│   │   │   ├── QrCodeServiceTest.php
│   │   │   └── AbsensiServiceTest.php
│   │   └── Models/
│   │       ├── GuruTest.php
│   │       └── AbsensiTest.php
│   │
│   ├── TestCase.php
│   └── CreatesApplication.php
│
├── .env                     # Environment configuration
├── .env.example            # Environment template
├── .gitignore
├── artisan                 # Laravel CLI
├── composer.json           # PHP dependencies
├── composer.lock
├── package.json            # NPM dependencies
├── package-lock.json
├── phpunit.xml            # PHPUnit configuration
├── README.md
└── vite.config.js         # Vite configuration

```

---

## 📦 DEPENDENCIES (composer.json)

### Required Packages

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "guzzlehttp/guzzle": "^7.8",
    "simplesoftwareio/simple-qrcode": "^4.2",
    "intervention/image": "^3.0",
    "barryvdh/laravel-dompdf": "^2.0",
    "maatwebsite/laravel-excel": "^3.1",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-activitylog": "^4.0"
  },
  "require-dev": {
    "laravel/pint": "^1.0",
    "laravel/sail": "^1.26",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.0",
    "phpunit/phpunit": "^11.0",
    "fakerphp/faker": "^1.23"
  }
}
```

---

## ⚙️ KONFIGURASI CUSTOM

### config/absensi.php

```php
<?php

return [
    // Metode Absensi
    'metode' => [
        'qr_code' => env('ABSENSI_QR_ENABLED', true),
        'selfie' => env('ABSENSI_SELFIE_ENABLED', true),
    ],

    // QR Code Settings
    'qr' => [
        'expiry_minutes' => env('QR_EXPIRY_MINUTES', 5),
        'auto_refresh' => env('QR_AUTO_REFRESH', true),
        'size' => env('QR_SIZE', 300),
    ],

    // Toleransi Waktu
    'toleransi' => [
        'terlambat_menit' => env('TOLERANSI_TERLAMBAT', 15),
        'absen_sebelum_menit' => env('ABSEN_SEBELUM', 30),
        'absen_setelah_menit' => env('ABSEN_SETELAH', 60),
    ],

    // Validasi
    'validasi' => [
        'wajib_selfie' => env('WAJIB_SELFIE', true),
        'wajib_gps' => env('WAJIB_GPS', true),
        'wajib_validasi_ketua' => env('WAJIB_VALIDASI_KETUA', true),
    ],

    // Selfie Settings
    'selfie' => [
        'max_size_mb' => env('SELFIE_MAX_SIZE', 5),
        'compression_quality' => env('SELFIE_QUALITY', 75),
        'resize_width' => env('SELFIE_WIDTH', 800),
        'resize_height' => env('SELFIE_HEIGHT', 600),
    ],

    // Surat Peringatan
    'surat_peringatan' => [
        'enabled' => env('SP_ENABLED', true),
        'sp1_threshold' => env('SP1_THRESHOLD', 3),
        'sp2_threshold' => env('SP2_THRESHOLD', 5),
        'sp3_threshold' => env('SP3_THRESHOLD', 7),
        'periode_hari' => env('SP_PERIODE', 30),
        'auto_generate' => env('SP_AUTO_GENERATE', true),
    ],
];
```

### config/gps.php

```php
<?php

return [
    'enabled' => env('GPS_ENABLED', true),

    'sekolah' => [
        'latitude' => env('GPS_LATITUDE', '-6.200000'),
        'longitude' => env('GPS_LONGITUDE', '106.816666'),
        'radius_meter' => env('GPS_RADIUS', 200),
    ],

    'strict_mode' => env('GPS_STRICT_MODE', false),
    'show_map' => env('GPS_SHOW_MAP', true),
];
```

### config/pwa.php

```php
<?php

return [
    'name' => env('PWA_NAME', 'SIAG NEKAS'),
    'short_name' => env('PWA_SHORT_NAME', 'SIAG NEKAS'),
    'description' => 'Sistem Informasi Absensi Guru SMK Negeri Kasomalang dengan QR Code dan Selfie',
    'theme_color' => env('PWA_THEME_COLOR', '#007bff'),
    'background_color' => env('PWA_BG_COLOR', '#ffffff'),
    'display' => 'standalone',
    'orientation' => 'portrait',
    'start_url' => '/',
    'scope' => '/',

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

    'offline' => [
        'enabled' => true,
        'fallback_url' => '/offline',
        'cache_strategy' => 'network_first',
    ],
];
```

---

## 🔐 MIDDLEWARE STRUCTURE

### Role-Based Middleware

```php
// app/Http/Kernel.php
protected $middlewareAliases = [
    'role.admin' => \App\Http\Middleware\RoleMiddleware::class.':admin',
    'role.guru' => \App\Http\Middleware\RoleMiddleware::class.':guru',
    'role.ketua_kelas' => \App\Http\Middleware\RoleMiddleware::class.':ketua_kelas',
    'role.guru_piket' => \App\Http\Middleware\RoleMiddleware::class.':guru_piket',
    'role.kepala_sekolah' => \App\Http\Middleware\RoleMiddleware::class.':kepala_sekolah',
    'role.kurikulum' => \App\Http\Middleware\RoleMiddleware::class.':kurikulum',
    'active.user' => \App\Http\Middleware\CheckActiveUser::class,
    'log.activity' => \App\Http\Middleware\LogActivity::class,
];
```

---

## 🛣️ ROUTING STRUCTURE

### routes/web.php (Simplified)

```php
<?php

use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'))->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// PWA Routes
Route::get('/manifest.json', [PwaController::class, 'manifest']);
Route::get('/service-worker.js', [PwaController::class, 'serviceWorker']);
Route::get('/offline', [PwaController::class, 'offline']);

// Authenticated Routes
Route::middleware(['auth', 'active.user', 'log.activity'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('role.admin')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('guru', Admin\GuruController::class);
        Route::resource('kelas', Admin\KelasController::class);
        Route::resource('mata-pelajaran', Admin\MataPelajaranController::class);
        Route::resource('jadwal', Admin\JadwalMengajarController::class);
        // ... more admin routes
    });

    // Guru Routes
    Route::prefix('guru')->name('guru.')->middleware('role.guru')->group(function () {
        Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/absensi', [Guru\AbsensiController::class, 'index'])->name('absensi.index');
        Route::post('/absensi/qr', [Guru\QrCodeController::class, 'absen'])->name('absensi.qr');
        Route::post('/absensi/selfie', [Guru\SelfieController::class, 'absen'])->name('absensi.selfie');
        // ... more guru routes
    });

    // Ketua Kelas Routes
    Route::prefix('ketua-kelas')->name('ketua-kelas.')->middleware('role.ketua_kelas')->group(function () {
        Route::get('/dashboard', [KetuaKelas\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/scan-qr', [KetuaKelas\ScanQrController::class, 'index'])->name('scan-qr');
        Route::post('/scan-qr/validate', [KetuaKelas\ScanQrController::class, 'validate'])->name('scan-qr.validate');
        // ... more ketua kelas routes
    });

    // Guru Piket Routes
    Route::prefix('guru-piket')->name('guru-piket.')->middleware('role.guru_piket')->group(function () {
        Route::get('/dashboard', [GuruPiket\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [GuruPiket\MonitoringController::class, 'index'])->name('monitoring');
        // ... more guru piket routes
    });

    // Kepala Sekolah Routes
    Route::prefix('kepala-sekolah')->name('kepala-sekolah.')->middleware('role.kepala_sekolah')->group(function () {
        Route::get('/dashboard', [KepalaSekolah\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/monitoring', [KepalaSekolah\MonitoringController::class, 'index'])->name('monitoring');
        // ... more kepala sekolah routes
    });

    // Kurikulum Routes
    Route::prefix('kurikulum')->name('kurikulum.')->middleware('role.kurikulum')->group(function () {
        Route::get('/dashboard', [Kurikulum\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('jadwal', Kurikulum\JadwalMengajarController::class);
        // ... more kurikulum routes
    });
});
```

### routes/api.php

```php
<?php

Route::middleware('auth:sanctum')->group(function () {
    // Notification API
    Route::get('/notifications', [Api\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [Api\NotificationController::class, 'markAsRead']);

    // Absensi API (for AJAX calls)
    Route::post('/absensi/check-status', [Api\AbsensiController::class, 'checkStatus']);
    Route::get('/absensi/today', [Api\AbsensiController::class, 'today']);

    // Settings API
    Route::get('/settings/{category}', [Api\SettingsController::class, 'getByCategory']);
});
```

---

## 🗄️ SERVICE LAYER STRUCTURE

### AbsensiService.php (Example)

```php
<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\JadwalMengajar;
use App\Exceptions\AbsensiException;
use Carbon\Carbon;

class AbsensiService
{
    public function __construct(
        private GpsService $gpsService,
        private ImageService $imageService,
        private NotificationService $notificationService
    ) {}

    public function absenMasukQr($guruId, $jadwalId, $qrData, $latitude, $longitude)
    {
        // Validasi QR Code
        $qrCode = $this->validateQrCode($qrData);

        // Validasi GPS
        if (config('gps.enabled')) {
            $this->gpsService->validate($latitude, $longitude);
        }

        // Cek sudah absen atau belum
        if ($this->sudahAbsen($guruId, $jadwalId)) {
            throw new AbsensiException('Anda sudah absen untuk jadwal ini');
        }

        // Tentukan status (hadir/terlambat)
        $status = $this->tentukanStatus($jadwalId);

        // Simpan absensi
        $absensi = Absensi::create([
            'guru_id' => $guruId,
            'jadwal_id' => $jadwalId,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => now()->toTimeString(),
            'status_kehadiran' => $status,
            'metode_absensi' => 'qr_code',
            'qr_code_data' => $qrData,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'validasi_gps' => true,
        ]);

        // Mark QR as used
        $qrCode->markAsUsed();

        // Send notification
        $this->notificationService->sendAbsensiSuccess($guruId);

        return $absensi;
    }

    public function absenMasukSelfie($guruId, $jadwalId, $foto, $latitude, $longitude)
    {
        // Validasi GPS
        if (config('gps.enabled')) {
            $this->gpsService->validate($latitude, $longitude);
        }

        // Cek sudah absen atau belum
        if ($this->sudahAbsen($guruId, $jadwalId)) {
            throw new AbsensiException('Anda sudah absen untuk jadwal ini');
        }

        // Process & save selfie
        $fotoPath = $this->imageService->saveSelfie($foto, $guruId);

        // Tentukan status
        $status = $this->tentukanStatus($jadwalId);

        // Simpan absensi
        $absensi = Absensi::create([
            'guru_id' => $guruId,
            'jadwal_id' => $jadwalId,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => now()->toTimeString(),
            'status_kehadiran' => $status,
            'metode_absensi' => 'selfie',
            'foto_selfie' => $fotoPath,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'validasi_gps' => true,
        ]);

        // Send notification
        $this->notificationService->sendAbsensiSuccess($guruId);

        return $absensi;
    }

    private function tentukanStatus($jadwalId)
    {
        $jadwal = JadwalMengajar::findOrFail($jadwalId);
        $toleransi = config('absensi.toleransi.terlambat_menit');

        $jamMulai = Carbon::parse($jadwal->jam_mulai);
        $batasTerlambat = $jamMulai->copy()->addMinutes($toleransi);

        return now()->greaterThan($batasTerlambat) ? 'terlambat' : 'hadir';
    }

    private function sudahAbsen($guruId, $jadwalId)
    {
        return Absensi::where('guru_id', $guruId)
            ->where('jadwal_id', $jadwalId)
            ->whereDate('tanggal', now())
            ->exists();
    }

    // ... more methods
}
```

---

## 📊 COMMAND SCHEDULER (Console Kernel)

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Kirim reminder 15 menit sebelum jadwal mengajar
        $schedule->command('absensi:send-reminder')
            ->everyFiveMinutes()
            ->between('06:00', '17:00')
            ->weekdays();

        // Cleanup expired QR codes
        $schedule->command('qr:cleanup-expired')
            ->everyTenMinutes();

        // Generate surat peringatan otomatis
        $schedule->command('sp:generate')
            ->dailyAt('23:00');

        // Auto backup database
        $schedule->command('backup:database')
            ->dailyAt('02:00')
            ->when(fn() => config('absensi.backup.auto_enabled'));

        // Calculate rekap jam mengajar bulanan
        $schedule->command('rekap:calculate')
            ->monthlyOn(1, '01:00');

        // Cleanup old logs
        $schedule->command('log:cleanup')
            ->weekly()
            ->sundays()
            ->at('03:00');
    }
}
```

---

## 🎨 BLADE COMPONENTS STRUCTURE

### Component Example: NotificationBadge

```blade
{{-- resources/views/components/notification-badge.blade.php --}}

<div x-data="notificationComponent()"
     x-init="init()"
     class="dropdown">

    <button class="btn btn-link position-relative"
            type="button"
            @click="toggleDropdown()">
        <i class="bi bi-bell"></i>
        <span x-show="unreadCount > 0"
              x-text="unreadCount"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        </span>
    </button>

    <div x-show="showDropdown"
         @click.away="showDropdown = false"
         class="dropdown-menu dropdown-menu-end show"
         style="max-height: 400px; overflow-y: auto;">

        <div class="dropdown-header d-flex justify-content-between">
            <span>Notifikasi</span>
            <button @click="markAllAsRead()" class="btn btn-sm btn-link">
                Tandai semua dibaca
            </button>
        </div>

        <template x-for="notif in notifications" :key="notif.id">
            <a :href="notif.link_url || '#'"
               class="dropdown-item"
               :class="{ 'bg-light': !notif.is_read }"
               @click="markAsRead(notif.id)">
                <div class="d-flex">
                    <i :class="'bi bi-' + notif.icon + ' me-2'"></i>
                    <div class="flex-grow-1">
                        <div class="fw-bold" x-text="notif.judul"></div>
                        <small x-text="notif.pesan"></small>
                        <div class="text-muted small" x-text="notif.created_at"></div>
                    </div>
                </div>
            </a>
        </template>

        <div x-show="notifications.length === 0" class="dropdown-item text-center text-muted">
            Tidak ada notifikasi
        </div>
    </div>
</div>

<script>
function notificationComponent() {
    return {
        notifications: [],
        unreadCount: 0,
        showDropdown: false,

        init() {
            this.fetchNotifications();
            // Poll every 30 seconds
            setInterval(() => this.fetchNotifications(), 30000);
        },

        async fetchNotifications() {
            const response = await fetch('/api/notifications');
            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
        },

        async markAsRead(id) {
            await fetch(`/api/notifications/${id}/read`, { method: 'POST' });
            this.fetchNotifications();
        },

        async markAllAsRead() {
            await fetch('/api/notifications/mark-all-read', { method: 'POST' });
            this.fetchNotifications();
        },

        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        }
    }
}
</script>
```

---

## 🔔 EVENT & LISTENER EXAMPLE

### Event: AbsensiCreated

```php
<?php

namespace App\Events;

use App\Models\Absensi;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AbsensiCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Absensi $absensi)
    {
    }
}
```

### Listener: SendAbsensiNotification

```php
<?php

namespace App\Listeners;

use App\Events\AbsensiCreated;
use App\Services\NotificationService;

class SendAbsensiNotification
{
    public function __construct(private NotificationService $notificationService)
    {
    }

    public function handle(AbsensiCreated $event): void
    {
        $absensi = $event->absensi;

        // Notif ke guru
        $this->notificationService->notifyGuru(
            $absensi->guru_id,
            'Absensi Berhasil',
            'Absensi Anda telah tercatat pada ' . $absensi->jam_masuk
        );

        // Notif ke guru piket jika terlambat
        if ($absensi->status_kehadiran === 'terlambat') {
            $this->notificationService->notifyGuruPiket(
                $absensi->guru->nama . ' terlambat ' . $absensi->hitungKeterlambatan() . ' menit'
            );
        }
    }
}
```

---

## 📱 PWA FILES

### public/manifest.json (Generated from config)

### public/service-worker.js

```javascript
const CACHE_NAME = "absensi-guru-v1";
const urlsToCache = [
  "/",
  "/assets/css/app.css",
  "/assets/js/app.js",
  "/assets/images/logo.png",
  "/offline",
];

// Install
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache))
  );
});

// Fetch
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches
      .match(event.request)
      .then((response) => {
        // Cache hit - return response
        if (response) {
          return response;
        }

        return fetch(event.request).then((response) => {
          // Check if valid response
          if (
            !response ||
            response.status !== 200 ||
            response.type !== "basic"
          ) {
            return response;
          }

          // Clone the response
          const responseToCache = response.clone();

          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });

          return response;
        });
      })
      .catch(() => {
        // Return offline page
        return caches.match("/offline");
      })
  );
});

// Activate
self.addEventListener("activate", (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Push notification
self.addEventListener("push", (event) => {
  const data = event.data.json();
  const options = {
    body: data.body,
    icon: "/assets/images/icon-192.png",
    badge: "/assets/images/badge.png",
    vibrate: [200, 100, 200],
    data: {
      url: data.url,
    },
  };

  event.waitUntil(self.registration.showNotification(data.title, options));
});

// Notification click
self.addEventListener("notificationclick", (event) => {
  event.notification.close();
  event.waitUntil(clients.openWindow(event.notification.data.url));
});
```

---

## 🔧 .env CONFIGURATION

```env
APP_NAME="Absensi Guru"
APP_ENV=local
APP_KEY=base64:xxx
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_guru
DB_USERNAME=root
DB_PASSWORD=

# PWA
PWA_NAME="Absensi Guru"
PWA_SHORT_NAME="Absensi"
PWA_THEME_COLOR="#007bff"

# GPS
GPS_ENABLED=true
GPS_LATITUDE=-6.200000
GPS_LONGITUDE=106.816666
GPS_RADIUS=200
GPS_STRICT_MODE=false

# QR Code
QR_EXPIRY_MINUTES=5
QR_AUTO_REFRESH=true
QR_SIZE=300

# Absensi
ABSENSI_QR_ENABLED=true
ABSENSI_SELFIE_ENABLED=true
TOLERANSI_TERLAMBAT=15
ABSEN_SEBELUM=30
ABSEN_SETELAH=60
WAJIB_SELFIE=true
WAJIB_GPS=true
WAJIB_VALIDASI_KETUA=true

# Selfie
SELFIE_MAX_SIZE=5
SELFIE_QUALITY=75
SELFIE_WIDTH=800
SELFIE_HEIGHT=600

# Surat Peringatan
SP_ENABLED=true
SP1_THRESHOLD=3
SP2_THRESHOLD=5
SP3_THRESHOLD=7
SP_PERIODE=30
SP_AUTO_GENERATE=true

# Email (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

# WhatsApp (Optional - Fonnte)
WHATSAPP_ENABLED=false
WHATSAPP_API_KEY=
WHATSAPP_API_URL=https://api.fonnte.com/send

# Queue
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Filesystem
FILESYSTEM_DISK=public
```

---

## 📝 KESIMPULAN

Struktur Laravel ini sudah disesuaikan dengan skema aplikasi original Anda dengan peningkatan:

✅ **MVC Pattern** yang jelas  
✅ **Service Layer** untuk business logic  
✅ **Repository Pattern** untuk data access  
✅ **Event-Driven** architecture  
✅ **Queue Jobs** untuk task berat  
✅ **Middleware** untuk authorization  
✅ **API Routes** untuk AJAX calls  
✅ **PWA Support** lengkap  
✅ **Custom Config** per fitur  
✅ **Blade Components** reusable  
✅ **Command Scheduler** untuk automation

**Next Steps:**

1. Review struktur ini
2. Diskusi jika ada yang perlu disesuaikan
3. Mulai implementasi bertahap

Bagaimana menurut Anda? Ada yang ingin ditambah atau diubah? 🚀
