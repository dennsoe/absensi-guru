<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CleanupExpiredQR;
use App\Jobs\GenerateSuratPeringatan;
use App\Jobs\AutoBackupDatabase;
use App\Jobs\SendReminderNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==========================================
// SCHEDULED TASKS
// ==========================================

// Cleanup Expired QR Codes - Setiap 15 menit
Schedule::job(new CleanupExpiredQR)
    ->everyFifteenMinutes()
    ->name('cleanup-expired-qr')
    ->withoutOverlapping();

// Generate Surat Peringatan - Menggunakan command karena butuh parameter
Schedule::command('app:generate-surat-peringatan')
    ->dailyAt('06:00')
    ->name('generate-surat-peringatan')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

// Auto Backup Database - Setiap hari pukul 02:00 WIB
Schedule::command('app:backup-database')
    ->dailyAt('02:00')
    ->name('auto-backup-database')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping();

// Send Reminder Absensi - Setiap hari pukul 07:00 WIB
Schedule::command('app:send-reminders absensi')
    ->dailyAt('07:00')
    ->name('reminder-absensi-pagi')
    ->timezone('Asia/Jakarta')
    ->weekdays()
    ->withoutOverlapping();

// Send Reminder Checkout - Setiap hari pukul 15:00 WIB
Schedule::command('app:send-reminders checkout')
    ->dailyAt('15:00')
    ->name('reminder-checkout-sore')
    ->timezone('Asia/Jakarta')
    ->weekdays()
    ->withoutOverlapping();

// Send Reminder Izin Pending - Setiap hari pukul 08:00 WIB
Schedule::command('app:send-reminders izin_pending')
    ->dailyAt('08:00')
    ->name('reminder-izin-pending')
    ->timezone('Asia/Jakarta')
    ->weekdays()
    ->withoutOverlapping();

// Clean up old logs - Setiap minggu
Schedule::command('log:clear')
    ->weekly()
    ->sundays()
    ->at('03:00')
    ->timezone('Asia/Jakarta');
