<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Libur;

class CheckAbsensiTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if today is a holiday
        if ($this->isHoliday()) {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini adalah hari libur. Absensi tidak dapat dilakukan.',
            ], 403);
        }

        // Check if today is weekend (Saturday or Sunday)
        if ($this->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Absensi hanya dapat dilakukan pada hari Senin - Jumat.',
            ], 403);
        }

        return $next($request);
    }

    /**
     * Check if today is a holiday
     */
    protected function isHoliday(): bool
    {
        $today = now()->format('Y-m-d');

        return Libur::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->exists();
    }

    /**
     * Check if today is weekend
     */
    protected function isWeekend(): bool
    {
        $dayOfWeek = now()->dayOfWeek;
        // 0 = Sunday, 6 = Saturday
        return in_array($dayOfWeek, [0, 6]);
    }
}
