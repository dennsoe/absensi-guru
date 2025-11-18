@extends('layouts.app')

@section('title', 'Absensi Keluar')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Absensi Keluar</li>
                </ol>
            </nav>
            <h2 class="mb-1">
                <i class="bi bi-box-arrow-right text-danger me-2"></i>
                Absensi Keluar
            </h2>
            <p class="text-muted mb-0">Scan QR Code untuk absensi keluar</p>
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
            <!-- Absensi Keluar Form -->
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-qr-code-scan me-2"></i>Scan QR Code Keluar
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($absensiMasukHariIni)
                            @if ($absensiMasukHariIni->jam_keluar)
                                <!-- Already Clock Out -->
                                <div class="alert alert-info text-center py-4">
                                    <i class="bi bi-check-circle fs-1"></i>
                                    <h4 class="mt-3">Anda Sudah Absen Keluar</h4>
                                    <p class="mb-2">Jam Masuk:
                                        <strong>{{ \Carbon\Carbon::parse($absensiMasukHariIni->jam_masuk)->format('H:i') }}</strong>
                                    </p>
                                    <p class="mb-0">Jam Keluar:
                                        <strong>{{ \Carbon\Carbon::parse($absensiMasukHariIni->jam_keluar)->format('H:i') }}</strong>
                                    </p>
                                    <hr>
                                    <a href="{{ route('guru.dashboard') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-house me-1"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                            @else
                                <!-- Can Clock Out -->
                                <div class="alert alert-success text-center mb-4">
                                    <i class="bi bi-check-circle fs-3"></i>
                                    <h5 class="mt-2 mb-1">Absensi Masuk Terdeteksi</h5>
                                    <p class="mb-0">Jam Masuk:
                                        <strong>{{ \Carbon\Carbon::parse($absensiMasukHariIni->jam_masuk)->format('H:i') }}</strong>
                                    </p>
                                </div>

                                <!-- QR Scanner -->
                                <div class="text-center mb-4">
                                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                                    <div id="qr-reader-results" class="mt-3"></div>
                                </div>

                                <!-- Manual Input (Alternative) -->
                                <div class="text-center">
                                    <p class="text-muted">Atau gunakan input manual:</p>
                                    <form method="POST" action="{{ route('guru.absensi.proses-keluar') }}">
                                        @csrf
                                        <input type="hidden" name="absensi_id" value="{{ $absensiMasukHariIni->id }}">
                                        <button type="submit" class="btn btn-danger btn-lg">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Absen Keluar Sekarang
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <!-- No Clock In Today -->
                            <div class="alert alert-warning text-center py-4">
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                                <h4 class="mt-3">Belum Ada Absensi Masuk</h4>
                                <p class="mb-3">Anda belum melakukan absensi masuk hari ini. Silakan absen masuk terlebih
                                    dahulu.</p>
                                <a href="{{ route('guru.absensi.scan-qr') }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Absen Masuk
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Pastikan Anda sudah melakukan absensi masuk sebelum absen keluar</li>
                            <li>Scan QR Code yang tersedia di lokasi absensi</li>
                            <li>Jam keluar akan tercatat secara otomatis</li>
                            <li>Pastikan GPS aktif untuk validasi lokasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        @if ($absensiMasukHariIni && !$absensiMasukHariIni->jam_keluar)
            function onScanSuccess(decodedText, decodedResult) {
                // Handle QR code scan result
                console.log(`Code matched = ${decodedText}`, decodedResult);

                // Send to server
                fetch('{{ route('guru.absensi.proses-keluar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_code: decodedText,
                            absensi_id: {{ $absensiMasukHariIni->id }}
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '{{ route('guru.dashboard') }}';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses absensi');
                    });
            }

            function onScanFailure(error) {
                // Handle scan failure
                console.warn(`Code scan error = ${error}`);
            }

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0
                },
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        @endif
    </script>
@endpush
