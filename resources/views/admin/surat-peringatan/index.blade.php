@extends('layouts.app')

@section('title', 'Surat Peringatan')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Surat Peringatan</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-file-earmark-text text-warning me-2"></i>
                        Surat Peringatan
                    </h2>
                    <p class="text-muted mb-0">Kelola surat peringatan untuk guru</p>
                </div>
                <a href="{{ route('admin.surat-peringatan.generate') }}" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-1"></i> Generate Surat Peringatan
                </a>
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
                <form method="GET" action="{{ route('admin.surat-peringatan.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tingkat SP</label>
                        <select name="tingkat" class="form-select">
                            <option value="">Semua Tingkat</option>
                            <option value="1" {{ request('tingkat') === '1' ? 'selected' : '' }}>SP 1</option>
                            <option value="2" {{ request('tingkat') === '2' ? 'selected' : '' }}>SP 2</option>
                            <option value="3" {{ request('tingkat') === '3' ? 'selected' : '' }}>SP 3</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
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

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total SP</h6>
                                <h3 class="mb-0">{{ $totalSP }}</h3>
                            </div>
                            <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">SP 1</h6>
                                <h3 class="mb-0">{{ $sp1Count }}</h3>
                            </div>
                            <i class="bi bi-1-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-orange text-white" style="background-color: #fd7e14;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">SP 2</h6>
                                <h3 class="mb-0">{{ $sp2Count }}</h3>
                            </div>
                            <i class="bi bi-2-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">SP 3</h6>
                                <h3 class="mb-0">{{ $sp3Count }}</h3>
                            </div>
                            <i class="bi bi-3-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Surat Peringatan -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Surat Peringatan
                </h5>
            </div>
            <div class="card-body">
                @if ($suratPeringatanList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>No. Surat</th>
                                    <th>Guru</th>
                                    <th>Tingkat</th>
                                    <th>Periode</th>
                                    <th>Total Alpha</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suratPeringatanList as $index => $sp)
                                    <tr>
                                        <td>{{ $suratPeringatanList->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $sp->nomor_surat }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $sp->guru->nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $sp->guru->nip }}</small>
                                        </td>
                                        <td>
                                            @if ($sp->tingkat == 1)
                                                <span class="badge bg-warning">SP 1</span>
                                            @elseif($sp->tingkat == 2)
                                                <span class="badge" style="background-color: #fd7e14;">SP 2</span>
                                            @else
                                                <span class="badge bg-danger">SP 3</span>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($sp->periode_awal)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($sp->periode_akhir)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ $sp->total_alpha }} hari</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.surat-peringatan.preview', $sp->id) }}"
                                                    class="btn btn-outline-primary" target="_blank" title="Preview PDF">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.surat-peringatan.download', $sp->id) }}"
                                                    class="btn btn-outline-success" title="Download PDF">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="deleteSP({{ $sp->id }})" title="Hapus">
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
                            Menampilkan {{ $suratPeringatanList->firstItem() }} - {{ $suratPeringatanList->lastItem() }}
                            dari {{ $suratPeringatanList->total() }} surat
                        </div>
                        <div>
                            {{ $suratPeringatanList->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada surat peringatan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Surat Peringatan
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Kriteria Surat Peringatan</h6>
                        <ul class="mb-3">
                            <li><strong>SP 1:</strong> Alpha 3-5 hari dalam sebulan</li>
                            <li><strong>SP 2:</strong> Alpha 6-9 hari dalam sebulan</li>
                            <li><strong>SP 3:</strong> Alpha 10+ hari dalam sebulan</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Generate Otomatis</h6>
                        <ul class="mb-3">
                            <li>Sistem otomatis generate SP setiap awal bulan</li>
                            <li>Berdasarkan data absensi bulan sebelumnya</li>
                            <li>Dapat di-generate manual kapan saja</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function deleteSP(spId) {
            if (!confirm('Apakah Anda yakin ingin menghapus surat peringatan ini?')) {
                return;
            }

            fetch('{{ route('admin.surat-peringatan.destroy', '') }}/' + spId, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus surat peringatan');
                });
        }
    </script>
@endpush
