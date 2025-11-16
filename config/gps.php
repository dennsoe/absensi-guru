<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GPS Coordinates - SMK Negeri Kasomalang
    |--------------------------------------------------------------------------
    | Koordinat GPS sekolah untuk validasi absensi
    | Gunakan Google Maps untuk mendapatkan koordinat yang akurat
    */
    'school_location' => [
        'latitude' => -6.4167, // Update dengan koordinat GPS sebenarnya
        'longitude' => 107.7667, // Update dengan koordinat GPS sebenarnya
        'name' => 'SMK Negeri Kasomalang',
        'address' => 'Jl. Raya Kasomalang, Kasomalang Kulon, Kec. Kasomalang, Kabupaten Subang, Jawa Barat 41281',
    ],

    /*
    |--------------------------------------------------------------------------
    | GPS Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'enabled' => true,
        'radius_meter' => 100, // Radius validasi dalam meter
        'strict_mode' => false, // Jika true, absensi di luar radius akan ditolak
    ],

    /*
    |--------------------------------------------------------------------------
    | Distance Calculation Method
    |--------------------------------------------------------------------------
    | Metode perhitungan jarak: 'haversine' atau 'vincenty'
    | Haversine: Lebih cepat, akurasi cukup untuk jarak pendek
    | Vincenty: Lebih akurat, lebih lambat
    */
    'calculation_method' => 'haversine',
];
