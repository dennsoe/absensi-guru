@extends('layouts.app')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Jadwal Mengajar Saya</h4>
                <p class="text-muted mb-0">Semester {{ $semester }} - Tahun Ajaran {{ $tahun_ajaran }}</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                                    <i class="bi bi-clock-history fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="fw-bold mb-0">{{ $total_jam_perminggu }}</h3>
                                <p class="text-muted mb-0 small">Jam/Minggu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-info bg-opacity-10 text-info rounded p-3">
                                    <i class="bi bi-people fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="fw-bold mb-0">{{ $total_kelas }}</h3>
                                <p class="text-muted mb-0 small">Total Kelas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                    <i class="bi bi-book fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="fw-bold mb-0">{{ $total_mapel }}</h3>
                                <p class="text-muted mb-0 small">Mata Pelajaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold small mb-1">Filter Hari</label>
                        <select name="hari" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Hari</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
                                <option value="{{ strtolower($h) }}"
                                    {{ request('hari') === strtolower($h) ? 'selected' : '' }}>
                                    {{ $h }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold small mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control form-control-sm"
                            value="{{ request('tahun_ajaran', '2025/2026') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label fw-semibold small mb-1">Semester</label>
                        <select name="semester" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="Ganjil" {{ request('semester', 'Ganjil') == 'Ganjil' ? 'selected' : '' }}>
                                Semester Ganjil
                            </option>
                            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>
                                Semester Genap
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <a href="{{ route('guru.jadwal.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Jadwal Grouped by Day -->
        @forelse($jadwal_grouped as $hari_name => $jadwals)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-2">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                            <h5 class="fw-bold mb-0">{{ $hari_name }}</h5>
                        </div>
                        <span class="badge bg-primary">{{ $jadwals->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    @foreach ($jadwals as $j)
                        <div class="card border mb-3 hover-shadow">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <!-- Waktu & Ruangan -->
                                    <div class="col-12 col-md-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-clock text-primary me-2"></i>
                                            <span class="fw-bold">
                                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            <span>{{ $j->ruangan ?? 'Ruangan belum ditentukan' }}</span>
                                        </div>
                                    </div>

                                    <!-- Kelas -->
                                    <div class="col-6 col-md-2">
                                        <div class="small text-muted mb-1">Kelas</div>
                                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                            {{ $j->kelas->nama_kelas }}
                                        </span>
                                    </div>

                                    <!-- Mata Pelajaran -->
                                    <div class="col-6 col-md-5">
                                        <div class="small text-muted mb-1">Mata Pelajaran</div>
                                        <div class="fw-semibold text-dark">{{ $j->mataPelajaran->nama_mapel }}</div>
                                    </div>

                                    <!-- Action -->
                                    <div class="col-12 col-md-2 d-flex align-items-center justify-content-md-end">
                                        <a href="{{ route('guru.jadwal.show', $j->id) }}"
                                            class="btn btn-sm btn-outline-primary w-100 w-md-auto">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 mb-2">Tidak Ada Jadwal Mengajar</h5>
                    <p class="text-muted mb-0">
                        Tidak ada jadwal yang ditemukan untuk filter yang dipilih.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
