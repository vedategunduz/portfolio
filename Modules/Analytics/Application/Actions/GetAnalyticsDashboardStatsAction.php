<?php

namespace Modules\Analytics\Application\Actions;

use Modules\Analytics\Models\ClassifiedVisitLog;
use Modules\Analytics\Models\ExploitSuspiciousEvent;
use Modules\Analytics\Models\RawRequestLog;

class GetAnalyticsDashboardStatsAction
{
    public function execute(): array
    {
        try {
            return [
                'total_hits' => ClassifiedVisitLog::count(),
                'human_hits' => ClassifiedVisitLog::where('traffic_type', 'human')->count(),
                'known_bot_hits' => ClassifiedVisitLog::where('traffic_type', 'known_bot')->count(),
                'suspicious_hits' => ClassifiedVisitLog::where('traffic_type', 'suspicious_bot')->count(),
                'unique_human_visitors' => ClassifiedVisitLog::where('traffic_type', 'human')->distinct('ip_address')->count('ip_address'),
                'today_hits' => ClassifiedVisitLog::whereDate('visited_at', today())->count(),
                'suspicious_last_24h' => ExploitSuspiciousEvent::where('created_at', '>=', now()->subDay())->count(),
                'top_request_ip' => RawRequestLog::select('ip_address')->selectRaw('count(*) as c')->groupBy('ip_address')->orderByDesc('c')->first(),
                'top_target_url' => RawRequestLog::select('path')->selectRaw('count(*) as c')->groupBy('path')->orderByDesc('c')->first(),
                'top_suspicious_pattern' => ExploitSuspiciousEvent::select('matched_rule')->selectRaw('count(*) as c')->whereNotNull('matched_rule')->groupBy('matched_rule')->orderByDesc('c')->first(),
            ];
        } catch (\Throwable $e) {
            return [
                'total_hits' => 0,
                'human_hits' => 0,
                'known_bot_hits' => 0,
                'suspicious_hits' => 0,
                'unique_human_visitors' => 0,
                'today_hits' => 0,
                'suspicious_last_24h' => 0,
                'top_request_ip' => null,
                'top_target_url' => null,
                'top_suspicious_pattern' => null,
            ];
        }
    }
}
