# Phase 3 Testing Guide

## Overview

Panduan lengkap untuk testing semua fitur yang diimplementasikan di Phase 3.

## Prerequisites

### 1. Environment Setup

```bash
# Copy .env.example jika belum ada .env
cp dokumentasi/.env.example .env

# Update koordinat GPS sesuai lokasi sekolah
# Edit .env dan sesuaikan:
SCHOOL_LATITUDE=-6.4167
SCHOOL_LONGITUDE=107.7667
GPS_RADIUS_METERS=200
```

### 2. Clear All Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### 3. Verify Routes

```bash
# Check semua export routes terdaftar
php artisan route:list --name=laporan

# Expected output:
# admin.laporan.export-pdf.per-guru
# admin.laporan.export-pdf.per-kelas
# admin.laporan.export-pdf.rekap-bulanan
# admin.laporan.export-pdf.keterlambatan
# admin.laporan.export-excel.per-guru
# admin.laporan.export-excel.per-kelas
# admin.laporan.export-excel.rekap-bulanan
# admin.laporan.export-excel.keterlambatan
```

---

## Testing Checklist

### A. Controller Integration Testing

#### ✅ Test 1: IzinController - Approve Izin

**File:** `app/Http/Controllers/Admin/IzinController.php`

**Steps:**

1. Login sebagai admin
2. Navigate ke `/admin/izin-cuti`
3. Pilih izin dengan status "pending"
4. Click tombol "Approve"
5. Isi catatan approval
6. Submit

**Expected Result:**

-   ✅ Status berubah menjadi "approved"
-   ✅ Email notifikasi terkirim (jika MAIL configured)
-   ✅ Catatan tersimpan
-   ✅ approved_by terisi dengan ID admin
-   ✅ approved_at terisi dengan timestamp

**Test with Tinker:**

```php
php artisan tinker

// Get pending izin
$izin = App\Models\IzinCuti::where('status', 'pending')->first();

// Test service method
$service = app(App\Services\IzinCutiService::class);
$result = $service->approveIzinCuti($izin->id, 1, 'Disetujui untuk keperluan keluarga');

// Verify
$izin->refresh();
dd($izin->status, $izin->approved_by, $izin->catatan_approval);
```

---

#### ✅ Test 2: IzinController - Reject Izin

**Steps:**

1. Login sebagai admin
2. Navigate ke `/admin/izin-cuti`
3. Pilih izin dengan status "pending"
4. Click tombol "Reject"
5. Isi alasan penolakan
6. Submit

**Expected Result:**

-   ✅ Status berubah menjadi "rejected"
-   ✅ Email notifikasi terkirim
-   ✅ Alasan tersimpan
-   ✅ approved_by terisi dengan ID admin
-   ✅ approved_at terisi dengan timestamp

---

#### ✅ Test 3: LaporanController - PDF Exports (4 Types)

**Test 3.1: Export PDF Per Guru**

```bash
# Direct URL test
curl -L "http://localhost/admin/laporan/export-pdf/per-guru?guru_id=1&bulan=11&tahun=2025" \
     -H "Cookie: laravel_session=YOUR_SESSION" \
     --output test_per_guru.pdf

# Check file
open test_per_guru.pdf
```

**Expected Result:**

-   ✅ PDF file downloaded
-   ✅ Filename format: `laporan_per_guru_YYYY-MM-DD_HHmmss.pdf`
-   ✅ Contains teacher info (nama, NIP)
-   ✅ Contains attendance data for selected month
-   ✅ Contains statistics (hadir, izin, sakit, alpha)

---

**Test 3.2: Export PDF Per Kelas**

```bash
curl -L "http://localhost/admin/laporan/export-pdf/per-kelas?kelas_id=1&bulan=11&tahun=2025" \
     -H "Cookie: laravel_session=YOUR_SESSION" \
     --output test_per_kelas.pdf
```

**Expected Result:**

-   ✅ PDF file downloaded
-   ✅ Contains class info (nama kelas, tingkat)
-   ✅ Contains all teachers teaching in that class
-   ✅ Contains attendance data grouped by teacher

---

**Test 3.3: Export PDF Rekap Bulanan**

```bash
curl -L "http://localhost/admin/laporan/export-pdf/rekap-bulanan?bulan=11&tahun=2025" \
     -H "Cookie: laravel_session=YOUR_SESSION" \
     --output test_rekap_bulanan.pdf
```

**Expected Result:**

-   ✅ PDF file downloaded
-   ✅ Contains summary for ALL teachers
-   ✅ Contains statistics: total hari kerja, hadir, izin, sakit, alpha
-   ✅ Contains percentage calculations
-   ✅ Sorted by teacher name

---

**Test 3.4: Export PDF Keterlambatan**

```bash
curl -L "http://localhost/admin/laporan/export-pdf/keterlambatan?bulan=11&tahun=2025" \
     -H "Cookie: laravel_session=YOUR_SESSION" \
     --output test_keterlambatan.pdf
```

**Expected Result:**

-   ✅ PDF file downloaded
-   ✅ Contains only late arrivals
-   ✅ Shows: tanggal, nama guru, jam seharusnya, jam datang, durasi terlambat
-   ✅ Sorted by date descending

---

#### ✅ Test 4: LaporanController - Excel Exports (4 Types)

**Test 4.1: Export Excel Per Guru**

```bash
# Browser test
# Navigate to: http://localhost/admin/laporan/export-excel/per-guru?guru_id=1&bulan=11&tahun=2025
```

**Expected Result:**

-   ✅ Excel file downloaded (.xlsx)
-   ✅ Filename format: `per_guru_YYYY-MM-DD_HHmmss.xlsx`
-   ✅ Sheet name: "Laporan Per Guru"
-   ✅ Header row styled (blue background, white text, bold)
-   ✅ Columns: No, Tanggal, Hari, Jam Masuk, Status Masuk, Jam Keluar, Status Keluar, Keterangan
-   ✅ Data formatted correctly (dates, times, status)

**Verify Excel Structure:**

```php
php artisan tinker

$export = new App\Exports\LaporanExport(collect([]), 'per_guru');
dd($export->headings());
// Should return 8 columns array
```

---

**Test 4.2: Export Excel Per Kelas**
**Expected Columns:** No, Nama Guru, Mata Pelajaran, Tanggal, Jam Masuk, Status Masuk, Jam Keluar, Status Keluar

---

**Test 4.3: Export Excel Rekap Bulanan**
**Expected Columns:** No, Nama Guru, NIP, Total Hari Kerja, Hadir Tepat Waktu, Hadir Terlambat, Izin, Sakit, Alpha, Persentase Kehadiran, Status

**Special Checks:**

-   ✅ Persentase formatted as percentage (e.g., "95.5%")
-   ✅ Status calculated correctly (Sangat Baik ≥95%, Baik ≥85%, Cukup ≥75%, Kurang <75%)

---

**Test 4.4: Export Excel Keterlambatan**
**Expected Columns:** No, Tanggal, Nama Guru, Jam Seharusnya, Jam Datang, Durasi Terlambat

**Special Checks:**

-   ✅ Durasi formatted as "X menit" or "X jam Y menit"
-   ✅ Only records with status "terlambat"

---

#### ✅ Test 5: DashboardController - Live Guru Status

**Test with AJAX:**

```javascript
// Open browser console on /admin/dashboard
fetch("/admin/dashboard/live-guru-status", {
    method: "GET",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
    },
})
    .then((r) => r.json())
    .then((data) => console.log(data));
```

**Expected Response:**

```json
{
    "success": true,
    "data": [
        {
            "guru_id": 1,
            "nama": "Budiman, S.Pd",
            "status": "hadir",
            "jam_masuk": "06:45:00",
            "kelas": "4A",
            "mata_pelajaran": "Matematika",
            "last_update": "2025-11-18 06:45:23"
        }
    ],
    "summary": {
        "total_guru": 20,
        "sudah_absen": 15,
        "belum_absen": 5,
        "terlambat": 2
    }
}
```

**Verify:**

-   ✅ Response is JSON
-   ✅ Contains guru list with current status
-   ✅ Summary stats calculated correctly
-   ✅ last_update shows recent timestamp

---

#### ✅ Test 6: AbsensiController - GPS Validation

**Test GPS Within Radius:**

```php
php artisan tinker

$service = app(App\Services\ValidationService::class);

// Test within radius (should pass)
$result = $service->validateGPSCoordinates(
    -6.4167,  // School latitude
    107.7667, // School longitude
    200       // Radius in meters
);

var_dump($result); // Should be true
```

**Test GPS Outside Radius:**

```php
// Test outside radius (should fail if strict mode ON)
$result = $service->validateGPSCoordinates(
    -6.5000,  // Far from school
    107.8000,
    200
);

var_dump($result); // Should be false
```

**Test with Config:**

```php
// Verify config is read correctly
$lat = config('gps.school_latitude');
$lng = config('gps.school_longitude');
$radius = config('gps.radius_meters');
$strict = config('gps.validation.strict_mode');

dd(compact('lat', 'lng', 'radius', 'strict'));
```

---

### B. Email Template Testing

#### ✅ Test 7: Email Notifications

**Test 7.1: Generic Notification Email**

```php
php artisan tinker

use Illuminate\Support\Facades\Mail;

Mail::send('emails.notification', [
    'title' => 'Test Notification',
    'user' => (object)['nama' => 'Test User'],
    'message' => 'This is a test notification message.'
], function($message) {
    $message->to('test@example.com')
            ->subject('Test Notification');
});
```

**Check:**

-   ✅ Email sent successfully (check mailtrap or log)
-   ✅ Responsive HTML rendering
-   ✅ Variables replaced correctly

---

**Test 7.2: Izin Approved Email**

```php
$izin = App\Models\IzinCuti::with(['guru', 'approvedBy'])->first();

Mail::send('emails.izin-approved', [
    'izinCuti' => $izin
], function($message) use ($izin) {
    $message->to($izin->guru->email)
            ->subject('Izin Anda Disetujui');
});
```

**Check:**

-   ✅ Success badge rendered
-   ✅ Izin details displayed correctly
-   ✅ Approver name shown
-   ✅ Catatan approval included

---

**Test 7.3: Izin Rejected Email**

```php
$izin = App\Models\IzinCuti::where('status', 'rejected')->first();

Mail::send('emails.izin-rejected', [
    'izinCuti' => $izin
], function($message) use ($izin) {
    $message->to($izin->guru->email)
            ->subject('Izin Anda Ditolak');
});
```

**Check:**

-   ✅ Danger badge rendered
-   ✅ Reason highlighted
-   ✅ Professional tone maintained

---

**Test 7.4: Missed Attendance Email**

```php
$guru = App\Models\Guru::first();
$jadwal = App\Models\JadwalMengajar::first();

Mail::send('emails.missed-attendance', [
    'guru' => $guru,
    'jadwal' => $jadwal,
    'tanggal' => now()->format('d F Y')
], function($message) use ($guru) {
    $message->to($guru->email)
            ->subject('Reminder: Anda Belum Absen');
});
```

**Check:**

-   ✅ Warning styling applied
-   ✅ Schedule details shown
-   ✅ CTA button links to absen page
-   ✅ Instructions clear

---

### C. Integration Testing

#### ✅ Test 8: Complete Workflow - Izin Approval

**Scenario:** Guru submits izin, admin approves, guru receives email

**Steps:**

1. Login as guru
2. Submit new izin request
3. Logout, login as admin
4. Navigate to izin list
5. Approve the request with catatan
6. Check email (mailtrap/log)
7. Verify database record

**Verify:**

-   ✅ Izin created with status "pending"
-   ✅ Admin can see in list
-   ✅ Approval updates status to "approved"
-   ✅ Email sent to guru
-   ✅ Catatan saved
-   ✅ Activity logged

---

#### ✅ Test 9: Complete Workflow - Report Export

**Scenario:** Admin generates and downloads report

**Steps:**

1. Login as admin
2. Navigate to `/admin/laporan`
3. Select filters (bulan, tahun, guru)
4. Click "Export PDF" button
5. Verify download
6. Click "Export Excel" button
7. Verify download

**Verify:**

-   ✅ Both files downloaded successfully
-   ✅ Filenames unique (timestamp)
-   ✅ Data matches filters
-   ✅ PDF formatted correctly
-   ✅ Excel formatted with headers
-   ✅ Activity logged

---

#### ✅ Test 10: Complete Workflow - GPS Validation

**Scenario:** Guru attempts absen with GPS

**Steps:**

1. Login as guru on mobile device
2. Navigate to `/guru/absensi/scan-qr`
3. Allow location access
4. Scan QR code
5. Take selfie
6. Submit absensi

**Test Cases:**

-   ✅ **Within radius:** Absensi accepted
-   ✅ **Outside radius (strict OFF):** Absensi accepted with warning
-   ✅ **Outside radius (strict ON):** Absensi rejected with error
-   ✅ **GPS disabled:** Error message shown
-   ✅ **Location denied:** Error message shown

---

### D. Performance Testing

#### ✅ Test 11: Large Dataset Export

**Setup:**

```php
php artisan tinker

// Create test data
factory(App\Models\Absensi::class, 1000)->create();
```

**Test:**

1. Export rekap bulanan with 100+ teachers
2. Measure generation time
3. Verify file size
4. Check memory usage

**Expected:**

-   ✅ PDF generation < 30 seconds
-   ✅ Excel generation < 20 seconds
-   ✅ Memory usage < 256MB
-   ✅ No timeout errors

---

#### ✅ Test 12: Concurrent Requests

**Test with Apache Bench:**

```bash
# Test PDF export endpoint
ab -n 10 -c 5 "http://localhost/admin/laporan/export-pdf/per-guru?guru_id=1&bulan=11&tahun=2025"

# Test live status endpoint
ab -n 100 -c 10 "http://localhost/admin/dashboard/live-guru-status"
```

**Expected:**

-   ✅ All requests successful (no 500 errors)
-   ✅ Response time < 2 seconds average
-   ✅ No database deadlocks

---

### E. Error Handling Testing

#### ✅ Test 13: Invalid Parameters

**Test Cases:**

1. Export without guru_id (when required)
2. Export with invalid guru_id
3. Export with future date
4. Export with invalid month (13)
5. Export with invalid year

**Expected:**

-   ✅ Friendly error message
-   ✅ Redirect to previous page
-   ✅ Flash message displayed
-   ✅ No system errors logged

---

#### ✅ Test 14: Missing Dependencies

**Test Cases:**

1. DOMPDF not installed → PDF export fails gracefully
2. maatwebsite/excel not installed → Excel export fails gracefully
3. Mail server down → Email fails but process continues

**Expected:**

-   ✅ Proper error handling
-   ✅ User-friendly messages
-   ✅ Errors logged to storage/logs

---

### F. Security Testing

#### ✅ Test 15: Authentication & Authorization

**Test Cases:**

1. Access export routes without login → Redirect to login
2. Access admin routes as guru → 403 Forbidden
3. Access other guru's report → Denied
4. CSRF token missing → 419 Error

**Expected:**

-   ✅ Proper authentication checks
-   ✅ Role-based authorization
-   ✅ CSRF protection active
-   ✅ Activity logged

---

#### ✅ Test 16: SQL Injection Prevention

**Test:**

```bash
# Try SQL injection in parameters
curl "http://localhost/admin/laporan/export-pdf/per-guru?guru_id=1%20OR%201=1"
```

**Expected:**

-   ✅ Query fails safely
-   ✅ No data leaked
-   ✅ Error handled gracefully

---

## Testing Tools

### 1. Manual Testing Browser

-   Chrome DevTools (Network, Console)
-   Firefox Developer Tools
-   Postman (API testing)

### 2. Command Line Tools

```bash
# Route testing
php artisan route:list --name=admin

# Clear caches
php artisan optimize:clear

# Check logs
tail -f storage/logs/laravel.log
```

### 3. Database Inspection

```bash
php artisan tinker

# Check recent exports
DB::table('activity_log')->where('description', 'like', '%export%')->latest()->limit(10)->get();

# Check email queue
DB::table('jobs')->count();

# Check izin statuses
DB::table('izin_cuti')->select('status', DB::raw('count(*) as count'))->groupBy('status')->get();
```

---

## Common Issues & Solutions

### Issue 1: PDF not downloading

```bash
# Check DOMPDF config
php artisan vendor:publish --tag=dompdf-config

# Increase memory limit in php.ini
memory_limit = 256M
```

### Issue 2: Excel export error

```bash
# Reinstall package
composer require maatwebsite/excel
php artisan config:clear
```

### Issue 3: Route not found

```bash
# Clear route cache
php artisan route:clear
php artisan route:cache

# Verify route exists
php artisan route:list --name=laporan.export
```

### Issue 4: GPS validation not working

```bash
# Check config
php artisan config:show gps

# Clear config cache
php artisan config:clear

# Verify .env values
cat .env | grep GPS
```

### Issue 5: Email not sending

```bash
# Check mail config
php artisan config:show mail

# Test mail connection
php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# Check queue
php artisan queue:work --once
```

---

## Final Checklist

Before marking Phase 3 as complete:

### Controllers

-   [x] IzinController integrated with IzinCutiService
-   [x] LaporanController has 8 export methods (4 PDF + 4 Excel)
-   [x] DashboardController has live monitoring endpoint
-   [x] AbsensiController uses ValidationService with GPS config

### Routes

-   [x] 4 PDF export routes registered
-   [x] 4 Excel export routes registered
-   [x] 1 live status route registered
-   [x] All routes protected by auth + admin middleware

### Services

-   [x] All 6 services functioning correctly
-   [x] Proper dependency injection
-   [x] Error handling implemented

### Templates

-   [x] 4 email templates created
-   [x] Responsive design
-   [x] Professional styling

### Configuration

-   [x] GPS config with env support
-   [x] .env.example updated
-   [x] All settings documented

### Documentation

-   [x] Phase 3 implementation summary
-   [x] Export routes guide
-   [x] Testing guide (this file)

---

**Testing Status:** Ready for execution  
**Estimated Testing Time:** 4-6 hours  
**Priority:** High (Required before production deployment)
