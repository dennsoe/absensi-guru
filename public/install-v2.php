<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installer - Sistem Absensi Guru v3.5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
            color: var(--gray-900);
        }

        .installer-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 2rem 1rem;
            text-align: center;
        }

        .installer-logo {
            width: 64px;
            height: 64px;
            background: var(--primary);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .installer-logo i {
            font-size: 32px;
            color: white;
        }

        .installer-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .installer-subtitle {
            color: var(--gray-600);
            font-size: 1rem;
        }

        .installer-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .progress-container {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .progress-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gray-200);
            color: var(--gray-600);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }

        .progress-step.active .progress-step-circle {
            background: var(--primary);
            color: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .progress-step.completed .progress-step-circle {
            background: var(--success);
            color: white;
        }

        .progress-step-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .progress-step.active .progress-step-label {
            color: var(--primary);
            font-weight: 600;
        }

        .progress-bar-custom {
            height: 2px;
            background: var(--gray-200);
            border-radius: 2px;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 2px;
            transition: width 0.4s ease;
        }

        .card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
        }

        .card-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        <blade keyframes|%20fadeIn%20%7B>from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
        }

        .check-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            margin-bottom: 0.75rem;
            transition: all 0.2s;
        }

        .check-item:hover {
            border-color: var(--gray-300);
            background: var(--gray-50);
        }

        .check-success {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .check-error {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .check-item-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--gray-700);
            font-size: 0.9375rem;
        }

        .check-item-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-secondary:hover {
            background: var(--gray-300);
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.9375rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #fefce8;
            color: #854d0e;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border-left: 4px solid var(--primary);
        }

        .spinner-border {
            width: 1.25rem;
            height: 1.25rem;
            border-width: 2px;
        }

        .success-icon {
            font-size: 64px;
            color: var(--success);
        }

        .installer-footer {
            background: white;
            border-top: 1px solid var(--gray-200);
            padding: 1.5rem;
            text-align: center;
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-top: 3rem;
        }

        <blade media|%20(max-width%3A%20768px)%20%7B>.installer-header {
            padding: 1.5rem 1rem;
        }

        .installer-title {
            font-size: 1.5rem;
        }

        .progress-step-label {
            display: none;
        }

        .installer-container {
            padding: 0 0.5rem;
        }

        .card-header,
        .card-body {
            padding: 1rem;
        }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="installer-header">
        <div class="installer-logo">
            <i class="bi bi-gear-fill"></i>
        </div>
        <h1 class="installer-title">Sistem Absensi Guru</h1>
        <p class="installer-subtitle">Installer v3.5 - Progressive Web App Edition</p>
    </div>

    <!-- Main Content -->
    <div class="installer-container">
        <!-- Progress Steps -->
        <div class="progress-container">
            <div class="progress-steps">
                <div class="progress-step active" id="progress-step-1">
                    <div class="progress-step-circle">1</div>
                    <div class="progress-step-label">Pemeriksaan</div>
                </div>
                <div class="progress-step" id="progress-step-2">
                    <div class="progress-step-circle">2</div>
                    <div class="progress-step-label">Database</div>
                </div>
                <div class="progress-step" id="progress-step-3">
                    <div class="progress-step-circle">3</div>
                    <div class="progress-step-label">Import</div>
                </div>
                <div class="progress-step" id="progress-step-4">
                    <div class="progress-step-circle">4</div>
                    <div class="progress-step-label">Admin</div>
                </div>
                <div class="progress-step" id="progress-step-5">
                    <div class="progress-step-circle">5</div>
                    <div class="progress-step-label">Selesai</div>
                </div>
            </div>
            <div class="progress-bar-custom">
                <div class="progress-bar-fill" id="progressBar" style="width: 20%"></div>
            </div>
        </div>

        <!-- Step 1: System Check -->
        <div class="card step active" id="step1">
            <div class="card-header">
                <h5><i class="bi bi-shield-check me-2"></i>Pemeriksaan Sistem</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Memeriksa persyaratan sistem...</p>

                <div id="requirementChecks">
                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-code-square"></i>
                            </div>
                            <span>PHP Version (≥ 7.4)</span>
                        </div>
                        <span id="check-php"></span>
                    </div>

                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-plugin"></i>
                            </div>
                            <span>PDO Extension</span>
                        </div>
                        <span id="check-pdo"></span>
                    </div>

                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-database"></i>
                            </div>
                            <span>PDO MySQL Driver</span>
                        </div>
                        <span id="check-pdo-mysql"></span>
                    </div>

                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <span>Writable: /public/uploads/</span>
                        </div>
                        <span id="check-uploads"></span>
                    </div>

                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <span>Writable: /logs/</span>
                        </div>
                        <span id="check-logs"></span>
                    </div>

                    <div class="check-item">
                        <div class="check-item-label">
                            <div class="check-item-icon">
                                <i class="bi bi-folder2"></i>
                            </div>
                            <span>Writable: /backup/</span>
                        </div>
                        <span id="check-backup"></span>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary" onclick="nextStep(2)">
                        Lanjutkan
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Database Config -->
        <div class="card step" id="step2">
            <div class="card-header">
                <h5><i class="bi bi-database me-2"></i>Konfigurasi Database</h5>
            </div>
            <div class="card-body">
                <form id="dbForm">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Database Host</label>
                            <input type="text" class="form-control" name="db_host" value="localhost" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Port</label>
                            <input type="text" class="form-control" name="db_port" value="3306" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Database Name</label>
                        <input type="text" class="form-control" name="db_name" value="absensi_guru" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="db_user" value="root" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="db_pass"
                            placeholder="Kosongkan jika tidak ada password">
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Penting:</strong> Pastikan database sudah dibuat terlebih dahulu melalui phpMyAdmin atau
                        MySQL console.
                    </div>

                    <div id="dbTestResult" class="mb-3"></div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </button>
                        <button type="button" class="btn btn-primary" onclick="testDatabase()">
                            <i class="bi bi-plug me-2"></i>
                            Test Koneksi
                        </button>
                        <button type="button" class="btn btn-success ms-auto" onclick="nextStep(3)">
                            Lanjutkan
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Step 3: Import Database -->
        <div class="card step" id="step3">
            <div class="card-header">
                <h5><i class="bi bi-download me-2"></i>Import Database</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Import struktur tabel dan data awal ke database...</p>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong> Proses ini akan membuat tabel dan data awal. Pastikan database kosong
                    atau backup data lama Anda.
                </div>

                <div id="importProgress" style="display: none;" class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Mengimport database...</p>
                </div>

                <div id="importResult"></div>

                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-secondary" onclick="prevStep(2)">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali
                    </button>
                    <button class="btn btn-primary" onclick="importDatabase()">
                        <i class="bi bi-download me-2"></i>
                        Import Database
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 4: Admin Setup -->
        <div class="card step" id="step4">
            <div class="card-header">
                <h5><i class="bi bi-person-badge me-2"></i>Setup Administrator</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Buat akun administrator untuk mengakses sistem.</p>

                <form id="adminForm">
                    <div class="mb-3">
                        <label class="form-label">Username Admin</label>
                        <input type="text" class="form-control" name="admin_username" value="admin" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="admin_password"
                            placeholder="Minimal 8 karakter" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="admin_password_confirm"
                            placeholder="Ulangi password" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(3)">
                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali
                        </button>
                        <button type="button" class="btn btn-success ms-auto" onclick="createAdmin()">
                            <i class="bi bi-check-circle me-2"></i>
                            Selesaikan Instalasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Step 5: Finish -->
        <div class="card step" id="step5">
            <div class="card-header">
                <h5><i class="bi bi-check-circle-fill me-2"></i>Instalasi Selesai!</h5>
            </div>
            <div class="card-body text-center py-5">
                <i class="bi bi-check-circle-fill success-icon"></i>
                <h3 class="mt-4 mb-2">Instalasi Berhasil!</h3>
                <p class="text-muted mb-4">Sistem Absensi Guru siap digunakan</p>

                <div class="alert alert-warning">
                    <strong><i class="bi bi-shield-exclamation me-2"></i>Keamanan:</strong>
                    Segera hapus semua file installer untuk keamanan sistem!
                    <br><small class="mt-2 d-block">Files: install.php, install-check.php, install-test-db.php,
                        install-import-db.php, install-drop-import.php, install-create-admin.php</small>
                </div>

                <div class="mt-4">
                    <a href="../login" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login ke Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="installer-footer">
        <p>&copy; <?= date('Y') ?> Sistem Absensi Guru v3.5 - Progressive Web App Edition</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check system requirements on load
        window.onload = function () {
            checkRequirements();
        };

        function checkRequirements() {
            fetch('install-check.php')
                .then(response => response.json())
                .then(data => {
                    updateCheck('php', data.php);
                    updateCheck('pdo', data.pdo);
                    updateCheck('pdo-mysql', data.pdo_mysql);
                    updateCheck('uploads', data.uploads);
                    updateCheck('logs', data.logs);
                    updateCheck('backup', data.backup);
                });
        }

        function updateCheck(id, passed) {
            const elem = document.getElementById('check-' + id);
            const parent = elem.closest('.check-item');

            if (passed) {
                elem.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-5"></i>';
                parent.classList.add('check-success');
            } else {
                elem.innerHTML = '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
                parent.classList.add('check-error');
            }
        }

        function nextStep(step) {
            // Hide all steps
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));

            // Show target step
            document.getElementById('step' + step).classList.add('active');

            // Update progress steps
            for (let i = 1; i <= 5; i++) {
                const progressStep = document.getElementById('progress-step-' + i);
                progressStep.classList.remove('active', 'completed');

                if (i < step) {
                    progressStep.classList.add('completed');
                } else if (i === step) {
                    progressStep.classList.add('active');
                }
            }

            // Update progress bar
            const progress = (step / 5) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }

        function prevStep(step) {
            nextStep(step);
        }

        function testDatabase() {
            const formData = new FormData(document.getElementById('dbForm'));

            fetch('install-test-db.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('dbTestResult');

                    if (data.success) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' + data
                            .message + '</div>';
                    } else {
                        resultDiv.innerHTML =
                            '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>' + data.message +
                            '</div>';
                    }
                });
        }

        function importDatabase() {
            document.getElementById('importProgress').style.display = 'block';
            document.getElementById('importResult').innerHTML = '';

            const formData = new FormData(document.getElementById('dbForm'));

            fetch('install-import-db.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('importProgress').style.display = 'none';
                    const resultDiv = document.getElementById('importResult');

                    if (data.success) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' + data
                            .message + '</div>';
                        setTimeout(() => nextStep(4), 1500);
                    } else {
                        // Check if drop option is available
                        if (data.drop_option) {
                            resultDiv.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>${data.message}
                                </div>
                                <div class="alert alert-warning">
                                    <strong><i class="bi bi-shield-exclamation me-2"></i>Perhatian!</strong><br>
                                    Hapus semua tabel akan menghapus data yang sudah ada. Pastikan Anda sudah backup data!
                                </div>
                                <button class="btn btn-danger w-100" onclick="dropAndImport()">
                                    <i class="bi bi-trash me-2"></i>
                                    Hapus Semua Tabel & Import Ulang
                                </button>
                            `;
                        } else {
                            resultDiv.innerHTML =
                                '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>' + data
                                .message +
                                '</div>';
                        }
                    }
                });
        }

        function dropAndImport() {
            if (!confirm(
                    'PERINGATAN!\n\nIni akan menghapus SEMUA tabel dan data di database!\nApakah Anda yakin ingin melanjutkan?'
                    )) {
                return;
            }

            document.getElementById('importProgress').style.display = 'block';
            document.getElementById('importResult').innerHTML = '';

            const formData = new FormData(document.getElementById('dbForm'));

            fetch('install-drop-import.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('importProgress').style.display = 'none';
                    const resultDiv = document.getElementById('importResult');

                    if (data.success) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>' + data
                            .message + '<br><small>Tabel dihapus: ' + data.dropped + '</small></div>';
                        setTimeout(() => nextStep(4), 2000);
                    } else {
                        resultDiv.innerHTML =
                            '<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>' + data.message +
                            '</div>';
                    }
                });
        }

        function createAdmin() {
            const formData = new FormData(document.getElementById('adminForm'));

            // Validate password
            if (formData.get('admin_password') !== formData.get('admin_password_confirm')) {
                alert('Password tidak cocok!');
                return;
            }

            if (formData.get('admin_password').length < 8) {
                alert('Password minimal 8 karakter!');
                return;
            }

            fetch('install-create-admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nextStep(5);
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }
    </script>
</body>

</html>