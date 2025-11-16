# SKEMA APLIKASI ABSENSI GURU

## 📋 INFORMASI UMUM

**Nama Aplikasi:** Sistem Absensi Guru  
**Platform:** Aplikasi Web PWA (Progressive Web App) - PHP MVC  
**Deployment:** Fleksibel - Shared Hosting / VPS / Cloud Platform  
**Akses:** **WAJIB melalui PWA** (Install di device sebagai app)  
**Hari Mengajar:** Senin - Jumat  
**Metode Absensi:** Kode QR / Swafoto (Dapat Diatur)  
**Responsif:** ✅ Mobile-First Design (100% support semua device)  
**Offline Support:** ✅ Cache & Background Sync  
**Push Notification:** ✅ Real-time notification via PWA

---

## 👥 ROLE & HAK AKSES

### 1. ADMIN

**Fungsi Utama:** Mengelola seluruh sistem

**Hak Akses:**

- ✅ CRUD data master (guru, kelas, mata pelajaran, jadwal)
- ✅ Atur metode absensi (QR/Swafoto/Keduanya)
- ✅ Kelola guru piket & ketua kelas
- ✅ Setuju/tolak izin, sakit, cuti, dinas
- ✅ Tugaskan guru pengganti
- ✅ Pantau waktu nyata semua absensi
- ✅ Ekspor laporan (Excel/PDF)
- ✅ **Halaman Pengaturan Lengkap**
  - Pengaturan Umum (nama sekolah, logo, alamat, kontak)
  - Pengaturan Absensi (metode, toleransi waktu, masa berlaku QR)
  - Pengaturan GPS (koordinat, radius, aktif/nonaktif)
  - Pengaturan Notifikasi (waktu, saluran, templat)
  - Pengaturan Email (SMTP, kredensial, templat)
  - Pengaturan WhatsApp (kunci API, endpoint, templat)
  - Pengaturan Surat Peringatan (ambang batas SP1/SP2/SP3, templat)
  - Pengaturan Keamanan (batas percobaan, batas waktu sesi, kebijakan kata sandi)
  - Pengaturan Cadangan (cadangan otomatis, jadwal, retensi)
  - Pengaturan Tahun Ajaran & Semester
  - Pengaturan Jam Kerja & Toleransi
  - Impor/Ekspor Pengaturan (JSON)
- ✅ Lihat log audit
- ✅ Setujui absensi manual dari guru piket
- ✅ Kelola kalender libur (tambah/ubah/hapus hari libur)
- ✅ Cadangkan & pulihkan basis data manual
- ✅ Kelola kunci API untuk integrasi eksternal

---

### 2. GURU

**Fungsi Utama:** Absensi mengajar

**Hak Akses:**

- ✅ Lihat jadwal mengajar pribadi
- ✅ Pemberitahuan 15 menit sebelum jam mengajar
- ✅ Absensi masuk (Kode QR / Swafoto)
- ✅ Absensi keluar kelas
- ✅ Isi keterangan tidak hadir (Sakit/Izin/Dinas/Cuti) dengan unggah dokumen
- ✅ Lihat riwayat absensi pribadi
- ✅ Perbarui profil pribadi

---

### 3. KETUA KELAS

**Fungsi Utama:** Validasi kehadiran guru di kelas

**Hak Akses:**

- ✅ Pindai Kode QR guru (jika metode QR aktif)
- ✅ Masukkan tanda tangan digital/PIN setelah pindai
- ✅ Lihat riwayat absensi guru di kelasnya
- ✅ Lihat jadwal kelas hari ini

---

### 4. GURU PIKET

**Fungsi Utama:** Monitoring & koordinasi kehadiran guru

**Hak Akses:**

- ✅ Pantau kehadiran semua guru waktu nyata
- ✅ Lihat jadwal mengajar semua guru hari ini
- ✅ Masukkan absensi manual (perlu persetujuan admin)
- ✅ Hubungi guru yang bermasalah (akses kontak)
- ✅ Buat laporan harian piket
- ✅ Lihat riwayat tugas piket
- ❌ TIDAK menggantikan guru mengajar
- ❌ TIDAK ubah/hapus data induk

---

### 5. KEPALA SEKOLAH

**Fungsi Utama:** Monitoring & pengambilan keputusan strategis

**Hak Akses:**

- ✅ Dasbor pemantauan waktu nyata seluruh kehadiran
- ✅ Laporan eksekutif (ringkasan statistik)
- ✅ Grafik analitik kehadiran guru
- ✅ Setujui izin/sakit/cuti guru
- ✅ Lihat laporan harian dari guru piket
- ✅ Ekspor laporan lengkap
- ✅ Pemberitahuan otomatis untuk masalah kritis
- ✅ Lihat kinerja kehadiran per guru/mapel/kelas
- ❌ TIDAK ubah data induk (baca saja)

---

### 6. KURIKULUM

**Fungsi Utama:** Manajemen akademik & penjadwalan

**Hak Akses:**

- ✅ Kelola jadwal mengajar (Tambah/Ubah/Hapus)
- ✅ Tugaskan guru pengganti ke jadwal
- ✅ Setuju/tolak izin/sakit/cuti guru
- ✅ Pantau kehadiran per mata pelajaran
- ✅ Laporan akademik (jam mengajar, efektivitas)
- ✅ Ubah data mata pelajaran & kelas
- ✅ Koordinasi dengan guru untuk penjadwalan ulang
- ✅ Ekspor laporan kehadiran per mapel
- ❌ TIDAK atur sistem (hanya admin)

---

## 📊 STATUS KEHADIRAN

### Status Absensi:

1. **Hadir** - Guru absen tepat waktu (sesuai jadwal)
2. **Terlambat** - Guru absen melebihi toleransi waktu (bawaan: 15 menit)
3. **Sakit** - Guru tidak hadir dengan surat keterangan sakit (perlu unggah dokumen + persetujuan)
4. **Izin** - Guru tidak hadir dengan surat izin (perlu unggah dokumen + persetujuan)
5. **Dinas** - Guru tidak hadir karena tugas dinas (perlu surat tugas + persetujuan)
6. **Cuti** - Guru mengambil cuti (perlu pengajuan sebelumnya + persetujuan)
7. **Alfa** - Guru tidak hadir tanpa keterangan

---

## 🗄️ STRUKTUR DATABASE

### Tabel 1: users

```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','guru','ketua_kelas','guru_piket','kepala_sekolah','kurikulum') NOT NULL,
  is_active ENUM('aktif','non_aktif') DEFAULT 'aktif',
  last_login TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel 2: guru

```sql
CREATE TABLE guru (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  nip VARCHAR(20) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  jenis_kelamin ENUM('L','P') NOT NULL,
  tempat_lahir VARCHAR(50),
  tanggal_lahir DATE,
  alamat TEXT,
  no_hp VARCHAR(15),
  email VARCHAR(100),
  foto VARCHAR(255),
  status ENUM('aktif','non_aktif','cuti') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel 3: kelas

```sql
CREATE TABLE kelas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama_kelas VARCHAR(50) NOT NULL,
  tingkat INT NOT NULL,
  jurusan VARCHAR(50),
  tahun_ajaran VARCHAR(20) NOT NULL,
  wali_kelas_id INT,
  ruangan VARCHAR(50),
  kapasitas INT DEFAULT 32,
  status ENUM('aktif','non_aktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (wali_kelas_id) REFERENCES guru(id) ON DELETE SET NULL
);
```

### Tabel 4: ketua_kelas

```sql
CREATE TABLE ketua_kelas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  kelas_id INT NOT NULL,
  nama_siswa VARCHAR(100) NOT NULL,
  nisn VARCHAR(20) NOT NULL,
  no_hp VARCHAR(15),
  periode_mulai DATE NOT NULL,
  periode_selesai DATE,
  status ENUM('aktif','non_aktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE
);
```

### Tabel 5: guru_piket

```sql
CREATE TABLE guru_piket (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  tahun_ajaran VARCHAR(20) NOT NULL,
  status ENUM('aktif','non_aktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  UNIQUE KEY unique_piket (guru_id, hari, tahun_ajaran)
);
```

### Tabel 6: mata_pelajaran

```sql
CREATE TABLE mata_pelajaran (
  id INT PRIMARY KEY AUTO_INCREMENT,
  kode VARCHAR(20) UNIQUE NOT NULL,
  nama VARCHAR(100) NOT NULL,
  kelompok ENUM('Umum','Peminatan','Lintas Minat') NOT NULL,
  deskripsi TEXT,
  status ENUM('aktif','non_aktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel 7: jadwal_mengajar

```sql
CREATE TABLE jadwal_mengajar (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  kelas_id INT NOT NULL,
  mapel_id INT NOT NULL,
  hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  jam_mulai TIME NOT NULL,
  jam_selesai TIME NOT NULL,
  ruangan VARCHAR(50),
  tahun_ajaran VARCHAR(20) NOT NULL,
  semester ENUM('1','2') NOT NULL,
  status ENUM('aktif','non_aktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
  FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE
);
```

### Tabel 8: jadwal_piket

```sql
CREATE TABLE jadwal_piket (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_piket_id INT NOT NULL,
  tanggal DATE NOT NULL,
  jam_mulai TIME DEFAULT '07:00:00',
  jam_selesai TIME DEFAULT '16:00:00',
  status ENUM('scheduled','on_duty','completed') DEFAULT 'scheduled',
  catatan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_piket_id) REFERENCES guru_piket(id) ON DELETE CASCADE,
  UNIQUE KEY unique_jadwal (guru_piket_id, tanggal)
);
```

### Tabel 9: absensi

```sql
CREATE TABLE absensi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  jadwal_id INT NOT NULL,
  guru_id INT NOT NULL,
  tanggal DATE NOT NULL,
  jam_absen_masuk TIME NOT NULL,
  metode_absen ENUM('qr_code','selfie','manual') NOT NULL,
  foto_selfie VARCHAR(255),
  qr_token VARCHAR(255),
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  status_kehadiran ENUM('hadir','terlambat','sakit','izin','dinas','cuti','alfa') DEFAULT 'hadir',
  keterlambatan_menit INT DEFAULT 0,
  validasi_ketua_kelas_id INT,
  tanda_tangan_digital TEXT,
  validasi_at TIMESTAMP NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (validasi_ketua_kelas_id) REFERENCES ketua_kelas(id) ON DELETE SET NULL
);
```

### Tabel 10: absensi_keluar

```sql
CREATE TABLE absensi_keluar (
  id INT PRIMARY KEY AUTO_INCREMENT,
  absensi_id INT NOT NULL,
  jam_keluar TIME NOT NULL,
  durasi_mengajar_menit INT NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (absensi_id) REFERENCES absensi(id) ON DELETE CASCADE
);
```

### Tabel 11: keterangan_tidak_hadir

```sql
CREATE TABLE keterangan_tidak_hadir (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  jadwal_id INT,
  tanggal_mulai DATE NOT NULL,
  tanggal_selesai DATE NOT NULL,
  jenis ENUM('sakit','izin','dinas','cuti') NOT NULL,
  keterangan TEXT NOT NULL,
  dokumen_pendukung VARCHAR(255),
  status_approval ENUM('pending','approved','rejected') DEFAULT 'pending',
  approved_by INT,
  approved_at TIMESTAMP NULL,
  alasan_reject TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE SET NULL,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Tabel 12: guru_pengganti

```sql
CREATE TABLE guru_pengganti (
  id INT PRIMARY KEY AUTO_INCREMENT,
  jadwal_id INT NOT NULL,
  guru_asli_id INT NOT NULL,
  guru_pengganti_id INT NOT NULL,
  tanggal DATE NOT NULL,
  alasan TEXT NOT NULL,
  assigned_by INT NOT NULL,
  status ENUM('assigned','completed','cancelled') DEFAULT 'assigned',
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_asli_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_pengganti_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel 13: qr_codes

```sql
CREATE TABLE qr_codes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  jadwal_id INT NOT NULL,
  token VARCHAR(255) UNIQUE NOT NULL,
  signature VARCHAR(255) NOT NULL,
  expired_at TIMESTAMP NOT NULL,
  is_used ENUM('yes','no') DEFAULT 'no',
  scanned_by INT,
  scanned_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE CASCADE,
  FOREIGN KEY (scanned_by) REFERENCES ketua_kelas(id) ON DELETE SET NULL
);
```

### Tabel 14: notifikasi

```sql
CREATE TABLE notifikasi (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  guru_id INT,
  jadwal_id INT,
  jenis ENUM('jadwal_mengajar','reminder_absen','absen_manual','guru_pengganti','approval','system') NOT NULL,
  judul VARCHAR(255) NOT NULL,
  pesan TEXT NOT NULL,
  is_read ENUM('yes','no') DEFAULT 'no',
  link VARCHAR(255),
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  read_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE SET NULL,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE SET NULL
);
```

### Tabel 15: audit_log

```sql
CREATE TABLE audit_log (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  action VARCHAR(50) NOT NULL,
  table_name VARCHAR(50) NOT NULL,
  record_id INT,
  old_value TEXT,
  new_value TEXT,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel 16: settings

```sql
CREATE TABLE settings (
  id INT PRIMARY KEY AUTO_INCREMENT,
  setting_key VARCHAR(100) UNIQUE NOT NULL,
  setting_value TEXT NOT NULL,
  description TEXT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel 17: absensi_manual

```sql
CREATE TABLE absensi_manual (
  id INT PRIMARY KEY AUTO_INCREMENT,
  absensi_id INT NOT NULL,
  input_by INT NOT NULL,
  alasan TEXT NOT NULL,
  bukti_file VARCHAR(255),
  status_approval ENUM('pending','approved','rejected') DEFAULT 'pending',
  approved_by INT,
  approved_at TIMESTAMP NULL,
  alasan_reject TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (absensi_id) REFERENCES absensi(id) ON DELETE CASCADE,
  FOREIGN KEY (input_by) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Tabel 18: laporan_piket

```sql
CREATE TABLE laporan_piket (
  id INT PRIMARY KEY AUTO_INCREMENT,
  jadwal_piket_id INT NOT NULL,
  total_guru INT NOT NULL,
  total_hadir INT NOT NULL,
  total_terlambat INT NOT NULL,
  total_izin INT NOT NULL,
  total_sakit INT NOT NULL,
  total_cuti INT NOT NULL,
  total_dinas INT NOT NULL,
  total_alfa INT NOT NULL,
  catatan_khusus TEXT,
  pdf_file VARCHAR(255),
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jadwal_piket_id) REFERENCES jadwal_piket(id) ON DELETE CASCADE
);
```

### Tabel 19: kalender_libur

```sql
CREATE TABLE kalender_libur (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tanggal DATE NOT NULL UNIQUE,
  nama_libur VARCHAR(100) NOT NULL,
  jenis ENUM('nasional','sekolah','cuti_bersama') NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel 20: rekap_jam_mengajar

```sql
CREATE TABLE rekap_jam_mengajar (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  bulan INT NOT NULL,
  tahun INT NOT NULL,
  total_jam_jadwal DECIMAL(5,2) NOT NULL DEFAULT 0,
  total_jam_efektif DECIMAL(5,2) NOT NULL DEFAULT 0,
  total_jam_pengganti DECIMAL(5,2) NOT NULL DEFAULT 0,
  total_jam_alfa DECIMAL(5,2) NOT NULL DEFAULT 0,
  persentase_kehadiran DECIMAL(5,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  UNIQUE KEY unique_rekap (guru_id, bulan, tahun)
);
```

### Tabel 21: tukar_jadwal

```sql
CREATE TABLE tukar_jadwal (
  id INT PRIMARY KEY AUTO_INCREMENT,
  jadwal_id INT NOT NULL,
  guru_pemberi_id INT NOT NULL,
  guru_penerima_id INT NOT NULL,
  tanggal DATE NOT NULL,
  alasan TEXT NOT NULL,
  status ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',
  approved_by INT,
  approved_at TIMESTAMP NULL,
  alasan_reject TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jadwal_id) REFERENCES jadwal_mengajar(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_pemberi_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (guru_penerima_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Tabel 22: surat_peringatan

```sql
CREATE TABLE surat_peringatan (
  id INT PRIMARY KEY AUTO_INCREMENT,
  guru_id INT NOT NULL,
  jenis_sp ENUM('SP1','SP2','SP3') NOT NULL,
  nomor_surat VARCHAR(50) NOT NULL,
  tanggal_surat DATE NOT NULL,
  alasan TEXT NOT NULL,
  total_pelanggaran INT NOT NULL,
  periode_mulai DATE NOT NULL,
  periode_selesai DATE NOT NULL,
  pdf_file VARCHAR(255),
  status ENUM('draft','issued','acknowledged') DEFAULT 'draft',
  issued_by INT NOT NULL,
  issued_at TIMESTAMP NULL,
  acknowledged_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
  FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel 23: broadcast_message

```sql
CREATE TABLE broadcast_message (
  id INT PRIMARY KEY AUTO_INCREMENT,
  judul VARCHAR(255) NOT NULL,
  pesan TEXT NOT NULL,
  jenis ENUM('info','warning','urgent') DEFAULT 'info',
  target_role VARCHAR(255),
  target_guru_ids TEXT,
  sent_by INT NOT NULL,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sent_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Tabel 24: notifikasi_preferences

```sql
CREATE TABLE notifikasi_preferences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  notif_web ENUM('yes','no') DEFAULT 'yes',
  notif_email ENUM('yes','no') DEFAULT 'yes',
  notif_whatsapp ENUM('yes','no') DEFAULT 'no',
  email_address VARCHAR(100),
  whatsapp_number VARCHAR(15),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user (user_id)
);
```

### Tabel 25: api_keys

```sql
CREATE TABLE api_keys (
  id INT PRIMARY KEY AUTO_INCREMENT,
  service_name VARCHAR(100) NOT NULL,
  api_key VARCHAR(255) NOT NULL,
  api_secret VARCHAR(255),
  endpoint_url VARCHAR(255),
  status ENUM('active','inactive') DEFAULT 'active',
  last_used TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Tabel 26: backup_history

```sql
CREATE TABLE backup_history (
  id INT PRIMARY KEY AUTO_INCREMENT,
  backup_type ENUM('manual','automatic') NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_size BIGINT NOT NULL,
  backup_path VARCHAR(255) NOT NULL,
  status ENUM('success','failed','in_progress') DEFAULT 'in_progress',
  error_message TEXT,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Tabel 27: settings_categories

```sql
CREATE TABLE settings_categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(50) NOT NULL UNIQUE,
  category_label VARCHAR(100) NOT NULL,
  icon VARCHAR(50),
  display_order INT DEFAULT 0,
  is_active ENUM('yes','no') DEFAULT 'yes',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel 28: settings_extended

```sql
CREATE TABLE settings_extended (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_id INT NOT NULL,
  setting_key VARCHAR(100) NOT NULL UNIQUE,
  setting_label VARCHAR(200) NOT NULL,
  setting_value TEXT NOT NULL,
  setting_type ENUM('text','number','boolean','email','url','json','file','select','textarea') DEFAULT 'text',
  setting_options TEXT,
  validation_rules TEXT,
  help_text TEXT,
  display_order INT DEFAULT 0,
  is_required ENUM('yes','no') DEFAULT 'no',
  is_encrypted ENUM('yes','no') DEFAULT 'no',
  updated_by INT,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES settings_categories(id) ON DELETE CASCADE,
  FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 🔧 DATA SETTINGS (KEY-VALUE)

### Default Settings dengan Kategori:

#### **1. Pengaturan Umum Sekolah**

```json
{
  "nama_sekolah": "SMA Negeri 1 Jakarta",
  "npsn": "12345678",
  "alamat_sekolah": "Jl. Pendidikan No. 123, Jakarta",
  "telepon_sekolah": "021-1234567",
  "email_sekolah": "info@sman1jkt.sch.id",
  "website_sekolah": "https://sman1jkt.sch.id",
  "logo_sekolah": "/assets/images/logo.png",
  "kepala_sekolah": "Dr. Ahmad Sutanto, M.Pd",
  "nip_kepala_sekolah": "196501011990031001"
}
```

#### **2. Pengaturan Tahun Ajaran**

```json
{
  "tahun_ajaran_aktif": "2025/2026",
  "semester_aktif": "1",
  "tanggal_mulai_semester": "2025-07-01",
  "tanggal_selesai_semester": "2025-12-31",
  "jumlah_minggu_efektif": 20
}
```

#### **3. Pengaturan Absensi**

```json
{
  "metode_qr": true,
  "metode_selfie": true,
  "qr_expired_minutes": 5,
  "qr_refresh_auto": true,
  "toleransi_keterlambatan_menit": 15,
  "absensi_before_minutes": 30,
  "absensi_after_minutes": 60,
  "wajib_absensi_keluar": true,
  "foto_selfie_required": true,
  "max_selfie_size_mb": 5,
  "selfie_compression_quality": 75
}
```

#### **4. Pengaturan GPS**

```json
{
  "gps_enabled": true,
  "gps_latitude_sekolah": "-6.200000",
  "gps_longitude_sekolah": "106.816666",
  "gps_radius_meter": 100,
  "gps_strict_mode": false,
  "gps_show_map": true
}
```

#### **5. Pengaturan Notifikasi**

```json
{
  "notifikasi_web_enabled": true,
  "notifikasi_sebelum_jam_mengajar_menit": 15,
  "reminder_belum_absen_menit": 10,
  "notifikasi_kritis_menit": 30,
  "notifikasi_ke_guru_piket": true,
  "notifikasi_ke_kepala_sekolah": true,
  "notifikasi_ke_kurikulum": true,
  "ajax_polling_interval_seconds": 30
}
```

#### **6. Pengaturan Email**

```json
{
  "email_enabled": false,
  "email_smtp_host": "smtp.gmail.com",
  "email_smtp_port": 587,
  "email_smtp_secure": "tls",
  "email_username": "",
  "email_password": "",
  "email_from_name": "Sistem Absensi Guru",
  "email_from_address": "noreply@sekolah.id",
  "email_template_header": "Template header HTML",
  "email_template_footer": "Template footer HTML"
}
```

#### **7. Pengaturan WhatsApp**

```json
{
  "whatsapp_enabled": false,
  "whatsapp_provider": "fonnte",
  "whatsapp_api_key": "",
  "whatsapp_api_url": "https://api.fonnte.com/send",
  "whatsapp_sender_number": "",
  "whatsapp_template_reminder": "Template pesan reminder",
  "whatsapp_template_approval": "Template pesan approval",
  "whatsapp_template_alert": "Template pesan alert"
}
```

#### **8. Pengaturan Surat Peringatan**

```json
{
  "sp_enabled": true,
  "sp1_threshold": 3,
  "sp2_threshold": 5,
  "sp3_threshold": 7,
  "sp_periode_hari": 30,
  "sp_auto_generate": true,
  "sp_nomor_format": "SP-{jenis}/{nomor}/KS/{bulan}/{tahun}",
  "sp_template_header": "Template header surat",
  "sp_template_footer": "Template footer surat",
  "sp_ttd_kepala_sekolah": true
}
```

#### **9. Pengaturan Security**

```json
{
  "max_login_attempts": 5,
  "login_lockout_minutes": 15,
  "session_timeout_minutes": 30,
  "password_min_length": 8,
  "password_require_uppercase": true,
  "password_require_number": true,
  "password_require_special": false,
  "force_password_change_days": 90,
  "enable_2fa": false,
  "enable_ip_whitelist": false,
  "allowed_ips": ""
}
```

#### **10. Pengaturan Backup**

```json
{
  "auto_backup_enabled": true,
  "backup_time": "23:00:00",
  "backup_frequency": "daily",
  "backup_retention_days": 30,
  "backup_location": "/backup/",
  "backup_include_uploads": true,
  "backup_compress": true,
  "backup_email_notification": true,
  "backup_email_recipients": "admin@sekolah.id"
}
```

#### **11. Pengaturan Jam Kerja**

```json
{
  "jam_masuk_sekolah": "07:00:00",
  "jam_pulang_sekolah": "16:00:00",
  "hari_kerja": ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"],
  "durasi_jam_pelajaran_menit": 45,
  "durasi_istirahat_menit": 15,
  "jam_istirahat_1": "09:30:00",
  "jam_istirahat_2": "12:00:00"
}
```

#### **12. Pengaturan Laporan**

```json
{
  "laporan_default_format": "excel",
  "laporan_include_foto": true,
  "laporan_watermark": true,
  "laporan_auto_email": false,
  "laporan_auto_email_schedule": "monthly",
  "laporan_recipients": "kepsek@sekolah.id,admin@sekolah.id",
  "laporan_logo": true,
  "laporan_ttd_digital": true
}
```

#### **13. Pengaturan Sistem**

```json
{
  "timezone": "Asia/Jakarta",
  "date_format": "d-m-Y",
  "time_format": "H:i:s",
  "language": "id",
  "maintenance_mode": false,
  "maintenance_message": "Sistem sedang dalam perbaikan",
  "debug_mode": false,
  "log_level": "error",
  "max_file_upload_mb": 5,
  "allowed_file_types": "jpg,jpeg,png,pdf"
}
```

#### **14. Pengaturan API & Integrasi**

```json
{
  "api_enabled": false,
  "api_require_auth": true,
  "api_rate_limit": 100,
  "api_rate_limit_window_minutes": 60,
  "webhook_enabled": false,
  "webhook_url": "",
  "webhook_secret": "",
  "sync_with_siakad": false,
  "siakad_api_url": "",
  "siakad_api_key": ""
}
```

---

## 🎯 FLOW PROSES BISNIS

### ALUR 1: Absensi Guru (Kode QR)

```
1. Guru masuk → Dasbor
2. Pemberitahuan muncul 15 menit sebelum jam mengajar
3. Guru klik "Mulai Absensi"
4. Sistem cek metode aktif (Kode QR)
5. Buat Kode QR:
   - Token unik (hash dari jadwal_id + timestamp + random)
   - Tanda tangan untuk validasi
   - Masa berlaku: 5 menit
6. Kode QR tampil di layar guru (layar penuh)
7. Ketua kelas buka menu "Pindai Absensi"
8. Pindai Kode QR dengan kamera HP
9. Sistem validasi:
   ✓ QR valid & belum kedaluwarsa?
   ✓ Token belum pernah digunakan?
   ✓ Jadwal sesuai dengan waktu sekarang?
   ✓ Ketua kelas sesuai dengan ruangan/kelas?
   ✓ Lokasi GPS dalam radius sekolah? (jika aktif)
10. Ketua kelas masukkan tanda tangan digital/PIN
11. Absensi tersimpan dengan status "hadir" atau "terlambat"
12. Pemberitahuan ke guru: "Absensi Berhasil ✅"
13. Kode QR ditandai "is_used = yes"
```

### ALUR 2: Absensi Guru (Swafoto)

```
1. Guru masuk → Dasbor
2. Pemberitahuan muncul 15 menit sebelum jam mengajar
3. Guru klik "Mulai Absensi"
4. Sistem cek metode aktif (Swafoto)
5. Buka kamera HP (HTML5 Camera API)
6. Guru ambil foto swafoto
7. Pratinjau foto sebelum kirim
8. Sistem validasi:
   ✓ File adalah gambar?
   ✓ Ukuran file tidak melebihi batas?
   ✓ Lokasi GPS dalam radius sekolah? (jika aktif)
9. Kompres gambar (ubah ukuran ke 800x600px)
10. Unggah & simpan foto ke server
11. Absensi tersimpan dengan status "hadir" atau "terlambat"
12. Pemberitahuan: "Absensi Berhasil ✅"
```

### ALUR 3: Guru Tidak Hadir (Izin/Sakit/Cuti/Dinas)

```
1. Guru masuk → Menu "Keterangan Tidak Hadir"
2. Pilih jenis: Sakit / Izin / Dinas / Cuti
3. Masukkan:
   - Tanggal mulai & selesai
   - Keterangan
   - Unggah dokumen pendukung (PDF/JPG)
4. Kirim pengajuan (status: menunggu)
5. Pemberitahuan ke Admin/Kepala Sekolah/Kurikulum
6. Admin/Kepala Sekolah/Kurikulum tinjau:
   - Setujui → Status diubah jadi "disetujui"
   - Tolak → Status "ditolak" + alasan
7. Pemberitahuan hasil persetujuan ke guru
8. Jika disetujui & ada jadwal mengajar:
   - Kurikulum tugaskan guru pengganti
   - Pemberitahuan ke guru pengganti
```

### ALUR 4: Pemantauan Guru Piket

```
1. Guru Piket masuk (pagi hari 07:00)
2. Dasbor menampilkan:
   - Semua jadwal mengajar hari ini
   - Status absensi setiap guru (waktu nyata)
3. Sistem otomatis tandai merah jika:
   - Guru belum absen 10 menit setelah jam mulai
4. Guru Piket:
   - Hubungi guru via WhatsApp/Telepon
   - Jika konfirmasi sudah mengajar → Masukkan absensi manual
5. Masukkan absensi manual:
   - Pilih guru & jadwal
   - Jam absen & status
   - Alasan input manual
   - Unggah bukti (opsional)
6. Kirim → Status "menunggu persetujuan"
7. Pemberitahuan ke Admin
8. Admin tinjau & setujui/tolak
9. Sore hari (15:30):
   - Guru Piket buat laporan harian
   - Isi: Rekapitulasi + catatan khusus
   - Buat PDF
10. Kirim laporan ke Admin & Kepala Sekolah
```

### ALUR 5: Admin Menugaskan Guru Pengganti

```
1. Admin/Kurikulum masuk
2. Pemberitahuan: "Pak Budi izin sakit (17-18 Nov)"
3. Cek jadwal Pak Budi yang bentrok
4. Cari guru yang tersedia di jam tersebut
5. Tugaskan guru pengganti:
   - Pilih jadwal
   - Pilih guru pengganti
   - Masukkan alasan
6. Kirim penugasan
7. Pemberitahuan ke guru pengganti:
   "Anda ditugaskan menggantikan Pak Budi
    Mapel: Matematika
    Kelas: XII IPA 1
    Tanggal: 17 Nov 2025
    Jam: 08:00-09:30"
8. Guru pengganti absen seperti biasa
9. Sistem catat sebagai "Mengajar Pengganti"
```

### ALUR 6: Pemantauan Kepala Sekolah

```
1. Kepala Sekolah masuk
2. Dasbor utama menampilkan:
   - Kartu statistik (Total Guru, Hadir Hari Ini, Izin, Alfa)
   - Grafik kehadiran bulanan
   - Daftar guru yang sering terlambat/alfa
3. Fitur detail lebih lanjut:
   - Klik guru → Detail kehadiran per bulan
   - Klik mapel → Kehadiran guru pengampu
4. Pemberitahuan waktu nyata:
   - Guru alfa tanpa keterangan
   - Persetujuan menunggu (izin/sakit/cuti)
5. Setujui/tolak keterangan dengan alasan
6. Ekspor laporan eksekutif untuk rapat
```

### ALUR 7: Kurikulum Kelola Jadwal

```
1. Kurikulum masuk
2. Menu "Jadwal Mengajar"
3. Kelola jadwal:
   - Tambah: Pilih guru, kelas, mapel, hari, jam
   - Ubah: Perbarui jadwal yang ada
   - Hapus: Non-aktifkan jadwal
4. Validasi bentrokan:
   ✗ Guru tidak bisa mengajar 2 kelas di jam sama
   ✗ Kelas tidak bisa ada 2 mapel di jam sama
   ✗ Tidak bisa jadwal di hari libur
5. Tugaskan guru pengganti untuk guru yang izin
6. Pantau kehadiran per mata pelajaran
7. Laporan: Efektivitas jam mengajar
```

### ALUR 8: Pengajuan Izin/Cuti Lebih Awal

```
1. Guru masuk (H-7 atau lebih)
2. Menu "Pengajuan Izin/Cuti"
3. Pilih jenis & tanggal (masa depan)
4. Unggah dokumen pendukung
5. Kirim → Status "menunggu"
6. Pemberitahuan ke Kurikulum & Kepala Sekolah
7. Kurikulum tinjau:
   - Cek jadwal yang bentrok
   - Cari kandidat guru pengganti
8. Kepala Sekolah setujui/tolak
9. Jika disetujui:
   - Pemberitahuan ke guru
   - Kurikulum tugaskan guru pengganti
   - Pengingat otomatis H-1 untuk guru pengganti
```

### ALUR 9: Tukar Jadwal antar Guru

```
1. Guru A masuk
2. Menu "Tukar Jadwal"
3. Pilih jadwal yang ingin ditukar
4. Pilih Guru B sebagai mitra tukar
5. Masukkan alasan & tanggal tukar
6. Kirim permintaan
7. Pemberitahuan ke Guru B
8. Guru B setujui/tolak dari dasbor
9. Jika Guru B setujui:
   - Pemberitahuan ke Kurikulum
   - Kurikulum tinjau & setujui/tolak final
10. Jika disetujui final:
    - Sistem tukar jadwal otomatis untuk tanggal tersebut
    - Absensi akan tercatat ke guru yang benar
```

### ALUR 10: Buat Otomatis Surat Peringatan

```
1. Sistem cron job harian cek pelanggaran
2. Hitung alfa tanpa keterangan per guru (30 hari terakhir)
3. Jika alfa >= 3 & belum ada SP1:
   - Buat SP1 (status: draf)
   - Pemberitahuan ke Admin & Kepala Sekolah
4. Admin tinjau & terbitkan SP1:
   - Buat PDF surat dengan templat
   - Nomor surat otomatis
   - Tanda tangan digital
5. Status SP1: "diterbitkan"
6. Pemberitahuan ke guru yang bersangkutan
7. Guru buka & akui (tanda terima digital)
8. Sama untuk SP2 (5x alfa) dan SP3 (7x alfa)
```

### ALUR 11: Pemberitahuan Multi-Saluran

```
PEMBERITAHUAN WEB (Bawaan):
1. Polling AJAX setiap 30 detik
2. Lencana penghitung di bilah navigasi
3. Daftar dropdown pemberitahuan
4. Klik → Tandai sudah dibaca

PEMBERITAHUAN EMAIL:
1. Pengguna atur preferensi email: AKTIF
2. Masukkan alamat email di profil
3. Saat ada kejadian penting:
   - Sistem kirim email via PHPMailer
   - Templat HTML profesional
   - Subjek sesuai jenis pemberitahuan

PEMBERITAHUAN WHATSAPP:
1. Admin atur API WhatsApp (Fonnte/Wablas)
2. Pengguna atur preferensi WA: AKTIF
3. Masukkan nomor WA di profil
4. Saat ada kejadian penting:
   - Sistem panggil API WhatsApp
   - Kirim pesan templat
   - Catat status pengiriman
```

### ALUR 12: Cadangkan & Pulihkan Manual

```
CADANGKAN:
1. Admin masuk → Menu "Cadangkan & Pulihkan"
2. Dasbor menampilkan:
   - Riwayat cadangan
   - Ukuran file
   - Status (berhasil/gagal)
3. Klik "Cadangkan Sekarang"
4. Sistem:
   - Dump basis data (mysqldump)
   - Kompres file (zip)
   - Simpan ke folder backup/YYYY/MM/
   - Catat ke tabel backup_history
5. Tautan unduh tersedia

PULIHKAN:
1. Admin pilih titik cadangan dari daftar
2. Konfirmasi pulihkan (peringatan: data sekarang akan diganti)
3. Sistem:
   - Ekstrak file zip
   - Hapus basis data yang ada
   - Impor basis data dari cadangan
   - Pulihkan file unggahan (jika ada)
4. Arahkan ke halaman masuk (sesi di-reset)
```

### ALUR 13: Pesan Siaran

```
1. Admin/Kepala Sekolah masuk
2. Menu "Pesan Siaran"
3. Formulir siaran:
   - Judul pesan
   - Isi pesan
   - Jenis: Info/Peringatan/Mendesak
   - Target:
     * Semua guru
     * Peran tertentu (guru/guru piket/dll)
     * Guru spesifik (pilih manual)
4. Kirim siaran
5. Sistem:
   - Simpan ke tabel broadcast_message
   - Buat pemberitahuan untuk setiap target
   - Kirim email/WA (jika aktif)
6. Pengguna target melihat pemberitahuan siaran
```

### ALUR 14: Halaman Pengaturan Lengkap

```
AKSES:
1. Admin masuk → Menu "Pengaturan Sistem"

TAMPILAN:
2. Sidebar kategori pengaturan:
   ├── 📋 Umum Sekolah
   ├── 📅 Tahun Ajaran
   ├── ✅ Absensi
   ├── 📍 Lokasi GPS
   ├── 🔔 Pemberitahuan
   ├── 📧 Email
   ├── 💬 WhatsApp
   ├── 📄 Surat Peringatan
   ├── 🔒 Keamanan
   ├── 💾 Cadangan
   ├── ⏰ Jam Kerja
   ├── 📊 Laporan
   ├── ⚙️ Sistem
   └── 🔌 API & Integrasi

FITUR PER KATEGORI:
3. Klik kategori → Formulir pengaturan muncul
4. Formulir menampilkan:
   - Label pengaturan
   - Kolom input (sesuai jenis: teks/angka/boolean/pilihan/area teks)
   - Teks bantuan/deskripsi
   - Aturan validasi
   - Tombol nilai bawaan (reset ke bawaan)
5. Ubah pengaturan sesuai kebutuhan
6. Pratinjau waktu nyata (untuk templat email/wa)
7. Tombol uji (untuk SMTP, API WhatsApp, GPS)
8. Simpan perubahan

FITUR TAMBAHAN:
9. Ekspor Semua Pengaturan:
   - Klik "Ekspor Pengaturan"
   - Unduh file JSON lengkap
   - Berguna untuk cadangan/duplikasi pengaturan

10. Impor Pengaturan:
    - Unggah file JSON
    - Pratinjau perubahan (beda)
    - Konfirmasi impor
    - Terapkan pengaturan

11. Reset ke Bawaan:
    - Per kategori atau semua
    - Konfirmasi dengan kata sandi admin
    - Pulihkan ke nilai bawaan

12. Riwayat Pengaturan:
    - Lihat riwayat perubahan pengaturan
    - Siapa yang ubah, kapan
    - Nilai sebelum/sesudah
    - Kembalikan ke versi sebelumnya

13. Uji Konfigurasi:
    - Uji Email: Kirim email uji
    - Uji WhatsApp: Kirim WA uji
    - Uji GPS: Cek koordinat sekarang
    - Uji Cadangan: Picu cadangan manual

VALIDASI & KEAMANAN:
14. Validasi input:
    - Format email valid
    - Angka dalam rentang
    - Format URL valid
    - Cek kolom wajib
15. Enkripsi data sensitif:
    - Kata sandi SMTP
    - Kunci API
    - Kunci rahasia
16. Log audit setiap perubahan
17. Konfirmasi kata sandi untuk perubahan kritis
```

---

## 📱 FITUR-FITUR DETAIL

### A. Pemberitahuan Waktu Nyata (Polling AJAX)

**Teknologi:** Permintaan AJAX setiap 30 detik

**Pemberitahuan untuk Guru:**

- 15 menit sebelum jam mengajar
- Pengingat jika belum absen 10 menit setelah jam mulai

**Pemberitahuan untuk Guru Piket:**

- Guru belum absen 10 menit setelah jam mulai

**Pemberitahuan untuk Admin:**

- Guru belum absen 30 menit setelah jam mulai
- Persetujuan menunggu (absensi manual, izin, sakit, cuti)

**Pemberitahuan untuk Kepala Sekolah:**

- Guru alfa tanpa keterangan
- Persetujuan menunggu

**Pemberitahuan untuk Kurikulum:**

- Guru izin/sakit butuh pengganti
- Persetujuan menunggu

---

### B. Validasi GPS

**Cara Kerja:**

1. Saat absen, sistem ambil koordinat GPS pengguna (HTML5 Geolocation)
2. Hitung jarak ke koordinat sekolah (Formula Haversine)
3. Validasi: Jarak ≤ 100 meter → Valid
4. Simpan latitude & longitude ke basis data

**Formula Haversine (PHP):**

```php
function hitungJarak($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000; // meter
    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    return $angle * $earthRadius;
}
```

---

### C. Kode QR Dinamis

**Pustaka:** `endroid/qr-code` (PHP) + `html5-qrcode` (JavaScript)

**Buat Kode QR:**

```php
$data = [
    'token' => bin2hex(random_bytes(32)),
    'jadwal_id' => $jadwalId,
    'guru_id' => $guruId,
    'timestamp' => time(),
];
$signature = hash_hmac('sha256', json_encode($data), SECRET_KEY);
$data['signature'] = $signature;

$qrCode = json_encode($data);
// Buat gambar Kode QR
```

**Validasi Kode QR:**

```php
$decoded = json_decode($qrData, true);
// 1. Cek tanda tangan
$expectedSignature = hash_hmac('sha256', json_encode([
    'token' => $decoded['token'],
    'jadwal_id' => $decoded['jadwal_id'],
    'guru_id' => $decoded['guru_id'],
    'timestamp' => $decoded['timestamp']
]), SECRET_KEY);

if ($decoded['signature'] !== $expectedSignature) {
    return "Kode QR tidak valid!";
}

// 2. Cek masa berlaku (5 menit)
if (time() - $decoded['timestamp'] > 300) {
    return "Kode QR sudah kedaluwarsa!";
}

// 3. Cek is_used
// dst...
```

---

### D. Kompres Gambar (Swafoto)

**Pustaka:** GD Library (Bawaan PHP)

```php
function compressImage($source, $destination, $quality = 75) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }

    // Ubah ukuran ke 800x600
    $width = 800;
    $height = 600;
    $imageResized = imagecreatetruecolor($width, $height);
    imagecopyresampled($imageResized, $image, 0, 0, 0, 0,
        $width, $height, $info[0], $info[1]);

    // Simpan
    imagejpeg($imageResized, $destination, $quality);
    imagedestroy($image);
    imagedestroy($imageResized);
}
```

---

### E. Kalender Libur & Validasi Hari Kerja

**Cara Kerja:**

1. Admin masukkan hari libur (nasional/sekolah/cuti bersama)
2. Sistem validasi saat:
   - Buat pemberitahuan (lewati di hari libur)
   - Cek keterlambatan (tidak berlaku di hari libur)
   - Buat jadwal baru (peringatan jika pilih hari libur)

**Fungsi Pembantu:**

```php
function isHariLibur($tanggal) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM kalender_libur WHERE tanggal = ?");
    $stmt->execute([$tanggal]);
    return $stmt->fetchColumn() > 0;
}

function isHariKerja($hari) {
    $hariKerja = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    return in_array($hari, $hariKerja);
}
```

---

### F. Rekap Jam Mengajar & Perhitungan

**Auto-Calculate setiap akhir bulan:**

```php
function hitungRekapJamMengajar($guruId, $bulan, $tahun) {
    // 1. Total jam jadwal (dari jadwal_mengajar)
    $totalJamJadwal = hitungJamDariJadwal($guruId, $bulan, $tahun);

    // 2. Total jam efektif (dari absensi status hadir/terlambat)
    $totalJamEfektif = hitungJamEfektif($guruId, $bulan, $tahun);

    // 3. Total jam pengganti
    $totalJamPengganti = hitungJamPengganti($guruId, $bulan, $tahun);

    // 4. Total jam alfa
    $totalJamAlfa = hitungJamAlfa($guruId, $bulan, $tahun);

    // 5. Persentase kehadiran
    $persentase = ($totalJamEfektif / $totalJamJadwal) * 100;

    // Simpan ke rekap_jam_mengajar
    saveRekap($guruId, $bulan, $tahun, [
        'total_jam_jadwal' => $totalJamJadwal,
        'total_jam_efektif' => $totalJamEfektif,
        'total_jam_pengganti' => $totalJamPengganti,
        'total_jam_alfa' => $totalJamAlfa,
        'persentase_kehadiran' => $persentase
    ]);
}
```

**Laporan untuk Penggajian:**

- Ekspor Excel dengan kolom: NIP, Nama, Total Jam Efektif, Total Jam Pengganti
- Cuti tidak mengurangi jam mengajar (tetap dihitung sebagai jam kerja)

---

### G. Toleransi Waktu Absensi Fleksibel

**Pengaturan di Admin:**

- `absensi_before_minutes`: 30 (guru bisa absen 30 menit sebelum jam mengajar)
- `absensi_after_minutes`: 60 (guru masih bisa absen sampai 60 menit setelah jam selesai)

**Validasi saat absensi:**

```php
function validateWaktuAbsensi($jadwalId, $waktuAbsen) {
    global $pdo;

    // Ambil jadwal
    $jadwal = getJadwal($jadwalId);
    $jamMulai = strtotime($jadwal['jam_mulai']);
    $jamSelesai = strtotime($jadwal['jam_selesai']);
    $waktuAbsenTimestamp = strtotime($waktuAbsen);

    // Ambil setting
    $beforeMinutes = getSetting('absensi_before_minutes');
    $afterMinutes = getSetting('absensi_after_minutes');

    $minTime = $jamMulai - ($beforeMinutes * 60);
    $maxTime = $jamSelesai + ($afterMinutes * 60);

    if ($waktuAbsenTimestamp < $minTime) {
        return ['valid' => false, 'message' => 'Absensi terlalu dini!'];
    }

    if ($waktuAbsenTimestamp > $maxTime) {
        return ['valid' => false, 'message' => 'Absensi sudah melewati batas waktu!'];
    }

    // Cek keterlambatan
    $toleransi = getSetting('toleransi_keterlambatan_menit');
    $batasLambat = $jamMulai + ($toleransi * 60);

    if ($waktuAbsenTimestamp > $batasLambat) {
        $keterlambatan = ($waktuAbsenTimestamp - $jamMulai) / 60;
        return [
            'valid' => true,
            'status' => 'terlambat',
            'keterlambatan_menit' => round($keterlambatan)
        ];
    }

    return ['valid' => true, 'status' => 'hadir'];
}
```

---

### H. Pemberitahuan Email (PHPMailer)

**Pengaturan:**

```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function kirimEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = getSetting('email_smtp_host');
        $mail->SMTPAuth = true;
        $mail->Username = getSetting('email_username');
        $mail->Password = getSetting('email_password');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getSetting('email_smtp_port');

        // Recipients
        $mail->setFrom('noreply@sekolah.id', 'Sistem Absensi Guru');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: {$mail->ErrorInfo}");
        return false;
    }
}
```

**Templat Email:**

```php
$emailTemplate = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: #007bff; color: white; padding: 20px; }
        .content { padding: 20px; }
        .footer { background: #f8f9fa; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>🔔 Pemberitahuan Jadwal Mengajar</h2>
    </div>
    <div class="content">
        <p>Yth. Bapak/Ibu <strong>{nama_guru}</strong>,</p>
        <p>Anda memiliki jadwal mengajar:</p>
        <ul>
            <li>Mata Pelajaran: <strong>{mapel}</strong></li>
            <li>Kelas: <strong>{kelas}</strong></li>
            <li>Waktu: <strong>{waktu}</strong></li>
            <li>Ruangan: <strong>{ruangan}</strong></li>
        </ul>
        <p>Jangan lupa lakukan absensi!</p>
        <p><a href="{link}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Buka Aplikasi</a></p>
    </div>
    <div class="footer">
        <small>© 2025 Sistem Absensi Guru</small>
    </div>
</body>
</html>
';
```

---

### I. Pemberitahuan WhatsApp (API)

**Integrasi Fonnte:**

```php
function kirimWhatsApp($nomor, $pesan) {
    $apiKey = getSetting('whatsapp_api_key');
    $apiUrl = getSetting('whatsapp_api_url');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl . '/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'target' => $nomor,
            'message' => $pesan,
            'countryCode' => '62'
        ],
        CURLOPT_HTTPHEADER => [
            'Authorization: ' . $apiKey
        ]
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return $httpCode == 200;
}
```

**Templat WhatsApp:**

```
🔔 *PENGINGAT JADWAL MENGAJAR*

Yth. Bapak/Ibu *{nama_guru}*

Anda memiliki jadwal mengajar:
📚 Mapel: *{mapel}*
🏫 Kelas: *{kelas}*
⏰ Waktu: *{waktu}*
📍 Ruangan: *{ruangan}*

Jangan lupa lakukan absensi!
🔗 {link}

--
_Sistem Absensi Guru_
```

---

### J. Aplikasi Web Progresif (PWA)

**Berkas: public/manifest.json**

```json
{
  "name": "Sistem Absensi Guru",
  "short_name": "Absensi Guru",
  "description": "Aplikasi absensi guru dengan Kode QR dan Swafoto",
  "start_url": "/",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#007bff",
  "icons": [
    {
      "src": "/assets/images/icon-192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/assets/images/icon-512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

**Berkas: public/service-worker.js**

```javascript
const CACHE_NAME = "absensi-guru-v1";
const urlsToCache = [
  "/",
  "/css/bootstrap.min.css",
  "/css/style.css",
  "/js/main.js",
  "/assets/images/logo.png",
];

// Pasang
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache))
  );
});

// Ambil
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches
      .match(event.request)
      .then((response) => response || fetch(event.request))
  );
});

// Aktifkan
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

// Pemberitahuan Dorong
self.addEventListener("push", (event) => {
  const data = event.data.json();
  const options = {
    body: data.body,
    icon: "/assets/images/icon-192.png",
    badge: "/assets/images/badge.png",
  };
  event.waitUntil(self.registration.showNotification(data.title, options));
});
```

**Daftarkan Service Worker (di main.js):**

```javascript
if ("serviceWorker" in navigator) {
  navigator.serviceWorker
    .register("/service-worker.js")
    .then((registration) => {
      console.log("Service Worker terdaftar");
    })
    .catch((error) => {
      console.log("Pendaftaran Service Worker gagal:", error);
    });
}
```

---

### K. Pembatasan Laju & Keamanan

**Batas Laju Masuk:**

```php
function checkLoginAttempts($username, $ipAddress) {
    global $pdo;

    $maxAttempts = getSetting('max_login_attempts');
    $lockoutMinutes = getSetting('login_lockout_minutes');

    // Cek percobaan dalam X menit terakhir
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM audit_log
        WHERE action = 'login_failed'
        AND (old_value = ? OR ip_address = ?)
        AND created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
    ");
    $stmt->execute([$username, $ipAddress, $lockoutMinutes]);
    $attempts = $stmt->fetchColumn();

    if ($attempts >= $maxAttempts) {
        return [
            'allowed' => false,
            'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$lockoutMinutes} menit."
        ];
    }

    return ['allowed' => true, 'remaining' => $maxAttempts - $attempts];
}

// Catat percobaan gagal
function logFailedLogin($username, $ipAddress) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO audit_log (user_id, action, table_name, old_value, ip_address, user_agent)
        VALUES (0, 'login_failed', 'users', ?, ?, ?)
    ");
    $stmt->execute([$username, $ipAddress, $_SERVER['HTTP_USER_AGENT']]);
}
```

**Batas Laju Pindai QR:**

```php
function checkScanCooldown($ketuaKelasId) {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT created_at FROM qr_codes
        WHERE scanned_by = ?
        ORDER BY scanned_at DESC
        LIMIT 1
    ");
    $stmt->execute([$ketuaKelasId]);
    $lastScan = $stmt->fetchColumn();

    if ($lastScan) {
        $elapsed = time() - strtotime($lastScan);
        if ($elapsed < 5) { // Jeda 5 detik
            return [
                'allowed' => false,
                'message' => 'Tunggu ' . (5 - $elapsed) . ' detik sebelum scan lagi'
            ];
        }
    }

    return ['allowed' => true];
}
```

---

### L. Dasbor Analitik untuk Kepala Sekolah

**Grafik Tren Kehadiran (Chart.js):**

```javascript
// Data 6 bulan terakhir
const chartData = {
  labels: ["Juni", "Juli", "Agustus", "September", "Oktober", "November"],
  datasets: [
    {
      label: "Hadir",
      data: [85, 88, 90, 87, 92, 89],
      backgroundColor: "rgba(40, 167, 69, 0.5)",
      borderColor: "rgba(40, 167, 69, 1)",
      borderWidth: 2,
    },
    {
      label: "Terlambat",
      data: [10, 8, 7, 9, 5, 8],
      backgroundColor: "rgba(255, 193, 7, 0.5)",
      borderColor: "rgba(255, 193, 7, 1)",
      borderWidth: 2,
    },
    {
      label: "Alfa",
      data: [5, 4, 3, 4, 3, 3],
      backgroundColor: "rgba(220, 53, 69, 0.5)",
      borderColor: "rgba(220, 53, 69, 1)",
      borderWidth: 2,
    },
  ],
};

const config = {
  type: "line",
  data: chartData,
  options: {
    responsive: true,
    plugins: {
      legend: { position: "top" },
      title: { display: true, text: "Trend Kehadiran 6 Bulan Terakhir" },
    },
  },
};

new Chart(document.getElementById("trendChart"), config);
```

**Pola Keterlambatan per Hari:**

```php
function getPolaTerlambat() {
    global $pdo;

    $stmt = $pdo->query("
        SELECT
            j.hari,
            COUNT(*) as total_terlambat
        FROM absensi a
        JOIN jadwal_mengajar j ON a.jadwal_id = j.id
        WHERE a.status_kehadiran = 'terlambat'
        AND a.tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY j.hari
        ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

---

### M. Ekspor Laporan

**Format:** Excel (PhpSpreadsheet) & PDF (TCPDF)

**Excel:**

```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Nama Guru');
// ... dst

$writer = new Xlsx($spreadsheet);
$writer->save('laporan.xlsx');
```

**PDF:**

```php
require_once('tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, 'LAPORAN KEHADIRAN GURU', '', 0, 'C');
// ... dst
$pdf->Output('laporan.pdf', 'D');
```

---

## 🔒 IMPLEMENTASI KEAMANAN

### 1. Hash Kata Sandi

```php
// Daftar/Buat Pengguna
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Masuk
if (password_verify($inputPassword, $hashedPassword)) {
    // Masuk berhasil
}
```

### 2. Perlindungan CSRF

```php
// Buat token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Validasi
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Token CSRF tidak valid!");
}
```

### 3. Pencegahan Injeksi SQL (PDO Prepared Statements)

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
$stmt->execute([$username, $role]);
```

### 4. Perlindungan XSS

```php
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');
```

### 5. Keamanan Sesi

```php
// Setelah masuk
session_regenerate_id(true);

// Batas waktu 30 menit
if (time() - $_SESSION['last_activity'] > 1800) {
    session_destroy();
    header("Location: login.php");
}
$_SESSION['last_activity'] = time();
```

---

## 📂 STRUKTUR FOLDER APLIKASI

```
/absen-guru/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── AdminController.php
│   │   ├── GuruController.php
│   │   ├── KetuaKelasController.php
│   │   ├── GuruPiketController.php
│   │   ├── KepalaSekolahController.php
│   │   ├── KurikulumController.php
│   │   ├── AbsensiController.php
│   │   ├── JadwalController.php
│   │   ├── LaporanController.php
│   │   └── NotifikasiController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Guru.php
│   │   ├── Kelas.php
│   │   ├── KetuaKelas.php
│   │   ├── GuruPiket.php
│   │   ├── MataPelajaran.php
│   │   ├── Jadwal.php
│   │   ├── Absensi.php
│   │   ├── AbsensiKeluar.php
│   │   ├── KeteranganTidakHadir.php
│   │   ├── GuruPengganti.php
│   │   ├── QRCode.php
│   │   ├── Notifikasi.php
│   │   ├── AuditLog.php
│   │   ├── Settings.php
│   │   └── Laporan.php
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   ├── sidebar_admin.php
│   │   │   ├── sidebar_guru.php
│   │   │   ├── sidebar_ketua_kelas.php
│   │   │   ├── sidebar_guru_piket.php
│   │   │   ├── sidebar_kepala_sekolah.php
│   │   │   └── sidebar_kurikulum.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── logout.php
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── guru_list.php
│   │   │   ├── guru_form.php
│   │   │   ├── kelas_list.php
│   │   │   ├── mapel_list.php
│   │   │   ├── jadwal_list.php
│   │   │   ├── ketua_kelas_list.php
│   │   │   ├── guru_piket_list.php
│   │   │   ├── settings/
│   │   │   │   ├── index.php (dashboard settings)
│   │   │   │   ├── umum.php
│   │   │   │   ├── tahun_ajaran.php
│   │   │   │   ├── absensi.php
│   │   │   │   ├── gps.php
│   │   │   │   ├── notifikasi.php
│   │   │   │   ├── email.php
│   │   │   │   ├── whatsapp.php
│   │   │   │   ├── surat_peringatan.php
│   │   │   │   ├── security.php
│   │   │   │   ├── backup.php
│   │   │   │   ├── jam_kerja.php
│   │   │   │   ├── laporan.php
│   │   │   │   ├── sistem.php
│   │   │   │   ├── api_integrasi.php
│   │   │   │   ├── export_settings.php
│   │   │   │   ├── import_settings.php
│   │   │   │   └── history_settings.php
│   │   │   ├── kalender_libur.php
│   │   │   ├── backup_restore.php
│   │   │   ├── api_keys.php
│   │   │   ├── approval_list.php
│   │   │   └── laporan.php
│   │   ├── guru/
│   │   │   ├── dashboard.php
│   │   │   ├── jadwal_mengajar.php
│   │   │   ├── absensi_masuk.php
│   │   │   ├── absensi_keluar.php
│   │   │   ├── qr_code_display.php
│   │   │   ├── selfie_capture.php
│   │   │   ├── riwayat_absensi.php
│   │   │   ├── keterangan_tidak_hadir.php
│   │   │   └── profil.php
│   │   ├── ketua_kelas/
│   │   │   ├── dashboard.php
│   │   │   ├── scan_qr.php
│   │   │   ├── tanda_tangan.php
│   │   │   └── riwayat_absensi.php
│   │   ├── guru_piket/
│   │   │   ├── dashboard.php
│   │   │   ├── monitoring.php
│   │   │   ├── absensi_manual.php
│   │   │   ├── laporan_harian.php
│   │   │   └── riwayat_piket.php
│   │   ├── kepala_sekolah/
│   │   │   ├── dashboard.php
│   │   │   ├── monitoring_realtime.php
│   │   │   ├── laporan_eksekutif.php
│   │   │   ├── approval.php
│   │   │   └── analytics.php
│   │   └── kurikulum/
│   │       ├── dashboard.php
│   │       ├── jadwal_mengajar.php
│   │       ├── guru_pengganti.php
│   │       ├── approval.php
│   │       └── laporan_akademik.php
│   └── core/
│       ├── App.php
│       ├── Controller.php
│       ├── Database.php
│       ├── Session.php
│       ├── Middleware.php
│       └── Helper.php
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── style.css
│   │   └── custom.css
│   ├── js/
│   │   ├── jquery.min.js
│   │   ├── bootstrap.bundle.min.js
│   │   ├── qr-scanner.js
│   │   ├── html5-qrcode.min.js
│   │   ├── camera.js
│   │   ├── notification.js
│   │   ├── signature_pad.min.js
│   │   ├── chart.min.js
│   │   ├── sweetalert2.min.js
│   │   ├── datatables.min.js
│   │   ├── settings.js (khusus halaman settings)
│   │   └── main.js
│   ├── uploads/
│   │   ├── selfies/
│   │   │   └── 2025/
│   │   │       └── 11/
│   │   ├── documents/
│   │   │   └── 2025/
│   │   │       └── 11/
│   │   └── laporan/
│   │       └── 2025/
│   │           └── 11/
│   └── assets/
│       ├── images/
│       │   ├── logo.png
│       │   ├── default-avatar.png
│       │   └── background.jpg
│       └── fonts/
├── config/
│   ├── config.php
│   └── database.php
├── libraries/
│   ├── phpqrcode/
│   ├── endroid-qrcode/
│   ├── phpspreadsheet/
│   ├── tcpdf/
│   └── html5-qrcode/
├── database/
│   ├── absensi_guru.sql
│   ├── seed_data.sql
│   └── migrations/
├── logs/
│   ├── error.log
│   └── access.log
├── backup/
│   └── 2025/
│       └── 11/
├── .gitignore
├── README.md
├── SKEMA_APLIKASI.md (file ini)
└── composer.json
```

---

## 🚀 INSTALASI & PENYEBARAN

### Persyaratan:

- PHP 7.4+ atau 8.x
- MySQL 5.7+ atau MariaDB 10.3+
- Apache/Nginx
- Composer
- Ekstensi PHP: PDO, GD, mbstring, json

### Langkah Instalasi:

1. **Unduh/Kloning Proyek**

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/
git clone [repo-url] absen-guru
cd absen-guru
```

2. **Pasang Dependensi**

```bash
composer install
```

3. **Konfigurasi Basis Data**

- Buat basis data baru: `absensi_guru`
- Impor berkas SQL: `database/absensi_guru.sql`
- Ubah `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'absensi_guru');
define('DB_USER', 'root');
define('DB_PASS', '');
```

4. **Konfigurasi Aplikasi**
   Ubah `config/config.php`:

```php
define('BASE_URL', 'http://localhost/absen-guru/');
define('SECRET_KEY', 'your-secret-key-here');
define('UPLOAD_PATH', '/public/uploads/');
```

5. **Atur Hak Akses**

```bash
chmod 755 public/uploads/
chmod 755 logs/
chmod 755 backup/
```

6. **Data Awal (Opsional)**

```bash
mysql -u root -p absensi_guru < database/seed_data.sql
```

7. **Akses Aplikasi**

```
http://localhost/absen-guru/
```

8. **Install PWA** (WAJIB!)

**Android/Desktop Chrome:**

- Buka aplikasi di browser
- Klik tombol "Pasang" di banner/address bar
- Aplikasi akan ter-install seperti app native

**iOS Safari:**

- Buka aplikasi di Safari
- Tap tombol Share (⬆️)
- Pilih "Add to Home Screen"

> ⚠️ **PENTING:** Aplikasi ini HARUS diakses sebagai PWA, bukan via browser biasa!

### Akun Masuk Bawaan:

- **Admin:** admin / admin123
- **Guru:** guru001 / guru123
- **Kepala Sekolah:** kepsek / kepsek123
- **Kurikulum:** kurikulum / kurikulum123

---

## 📊 CONTOH DATA SEED

### Users & Guru:

```sql
-- Admin
INSERT INTO users VALUES (1, 'admin', '$2y$10$hash...', 'admin', 'aktif', NOW(), NOW(), NOW());

-- Kepala Sekolah
INSERT INTO users VALUES (2, 'kepsek', '$2y$10$hash...', 'kepala_sekolah', 'aktif', NULL, NOW(), NOW());

-- Kurikulum
INSERT INTO users VALUES (3, 'kurikulum', '$2y$10$hash...', 'kurikulum', 'aktif', NULL, NOW(), NOW());

-- Guru
INSERT INTO users VALUES (4, 'guru001', '$2y$10$hash...', 'guru', 'aktif', NULL, NOW(), NOW());
INSERT INTO guru VALUES (1, 4, '198001012005011001', 'Budi Santoso', 'L', 'Jakarta', '1980-01-01', 'Jl. Merdeka No.1', '081234567890', 'budi@sekolah.id', NULL, 'aktif', NOW(), NOW());

-- Ketua Kelas
INSERT INTO users VALUES (5, 'ketua001', '$2y$10$hash...', 'ketua_kelas', 'aktif', NULL, NOW(), NOW());
INSERT INTO ketua_kelas VALUES (1, 5, 1, 'Andi Pratama', '0012345678', '081234567891', '2025-07-01', '2026-06-30', 'aktif', NOW());

-- Guru Piket
INSERT INTO guru_piket VALUES (1, 1, 'Senin', '2025/2026', 'aktif', NOW());
```

### Kelas & Mata Pelajaran:

```sql
INSERT INTO kelas VALUES (1, 'XII IPA 1', 12, 'IPA', '2025/2026', 1, 'Lab Komputer 2', 32, 'aktif', NOW());
INSERT INTO mata_pelajaran VALUES (1, 'MAT', 'Matematika', 'Umum', 'Matematika Wajib', 'aktif', NOW());
```

### Jadwal Mengajar:

```sql
INSERT INTO jadwal_mengajar VALUES
(1, 1, 1, 1, 'Senin', '08:00:00', '09:30:00', 'Lab Komputer 2', '2025/2026', '1', 'aktif', NOW());
```

### Settings:

```sql
INSERT INTO settings (setting_key, setting_value, description) VALUES
('metode_qr', 'true', 'Metode absensi QR Code aktif'),
('metode_selfie', 'true', 'Metode absensi Selfie aktif'),
('qr_expired_minutes', '5', 'QR Code kadaluarsa setelah berapa menit'),
('toleransi_keterlambatan_menit', '15', 'Toleransi keterlambatan dalam menit'),
('absensi_before_minutes', '30', 'Guru bisa absen berapa menit sebelum jam mengajar'),
('absensi_after_minutes', '60', 'Guru masih bisa absen sampai berapa menit setelah jam selesai'),
('gps_enabled', 'true', 'GPS validation aktif'),
('gps_latitude_sekolah', '-6.200000', 'Koordinat latitude sekolah'),
('gps_longitude_sekolah', '106.816666', 'Koordinat longitude sekolah'),
('gps_radius_meter', '100', 'Radius GPS dalam meter'),
('tahun_ajaran_aktif', '2025/2026', 'Tahun ajaran yang sedang berjalan'),
('semester_aktif', '1', 'Semester aktif (1/2)'),
('sp1_threshold', '3', 'Threshold alfa untuk SP1'),
('sp2_threshold', '5', 'Threshold alfa untuk SP2'),
('sp3_threshold', '7', 'Threshold alfa untuk SP3'),
('email_enabled', 'false', 'Notifikasi email aktif'),
('whatsapp_enabled', 'false', 'Notifikasi WhatsApp aktif'),
('max_login_attempts', '5', 'Maksimal percobaan login'),
('login_lockout_minutes', '15', 'Durasi lockout setelah melebihi max login attempts');
```

### Kalender Libur:

```sql
-- Contoh hari libur nasional
INSERT INTO kalender_libur (tanggal, nama_libur, jenis, keterangan) VALUES
('2025-01-01', 'Tahun Baru 2025', 'nasional', 'Libur Nasional'),
('2025-03-30', 'Idul Fitri', 'nasional', 'Hari Raya Idul Fitri 1446 H'),
('2025-03-31', 'Idul Fitri', 'nasional', 'Hari Raya Idul Fitri 1446 H'),
('2025-04-01', 'Cuti Bersama Idul Fitri', 'cuti_bersama', 'Cuti Bersama'),
('2025-04-02', 'Cuti Bersama Idul Fitri', 'cuti_bersama', 'Cuti Bersama'),
('2025-08-17', 'Hari Kemerdekaan RI', 'nasional', 'HUT RI ke-80'),
('2025-12-25', 'Hari Natal', 'nasional', 'Hari Natal 2025');

-- Libur sekolah
INSERT INTO kalender_libur (tanggal, nama_libur, jenis, keterangan) VALUES
('2025-12-21', 'Libur Semester Ganjil', 'sekolah', 'Libur Akhir Semester'),
('2025-12-22', 'Libur Semester Ganjil', 'sekolah', 'Libur Akhir Semester'),
('2025-12-23', 'Libur Semester Ganjil', 'sekolah', 'Libur Akhir Semester');
```

---

## 🎨 PEDOMAN UI/UX

### PWA & Mobile-First Approach:

- **Akses Utama:** PWA (Progressive Web App) - WAJIB install di device
- **Responsif:** Mobile-first design, sempurna di semua ukuran layar
- **Offline Support:** Cache strategy untuk akses tanpa internet
- **Install Prompt:** Banner otomatis untuk install PWA
- **Touch-Optimized:** Minimum 48px untuk semua touch target
- **Bottom Navigation:** Navigasi mobile di bawah untuk jangkauan mudah
- **Gesture Support:** Swipe, pull-to-refresh, long-press
- **Loading States:** Skeleton screens untuk UX lebih smooth

### Sistem Desain:

- **Kerangka Kerja:** Bootstrap 5 + Custom Mobile CSS
- **Palet Warna:**
  - Utama: #007bff (Biru)
  - Berhasil: #28a745 (Hijau)
  - Bahaya: #dc3545 (Merah)
  - Peringatan: #ffc107 (Kuning)
  - Info: #17a2b8 (Cyan)

### Tata Letak:

- **Responsif:** Mobile-first design dengan breakpoints
  - Mobile: < 768px (Bottom Navigation)
  - Tablet: 768px - 1024px (Sidebar collapsible)
  - Desktop: > 1024px (Full sidebar)
- **Sidebar:** Dapat dilipat untuk mobile/tablet
- **Kartu:** Untuk statistik & kotak info (shadow-sm)
- **Tabel:** DataTables dengan horizontal scroll di mobile
- **Tombol:** Bulat, minimum 48px tinggi, dengan ikon
- **Modal:** Full-screen di mobile, center di desktop

### Komponen:

- **Peringatan:** SweetAlert2 untuk pemberitahuan
- **Modal:** Bootstrap Modal responsif dengan animasi
- **Grafik:** Chart.js untuk grafik (responsive)
- **Ikon:** Font Awesome 6
- **Loading:** Spinner & skeleton screens
- **Toast:** Bottom toast di mobile, top-right di desktop

### PWA Specific:

- **Install Banner:** Custom banner dengan tombol install
- **Splash Screen:** Loading screen saat launch PWA
- **Offline Page:** Halaman khusus saat tidak ada koneksi
- **Update Prompt:** Notifikasi saat ada versi baru
- **Badge Count:** Notification count di app icon (jika support)
- **Shortcuts:** Quick actions dari app icon

---

## 📝 API ENDPOINTS (Internal)

### Auth:

- `POST /auth/login` - Masuk
- `POST /auth/logout` - Keluar

### Absensi:

- `POST /absensi/generate-qr` - Buat Kode QR
- `POST /absensi/validate-qr` - Validasi pindai QR
- `POST /absensi/selfie` - Unggah swafoto
- `POST /absensi/keluar` - Absensi keluar
- `GET /absensi/riwayat/{guru_id}` - Riwayat absensi

### Notifikasi:

- `GET /notifikasi/list` - Daftar pemberitahuan
- `POST /notifikasi/read/{id}` - Tandai sudah dibaca
- `GET /notifikasi/unread-count` - Jumlah belum dibaca

### Admin:

- `GET /admin/guru` - Daftar guru
- `POST /admin/guru/store` - Tambah guru
- `PUT /admin/guru/update/{id}` - Perbarui guru
- `DELETE /admin/guru/delete/{id}` - Hapus guru
- `GET /admin/jadwal` - Daftar jadwal
- `POST /admin/jadwal/store` - Tambah jadwal

### Settings:

- `GET /settings/categories` - Daftar kategori pengaturan
- `GET /settings/category/{id}` - Ambil pengaturan berdasarkan kategori
- `GET /settings/get/{key}` - Ambil pengaturan berdasarkan kunci
- `POST /settings/update` - Perbarui banyak pengaturan
- `POST /settings/test-email` - Uji konfigurasi email
- `POST /settings/test-whatsapp` - Uji konfigurasi WhatsApp
- `GET /settings/export` - Ekspor semua pengaturan (JSON)
- `POST /settings/import` - Impor pengaturan dari JSON
- `POST /settings/reset/{category}` - Reset ke bawaan
- `GET /settings/history` - Riwayat perubahan pengaturan
- `POST /settings/rollback/{id}` - Kembalikan ke versi sebelumnya

### Laporan:

- `GET /laporan/kehadiran` - Laporan kehadiran
- `GET /laporan/export-excel` - Ekspor Excel
- `GET /laporan/export-pdf` - Ekspor PDF

---

## 🔄 CRON JOBS

### 1. Pemberitahuan Jam Mengajar (Setiap 1 menit)

```bash
* * * * * php /path/to/absen-guru/cron/notifikasi_jadwal.php
```

### 2. Pengingat Belum Absen (Setiap 1 menit)

```bash
* * * * * php /path/to/absen-guru/cron/reminder_absen.php
```

### 3. Cadangan Otomatis Basis Data (Setiap hari jam 23:00)

```bash
0 23 * * * php /path/to/absen-guru/cron/backup_database.php
```

### 4. Bersihkan Kode QR Kedaluwarsa (Setiap 5 menit)

```bash
*/5 * * * * php /path/to/absen-guru/cron/clean_expired_qr.php
```

### 5. Rekap Jam Mengajar Bulanan (Setiap tanggal 1 jam 00:00)

```bash
0 0 1 * * php /path/to/absen-guru/cron/rekap_jam_mengajar.php
```

### 6. Cek Pelanggaran & Buat SP Otomatis (Setiap hari jam 22:00)

```bash
0 22 * * * php /path/to/absen-guru/cron/check_pelanggaran.php
```

### 7. Sinkronisasi Kalender Libur Nasional (Setiap bulan tanggal 1)

```bash
0 2 1 * * php /path/to/absen-guru/cron/sync_kalender_libur.php
```

---

## ✅ DAFTAR PERIKSA PENGEMBANGAN

### Fase 1: Fondasi (Minggu 1-2)

- [ ] Atur struktur proyek PWA
- [ ] Buat basis data & tabel
- [ ] Bangun sistem inti MVC dengan Router fleksibel
- [ ] Implementasi autentikasi
- [ ] Buat tata letak & templat dasar responsif
- [ ] Setup konfigurasi auto-detect base URL
- [ ] Konfigurasi .htaccess untuk clean URL

### Fase 2: Fitur Inti (Minggu 3-4)

- [ ] Absensi Kode QR
- [ ] Absensi Swafoto
- [ ] Validasi GPS
- [ ] Sistem pemberitahuan
- [ ] Dasbor Guru (mobile-optimized)

### Fase 3: Manajemen (Minggu 5-6)

- [ ] Dasbor Admin (CRUD) dengan responsive table
- [ ] Dasbor Ketua Kelas (touch-friendly)
- [ ] Dasbor Guru Piket (real-time monitoring)
- [ ] Keterangan tidak hadir dengan upload file
- [ ] Guru pengganti

### Fase 4: Lanjutan (Minggu 7-8)

- [ ] Dasbor Kepala Sekolah (executive dashboard)
- [ ] Dasbor Kurikulum
- [ ] **Halaman Pengaturan Lengkap (Manajemen Pengaturan)**
  - [ ] UI/UX halaman pengaturan dengan bilah sisi kategori
  - [ ] Pembuat formulir untuk setiap kategori
  - [ ] Validasi & sanitasi
  - [ ] Uji konfigurasi (email, WA, GPS)
  - [ ] Ekspor/Impor pengaturan (JSON)
  - [ ] Riwayat & kembalikan pengaturan
  - [ ] Enkripsi data sensitif
- [ ] Laporan & Ekspor
- [ ] Log Audit
- [ ] Penguatan keamanan

### Fase 5: Fitur Tambahan (Minggu 9-10)

- [ ] Kalender Libur & validasi hari kerja
- [ ] Rekap Jam Mengajar
- [ ] Toleransi waktu absensi fleksibel
- [ ] Pengajuan izin/cuti H-7
- [ ] Tukar jadwal antar guru

### Fase 6: PWA & Fitur Lanjutan (Minggu 11-12)

- [ ] **PWA Core Implementation**
  - [ ] Service Worker dengan cache strategy
  - [ ] Manifest.json dengan icons lengkap
  - [ ] Offline page & fallback
  - [ ] Install prompt & banner
  - [ ] iOS Add to Home Screen support
- [ ] **Push Notifications**
  - [ ] VAPID keys generation
  - [ ] Subscription handler
  - [ ] Notification display & actions
  - [ ] Badge updates
- [ ] Buat Surat Peringatan otomatis
- [ ] Pemberitahuan Email (PHPMailer)
- [ ] Pemberitahuan WhatsApp (API)
- [ ] Pembatasan Laju & Keamanan

### Fase 7: Analitik & Integrasi (Minggu 13-14)

- [ ] Dasbor Analitik (Chart.js responsive)
- [ ] Analisis tren & pola keterlambatan
- [ ] Pesan Siaran
- [ ] UI Cadangan & Pemulihan
- [ ] API untuk integrasi eksternal
- [ ] Background Sync untuk offline actions

### Fase 8: Penyempurnaan & Pengujian (Minggu 15-16)

- [ ] **Optimasi PWA**
  - [ ] Lighthouse audit (target: 100% PWA score)
  - [ ] Performance optimization
  - [ ] Offline functionality testing
  - [ ] Install testing di berbagai device
- [ ] Perbaikan UI/UX mobile
- [ ] Pengujian (unit, integrasi, UAT)
- [ ] Testing di berbagai device & browser
- [ ] Bug fixing
- [ ] Performance optimization
- [ ] Dokumentasi lengkap (termasuk DEPLOYMENT.md)
- [ ] Penyebaran & pelatihan
- [ ] Perbaikan bug
- [ ] Optimasi performa
- [ ] Dokumentasi lengkap
- [ ] Penyebaran & pelatihan

---

## 📞 DUKUNGAN & PEMELIHARAAN

### Strategi Cadangan:

- **Harian:** Cadangan otomatis basis data (23:00)
- **Mingguan:** Cadangan penuh (aplikasi + basis data)
- **Bulanan:** Arsip cadangan

### Pemantauan:

- Log kesalahan: `/logs/error.log`
- Log akses: `/logs/access.log`
- Log audit: Tabel basis data `audit_log`

### Jadwal Pembaruan:

- **Tambalan keamanan:** Segera
- **Perbaikan bug:** Mingguan
- **Pembaruan fitur:** Bulanan

---

## 🆕 FITUR TAMBAHAN YANG DIIMPLEMENTASIKAN

### ✅ Sudah Ditambahkan:

1. **Rekap Jam Mengajar** - Untuk penggajian & evaluasi kinerja
2. **Kalender Libur** - Lewati pemberitahuan otomatis di hari libur
3. **Toleransi Waktu Fleksibel** - Absen bisa dilakukan X menit sebelum/sesudah
4. **Pengajuan Izin H-7** - Guru bisa mengajukan izin lebih awal
5. **Tukar Jadwal antar Guru** - Fleksibilitas untuk guru
6. **Buat Surat Peringatan Otomatis** - SP1/SP2/SP3 otomatis
7. **Pemberitahuan Email** - Integrasi PHPMailer
8. **Pemberitahuan WhatsApp** - Integrasi API (Fonnte/Wablas)
9. **PWA (Aplikasi Web Progresif)** - Dapat dipasang, mode luring, pemberitahuan dorong
   - **WAJIB install sebagai PWA** - Bukan akses via browser
   - **Service Worker** - Cache strategy, offline support, background sync
   - **Manifest.json** - Icons, splash screens, shortcuts
   - **Install prompt** - Android, iOS, Desktop
   - **Offline page** - Fallback saat tidak ada koneksi
   - **Push notifications** - Real-time via VAPID
   - **App shortcuts** - Quick actions dari home screen
   - **Update handler** - Auto-update saat ada versi baru
10. **Pembatasan Laju** - Keamanan untuk masuk & API
11. **Dasbor Analitik** - Tren kehadiran, pola keterlambatan
12. **Pesan Siaran** - Admin bisa menyiarkan ke semua guru
13. **UI Cadangan & Pemulihan** - Cadangan/pemulihan manual dari dasbor
14. **Manajemen Kunci API** - Untuk integrasi sistem eksternal
15. **🎛️ Halaman Pengaturan Lengkap (Manajemen Pengaturan)** - **FITUR BARU!**
    - 14 kategori pengaturan lengkap
    - 100+ pengaturan yang bisa dikonfigurasi
    - Ekspor/Impor pengaturan (JSON)
    - Uji konfigurasi (Email, WhatsApp, GPS)
    - Riwayat & kembalikan pengaturan
    - Enkripsi data sensitif (kata sandi, kunci API)
    - UI intuitif dengan bilah sisi kategori
    - Pratinjau waktu nyata untuk templat
    - Validasi & sanitasi otomatis

### 📊 Total Basis Data:

- **28 Tabel** (dari 18 tabel awal + 10 tabel baru)
- **2 tabel khusus pengaturan:** `settings_categories` & `settings_extended`
- **14 kategori pengaturan** dengan 100+ konfigurasi
- 7 Cron Jobs (dari 4 menjadi 7)

### 🚀 Timeline Pengembangan:

- **Total:** 16 minggu (4 bulan)
- **Fase 1-2:** Foundation & Core (4 minggu) - MVC, Auth, Routing, PWA Setup
- **Fase 3-4:** Management & Settings (4 minggu) - Dashboard, Settings Page
- **Fase 5-6:** Advanced Features & PWA (4 minggu) - Notifications, Service Worker, Offline
- **Fase 7-8:** Analytics & Polish (4 minggu) - Testing, Optimization, Deployment

### 🎯 Keunggulan PWA Implementation:

1. **Native-like Experience** - Install di device seperti app native
2. **Offline-First** - Tetap bisa akses data cached tanpa internet
3. **Fast Loading** - Service Worker cache untuk load instant
4. **Push Notifications** - Real-time notification bahkan saat app ditutup
5. **Auto-Update** - Update otomatis saat ada versi baru
6. **Cross-Platform** - Android, iOS, Desktop dengan 1 codebase
7. **No App Store** - Tidak perlu publish ke Play Store/App Store
8. **Lightweight** - Lebih ringan dari native app
9. **Always Latest** - User selalu dapat versi terbaru
10. **SEO Friendly** - Tetap indexable oleh search engine

### 🌐 Deployment Flexibility:

1. **Shared Hosting** - cPanel dengan .htaccess support
2. **VPS** - Ubuntu/Debian dengan Nginx/Apache
3. **Cloud Platform** - Railway, Heroku, Vercel
4. **Auto Base URL Detection** - Tidak perlu edit config saat pindah server
5. **Clean URL** - SEO-friendly routing dengan mod_rewrite
6. **HTTPS Ready** - SSL/TLS untuk PWA requirement
7. **Environment Detection** - Auto switch development/production mode

### 📱 Device Support (100% Responsive):

- ✅ **Android** (Chrome, Samsung Internet, Firefox)
- ✅ **iOS** (Safari - via Add to Home Screen)
- ✅ **Desktop** (Chrome, Edge, Firefox)
- ✅ **Tablet** (iPad, Android Tablet)
- ✅ **Mobile** (Phone semua ukuran, notch support)
- ✅ **Landscape & Portrait** orientation
- ✅ **Touch & Mouse** input support

### 🎯 Keunggulan Halaman Pengaturan:

1. **Konfigurasi Terpusat** - Semua pengaturan di satu tempat
2. **Antarmuka Ramah Pengguna** - Tidak perlu edit berkas JSON manual
3. **Validasi Terintegrasi** - Cegah kesalahan konfigurasi
4. **Uji Sebelum Terapkan** - Uji email, WA, GPS sebelum simpan
5. **Pelacakan Riwayat** - Jejak audit setiap perubahan
6. **Cadangan Mudah** - Ekspor/impor pengaturan dengan mudah
7. **Kembalikan Aman** - Bisa kembali ke konfigurasi sebelumnya
8. **Keamanan Utama** - Enkripsi data sensitif, konfirmasi kata sandi untuk perubahan kritis
9. **Teks Bantuan** - Panduan untuk setiap pengaturan
10. **Nilai Bawaan** - Reset ke bawaan kapan saja

---

**Dokumen dibuat:** 16 November 2025  
**Versi:** 3.5 (PWA Full Implementation - Mobile-First Responsive)  
**Status:** Siap untuk Pengembangan - Kualitas Produksi  
**Deployment:** Fleksibel - Shared Hosting / VPS / Cloud  
**Akses:** **WAJIB via PWA** - Install di device untuk pengalaman optimal  
**Support:** Android, iOS, Desktop - 100% Responsive

📖 **Dokumentasi Deployment:** Lihat `DEPLOYMENT.md` untuk panduan lengkap  
🔧 **Auto-Configuration:** Base URL auto-detect, support berbagai environment  
📱 **PWA Ready:** Service Worker, Manifest, Push Notification, Offline Support
