@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Kepala Sekolah</h1>
        <p class="page-subtitle">Monitoring & Analytics - {{ date('d F Y') }}</p>
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
                <div class="stat-card-value">{{ number_format($persentase_kehadiran, 1) }}%</div>
                <div class="stat-card-label">Tingkat Kehadiran</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $pending_approval }}</div>
                <div class="stat-card-label">Pending Approval</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_pelanggaran_bulan_ini }}</div>
                <div class="stat-card-label">Pelanggaran Bulan Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-card-value">{{ $total_guru }}</div>
                <div class="stat-card-label">Total Guru</div>
            </div>
        </div>
    </div>

    {{-- Alert Pending Approval --}}
    @if ($pending_approval > 0)
        <div class="alert-card warning">
            <i class="bi bi-bell alert-card-icon"></i>
            <div class="alert-card-body">
                <div class="alert-card-title">{{ $pending_approval }} Pengajuan Menunggu Approval</div>
                <div class="alert-card-message">
                    Anda memiliki {{ $pending_approval }} pengajuan izin/cuti yang perlu di-review.
                </div>
            </div>
            <a href="{{ route('kepala-sekolah.approval') }}" class="btn btn-warning btn-sm">Review Sekarang</a>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Grafik Kehadiran 7 Hari Terakhir --}}
            <div class="page-section">
                <h3 class="section-title">Tren Kehadiran 7 Hari Terakhir</h3>
                <canvas id="chartKehadiran" height="80"></canvas>
            </div>

            {{-- Daftar Guru dengan Pelanggaran Tertinggi --}}
            <div class="data-table-container">
                <div class="data-table-header">
                    <h3 class="data-table-title">Guru dengan Pelanggaran Tertinggi (Bulan Ini)</h3>
                </div>

                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Alfa</th>
                                <th>Terlambat</th>
                                <th>Total Pelanggaran</th>
                                <th>Status SP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru_pelanggaran as $index => $guru)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $guru->nama }}</td>
                                    <td><span class="badge badge-danger">{{ $guru->alfa_count }}</span></td>
                                    <td><span class="badge badge-warning">{{ $guru->terlambat_count }}</span></td>
                                    <td><strong>{{ $guru->total_pelanggaran }}</strong></td>
                                    <td>
                                        @if ($guru->alfa_count >= 7)
                                            <span class="badge badge-danger">SP3</span>
                                        @elseif($guru->alfa_count >= 5)
                                            <span class="badge badge-warning">SP2</span>
                                        @elseif($guru->alfa_count >= 3)
                                            <span class="badge badge-primary">SP1</span>
                                        @else
                                            <span class="badge badge-success">Baik</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="empty-state">
                                            <i class="bi bi-emoji-smile empty-state-icon"></i>
                                            <div class="empty-state-title">Tidak ada pelanggaran bulan ini</div>
                                            <div class="empty-state-message">Semua guru memiliki kedisiplinan yang baik!
                                            </div>
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
                    <a href="{{ route('kepala-sekolah.approval') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <span class="quick-action-text">Approval</span>
                        @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                        @endif
                    </a>
                    <a href="{{ route('kepala-sekolah.monitoring') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-eye"></i>
                        </div>
                        <span class="quick-action-text">Monitoring</span>
                    </a>
                    <a href="{{ route('kepala-sekolah.laporan') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <span class="quick-action-text">Laporan</span>
                    </a>
                    <a href="{{ route('kepala-sekolah.analytics') }}" class="quick-action-btn">
                        <div class="quick-action-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <span class="quick-action-text">Analytics</span>
                    </a>
                </div>
            </div>

            {{-- Summary Bulan Ini --}}
            <div class="page-section">
                <h3 class="section-title">Ringkasan Bulan Ini</h3>
                <div class="absensi-summary-stats">
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-success">{{ $summary_bulan_ini['hadir'] }}</div>
                        <div class="absensi-summary-label">Hadir</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-warning">{{ $summary_bulan_ini['terlambat'] }}</div>
                        <div class="absensi-summary-label">Terlambat</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-primary">{{ $summary_bulan_ini['izin'] }}</div>
                        <div class="absensi-summary-label">Izin</div>
                    </div>
                    <div class="absensi-summary-item">
                        <div class="absensi-summary-value text-danger">{{ $summary_bulan_ini['alfa'] }}</div>
                        <div class="absensi-summary-label">Alfa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Chart Kehadiran 7 Hari Terakhir
        const ctx = document.getElementById('chartKehadiran');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    label: 'Hadir',
                    data: {!! json_encode($chart_hadir) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Terlambat',
                    data: {!! json_encode($chart_terlambat) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Alfa',
                    data: {!! json_encode($chart_alfa) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
