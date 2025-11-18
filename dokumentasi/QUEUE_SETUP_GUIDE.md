# Queue & Scheduled Tasks Setup Guide

## 📋 Overview

Aplikasi SIAG NEKAS menggunakan **Laravel Queue** dengan driver **Database** untuk menjalankan background jobs dan scheduled tasks.

## 🗂️ Queue Architecture

### Jobs yang Tersedia

1. **CleanupExpiredQR** - Cleanup QR codes kadaluarsa
2. **GenerateSuratPeringatan** - Generate PDF Surat Peringatan
3. **AutoBackupDatabase** - Backup database otomatis
4. **SendReminderNotification** - Kirim reminder notifications

### Listeners yang di-Queue

1. **SendAbsensiNotification** - Notifikasi saat absensi dibuat
2. **UpdateRekapJamMengajar** - Update rekap jam mengajar
3. **SendIzinApprovedNotification** - Notifikasi approval izin
4. **NotifyGuruPengganti** - Notifikasi guru pengganti

---

## ⚙️ Configuration

### 1. Environment Variables

Pastikan `.env` sudah configured:

```env
QUEUE_CONNECTION=database
```

### 2. Database Tables

Queue membutuhkan 2 tabel:

-   `jobs` - Menyimpan pending jobs
-   `failed_jobs` - Menyimpan failed jobs

Tabel sudah di-migrate saat setup awal.

---

## 🚀 Running Queue Worker

### Development Mode

```bash
# Cara 1: queue:work (recommended for production)
php artisan queue:work --tries=3 --timeout=60

# Cara 2: queue:listen (auto-reload code changes)
php artisan queue:listen --tries=3 --timeout=60

# Cara 3: Specific queue
php artisan queue:work --queue=high,default,low
```

### Production Mode dengan Supervisor (Linux)

Create file `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/absen-guru/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/absen-guru/storage/logs/worker.log
stopwaitsecs=3600
```

Reload supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### Alternatif: systemd Service (Linux)

Create file `/etc/systemd/system/laravel-queue.service`:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/path/to/absen-guru
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Enable dan start service:

```bash
sudo systemctl enable laravel-queue
sudo systemctl start laravel-queue
sudo systemctl status laravel-queue
```

---

## ⏰ Scheduled Tasks (Cron)

### Setup Cron Job

Tambahkan single entry ke crontab server:

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini:
* * * * * cd /path/to/absen-guru && php artisan schedule:run >> /dev/null 2>&1
```

Untuk XAMPP/Local Development:

```bash
* * * * * cd /Applications/XAMPP/xamppfiles/htdocs/absen-guru && php artisan schedule:run >> /dev/null 2>&1
```

### Scheduled Tasks yang Terdaftar

| Task                        | Schedule         | Waktu            | Deskripsi                     |
| --------------------------- | ---------------- | ---------------- | ----------------------------- |
| `cleanup-expired-qr`        | Every 15 minutes | _/15 _ \* \* \*  | Cleanup QR codes expired/used |
| `generate-surat-peringatan` | Daily            | 06:00 WIB        | Generate SP untuk guru alpha  |
| `auto-backup-database`      | Daily            | 02:00 WIB        | Backup database + compression |
| `reminder-absensi-pagi`     | Weekdays         | 07:00 WIB        | Reminder absen pagi           |
| `reminder-checkout-sore`    | Weekdays         | 15:00 WIB        | Reminder checkout sore        |
| `reminder-izin-pending`     | Weekdays         | 08:00 WIB        | Reminder approval izin        |
| `log:clear`                 | Weekly           | Sunday 03:00 WIB | Clear old logs                |

### Lihat Scheduled Tasks

```bash
php artisan schedule:list
```

### Test Schedule (tanpa tunggu waktu)

```bash
# Run all scheduled tasks sekarang
php artisan schedule:run

# Test specific command
php artisan app:generate-surat-peringatan
php artisan app:backup-database --sync
php artisan app:send-reminders absensi
```

---

## 🛠️ Console Commands

### 1. Generate Surat Peringatan

```bash
# Generate untuk semua guru
php artisan app:generate-surat-peringatan

# Generate untuk guru tertentu
php artisan app:generate-surat-peringatan --guru-id=1

# Generate untuk bulan/tahun tertentu
php artisan app:generate-surat-peringatan --bulan=11 --tahun=2025
```

### 2. Backup Database

```bash
# Dispatch to queue (async)
php artisan app:backup-database

# Run immediately (sync)
php artisan app:backup-database --sync
```

### 3. Send Reminders

```bash
# Reminder absensi pagi
php artisan app:send-reminders absensi

# Reminder checkout sore
php artisan app:send-reminders checkout

# Reminder izin pending
php artisan app:send-reminders izin_pending
```

### 4. Queue Monitoring

```bash
# Check queue status once
php artisan app:queue-monitor

# Watch mode (refresh every 3 seconds)
php artisan app:queue-monitor --watch
```

---

## 📊 Monitoring & Maintenance

### Check Queue Status

```bash
# Monitor queue
php artisan app:queue-monitor

# Check pending jobs
php artisan queue:work --once

# View failed jobs
php artisan queue:failed
```

### Manage Failed Jobs

```bash
# Retry specific failed job
php artisan queue:retry 1

# Retry all failed jobs
php artisan queue:retry all

# Delete specific failed job
php artisan queue:forget 1

# Delete all failed jobs
php artisan queue:flush
```

### Restart Queue Workers

```bash
# Graceful restart (finish current jobs)
php artisan queue:restart

# Force kill workers (for supervisor/systemd)
sudo supervisorctl restart laravel-worker:*
```

### Clear Queue

```bash
# Clear all pending jobs
php artisan queue:clear

# Clear specific connection
php artisan queue:clear database
```

---

## 🐛 Troubleshooting

### Queue Not Processing

1. **Check queue worker is running:**

    ```bash
    ps aux | grep "queue:work"
    ```

2. **Check database connection:**

    ```bash
    php artisan tinker --execute="DB::connection()->getPdo();"
    ```

3. **Check failed jobs:**

    ```bash
    php artisan queue:failed
    ```

4. **Check logs:**
    ```bash
    tail -f storage/logs/laravel.log
    ```

### Jobs Failing Repeatedly

1. **Increase timeout:**

    ```bash
    php artisan queue:work --timeout=120
    ```

2. **Increase tries:**

    ```bash
    php artisan queue:work --tries=5
    ```

3. **Check memory limit:**
    ```bash
    php artisan queue:work --memory=512
    ```

### Scheduled Tasks Not Running

1. **Verify cron is running:**

    ```bash
    sudo service cron status  # Linux
    ```

2. **Check cron logs:**

    ```bash
    grep CRON /var/log/syslog  # Ubuntu
    tail -f /var/log/cron       # CentOS
    ```

3. **Test schedule manually:**

    ```bash
    php artisan schedule:run -v
    ```

4. **Check timezone:**
    ```bash
    php artisan tinker --execute="echo config('app.timezone');"
    ```

---

## 📝 Best Practices

### Production Recommendations

1. **Use Supervisor/Systemd** untuk queue workers (auto-restart)
2. **Setup monitoring** untuk failed jobs (email alerts)
3. **Regular maintenance:**
    - Clean failed jobs setiap minggu
    - Monitor queue size
    - Check backup files size
4. **Set proper timeouts** sesuai job complexity
5. **Use queue priorities** untuk urgent jobs
6. **Log everything** untuk debugging

### Development Tips

1. **Use `queue:listen`** untuk auto-reload code changes
2. **Test jobs dengan `dispatch_sync()`** untuk debugging
3. **Use `--once` flag** untuk test single job
4. **Monitor logs** saat development
5. **Clear queue** setelah testing: `php artisan queue:clear`

---

## 🔐 Security Notes

1. **Backup files** disimpan di `storage/app/backups/` - pastikan folder tidak public accessible
2. **Queue jobs** bisa contains sensitive data - pastikan database secure
3. **Cron user** harus punya permission minimal (tidak perlu root)
4. **Log files** mungkin contains sensitive info - setup log rotation

---

## 📚 Related Documentation

-   [DEVELOPMENT_NOTES.md](./DEVELOPMENT_NOTES.md) - Complete development documentation
-   [TESTING_GUIDE.md](../TESTING_GUIDE.md) - Testing procedures
-   [DEPLOYMENT_CHECKLIST.md](../DEPLOYMENT_CHECKLIST.md) - Production deployment

---

**Last Updated:** November 17, 2025  
**Version:** 1.0.0
