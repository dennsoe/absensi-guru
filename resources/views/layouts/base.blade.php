<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Informasi Absensi Guru SMK Negeri Kasomalang">
    <meta name="theme-color" content="#2563eb">

    <title>@yield('title', 'SIAG NEKAS') - SMK Negeri Kasomalang</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logonekas.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logonekas.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])

    <!-- Page Specific Styles -->
    @stack('styles')

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="@yield('body-class')">

    <!-- Main Content -->
    <div id="app">
        @yield('content')
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.5); z-index: 9998;">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Page Specific Scripts -->
    @stack('scripts')

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('error') }}', 'danger');
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('warning') }}', 'warning');
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.SIAG.showToast('{{ session('info') }}', 'info');
            });
        </script>
    @endif

    <!-- Service Worker Registration (PWA) -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registered: ', registration);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
</body>

</html>
