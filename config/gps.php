<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GPS Coordinates - SDN Nekas
    |--------------------------------------------------------------------------
    | Koordinat GPS sekolah untuk validasi absensi
    | Gunakan Google Maps untuk mendapatkan koordinat yang akurat
    */
    'school_location' => [
        'latitude' => env('SCHOOL_LATITUDE', -6.4167),
        'longitude' => env('SCHOOL_LONGITUDE', 107.7667),
        'name' => env('SCHOOL_NAME', 'SDN Nekas'),
        'address' => env('SCHOOL_ADDRESS', 'Jl. Raya Kasomalang, Kasomalang Kulon, Kec. Kasomalang, Kabupaten Subang, Jawa Barat 41281'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GPS Validation - Used by ValidationService
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'enabled' => env('GPS_VALIDATION_ENABLED', true),
        'radius_meter' => env('GPS_RADIUS_METERS', 200), // Radius validasi dalam meter
        'strict_mode' => env('GPS_STRICT_MODE', false), // Jika true, absensi di luar radius akan ditolak
    ],

    // Backward compatibility - used by ValidationService
    'school_latitude' => env('SCHOOL_LATITUDE', -6.4167),
    'school_longitude' => env('SCHOOL_LONGITUDE', 107.7667),
    'radius_meters' => env('GPS_RADIUS_METERS', 200),

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
