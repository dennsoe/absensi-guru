@extends('layouts.app')

@section('title', 'Backup Database')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Backup Database</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-database text-primary me-2"></i>
                        Backup Database
                    </h2>
                    <p class="text-muted mb-0">Kelola backup database sistem</p>
                </div>
                <button type="button" class="btn btn-primary" onclick="triggerBackup()">
                    <i class="bi bi-plus-circle me-1"></i> Backup Sekarang
                </button>
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
                                <h6 class="card-title mb-1">Total Backup</h6>
                                <h3 class="mb-0">{{ $totalBackup }}</h3>
                            </div>
                            <i class="bi bi-archive fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Backup Hari Ini</h6>
                                <h3 class="mb-0">{{ $todayBackup }}</h3>
                            </div>
                            <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Total Ukuran</h6>
                                <h3 class="mb-0">{{ $totalSize }}</h3>
                            </div>
                            <i class="bi bi-hdd fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-1">Backup Terakhir</h6>
                                <h3 class="mb-0" style="font-size: 1.2rem;">
                                    {{ $lastBackupDate ?? 'Belum Ada' }}
                                </h3>
                            </div>
                            <i class="bi bi-clock-history fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Backup -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Backup
                </h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="cleanupOldBackups()">
                        <i class="bi bi-trash me-1"></i> Bersihkan Backup Lama
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if ($backupList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama File</th>
                                    <th>Tanggal</th>
                                    <th>Ukuran</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($backupList as $index => $backup)
                                    <tr>
                                        <td>{{ $backupList->firstItem() + $index }}</td>
                                        <td>
                                            <i class="bi bi-file-earmark-zip text-primary me-2"></i>
                                            <strong>{{ $backup['filename'] }}</strong>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 text-muted me-1"></i>
                                            {{ $backup['date'] }}
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock text-muted me-1"></i>
                                                {{ $backup['time'] }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $backup['size'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.backup.download', $backup['filename']) }}"
                                                    class="btn btn-outline-primary" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="deleteBackup('{{ $backup['filename'] }}')" title="Hapus">
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
                            Menampilkan {{ $backupList->firstItem() }} - {{ $backupList->lastItem() }}
                            dari {{ $backupList->total() }} backup
                        </div>
                        <div>
                            {{ $backupList->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada backup database</p>
                        <button type="button" class="btn btn-primary mt-3" onclick="triggerBackup()">
                            <i class="bi bi-plus-circle me-1"></i> Buat Backup Pertama
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Backup
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Backup Otomatis</h6>
                        <ul class="mb-3">
                            <li>Backup otomatis dilakukan setiap hari pukul 02:00 WIB</li>
                            <li>Sistem menyimpan backup 30 hari terakhir</li>
                            <li>Backup lama otomatis terhapus</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Backup Manual</h6>
                        <ul class="mb-3">
                            <li>Klik tombol "Backup Sekarang" untuk backup manual</li>
                            <li>Backup dapat didownload kapan saja</li>
                            <li>Gunakan untuk backup sebelum update sistem</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Simpan backup di lokasi aman terpisah dari server untuk mencegah kehilangan
                    data total.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function triggerBackup() {
            if (!confirm('Apakah Anda yakin ingin membuat backup database sekarang?')) {
                return;
            }

            // Show loading
            const btn = event.target;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            fetch('{{ route('admin.backup.trigger') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Backup berhasil dibuat!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat membuat backup');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                });
        }

        function deleteBackup(filename) {
            if (!confirm('Apakah Anda yakin ingin menghapus backup ini?')) {
                return;
            }

            fetch('{{ route('admin.backup.delete', '') }}/' + filename, {
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
                    alert('Terjadi kesalahan saat menghapus backup');
                });
        }

        function cleanupOldBackups() {
            if (!confirm('Apakah Anda yakin ingin menghapus semua backup yang lebih dari 30 hari?')) {
                return;
            }

            fetch('{{ route('admin.backup.cleanup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Berhasil menghapus ' + data.deleted + ' backup lama');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat membersihkan backup');
                });
        }
    </script>
@endpush
