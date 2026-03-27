<?php

namespace Modules\Analytics\Application\Services;

class BlogTrafficClassifierService
{
    public function classify(?string $userAgent, array $context = []): array
    {
        $ua = strtolower((string) $userAgent);
        $isBot = $this->isKnownBot($ua);

        $score = 0;
        if (blank($context['visitor_uuid'] ?? null) || blank($context['session_id'] ?? null)) {
            $score += 40;
        }

        $activeDelta = (int) ($context['active_time_delta'] ?? 0);
        if ($activeDelta > 20) {
            $score += 30;
        }

        $eventType = (string) ($context['event_type'] ?? '');
        if ($eventType === 'heartbeat' && $activeDelta === 0 && (int) ($context['max_scroll_percent'] ?? 0) === 0) {
            $score += 10;
        }

        if ((int) ($context['heartbeat_count'] ?? 0) > 500) {
            $score += 25;
        }

        return [
            'is_bot' => $isBot,
            'is_suspicious' => ! $isBot && $score >= 40,
            'suspicion_score' => $score,
        ];
    }

    private function isKnownBot(string $userAgent): bool
    {
        if ($userAgent === '') {
            return false;
        }

        $keywords = [
            'bot',
            'crawler',
            'spider',
            'slurp',
            'bingpreview',
            'facebookexternalhit',
            'googlebot',
            'ahrefsbot',
            'semrushbot',
            'yandexbot',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
