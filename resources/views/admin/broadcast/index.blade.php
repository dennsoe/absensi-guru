@extends('layouts.app')

@section('title', 'Broadcast Message')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Broadcast Message</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-megaphone text-primary me-2"></i>
                Broadcast Message
            </h2>
            <p class="text-muted mb-0">Kirim pesan ke semua pengguna atau grup tertentu</p>
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

        <div class="row">
            <!-- Form Broadcast -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Buat Broadcast Baru
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.broadcast.send') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Penerima <span class="text-danger">*</span></label>
                                <select name="target" class="form-select" id="targetSelect" required
                                    onchange="toggleTargetInfo()">
                                    <option value="">Pilih Penerima</option>
                                    <option value="all">Semua Pengguna</option>
                                    <option value="admin">Admin</option>
                                    <option value="guru">Semua Guru</option>
                                    <option value="ketua_kelas">Semua Ketua Kelas</option>
                                    <option value="guru_piket">Semua Guru Piket</option>
                                </select>
                                <div id="targetInfo" class="mt-2 text-muted small"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Pesan <span class="text-danger">*</span></label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Contoh: Pengumuman Penting" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prioritas</label>
                                <select name="priority" class="form-select">
                                    <option value="normal">Normal</option>
                                    <option value="high">Tinggi</option>
                                    <option value="urgent">Mendesak</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="8"
                                    placeholder="Tulis pesan broadcast di sini..." required>{{ old('message') }}</textarea>
                                <small class="text-muted">Maksimal 500 karakter</small>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="send_email" class="form-check-input" id="sendEmail"
                                    value="1">
                                <label class="form-check-label" for="sendEmail">
                                    Kirim juga via Email
                                </label>
                                <small class="text-muted d-block">Pesan akan dikirim ke email terdaftar pengguna</small>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" name="send_notification" class="form-check-input" id="sendNotif"
                                    value="1" checked>
                                <label class="form-check-label" for="sendNotif">
                                    Kirim Push Notification
                                </label>
                                <small class="text-muted d-block">Notifikasi akan muncul di aplikasi</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" onclick="previewMessage()">
                                    <i class="bi bi-eye me-1"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Kirim Broadcast
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Riwayat Broadcast -->
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Broadcast
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($broadcastHistory->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Judul</th>
                                            <th>Penerima</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($broadcastHistory as $broadcast)
                                            <tr>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($broadcast->created_at)->format('d/m/Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <strong>{{ $broadcast->title }}</strong>
                                                    @if ($broadcast->priority === 'urgent')
                                                        <span class="badge bg-danger ms-1">Mendesak</span>
                                                    @elseif($broadcast->priority === 'high')
                                                        <span class="badge bg-warning ms-1">Tinggi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ ucfirst($broadcast->target) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">Terkirim</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $broadcastHistory->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada riwayat broadcast</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-people me-2"></i>Statistik Pengguna
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Pengguna:</span>
                            <strong>{{ $totalUsers }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Admin:</span>
                            <strong>{{ $totalAdmin }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Guru:</span>
                            <strong>{{ $totalGuru }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ketua Kelas:</span>
                            <strong>{{ $totalKetuaKelas }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Guru Piket:</span>
                            <strong>{{ $totalGuruPiket }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Tips Broadcast
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Gunakan judul yang jelas dan informatif</li>
                            <li>Pastikan pesan singkat dan mudah dipahami</li>
                            <li>Gunakan prioritas "Mendesak" hanya untuk hal penting</li>
                            <li>Email broadcast memerlukan waktu lebih lama</li>
                            <li>Periksa target penerima sebelum mengirim</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>Peringatan
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 small">
                            <strong>Broadcast tidak dapat dibatalkan setelah dikirim.</strong>
                            Pastikan semua informasi sudah benar sebelum mengirim pesan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Pesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" id="previewTarget"></div>
                    <h6 id="previewTitle" class="fw-bold"></h6>
                    <p id="previewMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const userStats = {
            all: {{ $totalUsers }},
            admin: {{ $totalAdmin }},
            guru: {{ $totalGuru }},
            ketua_kelas: {{ $totalKetuaKelas }},
            guru_piket: {{ $totalGuruPiket }}
        };

        function toggleTargetInfo() {
            const target = document.getElementById('targetSelect').value;
            const infoDiv = document.getElementById('targetInfo');

            if (target && userStats[target] !== undefined) {
                const count = userStats[target];
                infoDiv.innerHTML =
                    `<i class="bi bi-info-circle me-1"></i> Pesan akan dikirim ke <strong>${count} pengguna</strong>`;
            } else {
                infoDiv.innerHTML = '';
            }
        }

        function previewMessage() {
            const target = document.getElementById('targetSelect').value;
            const title = document.querySelector('input[name="title"]').value;
            const message = document.querySelector('textarea[name="message"]').value;

            if (!target || !title || !message) {
                alert('Mohon lengkapi semua field yang wajib');
                return;
            }

            const targetText = document.getElementById('targetSelect').selectedOptions[0].text;
            document.getElementById('previewTarget').textContent = 'Penerima: ' + targetText;
            document.getElementById('previewTitle').textContent = title;
            document.getElementById('previewMessage').textContent = message;

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }
    </script>
@endpush
