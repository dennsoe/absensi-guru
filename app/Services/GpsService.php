<?php

namespace App\Services;

class GpsService
{
    /**
     * Validate GPS coordinates within radius
     */
    public function validateLocation(float $lat, float $lng): array
    {
        if (!config('gps.enabled')) {
            return [
                'valid' => true,
                'message' => 'GPS validation disabled',
            ];
        }

        $schoolLat = config('gps.sekolah.latitude');
        $schoolLng = config('gps.sekolah.longitude');
        $radius = config('gps.sekolah.radius_meter');

        $distance = $this->calculateDistance($lat, $lng, $schoolLat, $schoolLng);

        if ($distance <= $radius) {
            return [
                'valid' => true,
                'distance' => round($distance, 2),
                'message' => 'Lokasi valid',
            ];
        }

        $strictMode = config('gps.strict_mode', false);

        return [
            'valid' => !$strictMode,
            'distance' => round($distance, 2),
            'message' => $strictMode
                ? "Anda berada {$distance}m dari sekolah (maks: {$radius}m)"
                : "Peringatan: Anda berada {$distance}m dari sekolah",
            'strict_mode' => $strictMode,
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in meters
     */
    public function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Check if coordinates are valid
     */
    public function areCoordinatesValid(float $lat, float $lng): bool
    {
        return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
    }

    /**
     * Get school location
     */
    public function getSchoolLocation(): array
    {
        return [
            'latitude' => config('gps.sekolah.latitude'),
            'longitude' => config('gps.sekolah.longitude'),
            'radius' => config('gps.sekolah.radius_meter'),
        ];
    }

    /**
     * Format coordinates for display
     */
    public function formatCoordinates(float $lat, float $lng): string
    {
        $latDir = $lat >= 0 ? 'N' : 'S';
        $lngDir = $lng >= 0 ? 'E' : 'W';

        return sprintf(
            '%s°%s, %s°%s',
            abs($lat),
            $latDir,
            abs($lng),
            $lngDir
        );
    }
}
