<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    /**
     * Validate Indonesian NIP (18 digits)
     */
    public static function isValidNIP(string $nip): bool
    {
        // Remove all non-numeric characters
        $nip = preg_replace('/[^0-9]/', '', $nip);

        // Check if 18 digits
        if (strlen($nip) !== 18) {
            return false;
        }

        // Basic format validation for Indonesian NIP
        // Format: YYYYMMDDYYYYMMDDXX
        // Year (4) + Month (2) + Day (2) + Year Appointment (4) + Month Appointment (2) + Day Appointment (2) + Gender + Sequence (2)

        $year = (int)substr($nip, 0, 4);
        $month = (int)substr($nip, 4, 2);
        $day = (int)substr($nip, 6, 2);

        // Validate birth date
        if ($month < 1 || $month > 12) {
            return false;
        }

        if ($day < 1 || $day > 31) {
            return false;
        }

        if ($year < 1900 || $year > date('Y')) {
            return false;
        }

        return true;
    }

    /**
     * Validate Indonesian phone number
     */
    public static function isValidPhone(string $phone): bool
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if starts with 0 or 62
        if (!str_starts_with($phone, '0') && !str_starts_with($phone, '62')) {
            return false;
        }

        // Convert 62 to 0 for length check
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        // Check length (10-13 digits)
        $length = strlen($phone);

        return $length >= 10 && $length <= 13;
    }

    /**
     * Validate email address
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate URL
     */
    public static function isValidURL(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate coordinates (latitude, longitude)
     */
    public static function isValidCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude >= -90 && $latitude <= 90 &&
               $longitude >= -180 && $longitude <= 180;
    }

    /**
     * Validate time format (HH:MM or HH:MM:SS)
     */
    public static function isValidTime(string $time): bool
    {
        // Check HH:MM format
        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $time)) {
            return true;
        }

        // Check HH:MM:SS format
        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $time)) {
            return true;
        }

        return false;
    }

    /**
     * Validate date format (YYYY-MM-DD)
     */
    public static function isValidDate(string $date): bool
    {
        $validator = Validator::make(
            ['date' => $date],
            ['date' => 'date_format:Y-m-d']
        );

        return !$validator->fails();
    }

    /**
     * Validate password strength
     */
    public static function isStrongPassword(string $password, array $requirements = []): array
    {
        $minLength = $requirements['min_length'] ?? 6;
        $requireUppercase = $requirements['require_uppercase'] ?? false;
        $requireNumber = $requirements['require_number'] ?? false;
        $requireSpecial = $requirements['require_special'] ?? false;

        $errors = [];
        $isValid = true;

        // Check minimum length
        if (strlen($password) < $minLength) {
            $isValid = false;
            $errors[] = "Password minimal {$minLength} karakter";
        }

        // Check uppercase
        if ($requireUppercase && !preg_match('/[A-Z]/', $password)) {
            $isValid = false;
            $errors[] = "Password harus mengandung huruf kapital";
        }

        // Check number
        if ($requireNumber && !preg_match('/[0-9]/', $password)) {
            $isValid = false;
            $errors[] = "Password harus mengandung angka";
        }

        // Check special character
        if ($requireSpecial && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $isValid = false;
            $errors[] = "Password harus mengandung karakter khusus";
        }

        return [
            'valid' => $isValid,
            'errors' => $errors,
        ];
    }

    /**
     * Validate file extension
     */
    public static function isValidFileExtension(string $filename, array $allowedExtensions): bool
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($extension, array_map('strtolower', $allowedExtensions));
    }

    /**
     * Validate file size
     */
    public static function isValidFileSize(int $sizeInBytes, int $maxSizeInMB): bool
    {
        $maxSizeInBytes = $maxSizeInMB * 1024 * 1024;

        return $sizeInBytes <= $maxSizeInBytes;
    }

    /**
     * Validate image dimensions
     */
    public static function isValidImageDimensions(
        string $imagePath,
        ?int $minWidth = null,
        ?int $minHeight = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null
    ): bool {
        if (!file_exists($imagePath)) {
            return false;
        }

        [$width, $height] = getimagesize($imagePath);

        if ($minWidth && $width < $minWidth) {
            return false;
        }

        if ($minHeight && $height < $minHeight) {
            return false;
        }

        if ($maxWidth && $width > $maxWidth) {
            return false;
        }

        if ($maxHeight && $height > $maxHeight) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize input string
     */
    public static function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check if string contains only alphanumeric characters
     */
    public static function isAlphanumeric(string $input): bool
    {
        return ctype_alnum($input);
    }

    /**
     * Check if string contains only numeric characters
     */
    public static function isNumeric(string $input): bool
    {
        return ctype_digit($input);
    }

    /**
     * Check if string contains only alphabetic characters
     */
    public static function isAlpha(string $input): bool
    {
        return ctype_alpha($input);
    }

    /**
     * Validate Indonesian identity card number (NIK - 16 digits)
     */
    public static function isValidNIK(string $nik): bool
    {
        // Remove all non-numeric characters
        $nik = preg_replace('/[^0-9]/', '', $nik);

        // Check if 16 digits
        if (strlen($nik) !== 16) {
            return false;
        }

        // Basic format validation
        // Format: PPKKSSDDMMYYXXXX
        // PP = Province code (2 digits)
        // KK = City/Regency code (2 digits)
        // SS = District code (2 digits)
        // DDMMYY = Birth date (6 digits, DD+40 for female)
        // XXXX = Sequence number (4 digits)

        $day = (int)substr($nik, 6, 2);
        $month = (int)substr($nik, 8, 2);
        $year = (int)substr($nik, 10, 2);

        // For female, day is added by 40
        if ($day > 40) {
            $day -= 40;
        }

        // Validate month
        if ($month < 1 || $month > 12) {
            return false;
        }

        // Validate day
        if ($day < 1 || $day > 31) {
            return false;
        }

        return true;
    }

    /**
     * Validate NPSN (Nomor Pokok Sekolah Nasional - 8 digits)
     */
    public static function isValidNPSN(string $npsn): bool
    {
        // Remove all non-numeric characters
        $npsn = preg_replace('/[^0-9]/', '', $npsn);

        // Check if 8 digits
        return strlen($npsn) === 8;
    }
}
