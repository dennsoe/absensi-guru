<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\GuruPiket\GuruPiketController;
use App\Http\Controllers\KepalaSekolah\KepalaSekolahController;
use App\Http\Controllers\Kurikulum\KurikulumController;
use App\Http\Controllers\Absensi\AbsensiController;
use App\Http\Controllers\Jadwal\JadwalController;
use App\Http\Controllers\Laporan\LaporanController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Semua yang sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin', 'log.activity'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Guru Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:guru,ketua_kelas'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruController::class, 'dashboard'])->name('dashboard');
        Route::get('/absensi/riwayat', [GuruController::class, 'riwayatAbsensi'])->name('absensi.riwayat');
        Route::get('/absensi/{absensi}', [GuruController::class, 'detailAbsensi'])->name('absensi.detail');
    });

    /*
    |--------------------------------------------------------------------------
    | Guru Piket Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:guru_piket', 'log.activity'])->prefix('piket')->name('piket.')->group(function () {
        Route::get('/dashboard', [GuruPiketController::class, 'dashboard'])->name('dashboard');
        Route::get('/monitoring', [GuruPiketController::class, 'monitoringAbsensi'])->name('monitoring');
        Route::get('/absensi-manual', [GuruPiketController::class, 'inputAbsensiManual'])->name('absensi-manual');
        Route::post('/absensi-manual', [GuruPiketController::class, 'storeAbsensiManual'])->name('absensi-manual.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Kepala Sekolah Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kepala_sekolah', 'log.activity'])->prefix('kepsek')->name('kepsek.')->group(function () {
        Route::get('/dashboard', [KepalaSekolahController::class, 'dashboard'])->name('dashboard');

        // Approval Izin/Cuti
        Route::get('/izin-cuti', [KepalaSekolahController::class, 'izinCuti'])->name('izin-cuti');
        Route::post('/izin-cuti/{izin}/approve', [KepalaSekolahController::class, 'approveIzin'])->name('izin-cuti.approve');
        Route::post('/izin-cuti/{izin}/reject', [KepalaSekolahController::class, 'rejectIzin'])->name('izin-cuti.reject');

        // Laporan Kedisiplinan
        Route::get('/laporan/kedisiplinan', [KepalaSekolahController::class, 'laporanKedisiplinan'])->name('laporan.kedisiplinan');
    });

    /*
    |--------------------------------------------------------------------------
    | Kurikulum Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kurikulum', 'log.activity'])->prefix('kurikulum')->name('kurikulum.')->group(function () {
        Route::get('/dashboard', [KurikulumController::class, 'dashboard'])->name('dashboard');

        // Jadwal Mengajar
        Route::get('/jadwal', [KurikulumController::class, 'jadwal'])->name('jadwal');
        Route::get('/jadwal/create', [KurikulumController::class, 'createJadwal'])->name('jadwal.create');
        Route::post('/jadwal', [KurikulumController::class, 'storeJadwal'])->name('jadwal.store');
        Route::get('/jadwal/{jadwal}/edit', [KurikulumController::class, 'editJadwal'])->name('jadwal.edit');
        Route::put('/jadwal/{jadwal}', [KurikulumController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/{jadwal}', [KurikulumController::class, 'destroyJadwal'])->name('jadwal.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Absensi Routes (Semua yang sudah login bisa absen)
    |--------------------------------------------------------------------------
    */
    Route::middleware('absensi.time')->prefix('absensi')->name('absensi.')->group(function () {
        // QR Code
        Route::get('/scan-qr', [AbsensiController::class, 'scanQr'])->name('scan-qr');
        Route::post('/scan-qr', [AbsensiController::class, 'prosesAbsensiQr'])->name('scan-qr.proses');

        // Selfie
        Route::get('/selfie', [AbsensiController::class, 'selfie'])->name('selfie');
        Route::post('/selfie', [AbsensiController::class, 'prosesAbsensiSelfie'])->name('selfie.proses');
    });

    /*
    |--------------------------------------------------------------------------
    | Jadwal Routes (Semua bisa lihat jadwal)
    |--------------------------------------------------------------------------
    */
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/hari-ini', [JadwalController::class, 'hariIni'])->name('hari-ini');
        Route::get('/per-kelas', [JadwalController::class, 'perKelas'])->name('per-kelas');
        Route::get('/per-guru', [JadwalController::class, 'perGuru'])->name('per-guru');

        // QR Code Generation (Guru Piket only)
        Route::middleware(['role:guru_piket'])->group(function () {
            Route::post('/generate-qr', [JadwalController::class, 'generateQrCode'])->name('generate-qr');
            Route::post('/qr/{qrCode}/nonaktifkan', [JadwalController::class, 'nonaktifkanQrCode'])->name('qr.nonaktifkan');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Laporan Routes (Role tertentu)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,kepala_sekolah,kurikulum'])->prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('export-excel');
        Route::get('/detail-guru/{guru}', [LaporanController::class, 'detailGuru'])->name('detail-guru');
        Route::post('/simpan', [LaporanController::class, 'simpanLaporan'])->name('simpan');
    });
});
