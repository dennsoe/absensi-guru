# Export Routes Implementation Guide

## Overview

Panduan lengkap untuk menggunakan routes export PDF dan Excel yang telah ditambahkan ke sistem absensi guru.

## Available Routes

### PDF Export Routes

#### 1. Export Laporan Per Guru (PDF)

```php
Route: admin.laporan.export-pdf.per-guru
URL: /admin/laporan/export-pdf/per-guru
Method: GET
Controller: AdminLaporanController@exportPdfPerGuru
```

**Query Parameters:**

-   `guru_id` (required) - ID guru yang akan digenerate laporannya
-   `bulan` (optional) - Bulan laporan (1-12), default: bulan sekarang
-   `tahun` (optional) - Tahun laporan, default: tahun sekarang

**Example Usage:**

```blade
<a href="{{ route('admin.laporan.export-pdf.per-guru', [
    'guru_id' => $guru->id,
    'bulan' => 11,
    'tahun' => 2025
]) }}" class="btn btn-danger">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>
```

**Response:**

-   Downloads PDF file: `laporan_per_guru_YYYY-MM-DD_HHmmss.pdf`
-   Contains: Teacher attendance details, statistics, and summary

---

#### 2. Export Laporan Per Kelas (PDF)

```php
Route: admin.laporan.export-pdf.per-kelas
URL: /admin/laporan/export-pdf/per-kelas
Method: GET
Controller: AdminLaporanController@exportPdfPerKelas
```

**Query Parameters:**

-   `kelas_id` (required) - ID kelas yang akan digenerate laporannya
-   `bulan` (optional) - Bulan laporan (1-12)
-   `tahun` (optional) - Tahun laporan

**Example Usage:**

```blade
<a href="{{ route('admin.laporan.export-pdf.per-kelas', [
    'kelas_id' => $kelas->id,
    'bulan' => request('bulan'),
    'tahun' => request('tahun')
]) }}" class="btn btn-danger">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>
```

---

#### 3. Export Rekap Bulanan (PDF)

```php
Route: admin.laporan.export-pdf.rekap-bulanan
URL: /admin/laporan/export-pdf/rekap-bulanan
Method: GET
Controller: AdminLaporanController@exportPdfRekapBulanan
```

**Query Parameters:**

-   `bulan` (optional) - Bulan rekap (1-12), default: bulan sekarang
-   `tahun` (optional) - Tahun rekap, default: tahun sekarang
-   `kelas_id` (optional) - Filter by specific kelas

**Example Usage:**

```blade
<a href="{{ route('admin.laporan.export-pdf.rekap-bulanan', request()->query()) }}"
   class="btn btn-danger">
    <i class="fas fa-file-pdf"></i> Export Rekap PDF
</a>
```

---

#### 4. Export Laporan Keterlambatan (PDF)

```php
Route: admin.laporan.export-pdf.keterlambatan
URL: /admin/laporan/export-pdf/keterlambatan
Method: GET
Controller: AdminLaporanController@exportPdfKeterlambatan
```

**Query Parameters:**

-   `tanggal_mulai` (optional) - Start date (YYYY-MM-DD)
-   `tanggal_selesai` (optional) - End date (YYYY-MM-DD)
-   `guru_id` (optional) - Filter by specific guru

**Example Usage:**

```blade
<a href="{{ route('admin.laporan.export-pdf.keterlambatan', [
    'tanggal_mulai' => '2025-11-01',
    'tanggal_selesai' => '2025-11-30'
]) }}" class="btn btn-danger">
    <i class="fas fa-file-pdf"></i> Export Keterlambatan
</a>
```

---

### Excel Export Routes

#### 1. Export Laporan Per Guru (Excel)

```php
Route: admin.laporan.export-excel.per-guru
URL: /admin/laporan/export-excel/per-guru
Method: GET
Controller: AdminLaporanController@exportExcelPerGuru
```

**Query Parameters:** Same as PDF version

**Example Usage:**

```blade
<a href="{{ route('admin.laporan.export-excel.per-guru', [
    'guru_id' => $guru->id,
    'bulan' => 11,
    'tahun' => 2025
]) }}" class="btn btn-success">
    <i class="fas fa-file-excel"></i> Export Excel
</a>
```

**Response:**

-   Downloads Excel file: `per_guru_YYYY-MM-DD_HHmmss.xlsx`
-   **Sheet Columns:**
    1. No
    2. Tanggal
    3. Hari
    4. Jam Masuk
    5. Status Masuk
    6. Jam Keluar
    7. Status Keluar
    8. Keterangan

---

#### 2. Export Laporan Per Kelas (Excel)

```php
Route: admin.laporan.export-excel.per-kelas
URL: /admin/laporan/export-excel/per-kelas
Method: GET
Controller: AdminLaporanController@exportExcelPerKelas
```

**Sheet Columns:**

1. No
2. Nama Guru
3. Mata Pelajaran
4. Tanggal
5. Jam Masuk
6. Status Masuk
7. Jam Keluar
8. Status Keluar

---

#### 3. Export Rekap Bulanan (Excel)

```php
Route: admin.laporan.export-excel.rekap-bulanan
URL: /admin/laporan/export-excel/rekap-bulanan
Method: GET
Controller: AdminLaporanController@exportExcelRekapBulanan
```

**Sheet Columns:**

1. No
2. Nama Guru
3. NIP
4. Total Hari Kerja
5. Hadir Tepat Waktu
6. Hadir Terlambat
7. Izin
8. Sakit
9. Alpha
10. Persentase Kehadiran
11. Status

---

#### 4. Export Laporan Keterlambatan (Excel)

```php
Route: admin.laporan.export-excel.keterlambatan
URL: /admin/laporan/export-excel/keterlambatan
Method: GET
Controller: AdminLaporanController@exportExcelKeterlambatan
```

**Sheet Columns:**

1. No
2. Tanggal
3. Nama Guru
4. Jam Seharusnya
5. Jam Datang
6. Durasi Terlambat

---

## Dashboard Live Monitoring Route

```php
Route: admin.dashboard.live-guru-status
URL: /admin/dashboard/live-guru-status
Method: GET
Controller: AdminDashboardController@getLiveGuruStatus
```

**Purpose:** AJAX endpoint for real-time guru status monitoring

**Response Format:**

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

**Example AJAX Usage:**

```javascript
// Auto-refresh every 30 seconds
setInterval(function () {
    fetch('{{ route("admin.dashboard.live-guru-status") }}', {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                updateDashboardTable(data.data);
                updateSummaryStats(data.summary);
            }
        })
        .catch((error) => console.error("Error:", error));
}, 30000);
```

---

## Frontend Integration Examples

### Complete Export Button Group

```blade
<!-- resources/views/admin/laporan/per-guru.blade.php -->
<div class="card-header">
    <h5 class="card-title mb-0">Laporan Absensi - {{ $guru->nama }}</h5>
    <div class="btn-group mt-2" role="group">
        <!-- PDF Export -->
        <a href="{{ route('admin.laporan.export-pdf.per-guru', [
            'guru_id' => $guru->id,
            'bulan' => request('bulan', date('n')),
            'tahun' => request('tahun', date('Y'))
        ]) }}"
           class="btn btn-danger btn-sm"
           target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>

        <!-- Excel Export -->
        <a href="{{ route('admin.laporan.export-excel.per-guru', [
            'guru_id' => $guru->id,
            'bulan' => request('bulan', date('n')),
            'tahun' => request('tahun', date('Y'))
        ]) }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>

        <!-- Print View -->
        <button onclick="window.print()" class="btn btn-secondary btn-sm">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</div>
```

### Export with Loading Indicator

```blade
<button type="button"
        class="btn btn-danger"
        onclick="exportPDF(this)">
    <i class="fas fa-file-pdf"></i>
    <span class="btn-text">Export PDF</span>
    <span class="spinner-border spinner-border-sm d-none" role="status">
        <span class="visually-hidden">Loading...</span>
    </span>
</button>

<script>
function exportPDF(button) {
    // Show loading
    button.disabled = true;
    button.querySelector('.btn-text').textContent = 'Generating...';
    button.querySelector('.spinner-border').classList.remove('d-none');

    // Build URL with current filters
    const params = new URLSearchParams({
        guru_id: document.getElementById('guru_id').value,
        bulan: document.getElementById('bulan').value,
        tahun: document.getElementById('tahun').value
    });

    // Download file
    window.location.href = '{{ route("admin.laporan.export-pdf.per-guru") }}?' + params.toString();

    // Reset button after delay
    setTimeout(() => {
        button.disabled = false;
        button.querySelector('.btn-text').textContent = 'Export PDF';
        button.querySelector('.spinner-border').classList.add('d-none');
    }, 2000);
}
</script>
```

### Batch Export Multiple Reports

```blade
<form id="batchExportForm">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="reports[]" value="per_guru">
        <label class="form-check-label">Laporan Per Guru</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="reports[]" value="per_kelas">
        <label class="form-check-label">Laporan Per Kelas</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="reports[]" value="rekap_bulanan">
        <label class="form-check-label">Rekap Bulanan</label>
    </div>

    <button type="button" onclick="batchExport('pdf')" class="btn btn-danger mt-2">
        <i class="fas fa-download"></i> Download All as PDF
    </button>
    <button type="button" onclick="batchExport('excel')" class="btn btn-success mt-2">
        <i class="fas fa-download"></i> Download All as Excel
    </button>
</form>

<script>
function batchExport(format) {
    const form = document.getElementById('batchExportForm');
    const selected = Array.from(form.querySelectorAll('input[name="reports[]"]:checked'))
        .map(cb => cb.value);

    if (selected.length === 0) {
        alert('Pilih minimal 1 laporan');
        return;
    }

    // Download each report
    selected.forEach((reportType, index) => {
        setTimeout(() => {
            const url = format === 'pdf'
                ? `{{ url('/admin/laporan/export-pdf') }}/${reportType}`
                : `{{ url('/admin/laporan/export-excel') }}/${reportType}`;

            window.location.href = url + '?' + new URLSearchParams({
                bulan: document.getElementById('bulan').value,
                tahun: document.getElementById('tahun').value
            });
        }, index * 1000); // Delay 1 second between downloads
    });
}
</script>
```

---

## Middleware Protection

All export routes are protected by:

1. **Authentication:** User must be logged in
2. **Role Check:** User must have `admin` role
3. **Activity Logging:** All exports are logged via `log.activity` middleware

**Middleware Stack:**

```php
Route::middleware(['auth', 'role:admin', 'log.activity'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {
        // All export routes here
    });
```

---

## Error Handling

### Controller Level

```php
try {
    return $this->laporanService->generateLaporan($type, $params, $format);
} catch (\Exception $e) {
    return back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
}
```

### Frontend Level

```javascript
fetch(exportUrl)
    .then((response) => {
        if (!response.ok) {
            throw new Error("Export failed");
        }
        return response.blob();
    })
    .then((blob) => {
        // Download file
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = filename;
        a.click();
    })
    .catch((error) => {
        alert("Gagal export: " + error.message);
    });
```

---

## Performance Optimization

### Queue Large Exports

For reports with large datasets, queue the export job:

```php
// In controller
public function exportPdfRekapBulanan(Request $request)
{
    // For large datasets, dispatch job
    if ($this->isLargeDataset($request)) {
        dispatch(new GenerateLaporanJob(
            auth()->id(),
            'rekap_bulanan',
            $request->all(),
            'pdf'
        ));

        return back()->with('success',
            'Laporan sedang diproses. Anda akan menerima notifikasi via email.'
        );
    }

    // For small datasets, generate immediately
    return $this->laporanService->generateLaporan(
        'rekap_bulanan',
        $request->all(),
        'pdf'
    );
}
```

### Cache Frequently Accessed Reports

```php
// In LaporanService
public function generateLaporan($type, $params, $format)
{
    $cacheKey = "laporan_{$type}_{$format}_" . md5(json_encode($params));

    return Cache::remember($cacheKey, 300, function() use ($type, $params, $format) {
        // Generate report logic
    });
}
```

---

## Testing Checklist

-   [ ] Test all 4 PDF export routes with valid parameters
-   [ ] Test all 4 Excel export routes with valid parameters
-   [ ] Test exports with date range filters
-   [ ] Test exports with empty datasets
-   [ ] Test exports with large datasets (100+ records)
-   [ ] Verify PDF formatting and layout
-   [ ] Verify Excel column headers and styling
-   [ ] Test live guru status AJAX endpoint
-   [ ] Test middleware protection (non-admin access)
-   [ ] Test error handling (invalid parameters)
-   [ ] Verify filename timestamps are correct
-   [ ] Test concurrent export requests
-   [ ] Verify activity logging for all exports

---

## Troubleshooting

### Issue: PDF not downloading

**Solution:** Check DOMPDF configuration and memory limits

```bash
# Increase PHP memory limit
ini_set('memory_limit', '256M');

# Check DOMPDF config
php artisan vendor:publish --tag=dompdf-config
```

### Issue: Excel export error

**Solution:** Verify maatwebsite/excel installation

```bash
composer require maatwebsite/excel
php artisan config:clear
```

### Issue: Route not found

**Solution:** Clear route cache

```bash
php artisan route:clear
php artisan route:list --name=laporan
```

### Issue: Unauthorized access

**Solution:** Check user role and middleware

```php
// In web.php
Route::middleware(['auth', 'role:admin'])->group(function() {
    // Routes here
});
```

---

**Document Version:** 1.0  
**Last Updated:** November 18, 2025  
**Related Files:**

-   `routes/web.php` - Route definitions
-   `app/Http/Controllers/Admin/LaporanController.php` - Controller methods
-   `app/Services/LaporanService.php` - Business logic
-   `app/Exports/LaporanExport.php` - Excel export handler
