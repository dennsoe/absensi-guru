@extends('layouts.app')

@section('title', 'Edit Data Guru')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.guru') }}">Data Guru</a></li>
                    <li class="breadcrumb-item active">Edit Guru</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit Data Guru
            </h2>
            <p class="text-muted mb-0">Perbarui data guru: <strong>{{ $guru->nama }}</strong></p>
        </div>

        <form method="POST" action="{{ route('admin.guru.update', $guru->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Data Guru -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-vcard me-2"></i>Data Guru
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">NIP</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror"
                                        name="nip" value="{{ old('nip', $guru->nip) }}" required>
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        name="nama" value="{{ old('nama', $guru->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $guru->email) }}">
                                    <small class="text-muted">Format: nama@domain.com</small>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror"
                                        name="no_telepon" value="{{ old('no_telepon', $guru->no_telepon) }}">
                                    <small class="text-muted">Format: 08xxxxxxxxxx</small>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', $guru->alamat) }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status"
                                        required>
                                        <option value="">Pilih Status</option>
                                        <option value="aktif"
                                            {{ old('status', $guru->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif"
                                            {{ old('status', $guru->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Akun & Info -->
                <div class="col-lg-4">
                    @if ($guru->user)
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-shield-check me-2"></i>Data Akun
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control-plaintext"
                                        value="{{ $guru->user->username }}" readonly>
                                    <small class="text-muted">Username tidak dapat diubah</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control-plaintext"
                                        value="{{ ucfirst($guru->user->role) }}" readonly>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Status Akun</label>
                                    <div>
                                        @if ($guru->user->status == 'aktif')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Non-Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Update -->
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">
                                    <i class="bi bi-key me-2"></i>Ubah Password (Opsional)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Guru ini belum memiliki akun pengguna
                        </div>
                    @endif

                    <!-- Info Card -->
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="text-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informasi
                            </h6>
                            <ul class="small mb-0 ps-3">
                                <li>Dibuat: {{ $guru->created_at->format('d M Y H:i') }}</li>
                                <li>Terakhir diubah: {{ $guru->updated_at->format('d M Y H:i') }}</li>
                                @if ($jadwal_count = $guru->jadwalMengajar()->count())
                                    <li class="text-danger mt-2">
                                        <strong>{{ $jadwal_count }} jadwal mengajar aktif</strong>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.guru') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
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
