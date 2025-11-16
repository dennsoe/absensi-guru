@extends('layouts.app')

@section('title', 'Dashboard Ketua Kelas')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Ketua Kelas</h1>
        <p class="page-subtitle">{{ Auth::user()->nama }} - Kelas {{ Auth::user()->guru->kelas_id ?? '-' }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="bi bi-qr-code-scan"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_scan_hari_ini }}</div>
                <div class="stat-card-label">Scan Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $scan_valid }}</div>
                <div class="stat-card-label">Valid</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $scan_invalid }}</div>
                <div class="stat-card-label">Invalid</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_scan_minggu_ini }}</div>
                <div class="stat-card-label">Scan Minggu Ini</div>
            </div>
        </div>
    </div>

    {{-- Scan QR Button --}}
    <div class="page-section">
        <div class="text-center">
            <a href="{{ route('ketua-kelas.scan-qr') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-qr-code-scan"></i> Scan QR Code Guru
            </a>
            <p class="text-muted mt-3">
                Klik tombol di atas untuk memulai scan QR code absensi guru
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Riwayat Scan Hari Ini --}}
            <div class="data-table-container">
                <div class="data-table-header">
                    <h3 class="data-table-title">Riwayat Scan Hari Ini</h3>
                    <div class="data-table-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Waktu Scan</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat_scan_hari_ini as $scan)
                                <tr>
                                    <td>{{ date('H:i:s', strtotime($scan->created_at)) }}</td>
                                    <td>{{ $scan->absensi->guru->nama }}</td>
                                    <td>{{ $scan->absensi->jadwal->mataPelajaran->nama ?? '-' }}</td>
                                    <td>
                                        @if ($scan->is_valid)
                                            <span class="badge badge-success">Valid</span>
                                        @else
                                            <span class="badge badge-danger">Invalid</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($scan->is_valid)
                                            <span class="text-success">Absensi berhasil divalidasi</span>
                                        @else
                                            <span
                                                class="text-danger">{{ $scan->keterangan ?? 'QR expired atau tidak valid' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-qr-code empty-state-icon"></i>
                                            <div class="empty-state-title">Belum ada scan hari ini</div>
                                            <div class="empty-state-message">Mulai scan QR code guru untuk memvalidasi
                                                absensi</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Jadwal Kelas Hari Ini --}}
            <div class="data-table-container mt-4">
                <div class="data-table-header">
                    <h3 class="data-table-title">Jadwal Kelas Hari Ini</h3>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Status Absensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal_kelas_hari_ini as $jadwal)
                                <tr>
                                    <td>
                                        <strong>{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</strong>
                                        <span class="text-muted"> -
                                            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</span>
                                    </td>
                                    <td>{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td>
                                        {{ $jadwal->guru_pengganti_id ? $jadwal->guruPengganti->nama : $jadwal->guru->nama }}
                                        @if ($jadwal->guru_pengganti_id)
                                            <br><small class="text-muted">(Pengganti)</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $absensi = $jadwal->absensi()->whereDate('tanggal', today())->first();
                                        @endphp
                                        @if ($absensi)
                                            @if ($absensi->status === 'hadir')
                                                <span class="badge badge-success">Hadir</span>
                                            @elseif($absensi->status === 'terlambat')
                                                <span class="badge badge-warning">Terlambat</span>
                                            @elseif($absensi->is_validated)
                                                <span class="badge badge-success">Tervalidasi</span>
                                            @else
                                                <span class="badge badge-warning">Belum Divalidasi</span>
                                            @endif
                                        @else
                                            <span class="badge badge-gray">Belum Absen</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-calendar-x empty-state-icon"></i>
                                            <div class="empty-state-title">Tidak ada jadwal hari ini</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="page-section">
                <h3 class="section-title">Quick Actions</h3>
                <div class="quick-actions">
                    <a href="{{ route('ketua-kelas.scan-qr') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <span class="quick-action-text">Scan QR Code</span>
                    </a>
                    <a href="{{ route('ketua-kelas.riwayat') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <span class="quick-action-text">Riwayat Scan</span>
                    </a>
                    <a href="{{ route('ketua-kelas.jadwal') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <span class="quick-action-text">Jadwal Kelas</span>
                    </a>
                </div>
            </div>

            {{-- Panduan --}}
            <div class="page-section">
                <h3 class="section-title">Panduan Scan QR</h3>
                <div class="alert-card primary">
                    <i class="bi bi-info-circle alert-card-icon"></i>
                    <div class="alert-card-body">
                        <div class="alert-card-message">
                            <ol style="margin: 0; padding-left: 20px;">
                                <li>Klik tombol "Scan QR Code"</li>
                                <li>Izinkan akses kamera</li>
                                <li>Arahkan kamera ke QR code guru</li>
                                <li>Tunggu validasi otomatis</li>
                                <li>Lihat hasil validasi</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Mingguan --}}
            <div class="page-section">
                <h3 class="section-title">Statistik Minggu Ini</h3>
                <div class="absensi-summary-stats">
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-primary">{{ $total_scan_minggu_ini }}</div>
                        <div class="absensi-summary-label">Total Scan</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-success">{{ $scan_valid_minggu }}</div>
                        <div class="absensi-summary-label">Valid</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-danger">{{ $scan_invalid_minggu }}</div>
                        <div class="absensi-summary-label">Invalid</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-warning">{{ number_format($tingkat_keberhasilan, 1) }}%
                        </div>
                        <div class="absensi-summary-label">Keberhasilan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
