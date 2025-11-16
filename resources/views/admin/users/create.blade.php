@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Tambah User Baru</h1>
            <p class="page-subtitle">Buat akun guru atau admin baru</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="page-section">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Username untuk login ke aplikasi</small>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimal 6 karakter</small>
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            required>
                    </div>

                    <hr>

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIP --}}
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip"
                            name="nip" value="{{ old('nip') }}">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp"
                            name="no_hp" value="{{ old('no_hp') }}">
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Format: 08xxxxxxxxxx</small>
                    </div>

                    <hr>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role"
                            required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="guru_piket" {{ old('role') === 'guru_piket' ? 'selected' : '' }}>Guru Piket
                            </option>
                            <option value="kepala_sekolah" {{ old('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala
                                Sekolah</option>
                            <option value="kurikulum" {{ old('role') === 'kurikulum' ? 'selected' : '' }}>Kurikulum
                            </option>
                            <option value="ketua_kelas" {{ old('role') === 'ketua_kelas' ? 'selected' : '' }}>Ketua Kelas
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Guru (conditional) --}}
                    <div class="mb-3" id="guru-field" style="display: none;">
                        <label for="guru_id" class="form-label">Profil Guru <span class="text-danger">*</span></label>
                        <select class="form-control @error('guru_id') is-invalid @enderror" id="guru_id" name="guru_id">
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru_list as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }} - {{ $guru->nip ?? 'Tanpa NIP' }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Wajib diisi jika role bukan Guru</small>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                            {{ old('is_active', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Aktif (user dapat login)
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="page-section">
                <h3 class="section-title">Informasi</h3>
                <div class="alert-card primary">
                    <i class="bi bi-info-circle alert-card-icon"></i>
                    <div class="alert-card-body">
                        <div class="alert-card-title">Panduan Role</div>
                        <div class="alert-card-message">
                            <ul style="margin: 0; padding-left: 20px;">
                                <li><strong>Admin:</strong> Kelola seluruh data aplikasi</li>
                                <li><strong>Guru:</strong> Melakukan absensi & lihat jadwal</li>
                                <li><strong>Guru Piket:</strong> Monitoring absensi real-time</li>
                                <li><strong>Kepala Sekolah:</strong> Approval & laporan</li>
                                <li><strong>Kurikulum:</strong> Kelola jadwal & pengganti</li>
                                <li><strong>Ketua Kelas:</strong> Scan QR validasi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-section">
                <h3 class="section-title">Catatan</h3>
                <div class="alert-card warning">
                    <i class="bi bi-exclamation-triangle alert-card-icon"></i>
                    <div class="alert-card-body">
                        <div class="alert-card-message">
                            <ul style="margin: 0; padding-left: 20px;">
                                <li>Field bertanda <span class="text-danger">*</span> wajib diisi</li>
                                <li>Username harus unik</li>
                                <li>Password minimal 6 karakter</li>
                                <li>Role selain Guru memerlukan Profil Guru</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const guruField = document.getElementById('guru-field');
        const guruSelect = document.getElementById('guru_id');

        roleSelect.addEventListener('change', function() {
            if (this.value && this.value !== 'guru') {
                guruField.style.display = 'block';
                guruSelect.required = true;
            } else {
                guruField.style.display = 'none';
                guruSelect.required = false;
            }
        });

        // Trigger on page load if role is already selected
        if (roleSelect.value && roleSelect.value !== 'guru') {
            guruField.style.display = 'block';
            guruSelect.required = true;
        }
    </script>
@endpush
