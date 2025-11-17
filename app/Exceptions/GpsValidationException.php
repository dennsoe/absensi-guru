<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GpsValidationException extends Exception
{
    protected $code = 422;

    /**
     * Create a new exception for GPS disabled
     */
    public static function disabled(): self
    {
        return new self('Validasi GPS tidak aktif', 422);
    }

    /**
     * Create a new exception for location outside radius
     */
    public static function outsideRadius(float $distance, float $maxRadius): self
    {
        return new self(
            sprintf(
                'Anda berada di luar radius sekolah (Jarak: %.2f meter, Maksimal: %.2f meter)',
                $distance,
                $maxRadius
            ),
            422
        );
    }

    /**
     * Create a new exception for invalid coordinates
     */
    public static function invalidCoordinates(): self
    {
        return new self('Koordinat GPS tidak valid', 422);
    }

    /**
     * Create a new exception for GPS not available
     */
    public static function notAvailable(): self
    {
        return new self('GPS tidak tersedia, silakan aktifkan lokasi pada perangkat Anda', 422);
    }

    /**
     * Create a new exception for GPS permission denied
     */
    public static function permissionDenied(): self
    {
        return new self('Akses lokasi ditolak, silakan izinkan akses lokasi untuk melanjutkan', 403);
    }

    /**
     * Create a new exception for GPS accuracy too low
     */
    public static function accuracyTooLow(float $accuracy): self
    {
        return new self(
            sprintf(
                'Akurasi GPS terlalu rendah (%.2f meter). Silakan pindah ke area dengan sinyal GPS lebih baik',
                $accuracy
            ),
            422
        );
    }

    /**
     * Create a new exception for strict mode violation
     */
    public static function strictModeViolation(string $message): self
    {
        return new self($message, 422);
    }

    /**
     * Create a new exception with custom distance info
     */
    public static function withDistance(float $distance, float $maxRadius, bool $strictMode): self
    {
        if ($strictMode) {
            return self::outsideRadius($distance, $maxRadius);
        }

        return new self(
            sprintf(
                'Peringatan: Anda berada %.2f meter dari sekolah (radius normal: %.2f meter)',
                $distance,
                $maxRadius
            ),
            422
        );
    }

    /**
     * Render the exception as an HTTP response
     */
    public function render(Request $request): JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error_code' => 'GPS_VALIDATION_ERROR',
            ], $this->code);
        }

        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->code);
    }
}
