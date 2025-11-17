<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrCodeException extends Exception
{
    protected $code = 422;

    /**
     * Create a new exception for QR code expired
     */
    public static function expired(): self
    {
        return new self('QR Code telah kadaluarsa', 422);
    }

    /**
     * Create a new exception for QR code already used
     */
    public static function alreadyUsed(): self
    {
        return new self('QR Code sudah pernah digunakan', 422);
    }

    /**
     * Create a new exception for QR code not found
     */
    public static function notFound(): self
    {
        return new self('QR Code tidak ditemukan', 404);
    }

    /**
     * Create a new exception for invalid QR code
     */
    public static function invalid(): self
    {
        return new self('QR Code tidak valid', 422);
    }

    /**
     * Create a new exception for QR signature verification failed
     */
    public static function signatureVerificationFailed(): self
    {
        return new self('Verifikasi signature QR Code gagal', 422);
    }

    /**
     * Create a new exception for QR generation failed
     */
    public static function generationFailed(string $reason = ''): self
    {
        $message = 'Gagal membuat QR Code';

        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self($message, 500);
    }

    /**
     * Create a new exception for QR code not active
     */
    public static function notActive(): self
    {
        return new self('QR Code belum aktif', 422);
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
                'error_code' => 'QR_CODE_ERROR',
            ], $this->code);
        }

        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->code);
    }
}
