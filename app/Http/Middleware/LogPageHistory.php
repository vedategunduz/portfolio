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
        $response = $next($request);

        // Sayfa ziyaretini kaydet (response gittikten sonra)
        try {
            PageHistory::create([
                'ip_address' => $request->ip(),
                'path' => $request->path(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'session_id' => $request->getSession()?->getId(),
            ]);
        } catch (\Exception $e) {
            // Sessiz hata yönetimi
        }

        return $response;
    }
}
