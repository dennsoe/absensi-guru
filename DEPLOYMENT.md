# 📦 PANDUAN DEPLOYMENT - SISTEM ABSENSI GURU PWA

## 🎯 Ringkasan

Aplikasi ini adalah PWA (Progressive Web App) yang **WAJIB diakses melalui PWA** dengan dukungan penuh untuk mobile dan desktop. Routing fleksibel mendukung deployment di berbagai environment.

---

## 🚀 A. DEPLOYMENT KE SHARED HOSTING (cPanel)

### 1. Persyaratan

- PHP 7.4+
- MySQL 5.7+
- HTTPS (wajib untuk PWA)
- mod_rewrite enabled

### 2. Langkah-langkah

#### a. Upload Files

```bash
# Zip aplikasi dulu (exclude .git, node_modules)
zip -r absen-guru.zip . -x "*.git*" "node_modules/*"

# Upload via cPanel File Manager atau FTP ke public_html/absen-guru/
```

#### b. Extract & Set Permission

```bash
# Via cPanel Terminal atau SSH
cd public_html/absen-guru
unzip absen-guru.zip

# Set permission
chmod 755 public/uploads/
chmod 755 logs/
chmod 755 backup/
chmod 644 config/config.php
chmod 644 config/database.php
```

#### c. Buat Database

1. Masuk cPanel → MySQL Databases
2. Buat database baru: `username_absensi_guru`
3. Buat user dan password
4. Assign user ke database dengan ALL PRIVILEGES
5. Import SQL: `database/absensi_guru.sql`

#### d. Konfigurasi

Edit `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'username_absensi_guru');
define('DB_USER', 'username_dbuser');
define('DB_PASS', 'password_anda');
```

Edit `config/config.php`:

```php
// Ganti SECRET_KEY dengan random string
define('SECRET_KEY', 'GANTI-DENGAN-RANDOM-STRING-PANJANG');
define('JWT_SECRET', 'GANTI-JWT-SECRET-ANDA');

// Sesuaikan GPS koordinat sekolah
define('GPS_SCHOOL_LAT', -6.123456);
define('GPS_SCHOOL_LONG', 106.123456);
```

#### e. SSL Certificate (WAJIB untuk PWA!)

```bash
# Install SSL gratis via cPanel → SSL/TLS → Let's Encrypt
# Atau gunakan Cloudflare SSL
```

#### f. Test Aplikasi

```
https://domain-anda.com/absen-guru/
```

---

## ☁️ B. DEPLOYMENT KE VPS (Ubuntu/Debian)

### 1. Install LEMP Stack

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install PHP 8.1
sudo apt install php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-gd php8.1-curl -y

# Install MySQL
sudo apt install mysql-server -y
```

### 2. Konfigurasi Nginx

```bash
sudo nano /etc/nginx/sites-available/absensi-guru
```

```nginx
server {
    listen 80;
    server_name domain-anda.com;
    root /var/www/absen-guru/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # PWA Cache Headers
    location ~* \.(ico|jpg|jpeg|png|gif|js|css|woff|woff2|ttf|svg)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    location = /sw.js {
        expires 0;
        add_header Cache-Control "no-cache, no-store, must-revalidate";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/absensi-guru /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 3. Install SSL dengan Certbot

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d domain-anda.com
```

### 4. Deploy Aplikasi

```bash
cd /var/www/
sudo git clone https://github.com/yourusername/absen-guru.git
cd absen-guru
sudo chown -R www-data:www-data .
sudo chmod -R 755 public/uploads logs backup
```

### 5. Setup Database

```bash
mysql -u root -p

CREATE DATABASE absensi_guru;
CREATE USER 'absensi_user'@'localhost' IDENTIFIED BY 'password_kuat';
GRANT ALL PRIVILEGES ON absensi_guru.* TO 'absensi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

mysql -u root -p absensi_guru < database/absensi_guru.sql
```

### 6. Setup Cron Jobs

```bash
sudo crontab -e
```

```cron
# Notifikasi Jam Mengajar (Setiap 1 menit)
* * * * * php /var/www/absen-guru/cron/notifikasi_jadwal.php

# Reminder Belum Absen (Setiap 1 menit)
* * * * * php /var/www/absen-guru/cron/reminder_absen.php

# Auto Backup Database (Setiap hari jam 23:00)
0 23 * * * php /var/www/absen-guru/cron/backup_database.php

# Clean Expired QR Codes (Setiap 5 menit)
*/5 * * * * php /var/www/absen-guru/cron/clean_expired_qr.php

# Rekap Jam Mengajar Bulanan (Setiap tanggal 1 jam 00:00)
0 0 1 * * php /var/www/absen-guru/cron/rekap_jam_mengajar.php

# Check Pelanggaran & Auto-Generate SP (Setiap hari jam 22:00)
0 22 * * * php /var/www/absen-guru/cron/check_pelanggaran.php

# Sync Kalender Libur Nasional (Setiap bulan tanggal 1)
0 2 1 * * php /var/www/absen-guru/cron/sync_kalender_libur.php
```

---

## 🌐 C. DEPLOYMENT KE PLATFORM CLOUD

### Option 1: Railway.app

```bash
# Install Railway CLI
npm install -g railway

# Login
railway login

# Init project
railway init

# Deploy
railway up
```

**railway.json:**

```json
{
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "startCommand": "php -S 0.0.0.0:8080 -t public",
    "healthcheckPath": "/",
    "restartPolicyType": "ON_FAILURE"
  }
}
```

### Option 2: Heroku

```bash
# Install Heroku CLI
brew install heroku/brew/heroku  # macOS
# atau download dari heroku.com

# Login
heroku login

# Create app
heroku create absensi-guru-app

# Add buildpack
heroku buildpacks:set heroku/php

# Deploy
git push heroku main
```

**Procfile:**

```
web: vendor/bin/heroku-php-nginx -C nginx.conf public/
```

### Option 3: Vercel (untuk demo/testing)

```bash
# Install Vercel CLI
npm install -g vercel

# Deploy
vercel

# Production
vercel --prod
```

**vercel.json:**

```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "/public/index.php"
    }
  ]
}
```

---

## 🔐 D. KEAMANAN (POST-DEPLOYMENT)

### 1. Environment Variables

Buat `.env` file (JANGAN commit ke Git!):

```env
DB_HOST=localhost
DB_NAME=absensi_guru
DB_USER=dbuser
DB_PASS=password_kuat

SECRET_KEY=random-secret-key-panjang-minimal-32-karakter
JWT_SECRET=jwt-secret-key-panjang

VAPID_PUBLIC_KEY=your-vapid-public-key
VAPID_PRIVATE_KEY=your-vapid-private-key
```

### 2. Generate VAPID Keys untuk Push Notification

```bash
# Install web-push library
composer require minishlink/web-push

# Generate keys
php -r "require 'vendor/autoload.php'; \$keys = \Minishlink\WebPush\VAPID::createVapidKeys(); echo 'Public Key: ' . \$keys['publicKey'] . PHP_EOL; echo 'Private Key: ' . \$keys['privateKey'] . PHP_EOL;"
```

### 3. Disable Debug Mode

Edit `config/config.php`:

```php
define('DEBUG_MODE', false);
```

### 4. Secure File Permissions

```bash
chmod 640 config/config.php
chmod 640 config/database.php
chmod 640 .env
```

---

## 📱 E. INSTALASI PWA (User Side)

### Android (Chrome)

1. Buka https://domain-anda.com/absen-guru/
2. Klik tombol "Pasang" yang muncul di banner
3. Atau: Menu (⋮) → "Install app" atau "Add to Home screen"
4. Aplikasi akan muncul di home screen seperti app native

### iOS (Safari)

1. Buka https://domain-anda.com/absen-guru/
2. Tap tombol Share (⬆️)
3. Pilih "Add to Home Screen"
4. Tap "Add"
5. Aplikasi akan muncul di home screen

### Desktop (Chrome/Edge)

1. Buka https://domain-anda.com/absen-guru/
2. Klik icon install di address bar (⊕)
3. Atau: Menu (⋮) → "Install Absensi Guru"
4. Aplikasi akan terbuka sebagai window standalone

---

## 🧪 F. TESTING

### 1. PWA Checklist

- [ ] HTTPS aktif
- [ ] manifest.json accessible
- [ ] Service Worker registered
- [ ] Icons semua ukuran tersedia
- [ ] Install prompt muncul
- [ ] Offline page berfungsi
- [ ] Push notification berfungsi

### 2. Test di Berbagai Device

```
✅ Android Chrome
✅ iOS Safari
✅ Desktop Chrome
✅ Desktop Edge
✅ Desktop Firefox
```

### 3. Lighthouse Audit

```bash
# Via Chrome DevTools
1. Buka aplikasi
2. F12 → Lighthouse tab
3. Run audit untuk PWA, Performance, Accessibility
4. Target: PWA score 100%
```

---

## 🔧 G. TROUBLESHOOTING

### Issue: Base URL tidak benar setelah deploy

**Solusi:**

```php
// config/config.php sudah auto-detect, tapi jika masih error:
define('BASE_URL', 'https://domain-anda.com/absen-guru/');
```

### Issue: Service Worker tidak register

**Solusi:**

```javascript
// Periksa di browser console
// Clear cache: Chrome DevTools → Application → Clear storage
// Re-register: Delete existing service worker dan reload
```

### Issue: Push notification tidak muncul

**Solusi:**

```bash
# 1. Pastikan HTTPS aktif
# 2. Pastikan VAPID keys sudah di-generate
# 3. Periksa browser permission
# 4. Test dengan: Chrome DevTools → Application → Service Workers → Push
```

### Issue: .htaccess tidak bekerja

**Solusi:**

```bash
# Pastikan mod_rewrite enabled
sudo a2enmod rewrite
sudo systemctl restart apache2

# Atau edit httpd.conf:
<Directory /var/www/html>
    AllowOverride All
</Directory>
```

---

## 📊 H. MONITORING

### 1. Error Logs

```bash
# Location
/var/www/absen-guru/logs/error.log
/var/www/absen-guru/logs/access.log

# Monitor real-time
tail -f /var/www/absen-guru/logs/error.log
```

### 2. Performance Monitoring

- Google Analytics untuk user tracking
- New Relic atau Sentry untuk error tracking
- UptimeRobot untuk uptime monitoring

---

## 📚 I. RESOURCE

### Generate PWA Icons

```bash
# Via https://realfavicongenerator.net/
# Upload logo → Generate semua ukuran icon
```

### Testing Tools

- **Lighthouse:** Chrome DevTools
- **PWA Builder:** https://www.pwabuilder.com/
- **Can I Use:** https://caniuse.com/ (cek browser compatibility)

### Documentation

- PWA: https://web.dev/progressive-web-apps/
- Service Worker: https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API
- Web Push: https://web.dev/push-notifications/

---

## ✅ DEPLOYMENT CHECKLIST

- [ ] Upload semua file ke server
- [ ] Set file permissions yang benar
- [ ] Buat dan konfigurasi database
- [ ] Edit config/config.php dan config/database.php
- [ ] Generate SECRET_KEY dan VAPID keys
- [ ] Install SSL certificate (HTTPS)
- [ ] Test routing di berbagai path
- [ ] Setup cron jobs
- [ ] Disable DEBUG_MODE
- [ ] Test PWA install di Android
- [ ] Test PWA install di iOS
- [ ] Test PWA install di Desktop
- [ ] Test offline functionality
- [ ] Test push notifications
- [ ] Run Lighthouse audit (target: 100% PWA)
- [ ] Setup monitoring dan backup
- [ ] Dokumentasi untuk user

---

**Aplikasi siap digunakan sebagai PWA! 🎉**

**Support:**

- Email: support@sekolah.com
- Documentation: /docs/
- Issues: GitHub Issues
