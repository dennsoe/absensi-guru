@extends('layouts.app')

@section('title', 'Assign Guru Piket')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.guru-piket.index') }}">Guru Piket</a></li>
                    <li class="breadcrumb-item active">Assign Guru</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-person-plus text-primary me-2"></i>
                Assign Guru Piket
            </h2>
            <p class="text-muted mb-0">Tetapkan guru sebagai petugas piket untuk hari tertentu</p>
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
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-plus me-2"></i>Form Assignment
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.guru-piket.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label required">Pilih Guru</label>
                                <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach ($availableGuru as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }} - {{ $guru->nip }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Pilih guru yang akan ditugaskan sebagai guru piket
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Hari</label>
                                <select name="hari" class="form-select @error('hari') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hari --</option>
                                    <option value="Senin"
                                        {{ old('hari') === 'Senin' || request('hari') === 'Senin' ? 'selected' : '' }}>Senin
                                    </option>
                                    <option value="Selasa"
                                        {{ old('hari') === 'Selasa' || request('hari') === 'Selasa' ? 'selected' : '' }}>
                                        Selasa</option>
                                    <option value="Rabu"
                                        {{ old('hari') === 'Rabu' || request('hari') === 'Rabu' ? 'selected' : '' }}>Rabu
                                    </option>
                                    <option value="Kamis"
                                        {{ old('hari') === 'Kamis' || request('hari') === 'Kamis' ? 'selected' : '' }}>
                                        Kamis</option>
                                    <option value="Jumat"
                                        {{ old('hari') === 'Jumat' || request('hari') === 'Jumat' ? 'selected' : '' }}>
                                        Jumat</option>
                                    <option value="Sabtu"
                                        {{ old('hari') === 'Sabtu' || request('hari') === 'Sabtu' ? 'selected' : '' }}>
                                        Sabtu</option>
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label required">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusAktif"
                                        value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statusAktif">
                                        <span class="badge bg-success">Aktif</span>
                                        <span class="ms-2 text-muted">Guru piket aktif bertugas</span>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" name="status" id="statusNonaktif"
                                        value="nonaktif" {{ old('status') === 'nonaktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusNonaktif">
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                        <span class="ms-2 text-muted">Sementara tidak bertugas</span>
                                    </label>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Simpan Assignment
                                </button>
                                <a href="{{ route('admin.guru-piket.index') }}" class="btn btn-secondary">
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
                            <li class="mb-2">Pilih guru yang akan ditugaskan sebagai guru piket</li>
                            <li class="mb-2">Tentukan hari bertugas</li>
                            <li class="mb-2">Satu guru bisa ditugaskan di beberapa hari</li>
                            <li class="mb-2">Status aktif berarti guru sedang bertugas piket</li>
                            <li>Guru piket bertugas monitoring kehadiran dan membuat laporan</li>
                        </ul>
                    </div>
                </div>

                <!-- Summary Guru Piket -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        @endphp
                        @foreach ($hariList as $hari)
                            @php
                                $count = $summaryByDay[$hari] ?? 0;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $hari }}:</span>
                                <span class="badge {{ $count > 0 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $count }} Guru
                                </span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between align-items-center fw-bold">
                            <span>Total Guru Piket:</span>
                            <span class="badge bg-primary">{{ $totalGuruPiket }} Guru</span>
                        </div>
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
