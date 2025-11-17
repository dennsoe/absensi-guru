# TESTING WORKFLOW GUIDE - Aplikasi Absensi Guru

**Created**: November 17, 2025  
**Status**: Complete Testing Guide  
**Purpose**: End-to-end workflow testing after rebuild

---

## 🎯 TESTING OVERVIEW

This guide provides step-by-step testing procedures for all workflows in the rebuilt application.

### Prerequisites

-   ✅ All 7 controllers rebuilt
-   ✅ Routes updated (67 routes)
-   ✅ Database seeded with test data
-   ✅ XAMPP/Laravel running

---

## 📋 TEST DATA REQUIREMENTS

### Users Needed

```sql
-- Admin
username: admin
password: password
role: admin

-- Guru
username: guru1
password: password
role: guru

-- Ketua Kelas
username: ketua1
password: password
role: ketua_kelas

-- Guru Piket
username: piket1
password: password
role: guru_piket

-- Kepala Sekolah
username: kepsek
password: password
role: kepala_sekolah

-- Kurikulum
username: kurikulum1
password: password
role: kurikulum
```

---

## 🧪 WORKFLOW 1: QR CODE ATTENDANCE

### Part A: Generate QR Code (Ketua Kelas)

#### Step 1: Login as Ketua Kelas

```
URL: http://localhost/absen-guru/
Username: ketua1
Password: password
```

**Expected**: Redirect to `/ketua-kelas/dashboard`

#### Step 2: Access QR Generation Page

```
URL: http://localhost/absen-guru/ketua-kelas/generate-qr
Method: GET
```

**Expected**:

-   Form with jadwal selection
-   List of today's schedules for the class
-   QR generation button

#### Step 3: Generate QR Code

```
URL: http://localhost/absen-guru/ketua-kelas/qr-code
Method: POST
Data: {
  jadwal_id: [select from dropdown]
}
```

**Expected**:

-   ✅ QR Code displayed (SVG 300x300)
-   ✅ Saved to `qr_codes` table
-   ✅ Fields: `qr_code` (UUID), `jadwal_id`, `expired_at` (+15 min), `is_used` (false)
-   ✅ Display expiry countdown

**Database Check**:

```sql
SELECT * FROM qr_codes
WHERE jadwal_id = [selected_id]
ORDER BY created_at DESC
LIMIT 1;
```

**Verify**:

-   `qr_code` = UUID format
-   `expired_at` = now + 15 minutes
-   `is_used` = 0
-   `kelas_id` = ketua kelas's kelas

---

### Part B: Scan QR Code (Guru)

#### Step 4: Login as Guru

```
URL: http://localhost/absen-guru/
Username: guru1
Password: password
```

**Expected**: Redirect to `/guru/dashboard`

#### Step 5: Access QR Scanner

```
URL: http://localhost/absen-guru/guru/absensi/scan-qr
Method: GET
```

**Expected**:

-   Camera permission request
-   QR scanner interface
-   Manual input option

#### Step 6: Scan QR Code

```
URL: http://localhost/absen-guru/guru/absensi/proses-qr
Method: POST
Data: {
  qr_code: [UUID from ketua kelas],
  latitude: -6.200000,
  longitude: 106.816666
}
```

**Expected Success**:

-   ✅ QR Code valid
-   ✅ Not expired
-   ✅ Not used yet
-   ✅ GPS within 200m
-   ✅ Mark QR as used (`is_used` = true, `used_by_guru_id`, `used_at`)
-   ✅ Save to `absensi` table
-   ✅ Status: `hadir` or `terlambat` (based on time)
-   ✅ Redirect with success message

**Database Check**:

```sql
-- Check QR is marked as used
SELECT * FROM qr_codes WHERE qr_code = '[UUID]';

-- Check absensi created
SELECT * FROM absensi
WHERE guru_id = [guru_id]
AND jadwal_id = [jadwal_id]
AND tanggal = CURDATE();
```

**Verify**:

-   QR: `is_used` = 1, `used_by_guru_id` set, `used_at` set
-   Absensi: status = 'hadir' or 'terlambat', metode = 'qr_code'

---

### Part C: Error Scenarios

#### Test 7: Expired QR

```
Wait 15+ minutes after generating QR
Try to scan the same QR
```

**Expected**: Error "QR Code sudah expired"

#### Test 8: Already Used QR

```
Try to scan the same QR twice
```

**Expected**: Error "QR Code sudah digunakan"

#### Test 9: Invalid GPS Location

```
POST with latitude/longitude outside 200m radius
```

**Expected**: Error "Anda berada di luar area sekolah"

---

## 🧪 WORKFLOW 2: SELFIE ATTENDANCE

### Part A: Capture Selfie (Guru)

#### Step 1: Login as Guru

```
URL: http://localhost/absen-guru/guru/dashboard
```

#### Step 2: Access Selfie Page

```
URL: http://localhost/absen-guru/guru/absensi/selfie
Method: GET
```

**Expected**:

-   Camera interface
-   GPS detection
-   Capture button

#### Step 3: Submit Selfie

```
URL: http://localhost/absen-guru/guru/absensi/proses-selfie
Method: POST
Data: {
  jadwal_id: [from dropdown],
  foto_base64: [base64 image],
  latitude: -6.200000,
  longitude: 106.816666
}
```

**Expected**:

-   ✅ GPS validated (200m)
-   ✅ Base64 converted to JPG
-   ✅ Saved to `storage/app/public/selfie/`
-   ✅ Save to `absensi` table
-   ✅ `validasi_ketua_kelas` = false (pending)
-   ✅ `ketua_kelas_user_id` set
-   ✅ Success message: "Selfie berhasil disubmit, menunggu validasi ketua kelas"

**Database Check**:

```sql
SELECT * FROM absensi
WHERE guru_id = [guru_id]
AND jadwal_id = [jadwal_id]
AND tanggal = CURDATE()
AND metode = 'selfie';
```

**Verify**:

-   `foto_selfie` path exists
-   `validasi_ketua_kelas` = 0
-   `ketua_kelas_user_id` set
-   File exists: `storage/app/public/selfie/[filename].jpg`

---

### Part B: Validate Selfie (Ketua Kelas)

#### Step 4: Login as Ketua Kelas

```
URL: http://localhost/absen-guru/ketua-kelas/dashboard
```

#### Step 5: View Pending Selfies

```
URL: http://localhost/absen-guru/ketua-kelas/validasi-selfie
Method: GET
```

**Expected**:

-   List of pending selfies (`validasi_ketua_kelas` = false)
-   Show: guru name, jadwal, photo, location, time
-   Approve/Reject buttons

#### Step 6a: Approve Selfie

```
URL: http://localhost/absen-guru/ketua-kelas/selfie/{id}/approve
Method: POST (AJAX)
```

**Expected**:

-   ✅ Update `validasi_ketua_kelas` = true
-   ✅ Return JSON: `{ success: true, message: "..." }`
-   ✅ Remove from pending list

**Database Check**:

```sql
SELECT * FROM absensi WHERE id = [id];
```

**Verify**: `validasi_ketua_kelas` = 1

#### Step 6b: Reject Selfie

```
URL: http://localhost/absen-guru/ketua-kelas/selfie/{id}/reject
Method: POST (AJAX)
Data: {
  alasan_penolakan: "Foto tidak jelas"
}
```

**Expected**:

-   ✅ Update status = 'alpha'
-   ✅ Set `alasan_penolakan`
-   ✅ Return JSON: `{ success: true, message: "..." }`

**Database Check**:

```sql
SELECT * FROM absensi WHERE id = [id];
```

**Verify**: `status` = 'alpha', `alasan_penolakan` set

---

## 🧪 WORKFLOW 3: MONITORING & MANUAL INPUT

### Part A: Real-time Monitoring (Guru Piket)

#### Step 1: Login as Guru Piket

```
URL: http://localhost/absen-guru/piket/dashboard
```

**Expected**:

-   Today's schedule with status indicators
-   3-level alerts: CRITICAL, WARNING, INFO
-   Statistics: total jadwal, hadir, terlambat, izin, alpha

#### Step 2: AJAX Monitoring

```
URL: http://localhost/absen-guru/piket/monitoring-absensi
Method: GET (AJAX)
```

**Expected JSON**:

```json
{
  "jadwal": [
    {
      "id": 1,
      "guru": { "nama": "..." },
      "kelas": { "nama_kelas": "..." },
      "jam_mulai": "07:00:00",
      "jam_selesai": "08:30:00",
      "status_deteksi": "berlangsung",
      "absensi": { "status": "hadir" } | null,
      "alert_level": "critical" | "warning" | "info"
    }
  ],
  "statistik": {
    "total_jadwal": 10,
    "hadir": 7,
    "terlambat": 1,
    "belum_absen": 2
  }
}
```

**Test Auto-refresh**:

-   Set interval 30 seconds
-   Verify data updates without page reload

---

### Part B: Manual Attendance Input

#### Step 3: Access Manual Input Form

```
URL: http://localhost/absen-guru/piket/absensi-manual
Method: GET
```

**Expected**:

-   Form with: guru, jadwal, status dropdown
-   Only today's jadwal yang belum ada absensi

#### Step 4: Submit Manual Attendance

```
URL: http://localhost/absen-guru/piket/absensi-manual
Method: POST
Data: {
  guru_id: [guru_id],
  jadwal_id: [jadwal_id],
  status: 'hadir' | 'terlambat' | 'izin' | 'sakit' | 'alpha',
  keterangan: 'Alasan jika alpha/izin'
}
```

**Expected**:

-   ✅ Save to `absensi` table
-   ✅ `metode` = 'manual'
-   ✅ `validasi_guru_piket` = true
-   ✅ `jam_absen` = current time
-   ✅ Success message

**Database Check**:

```sql
SELECT * FROM absensi
WHERE guru_id = [guru_id]
AND jadwal_id = [jadwal_id]
AND tanggal = CURDATE();
```

**Verify**: `metode` = 'manual', `validasi_guru_piket` = 1

---

## 🧪 WORKFLOW 4: APPROVAL (IZIN/CUTI)

### Part A: Submit Izin/Cuti (Guru)

#### Step 1: Login as Guru

```
URL: http://localhost/absen-guru/guru/izin/create
```

**Expected**: Form with fields

#### Step 2: Submit Request

```
POST Data: {
  jenis: 'izin' | 'sakit' | 'cuti' | 'dinas',
  tanggal_mulai: '2025-11-18',
  tanggal_selesai: '2025-11-18',
  alasan: 'Keperluan keluarga',
  surat_pendukung: [file] (optional)
}
```

**Expected**:

-   ✅ Save to `izin_cuti` table
-   ✅ `status` = 'pending'
-   ✅ `guru_id` set
-   ✅ Success message

---

### Part B: Review & Approve (Kepala Sekolah)

#### Step 3: Login as Kepala Sekolah

```
URL: http://localhost/absen-guru/kepsek/izin-cuti
```

**Expected**:

-   List with filter: all/pending/approved/rejected
-   Show: guru name, jenis, tanggal, alasan, status

#### Step 4: View Detail

```
URL: http://localhost/absen-guru/kepsek/izin-cuti/{id}
Method: GET
```

**Expected**:

-   Full details
-   Approve/Reject buttons
-   Surat pendukung (if exists)

#### Step 5a: Approve Request

```
URL: http://localhost/absen-guru/kepsek/izin-cuti/{id}/approve
Method: POST (AJAX)
```

**Expected**:

-   ✅ Update `status` = 'approved'
-   ✅ Set `approved_by` = kepala sekolah's user_id
-   ✅ Set `approved_at` = current timestamp
-   ✅ Return JSON: `{ success: true }`

**Database Check**:

```sql
SELECT * FROM izin_cuti WHERE id = [id];
```

**Verify**: `status` = 'approved', `approved_by` set, `approved_at` set

#### Step 5b: Reject Request

```
URL: http://localhost/absen-guru/kepsek/izin-cuti/{id}/reject
Method: POST (AJAX)
Data: {
  rejection_reason: "Tidak ada pengganti"
}
```

**Expected**:

-   ✅ Update `status` = 'rejected'
-   ✅ Set `rejection_reason`
-   ✅ Return JSON: `{ success: true }`

---

## 🧪 WORKFLOW 5: SCHEDULE MANAGEMENT & CONFLICTS

### Part A: Create Schedule (Kurikulum)

#### Step 1: Login as Kurikulum

```
URL: http://localhost/absen-guru/kurikulum/jadwal/create
```

**Expected**: Form with guru, kelas, mapel, hari, waktu

#### Step 2: Submit Valid Schedule

```
POST Data: {
  guru_id: 1,
  kelas_id: 1,
  mapel_id: 1,
  hari: 'Senin',
  jam_mulai: '07:00',
  jam_selesai: '08:30',
  status: 'aktif'
}
```

**Expected**:

-   ✅ No conflicts detected
-   ✅ Save to `jadwal_mengajar` table
-   ✅ Success message

---

### Part B: Test Conflict Detection

#### Test 1: Guru Conflict

```
POST Data: {
  guru_id: 1,  // SAME GURU
  kelas_id: 2,  // Different class
  mapel_id: 2,
  hari: 'Senin',  // SAME DAY
  jam_mulai: '07:30',  // OVERLAPS (07:00-08:30)
  jam_selesai: '09:00',
  status: 'aktif'
}
```

**Expected**:

-   ❌ Validation error
-   ❌ Message: "KONFLIK: Guru sudah mengajar di kelas [nama_kelas] pada [jam]"
-   ❌ Not saved to database

#### Test 2: Kelas Conflict

```
POST Data: {
  guru_id: 2,  // Different guru
  kelas_id: 1,  // SAME CLASS
  mapel_id: 2,
  hari: 'Senin',  // SAME DAY
  jam_mulai: '07:30',  // OVERLAPS
  jam_selesai: '09:00',
  status: 'aktif'
}
```

**Expected**:

-   ❌ Validation error
-   ❌ Message: "KONFLIK: Kelas sudah ada pelajaran [mapel] pada [jam]"

#### Test 3: No Conflict (Different Time)

```
POST Data: {
  guru_id: 1,
  kelas_id: 1,
  mapel_id: 1,
  hari: 'Senin',
  jam_mulai: '09:00',  // After 08:30 (no overlap)
  jam_selesai: '10:30',
  status: 'aktif'
}
```

**Expected**:

-   ✅ No conflicts
-   ✅ Saved successfully

---

### Part C: Conflict Detection on Update

#### Step 3: Update Existing Schedule

```
URL: http://localhost/absen-guru/kurikulum/jadwal/{id}/edit
PUT Data: {
  jam_mulai: '08:00',  // Changed to overlap
  jam_selesai: '09:30'
}
```

**Expected**:

-   Re-check conflicts (excluding current jadwal)
-   Same validation as create

---

## 🧪 WORKFLOW 6: ADMIN MANAGEMENT

### Part A: User Management

#### Test 1: Create User

```
URL: http://localhost/absen-guru/admin/users/create
POST Data: {
  username: 'test_user',
  password: 'password',
  password_confirmation: 'password',
  nama: 'Test User',
  email: 'test@example.com',
  role: 'guru',
  guru_id: [if role = guru],
  status: 'aktif'
}
```

**Expected**: User created, redirect to list

#### Test 2: Cannot Delete Last Admin

```
URL: http://localhost/absen-guru/admin/users/{admin_id}
Method: DELETE
```

**Expected**: Error if only 1 admin exists

---

### Part B: Master Data CRUD

#### Test Guru CRUD

```
Create: POST /admin/guru
Edit:   PUT /admin/guru/{id}
Delete: DELETE /admin/guru/{id} (check protection)
```

#### Test Kelas CRUD

```
Create: POST /admin/kelas
Edit:   PUT /admin/kelas/{id}
Delete: DELETE /admin/kelas/{id} (check protection)
```

#### Test Mata Pelajaran CRUD

```
Create: POST /admin/mata-pelajaran
Edit:   PUT /admin/mata-pelajaran/{id}
Delete: DELETE /admin/mata-pelajaran/{id} (check protection)
```

**Protection Tests**:

-   Cannot delete guru with jadwal
-   Cannot delete kelas with jadwal
-   Cannot delete mapel with jadwal

---

## 📊 REPORTS TESTING

### Kepala Sekolah Reports

#### Laporan Kehadiran

```
URL: /kepsek/laporan/kehadiran?bulan=11&tahun=2025
```

**Expected**:

-   Per-guru monthly statistics
-   Percentages (hadir, terlambat, izin, alpha)
-   Summary totals

#### Laporan Kedisiplinan

```
URL: /kepsek/laporan/kedisiplinan?bulan=11&tahun=2025
```

**Expected**:

-   Ranking based on violations
-   Categories: Sangat Baik, Baik, Cukup, Perlu Perhatian
-   Alpha + Terlambat counts

---

### Kurikulum Reports

#### Laporan Akademik

```
URL: /kurikulum/laporan-akademik?bulan=11&tahun=2025
```

**Expected**:

-   Per-guru teaching statistics
-   Total jadwal, total mengajar, percentage

---

### Guru Piket Reports

#### Laporan Harian

```
URL: /piket/laporan-harian?tanggal=2025-11-17
```

**Expected**:

-   Daily summary
-   All jadwal with attendance status
-   Statistics

---

## ✅ AUTHORIZATION TESTING

### Test Role-Based Access

```bash
# Login as Guru, try to access:
GET /admin/dashboard           → Expected: Redirect + error
GET /kepsek/dashboard          → Expected: Redirect + error
GET /kurikulum/dashboard       → Expected: Redirect + error

# Login as Admin, try to access:
GET /admin/dashboard           → Expected: Success ✅
GET /kepsek/dashboard          → Expected: Redirect + error

# Login as Kepala Sekolah:
GET /kepsek/dashboard          → Expected: Success ✅
GET /admin/dashboard           → Expected: Redirect + error
```

---

## 🐛 ERROR SCENARIOS TO TEST

### QR Code Errors

-   [x] Expired QR (>15 min)
-   [x] Already used QR
-   [x] Invalid QR format
-   [x] GPS outside radius

### Selfie Errors

-   [x] GPS outside radius
-   [x] Invalid image format
-   [x] Missing jadwal_id

### Schedule Errors

-   [x] Guru time overlap
-   [x] Kelas time overlap
-   [x] Invalid time range (end before start)

### Authorization Errors

-   [x] Access route without permission
-   [x] Delete last admin
-   [x] Delete data with references

---

## 📝 TESTING CHECKLIST

### Workflows

-   [ ] QR Code Flow (Generate → Scan → Mark Used)
-   [ ] Selfie Flow (Capture → Pending → Approve/Reject)
-   [ ] Monitoring Flow (Real-time → Manual Input)
-   [ ] Approval Flow (Submit → Review → Approve/Reject)
-   [ ] Schedule Flow (Create → Conflict Check → Save)

### CRUD Operations

-   [ ] User Management (Admin)
-   [ ] Guru Management (Admin)
-   [ ] Kelas Management (Admin)
-   [ ] Mata Pelajaran Management (Admin)
-   [ ] Jadwal Management (Kurikulum)

### Reports

-   [ ] Laporan Kehadiran (Kepala Sekolah)
-   [ ] Laporan Kedisiplinan (Kepala Sekolah)
-   [ ] Laporan Akademik (Kurikulum)
-   [ ] Laporan Harian (Guru Piket)

### Security

-   [ ] Authorization checks
-   [ ] CSRF protection
-   [ ] SQL injection prevention
-   [ ] Password hashing

### Performance

-   [ ] AJAX real-time updates
-   [ ] Database query optimization
-   [ ] Image upload/processing
-   [ ] QR generation speed

---

## 🎯 SUCCESS CRITERIA

All workflows should:

1. ✅ Execute without errors
2. ✅ Update database correctly
3. ✅ Show appropriate messages
4. ✅ Maintain data integrity
5. ✅ Enforce authorization
6. ✅ Handle errors gracefully
7. ✅ Provide good UX

---

**Testing Guide Complete**: November 17, 2025 ✅  
**Ready for comprehensive testing** 🧪  
**Good luck!** 🍀
