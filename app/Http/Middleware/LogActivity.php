<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LogAktivitas;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and specific methods
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $this->logActivity($request);
        }

        return $response;
    }

    /**
     * Log the activity
     */
    protected function logActivity(Request $request)
    {
        try {
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aksi' => $this->getAction($request),
                'tabel' => $this->getTableName($request),
                'record_id' => $this->getRecordId($request),
                'data_lama' => null,
                'data_baru' => json_encode($request->except(['password', '_token', '_method'])),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - don't break the application if logging fails
            logger()->error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Get action from request
     */
    protected function getAction(Request $request): string
    {
        return match($request->method()) {
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'unknown',
        };
    }

    /**
     * Get table name from route
     */
    protected function getTableName(Request $request): ?string
    {
        $route = $request->route();
        if ($route) {
            $name = $route->getName();
            // Extract table name from route name (e.g., 'guru.store' -> 'guru')
            return explode('.', $name)[0] ?? null;
        }
        return null;
    }

    /**
     * Get record ID from route parameters
     */
    protected function getRecordId(Request $request): ?int
    {
        $route = $request->route();
        if ($route) {
            // Try common parameter names
            foreach (['id', 'guru', 'absensi', 'jadwal', 'kelas'] as $param) {
                if ($route->hasParameter($param)) {
                    $value = $route->parameter($param);
                    return is_numeric($value) ? (int) $value : null;
                }
            }
        }
        return null;
    }
}
