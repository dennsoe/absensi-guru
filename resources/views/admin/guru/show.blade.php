@extends('layouts.app')

@section('title', 'Detail Guru - ' . $guru->nama)

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.guru') }}">Data Guru</a></li>
                    <li class="breadcrumb-item active">Detail Guru</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Detail Data Guru
                    </h2>
                    <p class="text-muted mb-0">Informasi lengkap tentang guru: <strong>{{ $guru->nama }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.guru') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($guru->foto)
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="{{ $guru->nama }}"
                                    class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                                    style="width: 150px; height: 150px;">
                                    <span class="text-white fs-1 fw-bold">
                                        {{ strtoupper(substr($guru->nama, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h4 class="mb-1">{{ $guru->nama }}</h4>
                        <p class="text-muted mb-2">{{ $guru->nip }}</p>

                        <div class="mb-3">
                            @if ($guru->status === 'aktif')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Tidak Aktif
                                </span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="tel:{{ $guru->no_hp }}" class="btn btn-outline-primary">
                                <i class="bi bi-telephone me-1"></i> Hubungi
                            </a>
                            <a href="mailto:{{ $guru->user->email ?? '' }}" class="btn btn-outline-secondary">
                                <i class="bi bi-envelope me-1"></i> Email
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Statistik Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Mengajar:</span>
                            <strong>{{ $totalJadwal }} Jadwal</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Kehadiran Bulan Ini:</span>
                            <strong class="text-success">{{ $kehadiranBulanIni }}%</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total Absensi:</span>
                            <strong>{{ $totalAbsensi }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Izin/Cuti:</span>
                            <strong class="text-warning">{{ $totalIzin }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="col-lg-8">
                <!-- Data Pribadi -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-vcard me-2"></i>Data Pribadi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">NIP</label>
                                <p class="fw-bold">{{ $guru->nip }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Nama Lengkap</label>
                                <p class="fw-bold">{{ $guru->nama }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jenis Kelamin</label>
                                <p class="fw-bold">{{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Nomor HP</label>
                                <p class="fw-bold">{{ $guru->no_hp }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">Alamat</label>
                                <p class="fw-bold">{{ $guru->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Akun -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Data Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Username</label>
                                <p class="fw-bold">{{ $guru->user->username ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Email</label>
                                <p class="fw-bold">{{ $guru->user->email ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Role</label>
                                <p class="fw-bold text-capitalize">{{ $guru->user->role ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Status Akun</label>
                                <p>
                                    @if ($guru->user && $guru->user->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Mengajar -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-check me-2"></i>Jadwal Mengajar
                        </h5>
                        <span class="badge bg-white text-success">{{ $totalJadwal }} Jadwal</span>
                    </div>
                    <div class="card-body">
                        @if ($jadwalMengajar->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Kelas</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwalMengajar as $jadwal)
                                            <tr>
                                                <td>{{ $jadwal->hari }}</td>
                                                <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                                <td>{{ $jadwal->mataPelajaran->nama ?? '-' }}</td>
                                                <td>{{ $jadwal->kelas->nama ?? '-' }}</td>
                                                <td>
                                                    @if ($jadwal->status === 'aktif')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada jadwal mengajar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Riwayat Absensi Terbaru -->
                <div class="card">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Absensi Terbaru
                        </h5>
                        <a href="{{ route('admin.absensi.index') }}?guru_id={{ $guru->id }}"
                            class="btn btn-sm btn-dark">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($riwayatAbsensi->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatAbsensi as $absensi)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                                <td>
                                                    @if ($absensi->jam_masuk)
                                                        <i class="bi bi-clock text-primary me-1"></i>
                                                        {{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($absensi->jam_keluar)
                                                        <i class="bi bi-clock text-danger me-1"></i>
                                                        {{ \Carbon\Carbon::parse($absensi->jam_keluar)->format('H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $statusBadge = [
                                                            'hadir' => 'success',
                                                            'terlambat' => 'warning',
                                                            'izin' => 'info',
                                                            'sakit' => 'secondary',
                                                            'alpha' => 'danger',
                                                        ];
                                                        $badge =
                                                            $statusBadge[$absensi->status_kehadiran] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $badge }}">
                                                        {{ ucfirst($absensi->status_kehadiran) }}
                                                    </span>
                                                </td>
                                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada riwayat absensi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
