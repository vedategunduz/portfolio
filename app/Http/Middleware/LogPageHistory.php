<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageHistory;

class LogPageHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        // Admin sayfalarını loglama
        if ($request->is('admin/*')) {
            return $response;
        }

        // Sayfa ziyaretini kaydet (response gittikten sonra)
        try {
            $pathWithQuery = $request->getRequestUri();
            $responseTimeMs = (int) round((microtime(true) - $start) * 1000);

            PageHistory::create([
                'ip_address' => $request->ip(),
                'path' => $pathWithQuery,
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'session_id' => $request->getSession()?->getId(),
                'response_time_ms' => $responseTimeMs,
            ]);
        } catch (\Exception $e) {
            // Sessiz hata yönetimi
        }

        return $response;
    }
}
