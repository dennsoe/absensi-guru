@extends('layouts.app')

@section('title', 'Kelola Hari Libur')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kalender Libur</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-calendar-x text-danger me-2"></i>
                        Kalender Hari Libur
                    </h2>
                    <p class="text-muted mb-0">Kelola hari libur nasional dan sekolah</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLiburModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Hari Libur
                    </button>
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
                                <h6 class="card-title mb-1">Total Hari Libur</h6>
                                <h3 class="mb-0">{{ $totalLibur }}</h3>
                            </div>
                            <i class="bi bi-calendar-x fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Libur Nasional</h6>
                                <h3 class="mb-0">{{ $liburNasional }}</h3>
                            </div>
                            <i class="bi bi-flag fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Libur Sekolah</h6>
                                <h3 class="mb-0">{{ $liburSekolah }}</h3>
                            </div>
                            <i class="bi bi-building fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Bulan Ini</h6>
                                <h3 class="mb-0">{{ $liburBulanIni }}</h3>
                            </div>
                            <i class="bi bi-calendar-event fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.kalender-libur.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Libur</label>
                        <select name="jenis" class="form-select">
                            <option value="">Semua Jenis</option>
                            <option value="nasional" {{ request('jenis') === 'nasional' ? 'selected' : '' }}>Libur Nasional
                            </option>
                            <option value="sekolah" {{ request('jenis') === 'sekolah' ? 'selected' : '' }}>Libur Sekolah
                            </option>
                            <option value="cuti_bersama" {{ request('jenis') === 'cuti_bersama' ? 'selected' : '' }}>Cuti
                                Bersama</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Hari Libur -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Hari Libur
                </h5>
            </div>
            <div class="card-body">
                @if ($liburList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Tanggal</th>
                                    <th>Nama Libur</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th class="text-center" width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($liburList as $index => $libur)
                                    <tr>
                                        <td>{{ $liburList->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($libur->tanggal)->translatedFormat('d F Y') }}</strong>
                                            <br>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($libur->tanggal)->translatedFormat('l') }}</small>
                                        </td>
                                        <td><strong>{{ $libur->nama }}</strong></td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'nasional' => ['bg' => 'success', 'icon' => 'flag'],
                                                    'sekolah' => ['bg' => 'info', 'icon' => 'building'],
                                                    'cuti_bersama' => ['bg' => 'warning', 'icon' => 'calendar-check'],
                                                ];
                                                $badge = $badges[$libur->jenis] ?? [
                                                    'bg' => 'secondary',
                                                    'icon' => 'calendar-x',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $badge['bg'] }}">
                                                <i class="bi bi-{{ $badge['icon'] }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $libur->jenis)) }}
                                            </span>
                                        </td>
                                        <td>{{ $libur->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="editLibur({{ json_encode($libur) }})" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete({{ $libur->id }}, '{{ $libur->nama }}')"
                                                    title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $liburList->firstItem() }} - {{ $liburList->lastItem() }}
                            dari {{ $liburList->total() }} hari libur
                        </div>
                        <div>
                            {{ $liburList->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada data hari libur</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Libur Modal -->
    <div class="modal fade" id="addLiburModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.kalender-libur.store') }}">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Hari Libur
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label required">Nama Libur</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Jenis Libur</label>
                            <select class="form-select" name="jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="nasional">Libur Nasional</option>
                                <option value="sekolah">Libur Sekolah</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Libur Modal -->
    <div class="modal fade" id="editLiburModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editLiburForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil me-2"></i>Edit Hari Libur
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label class="form-label required">Nama Libur</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Tanggal</label>
                            <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Jenis Libur</label>
                            <select class="form-select" id="edit_jenis" name="jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="nasional">Libur Nasional</option>
                                <option value="sekolah">Libur Sekolah</option>
                                <option value="cuti_bersama">Cuti Bersama</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus hari libur <strong id="liburName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" class="d-inline">
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
        .required::after {
            content: " *";
            color: red;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function editLibur(libur) {
            document.getElementById('edit_id').value = libur.id;
            document.getElementById('edit_nama').value = libur.nama;
            document.getElementById('edit_tanggal').value = libur.tanggal;
            document.getElementById('edit_jenis').value = libur.jenis;
            document.getElementById('edit_keterangan').value = libur.keterangan || '';
            document.getElementById('editLiburForm').action = '{{ route('admin.kalender-libur.update', ':id') }}'.replace(
                ':id', libur.id);
            new bootstrap.Modal(document.getElementById('editLiburModal')).show();
        }

        function confirmDelete(id, nama) {
            document.getElementById('liburName').textContent = nama;
            document.getElementById('deleteForm').action = '{{ route('admin.kalender-libur.destroy', ':id') }}'.replace(
                ':id', id);
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
