<?php

namespace App\Helpers;

/**
 * Basit User-Agent ayrıştırıcı (browser, platform).
 * Production için jenssegers/agent gibi bir paket kullanılabilir.
 */
class UserAgentParser
{
    public static function parse(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return ['browser' => null, 'platform' => null, 'raw' => ''];
        }

        $browser = self::parseBrowser($userAgent);
        $platform = self::parsePlatform($userAgent);

        return [
            'browser' => $browser,
            'platform' => $platform,
            'raw' => $userAgent,
        ];
    }

    public static function format(?string $userAgent): string
    {
        $parsed = self::parse($userAgent);
        $parts = array_filter([$parsed['browser'], $parsed['platform']]);

        return $parts ? implode(' · ', $parts) : ($parsed['raw'] ?: '—');
    }

    protected static function parseBrowser(string $ua): ?string
    {
        if (preg_match('/Edg\/(\d+)/i', $ua, $m)) {
            return 'Edge ' . $m[1];
        }
        if (preg_match('/Chrome\/(\d+)/i', $ua, $m) && ! preg_match('/Chromium|Edg/i', $ua)) {
            return 'Chrome ' . $m[1];
        }
        if (preg_match('/Firefox\/(\d+)/i', $ua, $m)) {
            return 'Firefox ' . $m[1];
        }
        if (preg_match('/Safari\/(\d+)/i', $ua, $m) && ! preg_match('/Chrome/i', $ua)) {
            return 'Safari ' . $m[1];
        }
        if (preg_match('/OPR\/(\d+)/i', $ua, $m)) {
            return 'Opera ' . $m[1];
        }
        if (preg_match('/curl\/[\d.]+/i', $ua, $m)) {
            return 'curl';
        }
        if (preg_match('/bot|crawler|spider|scraper/i', $ua)) {
            return 'Bot';
        }

        return null;
    }

    protected static function parsePlatform(string $ua): ?string
    {
        if (preg_match('/Windows NT 10/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/Windows/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/Mac OS X (\d+[._]\d+)/i', $ua, $m)) {
            return 'macOS';
        }
        if (preg_match('/iPhone|iPad/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/Android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/Linux/i', $ua)) {
            return 'Linux';
        }

        return null;
    }
}
