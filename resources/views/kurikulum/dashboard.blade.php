@extends('layouts.app')

@section('title', 'Dashboard Kurikulum')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Kurikulum</h1>
        <p class="page-subtitle">Manajemen Jadwal & Pengganti - {{ date('d F Y') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_jadwal_hari_ini }}</div>
                <div class="stat-card-label">Jadwal Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="bi bi-person-plus"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $perlu_pengganti }}</div>
                <div class="stat-card-label">Perlu Pengganti</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $konflik_jadwal }}</div>
                <div class="stat-card-label">Konflik Jadwal</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_guru }}</div>
                <div class="stat-card-label">Total Guru</div>
            </div>
        </div>
    </div>

    {{-- Alert Perlu Guru Pengganti --}}
    @if ($perlu_pengganti > 0)
        <div class="alert-card warning">
            <i class="bi bi-exclamation-circle alert-card-icon"></i>
            <div class="alert-card-body">
                <div class="alert-card-title">{{ $perlu_pengganti }} Jadwal Perlu Guru Pengganti</div>
                <div class="alert-card-message">
                    Ada guru yang izin/cuti hari ini. Segera tentukan guru pengganti!
                </div>
            </div>
            <a href="{{ route('kurikulum.guru-pengganti') }}" class="btn btn-warning btn-sm">Atur Pengganti</a>
        </div>
    @endif

    {{-- Alert Konflik Jadwal --}}
    @if ($konflik_jadwal > 0)
        <div class="alert-card danger">
            <i class="bi bi-exclamation-triangle alert-card-icon"></i>
            <div class="alert-card-body">
                <div class="alert-card-title">{{ $konflik_jadwal }} Konflik Jadwal Ditemukan</div>
                <div class="alert-card-message">
                    Terdapat guru/kelas yang memiliki jadwal bertabrakan. Perlu perbaikan!
                </div>
            </div>
            <a href="{{ route('kurikulum.cek-konflik') }}" class="btn btn-danger btn-sm">Lihat Detail</a>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Jadwal yang Perlu Pengganti --}}
            <div class="data-table-container">
                <div class="data-table-header">
                    <h3 class="data-table-title">Jadwal Perlu Guru Pengganti Hari Ini</h3>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Guru Asli</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Alasan</th>
                                <th>Guru Pengganti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal_perlu_pengganti as $jadwal)
                                <tr>
                                    <td>
                                        <strong>{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</strong>
                                        <span class="text-muted"> -
                                            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</span>
                                    </td>
                                    <td>{{ $jadwal->guru->nama }}</td>
                                    <td>{{ $jadwal->kelas->nama }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td>
                                        @php
                                            $izin = $jadwal->guru
                                                ->izinCuti()
                                                ->whereDate('tanggal_mulai', '<=', today())
                                                ->whereDate('tanggal_selesai', '>=', today())
                                                ->where('status', 'approved')
                                                ->first();
                                        @endphp
                                        @if ($izin)
                                            <span class="badge badge-primary">{{ ucfirst($izin->jenis) }}</span>
                                        @else
                                            <span class="badge badge-gray">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($jadwal->guru_pengganti_id)
                                            <span class="badge badge-success">{{ $jadwal->guruPengganti->nama }}</span>
                                        @else
                                            <span class="badge badge-warning">Belum Ditentukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$jadwal->guru_pengganti_id)
                                            <a href="{{ route('kurikulum.atur-pengganti', $jadwal->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Atur Pengganti
                                            </a>
                                        @else
                                            <a href="{{ route('kurikulum.ubah-pengganti', $jadwal->id) }}"
                                                class="btn btn-sm btn-warning">
                                                Ubah
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-check-circle empty-state-icon"></i>
                                            <div class="empty-state-title">Semua jadwal normal</div>
                                            <div class="empty-state-message">Tidak ada jadwal yang memerlukan guru pengganti
                                                hari ini</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Jadwal Hari Ini Overview --}}
            <div class="data-table-container mt-4">
                <div class="data-table-header">
                    <h3 class="data-table-title">Semua Jadwal Hari Ini</h3>
                    <div class="data-table-actions">
                        <a href="{{ route('kurikulum.jadwal.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Jadwal
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal_hari_ini as $jadwal)
                                <tr>
                                    <td>
                                        <strong>{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</strong>
                                        <span class="text-muted"> -
                                            {{ date('H:i', strtotime($jadwal->jam_selesai)) }}</span>
                                    </td>
                                    <td>{{ $jadwal->kelas->nama }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td>
                                        {{ $jadwal->guru_pengganti_id ? $jadwal->guruPengganti->nama : $jadwal->guru->nama }}
                                        @if ($jadwal->guru_pengganti_id)
                                            <small class="text-muted">(Pengganti: {{ $jadwal->guru->nama }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($jadwal->guru_pengganti_id)
                                            <span class="badge badge-warning">Pengganti</span>
                                        @else
                                            <span class="badge badge-success">Normal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
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
                    <a href="{{ route('kurikulum.jadwal') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <span class="quick-action-text">Kelola Jadwal</span>
                    </a>
                    <a href="{{ route('kurikulum.guru-pengganti') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <span class="quick-action-text">Guru Pengganti</span>
                        @if ($perlu_pengganti > 0)
                            <span class="badge badge-warning">{{ $perlu_pengganti }}</span>
                        @endif
                    </a>
                    <a href="{{ route('kurikulum.cek-konflik') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <span class="quick-action-text">Cek Konflik</span>
                        @if ($konflik_jadwal > 0)
                            <span class="badge badge-danger">{{ $konflik_jadwal }}</span>
                        @endif
                    </a>
                    <a href="{{ route('kurikulum.laporan') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <span class="quick-action-text">Laporan</span>
                    </a>
                </div>
            </div>

            {{-- Statistik Mingguan --}}
            <div class="page-section">
                <h3 class="section-title">Statistik Minggu Ini</h3>
                <div class="absensi-summary-stats">
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-primary">{{ $stat_minggu_ini['total_jadwal'] }}</div>
                        <div class="absensi-summary-label">Total Jadwal</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-warning">{{ $stat_minggu_ini['total_pengganti'] }}</div>
                        <div class="absensi-summary-label">Guru Pengganti</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-danger">{{ $stat_minggu_ini['total_konflik'] }}</div>
                        <div class="absensi-summary-label">Konflik Terdeteksi</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-success">{{ $stat_minggu_ini['jadwal_berjalan'] }}</div>
                        <div class="absensi-summary-label">Berjalan Lancar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
