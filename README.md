# 📱 SISTEM ABSENSI GURU - PWA

Aplikasi absensi guru berbasis Progressive Web App (PWA) dengan dukungan penuh untuk mobile dan desktop.

## ✨ Fitur Utama

- 🎯 **PWA Native-like** - Install di device sebagai aplikasi
- 📱 **100% Responsive** - Mobile-first design, sempurna di semua device
- 🔄 **Offline Support** - Cache & background sync
- 🔔 **Push Notifications** - Real-time notification
- 📍 **GPS Validation** - Validasi lokasi absensi
- 📷 **QR Code & Selfie** - Dual method absensi
- ⚡ **Auto-Update** - Update otomatis saat ada versi baru
- 🌐 **Flexible Deployment** - Support shared hosting, VPS, cloud

## 🚀 Quick Start

### Persyaratan

- PHP 7.4+
- MySQL 5.7+
- HTTPS (wajib untuk PWA)
- mod_rewrite enabled

### Instalasi

```bash
# 1. Clone repository
git clone https://github.com/yourusername/absen-guru.git
cd absen-guru

# 2. Buat database
mysql -u root -p
CREATE DATABASE absensi_guru;
EXIT;

# 3. Import database
mysql -u root -p absensi_guru < database/absensi_guru.sql

# 4. Konfigurasi
cp config/database.php.example config/database.php
# Edit config/database.php dengan kredensial database Anda

# 5. Set permissions
chmod 755 public/uploads/
chmod 755 logs/
chmod 755 backup/

# 6. Generate VAPID keys untuk push notification
php generate-vapid-keys.php

# 7. Akses aplikasi
http://localhost/absen-guru/
```

### Install PWA (WAJIB!)

**Android/Desktop Chrome:**

- Buka aplikasi di browser
- Klik tombol "Pasang" di banner/address bar
- Aplikasi akan ter-install seperti app native

**iOS Safari:**

- Buka aplikasi di Safari
- Tap tombol Share (⬆️)
- Pilih "Add to Home Screen"

## 📚 Dokumentasi

- **[SKEMA_APLIKASI.md](SKEMA_APLIKASI.md)** - Dokumentasi lengkap aplikasi
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Panduan deployment ke berbagai platform

## 🏗️ Struktur Folder

```
absen-guru/
├── app/
│   ├── controllers/      # Controllers MVC
│   ├── models/           # Models database
│   ├── views/            # Views template
│   └── core/             # Core classes (Router, etc)
├── assets/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript
│   └── images/           # Images & icons
├── config/               # Configuration files
├── public/               # Public directory (Document root)
│   ├── index.php         # Entry point
│   ├── sw.js             # Service Worker
│   ├── manifest.json     # PWA Manifest
│   └── offline.html      # Offline page
├── database/             # SQL files
├── cron/                 # Cron jobs
├── logs/                 # Log files
└── backup/               # Backup directory
```

## 🔧 Konfigurasi

### Base URL (Auto-detect)

Aplikasi otomatis mendeteksi base URL. Tidak perlu edit config saat pindah server!

### Manual Override (jika diperlukan)

Edit `config/config.php`:

```php
define('BASE_URL', 'https://domain-anda.com/absen-guru/');
```

### GPS Coordinates

Edit `config/config.php`:

```php
define('GPS_SCHOOL_LAT', -6.123456);  // Latitude sekolah
define('GPS_SCHOOL_LONG', 106.123456); // Longitude sekolah
define('GPS_RADIUS', 100);             // Radius dalam meter
```

### Push Notification (VAPID)

Generate keys:

```bash
php generate-vapid-keys.php
```

Atau gunakan online generator: https://web-push-codelab.glitch.me/

## 🌐 Deployment

### Shared Hosting (cPanel)

1. Upload files via File Manager/FTP
2. Import database via phpMyAdmin
3. Edit `config/database.php`
4. Install SSL certificate
5. Test PWA install

### VPS (Ubuntu/Debian)

```bash
# Install LEMP stack
sudo apt update
sudo apt install nginx php8.1-fpm php8.1-mysql mysql-server -y

# Deploy aplikasi
sudo git clone <repo> /var/www/absen-guru
cd /var/www/absen-guru
sudo chown -R www-data:www-data .

# Install SSL
sudo certbot --nginx -d domain-anda.com
```

Lihat **[DEPLOYMENT.md](DEPLOYMENT.md)** untuk panduan lengkap.

## 👥 Default Login

- **Admin:** admin / admin123
- **Guru:** guru001 / guru123
- **Kepala Sekolah:** kepsek / kepsek123

⚠️ **Ganti password default setelah instalasi!**

## 🧪 Testing

### PWA Checklist

- [ ] HTTPS aktif
- [ ] Service Worker registered
- [ ] Install prompt muncul
- [ ] Offline mode berfungsi
- [ ] Push notification berfungsi

### Lighthouse Audit

Target: 100% PWA Score

```
Chrome DevTools → Lighthouse → Run audit
```

## 📱 Device Support

- ✅ Android (Chrome, Samsung Internet, Firefox)
- ✅ iOS (Safari)
- ✅ Desktop (Chrome, Edge, Firefox)
- ✅ Tablet (iPad, Android)

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

MIT License - Lihat [LICENSE](LICENSE) untuk detail.

## 💬 Support

- Email: support@sekolah.com
- Issues: [GitHub Issues](https://github.com/yourusername/absen-guru/issues)

## 🙏 Credits

Dibuat dengan ❤️ untuk meningkatkan efisiensi sistem absensi guru.

---

**© 2025 Sistem Absensi Guru - PWA**
