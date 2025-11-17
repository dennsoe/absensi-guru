<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Libur;

class DateHelper
{
    /**
     * Format date to Indonesian format
     */
    public static function format(string|Carbon $date, string $format = 'd F Y'): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->locale('id')->translatedFormat($format);
    }

    /**
     * Format date with day name
     */
    public static function formatWithDay(string|Carbon $date): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->locale('id')->translatedFormat('l, d F Y');
    }

    /**
     * Get Indonesian day name
     */
    public static function getDayName(string|Carbon $date): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->locale('id')->dayName;
    }

    /**
     * Get Indonesian month name
     */
    public static function getMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$month] ?? '';
    }

    /**
     * Check if date is weekend
     */
    public static function isWeekend(string|Carbon $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->isWeekend();
    }

    /**
     * Check if date is holiday
     */
    public static function isHoliday(string|Carbon $date): bool
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

        return Libur::where('tanggal', $carbon->format('Y-m-d'))
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if date is working day
     */
    public static function isWorkingDay(string|Carbon $date): bool
    {
        return !self::isWeekend($date) && !self::isHoliday($date);
    }

    /**
     * Get working days between two dates
     */
    public static function getWorkingDays(string|Carbon $start, string|Carbon $end): int
    {
        $startDate = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endDate = $end instanceof Carbon ? $end : Carbon::parse($end);

        $workingDays = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            if (self::isWorkingDay($current)) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    /**
     * Get holidays in a date range
     */
    public static function getHolidays(string|Carbon $start, string|Carbon $end): array
    {
        $startDate = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endDate = $end instanceof Carbon ? $end : Carbon::parse($end);

        return Libur::whereBetween('tanggal', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ])
            ->where('is_active', true)
            ->orderBy('tanggal')
            ->get()
            ->toArray();
    }

    /**
     * Format date range
     */
    public static function formatRange(
        string|Carbon $start,
        string|Carbon $end,
        bool $includeYear = true
    ): string {
        $startDate = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endDate = $end instanceof Carbon ? $end : Carbon::parse($end);

        $startFormat = $includeYear ? 'd F Y' : 'd F';
        $endFormat = 'd F Y';

        if ($startDate->format('Y-m') === $endDate->format('Y-m')) {
            // Same month
            return sprintf(
                '%d - %s',
                $startDate->day,
                self::format($endDate, $endFormat)
            );
        }

        return sprintf(
            '%s - %s',
            self::format($startDate, $startFormat),
            self::format($endDate, $endFormat)
        );
    }

    /**
     * Get relative time (human diff)
     */
    public static function humanDiff(string|Carbon $date): string
    {
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->locale('id')->diffForHumans();
    }

    /**
     * Parse Indonesian date string
     */
    public static function parseIndonesian(string $dateString): ?Carbon
    {
        try {
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get dates in a month
     */
    public static function getDatesInMonth(int $year, int $month): array
    {
        $dates = [];
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $current = $start->copy();
        while ($current->lte($end)) {
            $dates[] = [
                'date' => $current->format('Y-m-d'),
                'day' => $current->day,
                'day_name' => self::getDayName($current),
                'is_weekend' => self::isWeekend($current),
                'is_holiday' => self::isHoliday($current),
                'is_working_day' => self::isWorkingDay($current),
            ];
            $current->addDay();
        }

        return $dates;
    }

    /**
     * Get current academic year
     */
    public static function getCurrentAcademicYear(): string
    {
        $now = Carbon::now();
        $year = $now->year;

        // If current month is July or later, academic year is current year / next year
        // Otherwise, it's previous year / current year
        if ($now->month >= 7) {
            return $year . '/' . ($year + 1);
        }

        return ($year - 1) . '/' . $year;
    }
}
