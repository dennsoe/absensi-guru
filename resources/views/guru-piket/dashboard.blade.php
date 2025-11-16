@extends('layouts.app')

@section('title', 'Dashboard Guru Piket')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Guru Piket</h1>
        <p class="page-subtitle">Monitoring Absensi Real-time - {{ date('d F Y') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $guru_hadir }}</div>
                <div class="stat-card-label">Guru Hadir</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="bi bi-clock"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $guru_terlambat }}</div>
                <div class="stat-card-label">Terlambat</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $guru_belum_hadir }}</div>
                <div class="stat-card-label">Belum Absen</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_guru_mengajar }}</div>
                <div class="stat-card-label">Total Mengajar Hari Ini</div>
            </div>
        </div>
    </div>

    {{-- Alert Guru Belum Absen (10 menit sebelum jam mengajar) --}}
    @if ($guru_belum_absen_alert->count() > 0)
        <div class="alert-card danger">
            <i class="bi bi-exclamation-triangle alert-card-icon"></i>
            <div class="alert-card-body">
                <div class="alert-card-title">Perhatian: {{ $guru_belum_absen_alert->count() }} Guru Belum Absen!</div>
                <div class="alert-card-message">
                    Guru dengan jadwal 10 menit lagi belum melakukan absensi. Segera hubungi!
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Jadwal Mengajar Hari Ini --}}
            <div class="data-table-container">
                <div class="data-table-header">
                    <h3 class="data-table-title">Jadwal Mengajar Hari Ini</h3>
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
                                <th>Jam</th>
                                <th>Guru</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Status Absensi</th>
                                <th>Waktu Absen</th>
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
                                    <td>{{ $jadwal->guru->nama }}</td>
                                    <td>{{ $jadwal->kelas->nama }}</td>
                                    <td>{{ $jadwal->mataPelajaran->nama }}</td>
                                    <td>
                                        @php
                                            $absensi = $jadwal->absensi()->whereDate('tanggal', today())->first();
                                        @endphp
                                        @if ($absensi)
                                            @if ($absensi->status === 'hadir')
                                                <span class="badge badge-success">Hadir</span>
                                            @elseif($absensi->status === 'terlambat')
                                                <span class="badge badge-warning">Terlambat</span>
                                            @elseif($absensi->status === 'izin')
                                                <span class="badge badge-primary">Izin</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($absensi->status) }}</span>
                                            @endif
                                        @else
                                            <span class="badge badge-gray">Belum Absen</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($absensi)
                                            {{ date('H:i', strtotime($absensi->jam_masuk)) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
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
                    <a href="{{ route('guru-piket.absensi-manual') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <span class="quick-action-text">Absensi Manual</span>
                    </a>
                    <a href="{{ route('guru-piket.monitoring') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <span class="quick-action-text">Monitoring</span>
                    </a>
                    <a href="{{ route('guru-piket.kontak-guru') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <span class="quick-action-text">Kontak Guru</span>
                    </a>
                    <a href="{{ route('guru-piket.laporan') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <span class="quick-action-text">Laporan Piket</span>
                    </a>
                </div>
            </div>

            {{-- Guru yang Perlu Dihubungi --}}
            @if ($guru_belum_absen_alert->count() > 0)
                <div class="page-section">
                    <h3 class="section-title">Guru Perlu Dihubungi</h3>
                    @foreach ($guru_belum_absen_alert as $jadwal)
                        <div class="alert-card warning mb-3">
                            <i class="bi bi-phone alert-card-icon"></i>
                            <div class="alert-card-body">
                                <div class="alert-card-title">{{ $jadwal->guru->nama }}</div>
                                <div class="alert-card-message">
                                    Jam: {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ $jadwal->kelas->nama }}<br>
                                    <small>No HP: {{ $jadwal->guru->no_hp ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto refresh every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
@endpush
