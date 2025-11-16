@extends('layouts.app')

@section('title', 'Absensi QR Code')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Absensi dengan QR Code</h2>
                    <p class="text-muted">Tunjukkan QR Code ini kepada Ketua Kelas untuk diabsen</p>
                </div>

                <!-- Alert GPS -->
                <div id="gpsAlert" class="alert alert-warning d-none">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span id="gpsMessage">Mengaktifkan GPS...</span>
                </div>

                <!-- Alert Error -->
                <div id="errorAlert" class="alert alert-danger d-none">
                    <i class="bi bi-x-circle"></i>
                    <span id="errorMessage"></span>
                </div>

                <!-- QR Code Card -->
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <!-- QR Code Container -->
                        <div id="qrcode" class="mb-4 d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Generating QR Code...</span>
                            </div>
                        </div>

                        <!-- Timer -->
                        <div class="mb-3">
                            <h5 class="text-muted">QR Code berlaku selama:</h5>
                            <h1 class="fw-bold text-primary" id="timer">5:00</h1>
                            <p class="text-muted small">QR Code akan otomatis diperbarui</p>
                        </div>

                        <!-- GPS Status -->
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-geo-alt-fill"></i>
                            <strong>Lokasi GPS:</strong>
                            <div id="gpsStatus" class="mt-2">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                <span class="ms-2">Mendeteksi lokasi...</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-light border">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-info-circle"></i> Cara Menggunakan:
                            </h6>
                            <ol class="text-start mb-0 small">
                                <li>Pastikan GPS Anda aktif</li>
                                <li>Pastikan Anda berada di area sekolah (radius 200 meter)</li>
                                <li>Tunjukkan QR Code ini ke Ketua Kelas</li>
                                <li>Ketua Kelas akan memindai dengan perangkat mereka</li>
                                <li>QR Code akan otomatis diperbarui setiap 5 menit</li>
                            </ol>
                        </div>

                        <!-- Refresh Button -->
                        <button type="button" class="btn btn-primary" id="refreshBtn">
                            <i class="bi bi-arrow-clockwise"></i> Perbarui QR Code
                        </button>
                    </div>
                </div>

                <!-- Riwayat Absensi Hari Ini -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Absensi Hari Ini</h5>
                    </div>
                    <div class="card-body">
                        <div id="riwayatAbsensi">
                            <div class="text-center text-muted py-3">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                <span class="ms-2">Memuat riwayat...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        let qrCodeInstance = null;
        let timerInterval = null;
        let countdown = 300; // 5 minutes in seconds
        let userLocation = null;

        // GPS Configuration
        const SEKOLAH_LAT = -7.797068; // Ganti dengan koordinat sekolah
        const SEKOLAH_LNG = 110.370529; // Ganti dengan koordinat sekolah
        const RADIUS_METER = 200;

        // Get User Location
        function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    reject(new Error('GPS tidak didukung oleh browser Anda'));
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        reject(error);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        // Calculate Distance (Haversine Formula)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth radius in meters
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c; // Distance in meters
        }

        // Generate QR Code
        async function generateQRCode() {
            try {
                // Get GPS Location
                showGPSAlert('Mengaktifkan GPS...', 'warning');
                userLocation = await getUserLocation();

                // Check if within school radius
                const distance = calculateDistance(
                    userLocation.latitude,
                    userLocation.longitude,
                    SEKOLAH_LAT,
                    SEKOLAH_LNG
                );

                if (distance > RADIUS_METER) {
                    showError(
                        `Anda berada ${Math.round(distance)} meter dari sekolah. Harap mendekat ke area sekolah (maksimal ${RADIUS_METER} meter)`
                        );
                    return;
                }

                hideGPSAlert();
                updateGPSStatus(userLocation.latitude, userLocation.longitude, distance);

                // Generate QR Data
                const qrData = {
                    user_id: {{ auth()->user()->id }},
                    guru_id: {{ auth()->user()->guru_id }},
                    timestamp: Date.now(),
                    latitude: userLocation.latitude,
                    longitude: userLocation.longitude,
                    expires: Date.now() + (5 * 60 * 1000) // 5 minutes
                };

                const qrString = btoa(JSON.stringify(qrData));

                // Clear previous QR Code
                document.getElementById('qrcode').innerHTML = '';

                // Generate new QR Code
                qrCodeInstance = new QRCode(document.getElementById('qrcode'), {
                    text: qrString,
                    width: 300,
                    height: 300,
                    colorDark: '#2563eb',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });

                // Start Timer
                countdown = 300;
                startTimer();

                hideError();
            } catch (error) {
                console.error('Error generating QR Code:', error);
                if (error.code === 1) {
                    showError('Akses GPS ditolak. Mohon izinkan akses lokasi di browser Anda.');
                } else if (error.code === 2) {
                    showError('Lokasi tidak tersedia. Pastikan GPS perangkat Anda aktif.');
                } else if (error.code === 3) {
                    showError('Timeout mendapatkan lokasi. Coba lagi.');
                } else {
                    showError('Gagal menghasilkan QR Code: ' + error.message);
                }
                showGPSAlert('GPS tidak aktif atau akses ditolak', 'danger');
            }
        }

        // Start Timer
        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(() => {
                countdown--;

                const minutes = Math.floor(countdown / 60);
                const seconds = countdown % 60;
                document.getElementById('timer').textContent =
                    `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    generateQRCode(); // Auto refresh
                }
            }, 1000);
        }

        // Update GPS Status Display
        function updateGPSStatus(lat, lng, distance) {
            const statusDiv = document.getElementById('gpsStatus');
            statusDiv.innerHTML = `
        <div class="small">
            <div><strong>Latitude:</strong> ${lat.toFixed(6)}</div>
            <div><strong>Longitude:</strong> ${lng.toFixed(6)}</div>
            <div class="text-success"><strong>Jarak dari sekolah:</strong> ${Math.round(distance)} meter ✓</div>
        </div>
    `;
        }

        // Show/Hide GPS Alert
        function showGPSAlert(message, type = 'warning') {
            const alert = document.getElementById('gpsAlert');
            alert.className = `alert alert-${type}`;
            document.getElementById('gpsMessage').textContent = message;
            alert.classList.remove('d-none');
        }

        function hideGPSAlert() {
            document.getElementById('gpsAlert').classList.add('d-none');
        }

        // Show/Hide Error
        function showError(message) {
            const alert = document.getElementById('errorAlert');
            document.getElementById('errorMessage').textContent = message;
            alert.classList.remove('d-none');
        }

        function hideError() {
            document.getElementById('errorAlert').classList.add('d-none');
        }

        // Load Riwayat Absensi
        async function loadRiwayat() {
            try {
                const response = await fetch('{{ route('guru.absensi.riwayat') }}');
                const data = await response.json();

                const container = document.getElementById('riwayatAbsensi');

                if (data.length === 0) {
                    container.innerHTML =
                        '<p class="text-center text-muted py-3">Belum ada riwayat absensi hari ini</p>';
                    return;
                }

                let html = '<div class="list-group">';
                data.forEach(item => {
                    const statusClass = item.status_kehadiran === 'hadir' ? 'success' :
                        item.status_kehadiran === 'terlambat' ? 'warning' : 'danger';
                    const statusText = item.status_kehadiran === 'hadir' ? 'Hadir' :
                        item.status_kehadiran === 'terlambat' ? 'Terlambat' : 'Tidak Hadir';

                    html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.jadwal.mata_pelajaran}</h6>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> ${item.jam_absen}
                                ${item.metode_absensi === 'qr' ? '<i class="bi bi-qr-code ms-2"></i>' : '<i class="bi bi-camera ms-2"></i>'}
                                ${item.metode_absensi === 'qr' ? 'QR Code' : 'Selfie'}
                            </small>
                        </div>
                        <span class="badge bg-${statusClass}">${statusText}</span>
                    </div>
                </div>
            `;
                });
                html += '</div>';

                container.innerHTML = html;
            } catch (error) {
                console.error('Error loading riwayat:', error);
                document.getElementById('riwayatAbsensi').innerHTML =
                    '<p class="text-center text-danger py-3">Gagal memuat riwayat absensi</p>';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            generateQRCode();
            loadRiwayat();

            // Refresh Button
            document.getElementById('refreshBtn').addEventListener('click', function() {
                generateQRCode();
            });
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
        });
    </script>
@endpush
