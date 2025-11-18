@extends('layouts.app')

@section('title', 'Validasi Absensi')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ketua-kelas.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Validasi Absensi</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>
                Validasi Absensi
            </h2>
            <p class="text-muted mb-0">Validasi hasil scan QR code absensi guru</p>
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
                <form method="GET" action="{{ route('ketua-kelas.validasi') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="validated" {{ request('status') === 'validated' ? 'selected' : '' }}>Tervalidasi
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-select"
                            value="{{ request('tanggal', date('Y-m-d')) }}">
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

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Absensi</h6>
                                <h3 class="mb-0">{{ $totalAbsensi }}</h3>
                            </div>
                            <i class="bi bi-list-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Pending</h6>
                                <h3 class="mb-0">{{ $pendingCount }}</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Tervalidasi</h6>
                                <h3 class="mb-0">{{ $validatedCount }}</h3>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Ditolak</h6>
                                <h3 class="mb-0">{{ $rejectedCount }}</h3>
                            </div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Absensi -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Absensi Hari Ini
                </h5>
            </div>
            <div class="card-body">
                @if ($absensiList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Guru</th>
                                    <th>Jam Masuk</th>
                                    <th>Selfie</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensiList as $index => $absensi)
                                    <tr>
                                        <td>{{ $absensiList->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $absensi->guru->nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $absensi->guru->nip }}</small>
                                        </td>
                                        <td>
                                            <i class="bi bi-clock text-primary me-1"></i>
                                            {{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }}
                                        </td>
                                        <td>
                                            @if ($absensi->foto_selfie)
                                                <a href="{{ asset('storage/' . $absensi->foto_selfie) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $absensi->foto_selfie) }}"
                                                        alt="Selfie" class="rounded"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($absensi->latitude && $absensi->longitude)
                                                <a href="https://www.google.com/maps?q={{ $absensi->latitude }},{{ $absensi->longitude }}"
                                                    target="_blank" class="text-primary">
                                                    <i class="bi bi-geo-alt"></i> Lihat Map
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->validasi_ketua === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($absensi->validasi_ketua === 'validated')
                                                <span class="badge bg-success">Tervalidasi</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($absensi->validasi_ketua === 'pending')
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-success"
                                                        onclick="validateAbsensi({{ $absensi->id }}, 'validated')"
                                                        title="Validasi">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="validateAbsensi({{ $absensi->id }}, 'rejected')"
                                                        title="Tolak">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="validateAbsensi({{ $absensi->id }}, 'pending')"
                                                    title="Reset">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                                </button>
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
                            Menampilkan {{ $absensiList->firstItem() }} - {{ $absensiList->lastItem() }}
                            dari {{ $absensiList->total() }} absensi
                        </div>
                        <div>
                            {{ $absensiList->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Tidak ada data absensi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function validateAbsensi(absensiId, status) {
            if (!confirm('Apakah Anda yakin ingin ' + (status === 'validated' ? 'memvalidasi' : status === 'rejected' ?
                    'menolak' : 'mereset') + ' absensi ini?')) {
                return;
            }

            fetch('{{ route('ketua-kelas.validasi.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        absensi_id: absensiId,
                        status: status
                    })
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
                    alert('Terjadi kesalahan saat memproses validasi');
                });
        }
    </script>
@endpush
