<?php

namespace Modules\Analytics\Application\Services;

class AnalyticsFingerprintService
{
    public function hashIp(?string $ip): ?string
    {
        if (blank($ip)) {
            return null;
        }

        $pepper = config('app.key').(string) config('app.analytics_ip_hash_pepper', '');

        return hash_hmac('sha256', trim((string) $ip), $pepper);
    }

    public function parseUserAgent(?string $userAgent): array
    {
        $agent = strtolower((string) $userAgent);

        $deviceType = 'desktop';
        if (str_contains($agent, 'tablet') || str_contains($agent, 'ipad')) {
            $deviceType = 'tablet';
        } elseif (str_contains($agent, 'mobile') || str_contains($agent, 'android')) {
            $deviceType = 'mobile';
        }

        $browser = 'other';
        if (str_contains($agent, 'edg/')) {
            $browser = 'edge';
        } elseif (str_contains($agent, 'chrome/')) {
            $browser = 'chrome';
        } elseif (str_contains($agent, 'safari/') && ! str_contains($agent, 'chrome/')) {
            $browser = 'safari';
        } elseif (str_contains($agent, 'firefox/')) {
            $browser = 'firefox';
        }

        $os = 'other';
        if (str_contains($agent, 'windows')) {
            $os = 'windows';
        } elseif (str_contains($agent, 'mac os')) {
            $os = 'macos';
        } elseif (str_contains($agent, 'android')) {
            $os = 'android';
        } elseif (str_contains($agent, 'iphone') || str_contains($agent, 'ipad') || str_contains($agent, 'ios')) {
            $os = 'ios';
        } elseif (str_contains($agent, 'linux')) {
            $os = 'linux';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
        ];
    }
}
