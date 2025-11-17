@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="mb-1">
                <i class="bi bi-gear text-primary me-2"></i>
                Pengaturan Sistem
            </h2>
            <p class="text-muted mb-0">Konfigurasi sistem absensi guru</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="row">
                <!-- GPS Settings -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt me-2"></i>Pengaturan GPS
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Koordinat sekolah untuk validasi lokasi absensi
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Latitude Sekolah</label>
                                <input type="text" class="form-control @error('school_latitude') is-invalid @enderror"
                                    name="school_latitude"
                                    value="{{ old('school_latitude', $settings['school_latitude'] ?? '-6.200000') }}"
                                    required>
                                <small class="text-muted">Contoh: -6.200000</small>
                                @error('school_latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Longitude Sekolah</label>
                                <input type="text" class="form-control @error('school_longitude') is-invalid @enderror"
                                    name="school_longitude"
                                    value="{{ old('school_longitude', $settings['school_longitude'] ?? '106.816666') }}"
                                    required>
                                <small class="text-muted">Contoh: 106.816666</small>
                                @error('school_longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Radius Validasi (meter)</label>
                                <input type="number" class="form-control @error('gps_radius') is-invalid @enderror"
                                    name="gps_radius" value="{{ old('gps_radius', $settings['gps_radius'] ?? 200) }}"
                                    min="50" max="1000" required>
                                <small class="text-muted">Jarak maksimal dari sekolah (50-1000 meter)</small>
                                @error('gps_radius')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-2">Cara Mendapatkan Koordinat:</h6>
                                    <ol class="small mb-0">
                                        <li>Buka <a href="https://www.google.com/maps" target="_blank">Google Maps</a></li>
                                        <li>Klik kanan pada lokasi sekolah</li>
                                        <li>Pilih koordinat yang muncul</li>
                                        <li>Copy dan paste di form ini</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Settings -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock me-2"></i>Pengaturan Absensi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Toleransi waktu untuk status absensi
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Toleransi Terlambat (menit)</label>
                                <input type="number"
                                    class="form-control @error('toleransi_terlambat') is-invalid @enderror"
                                    name="toleransi_terlambat"
                                    value="{{ old('toleransi_terlambat', $settings['toleransi_terlambat'] ?? 15) }}"
                                    min="0" max="60" required>
                                <small class="text-muted">Absen setelah toleransi = status Terlambat</small>
                                @error('toleransi_terlambat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Masa Berlaku QR Code (menit)</label>
                                <input type="number" class="form-control @error('qr_expiry_minutes') is-invalid @enderror"
                                    name="qr_expiry_minutes"
                                    value="{{ old('qr_expiry_minutes', $settings['qr_expiry_minutes'] ?? 15) }}"
                                    min="5" max="60" required>
                                <small class="text-muted">QR Code akan expired setelah waktu ini (5-60 menit)</small>
                                @error('qr_expiry_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="mb-2">Contoh Penggunaan:</h6>
                                    <ul class="small mb-0">
                                        <li><strong>Toleransi 15 menit:</strong> Jam masuk 07:00, terlambat jika absen >
                                            07:15</li>
                                        <li><strong>QR 15 menit:</strong> QR Code dibuat 07:00, expired 07:15</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="enableSelfie" name="enable_selfie"
                                    value="1"
                                    {{ old('enable_selfie', $settings['enable_selfie'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableSelfie">
                                    Aktifkan Absensi Selfie
                                </label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enableQR" name="enable_qr"
                                    value="1"
                                    {{ old('enable_qr', $settings['enable_qr'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableQR">
                                    Aktifkan Absensi QR Code
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Settings -->
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="bi bi-app me-2"></i>Pengaturan Aplikasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Nama Sekolah</label>
                                <input type="text" class="form-control @error('school_name') is-invalid @enderror"
                                    name="school_name"
                                    value="{{ old('school_name', $settings['school_name'] ?? 'SMK Negeri Kasomalang') }}"
                                    required>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Sekolah</label>
                                <textarea class="form-control @error('school_address') is-invalid @enderror" name="school_address" rows="3">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea>
                                @error('school_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <input type="text" class="form-control @error('school_year') is-invalid @enderror"
                                    name="school_year"
                                    value="{{ old('school_year', $settings['school_year'] ?? '2024/2025') }}">
                                <small class="text-muted">Contoh: 2024/2025</small>
                                @error('school_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="col-lg-6">
                    <div class="card mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td><strong>Laravel Version:</strong></td>
                                    <td>{{ app()->version() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>PHP Version:</strong></td>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Environment:</strong></td>
                                    <td>
                                        <span
                                            class="badge {{ app()->environment('production') ? 'bg-success' : 'bg-warning' }}">
                                            {{ strtoupper(app()->environment()) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Debug Mode:</strong></td>
                                    <td>
                                        <span class="badge {{ config('app.debug') ? 'bg-danger' : 'bg-success' }}">
                                            {{ config('app.debug') ? 'ON' : 'OFF' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Timezone:</strong></td>
                                    <td>{{ config('app.timezone') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Database:</strong></td>
                                    <td>{{ config('database.default') }}</td>
                                </tr>
                            </table>

                            @if (app()->environment('production') && config('app.debug'))
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Warning:</strong> Debug mode aktif di production!
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="bi bi-shield-check me-2"></i>
                            Terakhir diubah: {{ $settings['updated_at'] ?? 'Belum pernah' }}
                        </div>
                        <div>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .required::after {
            content: ' *';
            color: red;
        }
    </style>
@endpush
