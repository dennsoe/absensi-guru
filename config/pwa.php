<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PWA Configuration
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_NAME', 'SIAG NEKAS'),
    'short_name' => 'SIAG NEKAS',
    'description' => 'Sistem Informasi Absensi Guru - SMK Negeri Kasomalang',
    'start_url' => '/',
    'display' => 'standalone',
    'theme_color' => '#2563eb', // Blue
    'background_color' => '#ffffff',
    'orientation' => 'portrait',

    /*
    |--------------------------------------------------------------------------
    | PWA Icons
    |--------------------------------------------------------------------------
    */
    'icons' => [
        [
            'src' => '/assets/images/logonekas-192.png',
            'sizes' => '192x192',
            'type' => 'image/png',
            'purpose' => 'any maskable',
        ],
        [
            'src' => '/assets/images/logonekas-512.png',
            'sizes' => '512x512',
            'type' => 'image/png',
            'purpose' => 'any maskable',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Worker
    |--------------------------------------------------------------------------
    */
    'service_worker' => [
        'enabled' => true,
        'cache_name' => 'siag-nekas-v1',
        'offline_page' => '/offline',
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Notifications
    |--------------------------------------------------------------------------
    */
    'push_notifications' => [
        'enabled' => true,
        'vapid_public_key' => env('VAPID_PUBLIC_KEY'),
        'vapid_private_key' => env('VAPID_PRIVATE_KEY'),
    ],
];
