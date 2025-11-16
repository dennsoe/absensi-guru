<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#007bff">
    <title>Login - Sistem Absensi Guru</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?= BASE_URL ?>manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Absensi Guru">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?= BASE_URL ?>assets/images/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>assets/images/icons/icon-192x192.png">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/auth.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="app-logo">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <h1>Sistem Absensi Guru</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            <div class="login-body">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>login" method="POST">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                            required autofocus>
                        <label for="username">
                            <i class="bi bi-person me-2"></i>Username
                        </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            required>
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>Password
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Sistem Absensi Guru v3.5<br>
                        © <?= date('Y') ?> - PWA Edition
                    </small>
                </div>
            </div>
        </div>

        <!-- Install PWA Prompt (if not installed) -->
        <div id="installPrompt" class="alert alert-info mt-3" style="display: none;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-download me-2"></i>
                    <strong>Install Aplikasi</strong><br>
                    <small>Tambahkan ke layar utama untuk akses cepat</small>
                </div>
                <button class="btn btn-sm btn-primary" onclick="installPWA()">
                    Install
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/pwa.js"></script>

    <script>
        // Show install prompt if not installed
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPrompt = e;
            document.getElementById('installPrompt').style.display = 'block';
        });
    </script>
</body>

</html>