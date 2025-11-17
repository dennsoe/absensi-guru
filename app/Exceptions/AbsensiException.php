<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbsensiException extends Exception
{
    protected $code = 422;

    /**
     * Create a new exception for absensi already exists
     */
    public static function alreadyExists(): self
    {
        return new self('Anda sudah melakukan absensi hari ini', 422);
    }

    /**
     * Create a new exception for not a working day
     */
    public static function notWorkingDay(): self
    {
        return new self('Hari ini bukan hari kerja', 422);
    }

    /**
     * Create a new exception for outside time range
     */
    public static function outsideTimeRange(): self
    {
        return new self('Absensi hanya dapat dilakukan pada jam kerja', 422);
    }

    /**
     * Create a new exception for checkout not allowed
     */
    public static function checkoutNotAllowed(string $reason): self
    {
        return new self("Checkout tidak dapat dilakukan: {$reason}", 422);
    }

    /**
     * Create a new exception for no check-in found
     */
    public static function noCheckInFound(): self
    {
        return new self('Anda belum melakukan check-in hari ini', 422);
    }

    /**
     * Create a new exception for already checked out
     */
    public static function alreadyCheckedOut(): self
    {
        return new self('Anda sudah melakukan checkout hari ini', 422);
    }

    /**
     * Create a new exception for invalid status
     */
    public static function invalidStatus(string $status): self
    {
        return new self("Status absensi tidak valid: {$status}", 422);
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
                'error_code' => 'ABSENSI_ERROR',
            ], $this->code);
        }

        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->code);
    }
}
