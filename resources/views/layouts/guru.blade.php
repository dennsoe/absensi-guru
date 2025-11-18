<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366F1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>@yield('title', 'SIAG NEKAS') - Guru</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logonekas.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logonekas.png') }}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="guru-layout @yield('body-class')">

    <!-- Top Bar -->
    <div class="guru-topbar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/images/logonekas.png') }}" alt="Logo" class="guru-logo">
                    <div class="ms-3">
                        <div class="guru-topbar-title">SIAG NEKAS</div>
                        <div class="guru-topbar-subtitle">{{ auth()->user()->guru->nama_lengkap ?? 'Guru' }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="btn btn-icon" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            @if (isset($unread_notifications) && $unread_notifications > 0)
                                <span class="notification-badge">{{ $unread_notifications }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                            <div class="dropdown-header">
                                <strong>Notifikasi</strong>
                            </div>
                            <div class="notification-list">
                                @forelse($notifications ?? [] as $notif)
                                    <a href="{{ $notif->link ?? '#' }}"
                                        class="notification-item {{ $notif->is_read ? '' : 'unread' }}">
                                        <div
                                            class="notification-icon bg-{{ $notif->type ?? 'primary' }}-light text-{{ $notif->type ?? 'primary' }}">
                                            <i class="bi bi-{{ $notif->icon ?? 'info-circle' }}"></i>
                                        </div>
                                        <div class="notification-content">
                                            <div class="notification-title">{{ $notif->title }}</div>
                                            <div class="notification-message">{{ $notif->message }}</div>
                                            <div class="notification-time">{{ $notif->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                                        <small>Tidak ada notifikasi</small>
                                    </div>
                                @endforelse
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('guru.notifications.index') }}"
                                class="dropdown-item text-center text-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>

                    <!-- Profile Menu -->
                    <div class="dropdown">
                        <button class="btn btn-icon" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">
                                <strong>{{ auth()->user()->guru->nama_lengkap ?? 'Guru' }}</strong>
                                <div class="small text-muted">{{ auth()->user()->guru->nip ?? '-' }}</div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('guru.profile.index') }}">
                                <i class="bi bi-person me-2"></i> Profil Saya
                            </a>
                            <a class="dropdown-item" href="{{ route('guru.settings.index') }}">
                                <i class="bi bi-gear me-2"></i> Pengaturan
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="guru-main-content">
        @yield('content')
    </div>

    <!-- Bottom Navigation -->
    <div class="guru-bottom-nav">
        <a href="{{ route('guru.dashboard') }}"
            class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('guru.jadwal.index') }}"
            class="nav-item {{ request()->routeIs('guru.jadwal.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i>
            <span>Jadwal</span>
        </a>
        <a href="{{ route('guru.absensi.scan') }}"
            class="nav-item nav-item-fab {{ request()->routeIs('guru.absensi.scan') ? 'active' : '' }}">
            <div class="fab-button">
                <i class="bi bi-qr-code-scan"></i>
            </div>
            <span>Absen</span>
        </a>
        <a href="{{ route('guru.izin.index') }}"
            class="nav-item {{ request()->routeIs('guru.izin.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>Izin</span>
        </a>
        <a href="{{ route('guru.riwayat.index') }}"
            class="nav-item {{ request()->routeIs('guru.riwayat.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>
        </a>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveToast" class="toast" role="alert">
            <div class="toast-header">
                <strong class="me-auto">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    @stack('scripts')

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('error') }}', 'error');
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('warning') }}', 'warning');
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('info') }}', 'info');
            });
        </script>
    @endif

    <style>
        /* Guru Mobile Layout Styles */
        body.guru-layout {
            padding-top: 64px;
            padding-bottom: 72px;
            background-color: var(--color-gray-50);
        }

        /* Top Bar */
        .guru-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: linear-gradient(135deg, var(--color-primary) 0%, #4F46E5 100%);
            box-shadow: var(--shadow-md);
            z-index: 1000;
            color: white;
        }

        .guru-topbar .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .guru-logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-lg);
            background: white;
            padding: 4px;
        }

        .guru-topbar-title {
            font-weight: var(--font-weight-bold);
            font-size: var(--font-size-base);
            color: white;
            line-height: 1.2;
        }

        .guru-topbar-subtitle {
            font-size: var(--font-size-xs);
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.2;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            font-size: 20px;
            position: relative;
            transition: all var(--transition-fast);
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 18px;
            height: 18px;
            background: var(--color-danger);
            color: white;
            border-radius: var(--radius-full);
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: var(--font-weight-bold);
            border: 2px solid var(--color-primary);
        }

        /* Notifications Dropdown */
        .notification-dropdown {
            width: 320px;
            max-height: 480px;
            overflow: hidden;
        }

        .notification-list {
            max-height: 360px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: var(--color-gray-900);
            border-bottom: 1px solid var(--color-gray-100);
            transition: background-color var(--transition-fast);
        }

        .notification-item:hover {
            background-color: var(--color-gray-50);
        }

        .notification-item.unread {
            background-color: #EEF2FF;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-sm);
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: var(--font-size-xs);
            color: var(--color-gray-600);
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notification-time {
            font-size: var(--font-size-xs);
            color: var(--color-gray-500);
        }

        /* Main Content */
        .guru-main-content {
            min-height: calc(100vh - 136px);
            padding: 1rem;
        }

        /* Bottom Navigation */
        .guru-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: white;
            border-top: 1px solid var(--color-gray-200);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 8px 0;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .guru-bottom-nav .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: var(--color-gray-500);
            text-decoration: none;
            font-size: var(--font-size-xs);
            font-weight: var(--font-weight-medium);
            transition: all var(--transition-fast);
            flex: 1;
            padding: 8px 4px;
        }

        .guru-bottom-nav .nav-item i {
            font-size: 24px;
        }

        .guru-bottom-nav .nav-item.active {
            color: var(--color-primary);
        }

        .guru-bottom-nav .nav-item:not(.active):hover {
            color: var(--color-gray-700);
        }

        /* FAB Button for Scan */
        .nav-item-fab {
            margin-top: -24px;
        }

        .fab-button {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--color-primary) 0%, #4F46E5 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: var(--shadow-lg);
            transition: all var(--transition-fast);
        }

        .nav-item-fab:hover .fab-button,
        .nav-item-fab.active .fab-button {
            transform: scale(1.1);
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
        }

        /* Responsive - Desktop */
        @media (min-width: 768px) {
            body.guru-layout {
                padding-bottom: 0;
            }

            .guru-bottom-nav {
                display: none;
            }

            .guru-main-content {
                min-height: calc(100vh - 64px);
                max-width: 768px;
                margin: 0 auto;
            }
        }

        /* Light Mode Variants */
        .bg-primary-light {
            background-color: #E0E7FF !important;
        }

        .bg-success-light {
            background-color: #D1FAE5 !important;
        }

        .bg-warning-light {
            background-color: #FEF3C7 !important;
        }

        .bg-danger-light {
            background-color: #FEE2E2 !important;
        }

        .bg-info-light {
            background-color: #CFFAFE !important;
        }
    </style>
</body>

</html>
