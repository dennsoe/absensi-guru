@extends('layouts.app')

@section('title', 'Assign Ketua Kelas')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.ketua-kelas.index') }}">Ketua Kelas</a></li>
                    <li class="breadcrumb-item active">Assign Ketua Kelas</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-person-plus text-success me-2"></i>
                {{ $kelas ? 'Ganti' : 'Assign' }} Ketua Kelas
            </h2>
            <p class="text-muted mb-0">Tetapkan guru sebagai ketua kelas</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Form Assign -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-check me-2"></i>Form Assignment
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.ketua-kelas.store') }}">
                            @csrf

                            @if ($kelas)
                                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

                                <!-- Info Kelas -->
                                <div class="alert alert-info mb-4">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Kelas
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Kelas:</strong> {{ $kelas->nama }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Tingkat:</strong> {{ $kelas->tingkat }}
                                        </div>
                                        @if ($kelas->waliKelas)
                                            <div class="col-12 mt-2">
                                                <strong>Wali Kelas:</strong> {{ $kelas->waliKelas->nama }}
                                            </div>
                                        @endif
                                        @if ($kelas->ketuaKelas)
                                            <div class="col-12 mt-2">
                                                <span class="badge bg-warning">
                                                    Ketua kelas saat ini: {{ $kelas->ketuaKelas->nama }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="form-label required">Pilih Kelas</label>
                                    <select name="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach ($availableKelas as $k)
                                            <option value="{{ $k->id }}"
                                                {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama }} - Tingkat {{ $k->tingkat }}
                                                @if ($k->ketuaKelas)
                                                    (Ketua: {{ $k->ketuaKelas->nama }})
                                                @else
                                                    (Belum ada ketua)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label required">Pilih Guru sebagai Ketua Kelas</label>
                                <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($availableGuru as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }} - {{ $guru->nip }}
                                            @if ($guru->kelas_count > 0)
                                                (Sudah ketua di {{ $guru->kelas_count }} kelas)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Guru yang dipilih akan menjadi ketua kelas dan memiliki akses untuk scan QR absensi
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                    placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Assignment
                                </button>
                                <a href="{{ route('admin.ketua-kelas.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info & Summary -->
            <div class="col-lg-4">
                <!-- Petunjuk -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Petunjuk
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 ps-3">
                            <li class="mb-2">Pilih kelas yang akan diberi ketua kelas</li>
                            <li class="mb-2">Pilih guru yang akan menjadi ketua kelas</li>
                            <li class="mb-2">Satu guru bisa menjadi ketua di beberapa kelas</li>
                            <li class="mb-2">Ketua kelas bertugas generate QR code dan scan absensi</li>
                            <li>Ketua kelas dapat melihat riwayat absensi kelasnya</li>
                        </ul>
                    </div>
                </div>

                <!-- Summary -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Statistik
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total Kelas:</span>
                            <span class="badge bg-primary">{{ $totalKelas }} Kelas</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Sudah Ada Ketua:</span>
                            <span class="badge bg-success">{{ $kelasWithKetua }} Kelas</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Belum Ada Ketua:</span>
                            <span class="badge bg-warning">{{ $kelasWithoutKetua }} Kelas</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center fw-bold">
                            <span>Persentase:</span>
                            <span class="badge bg-info">
                                {{ $totalKelas > 0 ? round(($kelasWithKetua / $totalKelas) * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>Tips
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Kriteria Ketua Kelas:</strong></p>
                        <ul class="mb-0 ps-3 small">
                            <li>Guru aktif mengajar di kelas tersebut</li>
                            <li>Bertanggung jawab dan disiplin</li>
                            <li>Memiliki akses smartphone untuk scan QR</li>
                            <li>Dapat berkoordinasi dengan wali kelas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .required::after {
            content: " *";
            color: red;
        }
    </style>
@endpush
