<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Format currency to Indonesian Rupiah
     */
    public static function currency(float $amount, bool $includeSymbol = true): string
    {
        $formatted = number_format($amount, 0, ',', '.');

        return $includeSymbol ? 'Rp ' . $formatted : $formatted;
    }

    /**
     * Format number with thousand separator
     */
    public static function number(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }

    /**
     * Format percentage
     */
    public static function percentage(float $value, int $decimals = 2): string
    {
        return number_format($value, $decimals, ',', '.') . '%';
    }

    /**
     * Format phone number to Indonesian format
     */
    public static function phone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 62 prefix to 0
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        // Format based on length
        if (strlen($phone) >= 10) {
            // Format: 0812-3456-7890
            return preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $phone);
        }

        return $phone;
    }

    /**
     * Format phone number for WhatsApp (with +62)
     */
    public static function phoneForWhatsApp(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 0 prefix to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Add + prefix if not present
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Format NIP (Nomor Induk Pegawai)
     */
    public static function nip(string $nip): string
    {
        // Remove all non-numeric characters
        $nip = preg_replace('/[^0-9]/', '', $nip);

        // Format 18 digit NIP: 1234 5678 9012 345678
        if (strlen($nip) === 18) {
            return preg_replace('/(\d{4})(\d{4})(\d{4})(\d{6})/', '$1 $2 $3 $4', $nip);
        }

        return $nip;
    }

    /**
     * Format file size to human readable
     */
    public static function fileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Truncate string with ellipsis
     */
    public static function truncate(string $text, int $length = 100, string $ending = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length - mb_strlen($ending)) . $ending;
    }

    /**
     * Format name to title case
     */
    public static function name(string $name): string
    {
        return mb_convert_case(trim($name), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Format initial/acronym from name
     */
    public static function initial(string $name, int $length = 2): string
    {
        $words = explode(' ', trim($name));
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
                if (mb_strlen($initials) >= $length) {
                    break;
                }
            }
        }

        return $initials;
    }

    /**
     * Format status badge HTML
     */
    public static function statusBadge(string $status): string
    {
        $badges = [
            'hadir' => '<span class="badge bg-success">Hadir</span>',
            'terlambat' => '<span class="badge bg-warning">Terlambat</span>',
            'alpha' => '<span class="badge bg-danger">Alpha</span>',
            'sakit' => '<span class="badge bg-info">Sakit</span>',
            'izin' => '<span class="badge bg-primary">Izin</span>',
            'cuti' => '<span class="badge bg-secondary">Cuti</span>',
            'aktif' => '<span class="badge bg-success">Aktif</span>',
            'nonaktif' => '<span class="badge bg-secondary">Non-Aktif</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        ];

        return $badges[strtolower($status)] ?? '<span class="badge bg-secondary">' . $status . '</span>';
    }

    /**
     * Format email to hide part of it
     */
    public static function hideEmail(string $email): string
    {
        [$username, $domain] = explode('@', $email);

        $usernameLength = strlen($username);
        $visibleLength = max(2, (int)($usernameLength / 3));

        $hiddenUsername = substr($username, 0, $visibleLength) .
                         str_repeat('*', $usernameLength - $visibleLength);

        return $hiddenUsername . '@' . $domain;
    }

    /**
     * Format NIP to hide part of it
     */
    public static function hideNIP(string $nip): string
    {
        $length = strlen($nip);

        if ($length < 8) {
            return $nip;
        }

        $visible = 4;
        return substr($nip, 0, $visible) . str_repeat('*', $length - ($visible * 2)) . substr($nip, -$visible);
    }

    /**
     * Format boolean to Yes/No or Ya/Tidak
     */
    public static function boolean(bool $value, string $lang = 'id'): string
    {
        if ($lang === 'id') {
            return $value ? 'Ya' : 'Tidak';
        }

        return $value ? 'Yes' : 'No';
    }

    /**
     * Format array to comma-separated string
     */
    public static function list(array $items, string $separator = ', ', string $lastSeparator = ' dan '): string
    {
        if (empty($items)) {
            return '';
        }

        if (count($items) === 1) {
            return $items[0];
        }

        $lastItem = array_pop($items);

        return implode($separator, $items) . $lastSeparator . $lastItem;
    }

    /**
     * Sanitize string for URL slug
     */
    public static function slug(string $text): string
    {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, '-');

        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // Lowercase
        $text = strtolower($text);

        return $text ?: 'n-a';
    }
}
