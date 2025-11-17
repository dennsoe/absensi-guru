<?php

namespace App\Services;

use App\Models\PengaturanSistem;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected $cacheKey = 'app.settings';
    protected $cacheDuration = 3600; // 1 hour

    /**
     * Get setting value by key
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAllCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Set setting value
     */
    public function set(string $key, $value): bool
    {
        $setting = PengaturanSistem::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        $this->clearCache();
        return $setting !== null;
    }

    /**
     * Set multiple settings at once
     */
    public function setMany(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            PengaturanSistem::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->clearCache();
        return true;
    }

    /**
     * Get all settings (cached)
     */
    public function getAllCached(): array
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            return PengaturanSistem::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get settings by category
     */
    public function getByCategory(string $category): array
    {
        return PengaturanSistem::where('category', $category)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Get all categories
     */
    public function getAllCategories(): array
    {
        return PengaturanSistem::select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Reset setting to default value
     */
    public function resetToDefault(string $key): bool
    {
        $defaults = $this->getDefaults();

        if (!isset($defaults[$key])) {
            return false;
        }

        return $this->set($key, $defaults[$key]);
    }

    /**
     * Reset all settings in a category
     */
    public function resetCategoryToDefault(string $category): bool
    {
        $defaults = $this->getDefaults();
        $categorySettings = array_filter($defaults, function($key) use ($category) {
            return strpos($key, $category . '_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        return $this->setMany($categorySettings);
    }

    /**
     * Clear settings cache
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Default settings values
     */
    protected function getDefaults(): array
    {
        return [
            // GPS Settings
            'gps_enabled' => true,
            'gps_latitude' => -6.4167,
            'gps_longitude' => 107.7667,
            'gps_radius' => 200,
            'gps_strict_mode' => false,

            // QR Code Settings
            'qr_expiry_minutes' => 5,
            'qr_auto_refresh' => true,
            'qr_size' => 300,

            // Absensi Settings
            'absensi_qr_enabled' => true,
            'absensi_selfie_enabled' => true,
            'toleransi_terlambat' => 15,
            'absen_sebelum' => 30,
            'absen_setelah' => 60,
            'wajib_selfie' => true,
            'wajib_gps' => true,

            // Notification Settings
            'notif_web_enabled' => true,
            'notif_email_enabled' => false,
            'notif_whatsapp_enabled' => false,
            'notif_sebelum_jadwal_menit' => 15,
            'notif_ajax_polling_interval' => 30,

            // Surat Peringatan
            'sp_enabled' => true,
            'sp1_threshold' => 3,
            'sp2_threshold' => 5,
            'sp3_threshold' => 7,
            'sp_periode_hari' => 30,
        ];
    }

    /**
     * Validate setting value
     */
    public function validate(string $key, $value): bool
    {
        $rules = [
            'gps_latitude' => ['numeric', 'min:-90', 'max:90'],
            'gps_longitude' => ['numeric', 'min:-180', 'max:180'],
            'gps_radius' => ['integer', 'min:10', 'max:1000'],
            'qr_expiry_minutes' => ['integer', 'min:1', 'max:60'],
            'toleransi_terlambat' => ['integer', 'min:0', 'max:60'],
        ];

        if (!isset($rules[$key])) {
            return true; // No validation rules for this key
        }

        $validator = validator(['value' => $value], ['value' => $rules[$key]]);
        return !$validator->fails();
    }
}
