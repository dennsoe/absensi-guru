<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#007bff">
    <meta name="description" content="Sistem Absensi Guru dengan GPS, QR Code, dan Notifikasi Real-time">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Absensi Guru">

    <title><?= $title ?? 'Sistem Absensi Guru' ?></title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= Router::url('manifest.json') ?>">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?= Router::url('assets/images/icons/icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= Router::url('assets/images/icons/icon-512x512.png') ?>">
    <link rel="apple-touch-icon" href="<?= Router::url('assets/images/icons/icon-192x192.png') ?>">

    <!-- iOS Splash Screens -->
    <link rel="apple-touch-startup-image" href="<?= Router::url('assets/images/splash/iphone-x.png') ?>"
        media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">
    <link rel="apple-touch-startup-image" href="<?= Router::url('assets/images/splash/ipad.png') ?>"
        media="(device-width: 768px) and (device-height: 1024px)">

    <!-- VAPID Public Key -->
    <meta name="vapid-public-key" content="<?= VAPID_PUBLIC_KEY ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= Router::url('assets/css/mobile.css') ?>">
    <link rel="stylesheet" href="<?= Router::url('assets/css/style.css') ?>">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <!-- Install Banner -->
    <div id="install-banner" class="install-banner" style="display: none;">
        <div class="install-banner-content">
            <i class="fas fa-download"></i>
            <div class="install-banner-text">
                <strong>Pasang Aplikasi</strong>
                <p>Install aplikasi untuk akses lebih cepat</p>
            </div>
            <button onclick="PWA.install()" class="btn btn-primary btn-sm">Pasang</button>
            <button onclick="hideInstallBanner()" class="btn btn-link btn-sm">Nanti</button>
        </div>
    </div>

    <!-- iOS Install Prompt -->
    <div id="ios-install-prompt" class="ios-prompt" style="display: none;">
        <div class="ios-prompt-content">
            <i class="fas fa-share"></i>
            <p>Tap tombol <strong>Share</strong> lalu pilih <strong>"Add to Home Screen"</strong> untuk install aplikasi
            </p>
            <button onclick="this.parentElement.parentElement.style.display='none'"
                class="btn btn-sm btn-secondary">Tutup</button>
        </div>
    </div>

    <!-- Connection Status Bar -->
    <div id="connection-status" class="connection-status" style="display: none;"></div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav d-md-none">
        <a href="<?= Router::url('dashboard') ?>"
            class="bottom-nav-item <?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="<?= Router::url('absensi') ?>"
            class="bottom-nav-item <?= ($page ?? '') === 'absensi' ? 'active' : '' ?>">
            <i class="fas fa-qrcode"></i>
            <span>Absen</span>
        </a>
        <a href="<?= Router::url('jadwal') ?>"
            class="bottom-nav-item <?= ($page ?? '') === 'jadwal' ? 'active' : '' ?>">
            <i class="fas fa-calendar"></i>
            <span>Jadwal</span>
        </a>
        <a href="<?= Router::url('notifikasi') ?>"
            class="bottom-nav-item <?= ($page ?? '') === 'notifikasi' ? 'active' : '' ?>">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
        <a href="<?= Router::url('profil') ?>"
            class="bottom-nav-item <?= ($page ?? '') === 'profil' ? 'active' : '' ?>">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
    </nav>

    <!-- Desktop Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-none d-md-flex">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= Router::url('/') ?>">
                <img src="<?= Router::url('assets/images/logo.png') ?>" alt="Logo" height="40">
                <span>Absensi Guru</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Router::url('dashboard') ?>">
                            <i class="fas fa-home"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Router::url('absensi') ?>">
                            <i class="fas fa-qrcode"></i> Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Router::url('jadwal') ?>">
                            <i class="fas fa-calendar"></i> Jadwal
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger" id="notif-count">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
                            <li>
                                <h6 class="dropdown-header">Notifikasi</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li id="notif-list">
                                <a class="dropdown-item" href="#">Tidak ada notifikasi</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <img src="<?= Router::url('assets/images/user-default.png') ?>" alt="User"
                                class="rounded-circle" width="30" height="30">
                            <span><?= $_SESSION['nama'] ?? 'User' ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= Router::url('profil') ?>"><i class="fas fa-user"></i>
                                    Profil</a></li>
                            <li><a class="dropdown-item" href="<?= Router::url('pengaturan') ?>"><i
                                        class="fas fa-cog"></i> Pengaturan</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?= Router::url('logout') ?>"><i
                                        class="fas fa-sign-out-alt"></i> Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?php if (isset($content)) { include $content; } ?>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="<?= Router::url('assets/js/pwa.js') ?>"></script>
    <script src="<?= Router::url('assets/js/app.js') ?>"></script>

    <!-- Additional Scripts -->
    <?php if (isset($scripts)) { include $scripts; } ?>

    <style>
        /* Install Banner Styles */
        .install-banner {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .install-banner-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .install-banner i {
            font-size: 2rem;
        }

        .install-banner-text {
            flex: 1;
        }

        .install-banner-text p {
            margin: 0;
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* iOS Prompt */
        .ios-prompt {
            position: fixed;
            bottom: 80px;
            left: 1rem;
            right: 1rem;
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            text-align: center;
        }

        .ios-prompt i {
            font-size: 3rem;
            color: #007bff;
            margin-bottom: 1rem;
        }

        /* Connection Status */
        .connection-status {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 0.5rem;
            text-align: center;
            z-index: 9998;
            font-weight: 600;
        }

        .status-online {
            background-color: #28a745;
            color: white;
        }

        .status-offline {
            background-color: #dc3545;
            color: white;
        }

        /* Main Content - add padding for bottom nav on mobile */
        .main-content {
            padding: 1rem;
            padding-bottom: calc(var(--bottom-nav-height) + 1rem);
        }

        <blade media|%20(min-width%3A%20768px)%20%7B>.main-content {
            padding-bottom: 1rem;
        }
        }
    </style>
</body>

</html>