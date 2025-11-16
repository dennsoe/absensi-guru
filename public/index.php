<?php
/**
 * Index.php - Entry Point
 * Load konfigurasi dan jalankan router
 */

// Start session
session_start();

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once __DIR__ . '/../app/core/Router.php';

// Initialize router
$router = new Router();

// ==================== PUBLIC ROUTES ====================
$router->get('/', function() {
    if (isset($_SESSION['user_id'])) {
        Router::redirect('dashboard');
    } else {
        Router::redirect('login');
    }
});

$router->get('/login', function() {
    $title = 'Masuk - Sistem Absensi Guru';
    $content = __DIR__ . '/../app/views/auth/login.php';
    include __DIR__ . '/../app/views/layouts/auth.php';
});

$router->post('/login', 'AuthController@login');

$router->get('/logout', 'AuthController@logout');

// ==================== DASHBOARD ====================
$router->get('/dashboard', function() {
    // Check authentication
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Dasbor - Sistem Absensi Guru';
    $page = 'dashboard';
    $content = __DIR__ . '/../app/views/dashboard/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

// ==================== ABSENSI ROUTES ====================
$router->get('/absensi', function() {
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Absensi - Sistem Absensi Guru';
    $page = 'absensi';
    $content = __DIR__ . '/../app/views/absensi/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

$router->get('/absensi/masuk', 'AbsensiController@masuk');
$router->post('/absensi/masuk', 'AbsensiController@submitMasuk');

$router->get('/absensi/keluar', 'AbsensiController@keluar');
$router->post('/absensi/keluar', 'AbsensiController@submitKeluar');

$router->get('/absensi/riwayat', 'AbsensiController@riwayat');

// ==================== JADWAL ROUTES ====================
$router->get('/jadwal', function() {
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Jadwal - Sistem Absensi Guru';
    $page = 'jadwal';
    $content = __DIR__ . '/../app/views/jadwal/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

// ==================== NOTIFIKASI ROUTES ====================
$router->get('/notifikasi', function() {
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Notifikasi - Sistem Absensi Guru';
    $page = 'notifikasi';
    $content = __DIR__ . '/../app/views/notifikasi/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

// ==================== PROFIL ROUTES ====================
$router->get('/profil', function() {
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Profil - Sistem Absensi Guru';
    $page = 'profil';
    $content = __DIR__ . '/../app/views/profil/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

// ==================== PENGATURAN ROUTES ====================
$router->get('/pengaturan', function() {
    if (!isset($_SESSION['user_id'])) {
        Router::redirect('login');
    }
    
    $title = 'Pengaturan - Sistem Absensi Guru';
    $page = 'pengaturan';
    $content = __DIR__ . '/../app/views/settings/index.php';
    include __DIR__ . '/../app/views/layouts/main.php';
});

// ==================== API ROUTES ====================
$router->post('/api/push/subscribe', 'PushController@subscribe');
$router->post('/api/absensi/generate-qr', 'AbsensiController@generateQR');
$router->post('/api/absensi/validate-qr', 'AbsensiController@validateQR');
$router->post('/api/absensi/selfie', 'AbsensiController@uploadSelfie');
$router->get('/api/notifikasi/list', 'NotifikasiController@list');
$router->get('/api/notifikasi/unread-count', 'NotifikasiController@unreadCount');

// ==================== ADMIN ROUTES ====================
$router->get('/admin', 'AdminController@index');
$router->get('/admin/guru', 'AdminController@guru');
$router->get('/admin/jadwal', 'AdminController@jadwal');
$router->get('/admin/laporan', 'AdminController@laporan');

// ==================== 404 NOT FOUND ====================
$router->notFound(function() {
    http_response_code(404);
    
    // Check if API request
    $requestUri = $_SERVER['REQUEST_URI'];
    if (strpos($requestUri, '/api/') !== false) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint tidak ditemukan'
        ]);
    } else {
        $title = '404 - Halaman Tidak Ditemukan';
        include __DIR__ . '/../app/views/errors/404.php';
    }
});

// Run router
$router->run();