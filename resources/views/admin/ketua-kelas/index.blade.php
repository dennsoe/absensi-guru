@extends('layouts.app')

@section('title', 'Manajemen Ketua Kelas')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Ketua Kelas</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-badge text-success me-2"></i>
                        Manajemen Ketua Kelas
                    </h2>
                    <p class="text-muted mb-0">Kelola assignment ketua kelas untuk setiap kelas</p>
                </div>
                <div>
                    <a href="{{ route('admin.ketua-kelas.assign') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Assign Ketua Kelas
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Kelas</h6>
                                <h3 class="mb-0">{{ $totalKelas }}</h3>
                            </div>
                            <i class="bi bi-building fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Sudah Ada Ketua</h6>
                                <h3 class="mb-0">{{ $kelasWithKetua }}</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Belum Ada Ketua</h6>
                                <h3 class="mb-0">{{ $kelasWithoutKetua }}</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Ketua Aktif</h6>
                                <h3 class="mb-0">{{ $ketuaKelasAktif }}</h3>
                            </div>
                            <i class="bi bi-people fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.ketua-kelas.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tingkat</label>
                        <select name="tingkat" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="10" {{ request('tingkat') === '10' ? 'selected' : '' }}>Kelas 10</option>
                            <option value="11" {{ request('tingkat') === '11' ? 'selected' : '' }}>Kelas 11</option>
                            <option value="12" {{ request('tingkat') === '12' ? 'selected' : '' }}>Kelas 12</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="with_ketua" {{ request('status') === 'with_ketua' ? 'selected' : '' }}>Sudah Ada
                                Ketua</option>
                            <option value="without_ketua" {{ request('status') === 'without_ketua' ? 'selected' : '' }}>
                                Belum Ada Ketua</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Kelas dan Ketua Kelas -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Kelas & Ketua Kelas
                </h5>
            </div>
            <div class="card-body">
                @if ($kelasList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Ketua Kelas</th>
                                    <th>NIP Ketua</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelasList as $index => $kelas)
                                    <tr>
                                        <td>{{ $kelasList->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $kelas->nama }}</strong>
                                            <br>
                                            <small class="text-muted">Tingkat {{ $kelas->tingkat }}</small>
                                        </td>
                                        <td>
                                            @if ($kelas->waliKelas)
                                                <i class="bi bi-person text-primary me-1"></i>
                                                {{ $kelas->waliKelas->nama }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kelas->ketuaKelas)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        {{ strtoupper(substr($kelas->ketuaKelas->nama, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $kelas->ketuaKelas->nama }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="bi bi-person-circle"></i>
                                                            {{ $kelas->ketuaKelas->user->username ?? '-' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted fst-italic">Belum ada ketua kelas</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($kelas->ketuaKelas)
                                                {{ $kelas->ketuaKelas->nip }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($kelas->ketuaKelas)
                                                @if ($kelas->ketuaKelas->status === 'aktif')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle me-1"></i>Non-Aktif
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>Belum Assign
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($kelas->ketuaKelas)
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.ketua-kelas.assign') }}?kelas_id={{ $kelas->id }}"
                                                        class="btn btn-outline-primary" title="Ganti Ketua">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="confirmRemove({{ $kelas->id }}, '{{ $kelas->ketuaKelas->nama }}', '{{ $kelas->nama }}')"
                                                        title="Hapus Ketua">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <a href="{{ route('admin.ketua-kelas.assign') }}?kelas_id={{ $kelas->id }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Assign
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $kelasList->firstItem() }} - {{ $kelasList->lastItem() }}
                            dari {{ $kelasList->total() }} kelas
                        </div>
                        <div>
                            {{ $kelasList->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Tidak ada data kelas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Remove Confirmation Modal -->
    <div class="modal fade" id="removeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Ketua Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus <strong id="ketuaName"></strong> sebagai ketua kelas <strong
                            id="kelasName"></strong>?</p>
                    <p class="text-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Ketua kelas akan dihapus dan kelas akan tidak memiliki ketua.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="removeForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.85rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmRemove(kelasId, ketuaNama, kelasNama) {
            document.getElementById('ketuaName').textContent = ketuaNama;
            document.getElementById('kelasName').textContent = kelasNama;
            document.getElementById('removeForm').action = '{{ route('admin.ketua-kelas.remove', ':id') }}'.replace(':id',
                kelasId);
            new bootstrap.Modal(document.getElementById('removeModal')).show();
        }
    </script>
@endpush
