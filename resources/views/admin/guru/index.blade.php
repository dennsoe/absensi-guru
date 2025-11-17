@extends('layouts.app')

@section('title', 'Kelola Data Guru')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-person-badge text-primary me-2"></i>
                    Kelola Data Guru
                </h2>
                <p class="text-muted mb-0">Manajemen data guru dan pengajar</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Guru
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.guru') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Cari Guru</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                placeholder="Nama, NIP, atau email...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutkan</label>
                            <select class="form-select" name="sort">
                                <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama A-Z
                                </option>
                                <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama Z-A
                                </option>
                                <option value="nip_asc" {{ request('sort') == 'nip_asc' ? 'selected' : '' }}>NIP Asc
                                </option>
                                <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>
                                    Terbaru</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guru Table -->
        <div class="card">
            <div class="card-body">
                @if ($guru_list->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">Tidak ada data guru</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">NIP</th>
                                    <th width="25%">Nama Lengkap</th>
                                    <th width="20%">Email</th>
                                    <th width="15%">No. Telepon</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guru_list as $index => $guru)
                                    <tr>
                                        <td>{{ $guru_list->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $guru->nip }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ substr($guru->nama, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $guru->nama }}</div>
                                                    @if ($guru->user)
                                                        <small class="text-muted">
                                                            <i class="bi bi-person-circle"></i>
                                                            {{ $guru->user->username }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="bi bi-envelope text-muted me-1"></i>
                                            {{ $guru->email ?? '-' }}
                                        </td>
                                        <td>
                                            <i class="bi bi-telephone text-muted me-1"></i>
                                            {{ $guru->no_telepon ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($guru->status == 'aktif')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-x-circle me-1"></i>Non-Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                                    class="btn btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="confirmDelete({{ $guru->id }}, '{{ $guru->nama }}')"
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
                            Menampilkan {{ $guru_list->firstItem() }} - {{ $guru_list->lastItem() }}
                            dari {{ $guru_list->total() }} data
                        </div>
                        <div>
                            {{ $guru_list->links() }}
                        </div>
                    </div>
                @endif
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
                    <p>Apakah Anda yakin ingin menghapus guru <strong id="guruName"></strong>?</p>
                    <p class="text-danger mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
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
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(id, nama) {
            document.getElementById('guruName').textContent = nama;
            document.getElementById('deleteForm').action = `/admin/guru/${id}`;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
