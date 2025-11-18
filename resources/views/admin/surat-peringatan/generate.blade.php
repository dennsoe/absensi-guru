@extends('layouts.app')

@section('title', 'Generate Surat Peringatan')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.surat-peringatan.index') }}">Surat Peringatan</a>
                    </li>
                    <li class="breadcrumb-item active">Generate</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-file-earmark-plus text-warning me-2"></i>
                Generate Surat Peringatan
            </h2>
            <p class="text-muted mb-0">Generate surat peringatan berdasarkan data absensi</p>
        </div>

        <div class="row">
            <!-- Form Generate -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-sliders me-2"></i>Pengaturan Generate
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.surat-peringatan.process') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Periode <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label small">Bulan</label>
                                        <select name="bulan" class="form-select" required>
                                            <option value="">Pilih Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small">Tahun</label>
                                        <select name="tahun" class="form-select" required>
                                            @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                                                <option value="{{ $y }}"
                                                    {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>
                                                    {{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <small class="text-muted">Pilih periode untuk menghitung data absensi</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Minimum Alpha untuk SP 1 <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="min_alpha_sp1" class="form-control"
                                    value="{{ old('min_alpha_sp1', 3) }}" min="1" max="10" required>
                                <small class="text-muted">Default: 3 hari alpha</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Minimum Alpha untuk SP 2 <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="min_alpha_sp2" class="form-control"
                                    value="{{ old('min_alpha_sp2', 6) }}" min="1" max="15" required>
                                <small class="text-muted">Default: 6 hari alpha</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Minimum Alpha untuk SP 3 <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="min_alpha_sp3" class="form-control"
                                    value="{{ old('min_alpha_sp3', 10) }}" min="1" max="20" required>
                                <small class="text-muted">Default: 10 hari alpha</small>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="preview_only" class="form-check-input" id="previewOnly"
                                    value="1">
                                <label class="form-check-label" for="previewOnly">
                                    Preview saja (tidak menyimpan ke database)
                                </label>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.surat-peringatan.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-play-circle me-1"></i> Generate Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary">Kriteria SP</h6>
                        <ul class="mb-3">
                            <li><strong>SP 1:</strong> Teguran ringan</li>
                            <li><strong>SP 2:</strong> Teguran sedang</li>
                            <li><strong>SP 3:</strong> Teguran berat</li>
                        </ul>

                        <h6 class="text-primary">Proses Generate</h6>
                        <ol class="mb-3">
                            <li>Sistem menghitung total alpha per guru</li>
                            <li>Membandingkan dengan kriteria SP</li>
                            <li>Menghasilkan surat sesuai tingkat</li>
                            <li>Menyimpan ke database</li>
                        </ol>

                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Catatan:</strong> Guru yang sudah pernah mendapat SP di periode yang sama tidak akan
                            di-generate ulang.
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Statistik Bulan Ini
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Guru Aktif:</span>
                            <strong>{{ $totalGuruAktif }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Absensi:</span>
                            <strong>{{ $totalAbsensiBulanIni }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Alpha:</span>
                            <strong class="text-danger">{{ $totalAlphaBulanIni }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Potensi SP:</span>
                            <strong class="text-warning">{{ $potensiSP }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
