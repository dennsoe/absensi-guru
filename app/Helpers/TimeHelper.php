<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Format time to Indonesian format (HH:MM)
     */
    public static function format(string|Carbon $time, string $format = 'H:i'): string
    {
        if ($time instanceof Carbon) {
            return $time->format($format);
        }

        return Carbon::parse($time)->format($format);
    }

    /**
     * Format time with seconds (HH:MM:SS)
     */
    public static function formatWithSeconds(string|Carbon $time): string
    {
        return self::format($time, 'H:i:s');
    }

    /**
     * Calculate duration between two times in minutes
     */
    public static function getDurationMinutes(
        string|Carbon $start,
        string|Carbon $end
    ): int {
        $startTime = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endTime = $end instanceof Carbon ? $end : Carbon::parse($end);

        return $startTime->diffInMinutes($endTime);
    }

    /**
     * Calculate duration between two times in hours
     */
    public static function getDurationHours(
        string|Carbon $start,
        string|Carbon $end
    ): float {
        $minutes = self::getDurationMinutes($start, $end);
        return round($minutes / 60, 2);
    }

    /**
     * Format duration in minutes to human readable
     */
    public static function formatDuration(int $minutes, bool $short = false): string
    {
        if ($minutes < 60) {
            return $short ? $minutes . 'm' : $minutes . ' menit';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $short ? $hours . 'j' : $hours . ' jam';
        }

        if ($short) {
            return $hours . 'j ' . $remainingMinutes . 'm';
        }

        return $hours . ' jam ' . $remainingMinutes . ' menit';
    }

    /**
     * Check if time is within range
     */
    public static function isWithinRange(
        string|Carbon $time,
        string|Carbon $start,
        string|Carbon $end
    ): bool {
        $checkTime = $time instanceof Carbon ? $time : Carbon::parse($time);
        $startTime = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endTime = $end instanceof Carbon ? $end : Carbon::parse($end);

        return $checkTime->between($startTime, $endTime);
    }

    /**
     * Check if current time is within range
     */
    public static function isNowWithinRange(
        string|Carbon $start,
        string|Carbon $end
    ): bool {
        return self::isWithinRange(Carbon::now(), $start, $end);
    }

    /**
     * Get time difference in human readable format
     */
    public static function getDifference(
        string|Carbon $start,
        string|Carbon $end,
        bool $absolute = true
    ): string {
        $startTime = $start instanceof Carbon ? $start : Carbon::parse($start);
        $endTime = $end instanceof Carbon ? $end : Carbon::parse($end);

        return $startTime->locale('id')->diffForHumans($endTime, [
            'syntax' => Carbon::DIFF_ABSOLUTE,
            'options' => $absolute ? Carbon::NO_ZERO_DIFF : 0,
        ]);
    }

    /**
     * Add minutes to time
     */
    public static function addMinutes(string|Carbon $time, int $minutes): Carbon
    {
        $carbon = $time instanceof Carbon ? $time->copy() : Carbon::parse($time);
        return $carbon->addMinutes($minutes);
    }

    /**
     * Subtract minutes from time
     */
    public static function subtractMinutes(string|Carbon $time, int $minutes): Carbon
    {
        $carbon = $time instanceof Carbon ? $time->copy() : Carbon::parse($time);
        return $carbon->subMinutes($minutes);
    }

    /**
     * Get time slots between start and end with interval
     */
    public static function getTimeSlots(
        string|Carbon $start,
        string|Carbon $end,
        int $intervalMinutes = 30
    ): array {
        $startTime = $start instanceof Carbon ? $start->copy() : Carbon::parse($start);
        $endTime = $end instanceof Carbon ? $end : Carbon::parse($end);

        $slots = [];
        $current = $startTime->copy();

        while ($current->lt($endTime)) {
            $slotEnd = $current->copy()->addMinutes($intervalMinutes);

            if ($slotEnd->lte($endTime)) {
                $slots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'label' => $current->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                ];
            }

            $current->addMinutes($intervalMinutes);
        }

        return $slots;
    }

    /**
     * Check if time is late based on threshold
     */
    public static function isLate(
        string|Carbon $actualTime,
        string|Carbon $expectedTime
    ): bool {
        $actual = $actualTime instanceof Carbon ? $actualTime : Carbon::parse($actualTime);
        $expected = $expectedTime instanceof Carbon ? $expectedTime : Carbon::parse($expectedTime);

        return $actual->gt($expected);
    }

    /**
     * Get late minutes
     */
    public static function getLateMinutes(
        string|Carbon $actualTime,
        string|Carbon $expectedTime
    ): int {
        if (!self::isLate($actualTime, $expectedTime)) {
            return 0;
        }

        return self::getDurationMinutes($expectedTime, $actualTime);
    }

    /**
     * Check if time is early
     */
    public static function isEarly(
        string|Carbon $actualTime,
        string|Carbon $expectedTime
    ): bool {
        $actual = $actualTime instanceof Carbon ? $actualTime : Carbon::parse($actualTime);
        $expected = $expectedTime instanceof Carbon ? $expectedTime : Carbon::parse($expectedTime);

        return $actual->lt($expected);
    }

    /**
     * Parse time string to Carbon
     */
    public static function parse(string $time, ?string $date = null): Carbon
    {
        if ($date) {
            return Carbon::parse($date . ' ' . $time);
        }

        return Carbon::parse($time);
    }

    /**
     * Get current time formatted
     */
    public static function now(string $format = 'H:i:s'): string
    {
        return Carbon::now()->format($format);
    }

    /**
     * Convert minutes to hours and minutes array
     */
    public static function minutesToHoursMinutes(int $minutes): array
    {
        return [
            'hours' => floor($minutes / 60),
            'minutes' => $minutes % 60,
        ];
    }
}
