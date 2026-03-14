<?php

namespace App\Http\Middleware;

use App\Services\VisitorClassificationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sayfa / ziyaret geçmişini kaydeder: ham istek (raw_request_logs),
 * sınıflandırılmış trafik (classified_visit_logs) ve şüpheli olaylar (exploit_suspicious_events).
 * Admin ve config'deki skip_paths atlanır.
 */
class LogPageHistory
{
    public function __construct(
        protected VisitorClassificationService $classificationService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        if (! config('page_history.enabled', true)) {
            return $response;
        }

        // Admin sayfaları atlanır; sadece login sayfası loglansın (kimler denemiş görmek için).
        if ($request->is('admin/*') && ! $request->is('admin/login')) {
            return $response;
        }

        foreach (config('page_history.skip_paths', []) as $skip) {
            if (str_starts_with($request->path(), ltrim($skip, '/'))) {
                return $response;
            }
        }

        $responseTimeMs = (int) round((microtime(true) - $start) * 1000);
        $path = '/' . ltrim($request->path(), '/');
        $path = $path === '/' && $request->getRequestUri() !== '/' ? $request->getRequestUri() : $path;
        $fullUrl = $request->fullUrl();
        $queryString = $request->getQueryString();

        $extensions = config('page_history.asset_extensions', []);
        $isAsset = false;
        foreach ($extensions as $ext) {
            if (preg_match('#\.' . preg_quote($ext, '#') . '(\?|$)#i', $path)) {
                $isAsset = true;
                break;
            }
        }

        $rawPayload = [
            'ip_address' => $request->ip() ?? '0.0.0.0',
            'method' => $request->method(),
            'full_url' => $fullUrl,
            'path' => $path,
            'query_string' => $queryString,
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'status_code' => $response->getStatusCode(),
            'response_time_ms' => $responseTimeMs,
            'visited_at' => now(),
            'is_asset_request' => $isAsset,
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'request_fingerprint' => $this->fingerprint($request),
        ];

        $this->classificationService->recordRequest(
            $request,
            $rawPayload,
            $response->getStatusCode(),
            $responseTimeMs
        );

        return $response;
    }

    /**
     * İstek için kısa fingerprint (aynı ziyaretçi gruplama için opsiyonel).
     */
    protected function fingerprint(Request $request): string
    {
        return hash('xxh64', implode('|', [
            $request->ip(),
            $request->userAgent() ?? '',
            $request->path(),
        ]));
    }
}
