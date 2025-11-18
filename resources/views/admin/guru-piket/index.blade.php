@extends('layouts.app')

@section('title', 'Manajemen Guru Piket')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Guru Piket</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Manajemen Guru Piket
                    </h2>
                    <p class="text-muted mb-0">Kelola jadwal dan assignment guru piket</p>
                </div>
                <div>
                    <a href="{{ route('admin.guru-piket.assign') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Assign Guru Piket
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

        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.guru-piket.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-select">
                            <option value="">Semua Hari</option>
                            <option value="Senin" {{ request('hari') === 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ request('hari') === 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ request('hari') === 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ request('hari') === 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ request('hari') === 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ request('hari') === 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cari Guru</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama guru..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Jadwal Guru Piket per Hari -->
        <div class="row">
            @php
                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'];
            @endphp

            @foreach ($hariList as $index => $hari)
                @php
                    $piketHari = $guruPiket->where('hari', $hari);
                @endphp
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-{{ $colors[$index] }} text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-calendar-check me-2"></i>{{ $hari }}
                                <span class="badge bg-white text-{{ $colors[$index] }} float-end">
                                    {{ $piketHari->count() }} Guru
                                </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($piketHari->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach ($piketHari as $piket)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-3">
                                                        {{ strtoupper(substr($piket->guru->nama, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $piket->guru->nama }}</h6>
                                                        <small class="text-muted">{{ $piket->guru->nip }}</small>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($piket->status === 'aktif')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Non-Aktif</span>
                                                    @endif
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="confirmDelete({{ $piket->id }}, '{{ $piket->guru->nama }}', '{{ $hari }}')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                    <p class="text-muted mt-2 mb-0">Belum ada guru piket untuk {{ $hari }}</p>
                                    <a href="{{ route('admin.guru-piket.assign') }}?hari={{ $hari }}"
                                        class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Assign Guru
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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
                    <p>Apakah Anda yakin ingin menghapus assignment guru piket <strong id="guruName"></strong> pada hari
                        <strong id="hariName"></strong>?
                    </p>
                    <p class="text-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Guru akan dihapus dari jadwal piket hari tersebut.
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
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(id, nama, hari) {
            document.getElementById('guruName').textContent = nama;
            document.getElementById('hariName').textContent = hari;
            document.getElementById('deleteForm').action = '{{ route('admin.guru-piket.destroy', ':id') }}'.replace(':id',
                id);
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
