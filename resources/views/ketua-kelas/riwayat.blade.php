@extends('layouts.app')

@section('title', 'Riwayat Scan QR')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">Riwayat Scan QR Code</h2>
                        <p class="text-muted mb-0">Histori absensi yang telah dipindai</p>
                    </div>
                    <a href="{{ route('ketua-kelas.scan-qr') }}" class="btn btn-primary">
                        <i class="bi bi-qr-code-scan"></i> Scan QR Baru
                    </a>
                </div>

                <!-- Filter Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="tanggal_mulai"
                                    value="{{ request('tanggal_mulai', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="tanggal_akhir"
                                    value="{{ request('tanggal_akhir', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>
                                        Terlambat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                    <a href="{{ route('ketua-kelas.riwayat') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Total Scan</p>
                                        <h3 class="fw-bold mb-0" id="totalScan">0</h3>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-qr-code" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Berhasil</p>
                                        <h3 class="fw-bold mb-0 text-success" id="totalBerhasil">0</h3>
                                    </div>
                                    <div class="text-success">
                                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Terlambat</p>
                                        <h3 class="fw-bold mb-0 text-warning" id="totalTerlambat">0</h3>
                                    </div>
                                    <div class="text-warning">
                                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Tingkat Keberhasilan</p>
                                        <h3 class="fw-bold mb-0 text-info" id="tingkatKeberhasilan">0%</h3>
                                    </div>
                                    <div class="text-info">
                                        <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Table -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Riwayat</h5>
                        <button class="btn btn-sm btn-outline-success" id="exportBtn">
                            <i class="bi bi-download"></i> Export Excel
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Waktu Scan</th>
                                        <th>Nama Guru</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Jam Jadwal</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="riwayatTableBody">
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            <span class="ms-2">Memuat data...</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav id="paginationContainer" class="mt-3">
                            <!-- Pagination will be inserted here -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Detail content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('scripts')
    <script>
        let currentPage = 1;

        // Load Riwayat Data
        async function loadRiwayat(page = 1) {
            try {
                const params = new URLSearchParams(window.location.search);
                params.set('page', page);

                // For now, use dummy data
                // Later: const response = await fetch(`/ketua-kelas/riwayat-data?${params}`);

                // Dummy data
                const data = {
                    data: [{
                            id: 1,
                            tanggal: '2025-11-16',
                            jam_absen: '07:15',
                            guru: {
                                nama: 'Drs. Ahmad Wijaya'
                            },
                            jadwal: {
                                mata_pelajaran: 'Matematika',
                                jam_mulai: '07:00',
                                jam_selesai: '08:30'
                            },
                            status_kehadiran: 'hadir',
                            keterangan: null
                        },
                        {
                            id: 2,
                            tanggal: '2025-11-16',
                            jam_absen: '08:45',
                            guru: {
                                nama: 'Sri Mulyani, S.Pd'
                            },
                            jadwal: {
                                mata_pelajaran: 'Fisika',
                                jam_mulai: '08:30',
                                jam_selesai: '10:00'
                            },
                            status_kehadiran: 'terlambat',
                            keterangan: 'Terlambat 15 menit'
                        }
                    ],
                    total: 2,
                    per_page: 10,
                    current_page: page
                };

                renderTable(data.data);
                renderPagination(data);
                updateStatistics(data.data);

            } catch (error) {
                console.error('Error loading riwayat:', error);
                document.getElementById('riwayatTableBody').innerHTML =
                    '<tr><td colspan="9" class="text-center text-danger py-4">Gagal memuat data</td></tr>';
            }
        }

        // Render Table
        function renderTable(data) {
            const tbody = document.getElementById('riwayatTableBody');

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data</td></tr>';
                return;
            }

            let html = '';
            data.forEach((item, index) => {
                const statusClass = item.status_kehadiran === 'hadir' ? 'success' :
                    item.status_kehadiran === 'terlambat' ? 'warning' : 'danger';
                const statusText = item.status_kehadiran === 'hadir' ? 'Hadir' :
                    item.status_kehadiran === 'terlambat' ? 'Terlambat' : 'Alfa';

                html += `
            <tr>
                <td>${(currentPage - 1) * 10 + index + 1}</td>
                <td>${formatDate(item.tanggal)}</td>
                <td>${item.jam_absen}</td>
                <td>${item.guru.nama}</td>
                <td>${item.jadwal.mata_pelajaran}</td>
                <td>${item.jadwal.jam_mulai} - ${item.jadwal.jam_selesai}</td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td class="small">${item.keterangan || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="showDetail(${item.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                </td>
            </tr>
        `;
            });

            tbody.innerHTML = html;
        }

        // Render Pagination
        function renderPagination(data) {
            const container = document.getElementById('paginationContainer');
            const totalPages = Math.ceil(data.total / data.per_page);

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<ul class="pagination justify-content-center">';

            // Previous
            html += `
        <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadRiwayat(${data.current_page - 1}); return false;">Previous</a>
        </li>
    `;

            // Pages
            for (let i = 1; i <= totalPages; i++) {
                html += `
            <li class="page-item ${i === data.current_page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="loadRiwayat(${i}); return false;">${i}</a>
            </li>
        `;
            }

            // Next
            html += `
        <li class="page-item ${data.current_page === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="loadRiwayat(${data.current_page + 1}); return false;">Next</a>
        </li>
    `;

            html += '</ul>';
            container.innerHTML = html;
        }

        // Update Statistics
        function updateStatistics(data) {
            const total = data.length;
            const berhasil = data.filter(item => item.status_kehadiran === 'hadir').length;
            const terlambat = data.filter(item => item.status_kehadiran === 'terlambat').length;
            const tingkat = total > 0 ? Math.round((berhasil / total) * 100) : 0;

            document.getElementById('totalScan').textContent = total;
            document.getElementById('totalBerhasil').textContent = berhasil;
            document.getElementById('totalTerlambat').textContent = terlambat;
            document.getElementById('tingkatKeberhasilan').textContent = tingkat + '%';
        }

        // Show Detail Modal
        function showDetail(id) {
            // Fetch detail data
            // For now, show dummy data
            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
        <div class="row">
            <div class="col-6 mb-2"><strong>Nama Guru:</strong></div>
            <div class="col-6 mb-2">Drs. Ahmad Wijaya</div>

            <div class="col-6 mb-2"><strong>Mata Pelajaran:</strong></div>
            <div class="col-6 mb-2">Matematika</div>

            <div class="col-6 mb-2"><strong>Tanggal:</strong></div>
            <div class="col-6 mb-2">16 November 2025</div>

            <div class="col-6 mb-2"><strong>Jam Jadwal:</strong></div>
            <div class="col-6 mb-2">07:00 - 08:30</div>

            <div class="col-6 mb-2"><strong>Waktu Scan:</strong></div>
            <div class="col-6 mb-2">07:15</div>

            <div class="col-6 mb-2"><strong>Status:</strong></div>
            <div class="col-6 mb-2"><span class="badge bg-success">Hadir</span></div>

            <div class="col-6 mb-2"><strong>Lokasi GPS:</strong></div>
            <div class="col-6 mb-2">-7.797068, 110.370529</div>

            <div class="col-6 mb-2"><strong>Jarak dari Sekolah:</strong></div>
            <div class="col-6 mb-2">45 meter</div>
        </div>
    `;

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }

        // Format Date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            return date.toLocaleDateString('id-ID', options);
        }

        // Export to Excel (placeholder)
        document.getElementById('exportBtn').addEventListener('click', function() {
            alert('Fitur export Excel akan segera tersedia');
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadRiwayat(1);
        });
    </script>
@endpush
