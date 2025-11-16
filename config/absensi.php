<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Metode Absensi
    |--------------------------------------------------------------------------
    */
    'metode' => [
        'qr_code' => true,
        'selfie' => true,
        'manual' => false, // Hanya admin yang bisa input manual
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code Settings
    |--------------------------------------------------------------------------
    */
    'qr_code' => [
        'expire_minutes' => 10, // QR code expired dalam 10 menit
        'size' => 300, // Ukuran QR code dalam pixel
        'cleanup_hours' => 24, // Hapus QR code lama setiap 24 jam
    ],

    /*
    |--------------------------------------------------------------------------
    | Toleransi Waktu
    |--------------------------------------------------------------------------
    */
    'toleransi' => [
        'terlambat_menit' => 15, // Toleransi keterlambatan 15 menit
        'pulang_cepat_menit' => 15, // Toleransi pulang lebih cepat 15 menit
        'lupa_absen_jam' => 2, // Batas lupa absen (setelah 2 jam dari jadwal)
    ],

    /*
    |--------------------------------------------------------------------------
    | Validasi GPS
    |--------------------------------------------------------------------------
    */
    'validasi_gps' => true,
    'radius_meter' => 100, // Radius validasi GPS dalam meter

    /*
    |--------------------------------------------------------------------------
    | Selfie Settings
    |--------------------------------------------------------------------------
    */
    'selfie' => [
        'required' => true,
        'max_size_kb' => 2048, // Max 2MB
        'allowed_formats' => ['jpg', 'jpeg', 'png'],
        'compress_quality' => 80, // Kualitas kompresi 0-100
    ],

    /*
    |--------------------------------------------------------------------------
    | Surat Peringatan
    |--------------------------------------------------------------------------
    */
    'surat_peringatan' => [
        'sp1' => [
            'alpha_count' => 3,
            'message' => 'Surat Peringatan 1: Alpha 3 kali dalam sebulan',
        ],
        'sp2' => [
            'alpha_count' => 5,
            'message' => 'Surat Peringatan 2: Alpha 5 kali dalam sebulan',
        ],
        'sp3' => [
            'alpha_count' => 7,
            'message' => 'Surat Peringatan 3: Alpha 7 kali dalam sebulan',
        ],
    ],
];
