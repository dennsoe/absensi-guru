<?php

namespace App\Services;

use App\Models\QrCode;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService
{
    /**
     * Generate QR Code for guru
     */
    public function generate($guruId, $jadwalId = null)
    {
        $token = $this->generateUniqueToken();
        $expiredAt = now()->addMinutes(config('absensi.qr.expiry_minutes', 5));

        $qrCode = QrCode::create([
            'guru_id' => $guruId,
            'jadwal_id' => $jadwalId,
            'token' => $token,
            'qr_string' => $this->generateQrString($token, $guruId),
            'expired_at' => $expiredAt,
            'is_used' => false,
        ]);

        return $qrCode;
    }

    /**
     * Validate QR Code token
     */
    public function validate(string $token)
    {
        $qrCode = QrCode::where('token', $token)
            ->where('is_used', false)
            ->where('expired_at', '>', now())
            ->first();

        if (!$qrCode) {
            return [
                'valid' => false,
                'message' => 'QR Code tidak valid atau sudah kadaluarsa',
            ];
        }

        return [
            'valid' => true,
            'qrCode' => $qrCode,
        ];
    }

    /**
     * Mark QR Code as used
     */
    public function markAsUsed($qrCodeId)
    {
        return QrCode::where('id', $qrCodeId)->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Generate QR Code image
     */
    public function generateImage($qrString, $size = 300)
    {
        return QrCodeGenerator::format('png')
            ->size($size)
            ->generate($qrString);
    }

    /**
     * Clean up expired QR Codes
     */
    public function cleanupExpired()
    {
        return QrCode::where('expired_at', '<', now())
            ->orWhere('is_used', true)
            ->delete();
    }

    /**
     * Generate unique token
     */
    protected function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while (QrCode::where('token', $token)->exists());

        return $token;
    }

    /**
     * Generate QR string with signature
     */
    protected function generateQrString($token, $guruId): string
    {
        $signature = hash_hmac('sha256', $token . $guruId, config('app.key'));
        return json_encode([
            'token' => $token,
            'guru_id' => $guruId,
            'signature' => $signature,
            'timestamp' => now()->timestamp,
        ]);
    }

    /**
     * Verify QR signature
     */
    public function verifySignature($qrString): bool
    {
        try {
            $data = json_decode($qrString, true);

            if (!isset($data['token'], $data['guru_id'], $data['signature'])) {
                return false;
            }

            $expectedSignature = hash_hmac(
                'sha256',
                $data['token'] . $data['guru_id'],
                config('app.key')
            );

            return hash_equals($expectedSignature, $data['signature']);
        } catch (\Exception $e) {
            return false;
        }
    }
}
