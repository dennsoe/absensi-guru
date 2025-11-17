# DEPLOYMENT CHECKLIST - Aplikasi Absensi Guru

**Created**: November 17, 2025  
**Status**: Production Readiness Guide  
**Version**: 1.0.0

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### ✅ Code Quality & Completeness

#### Controllers (7/7 Complete)

-   [x] GuruController - Dashboard & alerts
-   [x] KetuaKelasController - QR & selfie validation
-   [x] Guru\AbsensiController - QR scan & selfie capture
-   [x] GuruPiketController - Monitoring & manual input
-   [x] KepalaSekolahController - Approval & reports
-   [x] KurikulumController - Schedule CRUD & conflicts
-   [x] AdminController - Full system management

#### Routes (67 Routes)

-   [x] All routes connected to controller methods
-   [x] Authorization middleware applied
-   [x] AJAX endpoints properly configured
-   [x] No syntax errors in routes/web.php

#### Database

-   [x] Schema complete (all tables created)
-   [x] Foreign keys properly configured
-   [x] Indexes on frequently queried columns
-   [x] Migrations tested and working

---

## 🔐 SECURITY CHECKLIST

### Authentication & Authorization

-   [ ] All routes have proper middleware (`auth`, role checks)
-   [ ] Password hashing using bcrypt (Laravel default)
-   [ ] Session security configured
-   [ ] CSRF protection enabled on all forms
-   [ ] Remember me token security

### Input Validation

-   [ ] All POST/PUT requests have validation rules
-   [ ] File upload validation (size, type, mime)
-   [ ] SQL injection prevention (Eloquent ORM)
-   [ ] XSS protection (Blade templating)
-   [ ] Rate limiting on sensitive endpoints

### Data Protection

-   [ ] Sensitive data encrypted in database
-   [ ] `.env` file not in version control
-   [ ] API keys secured
-   [ ] Database credentials secured
-   [ ] File upload directory permissions set correctly

---

## 🗄️ DATABASE PREPARATION

### Production Database Setup

#### Step 1: Create Production Database

```sql
CREATE DATABASE absensi_guru_production
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

CREATE USER 'absensi_user'@'localhost'
IDENTIFIED BY '[SECURE_PASSWORD]';

GRANT ALL PRIVILEGES ON absensi_guru_production.*
TO 'absensi_user'@'localhost';

FLUSH PRIVILEGES;
```

#### Step 2: Run Migrations

```bash
cd /path/to/absen-guru
php artisan migrate --env=production
```

#### Step 3: Seed Initial Data

```bash
# Seed admin user
php artisan db:seed --class=AdminSeeder --env=production

# Seed roles and permissions
php artisan db:seed --class=RoleSeeder --env=production

# Optional: seed test data
php artisan db:seed --env=production
```

#### Step 4: Verify Tables

```sql
USE absensi_guru_production;
SHOW TABLES;

-- Verify key tables exist:
-- users, guru, kelas, mata_pelajaran, jadwal_mengajar
-- absensi, qr_codes, izin_cuti, activity_logs
```

---

## ⚙️ ENVIRONMENT CONFIGURATION

### Production `.env` File

```env
APP_NAME="Absensi Guru"
APP_ENV=production
APP_KEY=base64:[GENERATE_NEW_KEY]
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_guru_production
DB_USERNAME=absensi_user
DB_PASSWORD=[SECURE_PASSWORD]

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# GPS Settings (from admin settings or env)
SCHOOL_LATITUDE=-6.200000
SCHOOL_LONGITUDE=106.816666
GPS_RADIUS_METERS=200

# Tolerances
TOLERANSI_TERLAMBAT_MENIT=15
QR_EXPIRY_MINUTES=15

# Storage
SELFIE_STORAGE_PATH=storage/app/public/selfie
QR_CODE_STORAGE_PATH=storage/app/public/qr_codes
```

### Generate Application Key

```bash
php artisan key:generate --env=production
```

### Clear & Cache Config

```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📁 FILE PERMISSIONS

### Set Proper Permissions

```bash
# Storage directory
chmod -R 775 storage/
chown -R www-data:www-data storage/

# Bootstrap cache
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data bootstrap/cache/

# Public uploads
chmod -R 775 public/assets/
chown -R www-data:www-data public/assets/

# Selfie storage
mkdir -p storage/app/public/selfie
chmod -R 775 storage/app/public/selfie
chown -R www-data:www-data storage/app/public/selfie

# Create symbolic link
php artisan storage:link
```

---

## 🌐 WEB SERVER CONFIGURATION

### Apache Configuration

#### .htaccess (already exists in Laravel)

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### VirtualHost Configuration

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/absen-guru/public

    <Directory /var/www/absen-guru/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/absen-guru-error.log
    CustomLog ${APACHE_LOG_DIR}/absen-guru-access.log combined
</VirtualHost>
```

#### Enable Modules

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

---

### Nginx Configuration (Alternative)

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/absen-guru/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 🔒 SSL/HTTPS Setup (Recommended)

### Using Let's Encrypt (Certbot)

```bash
# Install Certbot
sudo apt-get update
sudo apt-get install certbot python3-certbot-apache

# Get SSL certificate
sudo certbot --apache -d yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

---

## 📊 MONITORING & LOGGING

### Enable Laravel Logging

#### config/logging.php

```php
'channels' => [
    'production' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'warning',
        'days' => 14,
    ],
],
```

### Monitor Key Logs

```bash
# Watch Laravel logs
tail -f storage/logs/laravel.log

# Watch Apache/Nginx errors
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

### Activity Logging

-   [ ] Admin actions logged (create, update, delete)
-   [ ] Attendance actions logged
-   [ ] Approval actions logged
-   [ ] Failed login attempts logged

---

## 🧪 PRE-PRODUCTION TESTING

### Functional Testing

-   [ ] All 6 workflows tested (QR, Selfie, Monitoring, Approval, Schedule, Admin)
-   [ ] CRUD operations for all master data
-   [ ] Reports generation working
-   [ ] AJAX endpoints responding correctly

### Security Testing

-   [ ] Test unauthorized access attempts
-   [ ] Test SQL injection vulnerabilities
-   [ ] Test XSS vulnerabilities
-   [ ] Test CSRF protection

### Performance Testing

-   [ ] Load testing (100+ concurrent users)
-   [ ] Database query optimization
-   [ ] Image upload/processing speed
-   [ ] AJAX real-time update performance

### Browser Testing

-   [ ] Chrome/Edge (latest)
-   [ ] Firefox (latest)
-   [ ] Safari (latest)
-   [ ] Mobile browsers (responsive)

---

## 📱 MOBILE/PWA PREPARATION

### PWA Configuration

#### public/manifest.json

```json
{
    "name": "Absensi Guru",
    "short_name": "AbsenGuru",
    "description": "Aplikasi Absensi Guru dengan QR Code & Selfie",
    "start_url": "/",
    "display": "standalone",
    "theme_color": "#007bff",
    "background_color": "#ffffff",
    "icons": [
        {
            "src": "/assets/images/icon-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/assets/images/icon-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

#### Service Worker Registration

```javascript
// public/sw.js
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open("v1").then((cache) => {
            return cache.addAll([
                "/",
                "/assets/css/style.css",
                "/assets/js/app.js",
                // Add other assets
            ]);
        })
    );
});
```

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Backup Current System

```bash
# Backup database
mysqldump -u root -p absensi_guru > backup_$(date +%F).sql

# Backup files
tar -czf backup_files_$(date +%F).tar.gz /path/to/absen-guru
```

### Step 2: Upload Files

```bash
# Via Git
git clone https://github.com/yourusername/absen-guru.git
cd absen-guru
git checkout production

# Or via SCP/FTP
scp -r absen-guru/ user@server:/var/www/
```

### Step 3: Install Dependencies

```bash
cd /var/www/absen-guru
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### Step 4: Configure Environment

```bash
cp .env.example .env
nano .env  # Edit with production values
php artisan key:generate
```

### Step 5: Database Migration

```bash
php artisan migrate --force --env=production
php artisan db:seed --class=AdminSeeder --env=production
```

### Step 6: Optimize & Cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 7: Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

### Step 8: Test Production

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test routes
php artisan route:list

# Test application
curl -I https://yourdomain.com
```

---

## 📋 POST-DEPLOYMENT VERIFICATION

### Immediate Checks (Within 1 Hour)

-   [ ] Homepage loads correctly
-   [ ] Login system works
-   [ ] All 6 role dashboards accessible
-   [ ] QR generation works
-   [ ] Selfie capture works
-   [ ] Database connections stable

### Daily Monitoring (First Week)

-   [ ] Check error logs daily
-   [ ] Monitor user activity
-   [ ] Verify attendance data integrity
-   [ ] Check storage space usage
-   [ ] Monitor database performance

### Weekly Monitoring (First Month)

-   [ ] Review activity logs
-   [ ] Check report accuracy
-   [ ] Verify backup system
-   [ ] Monitor system performance
-   [ ] Collect user feedback

---

## 🔧 MAINTENANCE PLAN

### Daily Tasks

-   Check error logs
-   Monitor active users
-   Verify backup completion

### Weekly Tasks

-   Database backup verification
-   Storage cleanup (old QR codes, old logs)
-   Performance monitoring

### Monthly Tasks

-   Security updates (Laravel, PHP, dependencies)
-   Database optimization
-   User access review
-   Report generation & analysis

---

## 🆘 ROLLBACK PLAN

### If Deployment Fails

#### Step 1: Restore Database

```bash
mysql -u absensi_user -p absensi_guru_production < backup_YYYY-MM-DD.sql
```

#### Step 2: Restore Files

```bash
cd /var/www
rm -rf absen-guru
tar -xzf backup_files_YYYY-MM-DD.tar.gz
```

#### Step 3: Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Step 4: Restart Services

```bash
sudo systemctl restart apache2
# or
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

---

## 📞 SUPPORT & CONTACTS

### Technical Support

-   **Developer**: [Your Name/Team]
-   **Email**: [support@example.com]
-   **Phone**: [+62-xxx-xxx-xxxx]

### Emergency Contacts

-   **System Admin**: [Name/Phone]
-   **Database Admin**: [Name/Phone]
-   **Server Provider**: [Provider/Support]

---

## ✅ FINAL CHECKLIST BEFORE GO-LIVE

### Code & Configuration

-   [ ] All controllers tested (7/7)
-   [ ] All routes working (67 routes)
-   [ ] Database schema complete
-   [ ] `.env` configured for production
-   [ ] APP_DEBUG=false
-   [ ] Error reporting configured

### Security

-   [ ] HTTPS enabled
-   [ ] Firewall configured
-   [ ] File permissions set
-   [ ] Sensitive data encrypted
-   [ ] CSRF protection enabled

### Performance

-   [ ] Config cached
-   [ ] Routes cached
-   [ ] Views cached
-   [ ] Composer optimized
-   [ ] Assets minified

### Backup & Recovery

-   [ ] Database backup automated
-   [ ] File backup automated
-   [ ] Rollback plan documented
-   [ ] Recovery tested

### Documentation

-   [ ] User manual created
-   [ ] Admin guide created
-   [ ] API documentation (if needed)
-   [ ] Deployment notes documented

### Training & Support

-   [ ] Admin users trained
-   [ ] Key users trained
-   [ ] Support system ready
-   [ ] Feedback mechanism in place

---

## 🎯 SUCCESS METRICS

### Week 1 Targets

-   90% uptime
-   <2 second page load
-   Zero critical errors
-   80% user adoption

### Month 1 Targets

-   95% uptime
-   <1.5 second page load
-   <5 minor bugs reported
-   90% user adoption

### Quarter 1 Targets

-   99% uptime
-   <1 second page load
-   All reported bugs fixed
-   100% user adoption

---

**Deployment Checklist Complete**: November 17, 2025 ✅  
**Production Readiness**: READY 🚀  
**Let's Deploy!** 💪
