<?php

namespace Modules\Analytics\Application\Services;

use App\Enums\RiskLevel;
use App\Enums\TrafficType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Analytics\Models\ClassifiedVisitLog;
use Modules\Analytics\Models\ExploitSuspiciousEvent;
use Modules\Analytics\Models\RawRequestLog;

/**
 * Gelen isteği sinyallere göre sınıflandırır: human, known_bot, suspicious_bot, monitoring, internal.
 * User-Agent tek başına yeterli sayılmaz; path, IP sıklığı, asset isteği vb. birlikte değerlendirilir.
 */
class VisitorClassificationService
{
    public function __construct(
        protected SuspiciousPatternMatcher $suspiciousMatcher,
        protected array $config
    ) {
    }

    /**
     * İsteği yanıt üretmeden sınıflandır; exploit event gerekirse burada yazılır.
     * Middleware şüpheli istekleri erken yakalayıp 403 dönebilmek için bunu kullanır.
     * Dönen array: traffic_type, risk_level, suspicion_reason?, bot_name?, matched_rule?, is_suspicious_event (bool).
     */
    public function getClassification(Request $request): array
    {
        $path = $request->path();
        $pathWithQuery = $request->getRequestUri();
        $queryString = $request->getQueryString() ?? '';
        $ua = $request->userAgent() ?? '';
        $ip = $request->ip() ?? '';

        if ($this->isInternalIp($ip)) {
            return [
                'traffic_type' => TrafficType::Internal,
                'risk_level' => RiskLevel::Low,
                'suspicion_reason' => null,
                'bot_name' => null,
                'matched_rule' => 'internal_ip',
                'is_suspicious_event' => false,
            ];
        }

        if ($this->isMonitoringPath($path)) {
            return [
                'traffic_type' => TrafficType::Monitoring,
                'risk_level' => RiskLevel::Low,
                'suspicion_reason' => null,
                'bot_name' => null,
                'matched_rule' => 'monitoring_path',
                'is_suspicious_event' => false,
            ];
        }

        $suspiciousMatch = $this->suspiciousMatcher->firstMatch($path, $pathWithQuery);
        if ($suspiciousMatch !== null) {
            $this->recordExploitEvent($ip, 'suspicious_pattern', 'Şüpheli URL/query eşleşmesi', $pathWithQuery, $ua, $suspiciousMatch['rule'], 'high', [
                'type' => $suspiciousMatch['type'],
                'path' => $path,
            ]);
            return [
                'traffic_type' => TrafficType::SuspiciousBot,
                'risk_level' => RiskLevel::High,
                'suspicion_reason' => 'Şüpheli path/query pattern: '.$suspiciousMatch['rule'],
                'bot_name' => null,
                'matched_rule' => $suspiciousMatch['rule'],
                'is_suspicious_event' => true,
            ];
        }

        if ($this->isRateAbuse($ip)) {
            $this->recordExploitEvent($ip, 'rate_abuse', 'Kısa sürede yoğun istek', $pathWithQuery, $ua, 'rate_limit', 'medium', ['path' => $path]);
            return [
                'traffic_type' => TrafficType::SuspiciousBot,
                'risk_level' => RiskLevel::Medium,
                'suspicion_reason' => 'Aynı IP kısa sürede çok sayıda istek',
                'bot_name' => null,
                'matched_rule' => 'rate_abuse',
                'is_suspicious_event' => true,
            ];
        }

        $botName = $this->detectKnownBot($ua);
        if ($botName !== null) {
            return [
                'traffic_type' => TrafficType::KnownBot,
                'risk_level' => RiskLevel::Low,
                'suspicion_reason' => null,
                'bot_name' => $botName,
                'matched_rule' => 'known_bot_ua',
                'is_suspicious_event' => false,
            ];
        }

        if ($this->isRobotsRequest($path)) {
            return [
                'traffic_type' => TrafficType::KnownBot,
                'risk_level' => RiskLevel::Low,
                'suspicion_reason' => null,
                'bot_name' => 'robots_request',
                'matched_rule' => 'robots_txt',
                'is_suspicious_event' => false,
            ];
        }

        return [
            'traffic_type' => TrafficType::Human,
            'risk_level' => RiskLevel::Low,
            'suspicion_reason' => null,
            'bot_name' => null,
            'matched_rule' => null,
            'is_suspicious_event' => false,
        ];
    }

    public function classify(Request $request, RawRequestLog $rawLog): array
    {
        return $this->getClassification($request);
    }

    public function recordRequestWithClassification(Request $request, array $rawPayload, array $classification): void
    {
        if (! ($this->config['enabled'] ?? true)) {
            return;
        }

        $this->incrementRateCounter($rawPayload['ip_address']);

        try {
            $rawLog = RawRequestLog::create($rawPayload);

            ClassifiedVisitLog::create([
                'raw_log_id' => $rawLog->id,
                'ip_address' => $rawPayload['ip_address'],
                'traffic_type' => $classification['traffic_type']->value,
                'suspicion_reason' => $classification['suspicion_reason'],
                'bot_name' => $classification['bot_name'],
                'risk_level' => $classification['risk_level']->value,
                'matched_rule' => $classification['matched_rule'],
                'visited_at' => $rawPayload['visited_at'],
            ]);
        } catch (\Throwable $e) {
            Log::channel('single')->warning('Visitor logging failed: '.$e->getMessage(), ['exception' => $e]);
        }
    }

    public function recordRequest(Request $request, array $rawPayload, int $statusCode, int $responseTimeMs): void
    {
        if (! ($this->config['enabled'] ?? true)) {
            return;
        }

        $this->incrementRateCounter($rawPayload['ip_address']);

        try {
            $rawLog = RawRequestLog::create($rawPayload);
            $classification = $this->classify($request, $rawLog);

            ClassifiedVisitLog::create([
                'raw_log_id' => $rawLog->id,
                'ip_address' => $rawPayload['ip_address'],
                'traffic_type' => $classification['traffic_type']->value,
                'suspicion_reason' => $classification['suspicion_reason'],
                'bot_name' => $classification['bot_name'],
                'risk_level' => $classification['risk_level']->value,
                'matched_rule' => $classification['matched_rule'],
                'visited_at' => $rawPayload['visited_at'],
            ]);
        } catch (\Throwable $e) {
            Log::channel('single')->warning('Visitor logging failed: '.$e->getMessage(), ['exception' => $e]);
        }
    }

    protected function isInternalIp(string $ip): bool
    {
        $allowed = $this->config['internal_ips'] ?? [];
        return in_array($ip, $allowed, true);
    }

    protected function isMonitoringPath(string $path): bool
    {
        $paths = $this->config['monitoring_paths'] ?? [];
        foreach ($paths as $p) {
            if (str_starts_with('/'.ltrim($path, '/'), $p)) {
                return true;
            }
        }
        return false;
    }

    protected function isRateAbuse(string $ip): bool
    {
        $max = (int) ($this->config['rate_limit_max_requests'] ?? 30);
        $key = 'visitor_rate:'.$ip;
        $count = (int) Cache::get($key, 0);
        return $count >= $max;
    }

    public function incrementRateCounter(string $ip): void
    {
        $window = (int) ($this->config['rate_limit_window_seconds'] ?? 10);
        $key = 'visitor_rate:'.$ip;
        $count = (int) Cache::get($key, 0);
        Cache::put($key, $count + 1, $window);
    }

    protected function detectKnownBot(string $ua): ?string
    {
        $signatures = $this->config['known_bot_signatures'] ?? [];
        foreach ($signatures as $sig) {
            if (stripos($ua, $sig) !== false) {
                return $sig;
            }
        }
        return null;
    }

    protected function isRobotsRequest(string $path): bool
    {
        $robots = $this->config['robots_path'] ?? '/robots.txt';
        return rtrim($path, '/') === rtrim(parse_url($robots, PHP_URL_PATH) ?: $robots, '/');
    }

    protected function recordExploitEvent(
        string $ip,
        string $eventType,
        string $title,
        ?string $fullUrl,
        ?string $userAgent,
        ?string $matchedRule,
        string $severity = 'medium',
        array $meta = []
    ): void {
        try {
            ExploitSuspiciousEvent::create([
                'ip_address' => $ip,
                'event_type' => $eventType,
                'title' => $title,
                'full_url' => $fullUrl,
                'user_agent' => $userAgent,
                'matched_rule' => $matchedRule,
                'severity' => $severity,
                'meta' => $meta,
            ]);
        } catch (\Throwable $e) {
            Log::channel('single')->warning('Exploit event record failed: '.$e->getMessage());
        }
    }
}
