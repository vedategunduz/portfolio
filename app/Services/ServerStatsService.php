<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ServerStatsService
{
    private const CACHE_KEY = 'admin_server_stats';
    private const CACHE_TTL_SECONDS = 60;

    public function getStats(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            return [
                'cpu_percent' => $this->getCpuUsage(),
                'ram_percent' => $this->getRamUsage(),
                'disk_percent' => $this->getDiskUsage(),
                'uptime' => $this->getUptime(),
                'load_average' => $this->getLoadAverage(),
                'nginx_status' => $this->getServiceStatus('nginx'),
                'mysql_status' => $this->getMysqlStatus(),
                'php_fpm_status' => $this->getPhpFpmStatus(),
                'last_deploy' => $this->getLastDeployTime(),
                'failed_jobs_count' => $this->getFailedJobsCount(),
                'updated_at' => now()->format('H:i:s'),
            ];
        });
    }

    private function getCpuUsage(): ?float
    {
        if (!is_readable('/proc/stat')) {
            return $this->getCpuFromTop();
        }
        $stat1 = @file_get_contents('/proc/stat');
        if ($stat1 === false) {
            return null;
        }
        usleep(300000); // 0.3 s
        $stat2 = @file_get_contents('/proc/stat');
        if ($stat2 === false) {
            return null;
        }
        $info1 = $this->parseProcStatCpu($stat1);
        $info2 = $this->parseProcStatCpu($stat2);
        if ($info1 === null || $info2 === null) {
            return null;
        }
        $total = $info2['total'] - $info1['total'];
        $idle = $info2['idle'] - $info1['idle'];
        if ($total <= 0) {
            return null;
        }
        return round(100 * (1 - $idle / $total), 1);
    }

    private function parseProcStatCpu(string $stat): ?array
    {
        foreach (explode("\n", $stat) as $line) {
            if (str_starts_with($line, 'cpu ')) {
                $parts = preg_split('/\s+/', trim($line), 0, PREG_SPLIT_NO_EMPTY);
                if (count($parts) < 5) {
                    return null;
                }
                $user = (int) $parts[1];
                $nice = (int) $parts[2];
                $system = (int) $parts[3];
                $idle = (int) $parts[4];
                $iowait = isset($parts[5]) ? (int) $parts[5] : 0;
                $irq = isset($parts[6]) ? (int) $parts[6] : 0;
                $softirq = isset($parts[7]) ? (int) $parts[7] : 0;
                $total = $user + $nice + $system + $idle + $iowait + $irq + $softirq;
                return ['total' => $total, 'idle' => $idle];
            }
        }
        return null;
    }

    private function getCpuFromTop(): ?float
    {
        $out = [];
        @exec('top -bn1 2>/dev/null | grep "Cpu(s)"', $out);
        if (empty($out) || !preg_match('/([\d.]+)\s*%\s*id/', $out[0], $m)) {
            return null;
        }
        $idle = (float) $m[1];
        return round(100 - $idle, 1);
    }

    private function getRamUsage(): ?array
    {
        if (is_readable('/proc/meminfo')) {
            $mem = @file_get_contents('/proc/meminfo');
            if ($mem === false) {
                return null;
            }
            $total = $this->parseMeminfoValue($mem, 'MemTotal');
            $available = $this->parseMeminfoValue($mem, 'MemAvailable');
            if ($total === null || $available === null || $total <= 0) {
                return null;
            }
            $used = $total - $available;
            return [
                'percent' => round(100 * $used / $total, 1),
                'used_mb' => round($used / 1024, 1),
                'total_mb' => round($total / 1024, 1),
            ];
        }
        $out = [];
        @exec('free -m 2>/dev/null', $out);
        if (count($out) < 2) {
            return null;
        }
        if (preg_match('/Mem:\s*(\d+)\s+(\d+)\s+\d+\s+\d+\s+(\d+)\s+(\d+)/', $out[1], $m)) {
            $total = (int) $m[1];
            $used = (int) $m[2];
            if ($total <= 0) {
                return null;
            }
            return [
                'percent' => round(100 * $used / $total, 1),
                'used_mb' => $used,
                'total_mb' => $total,
            ];
        }
        return null;
    }

    private function parseMeminfoValue(string $mem, string $key): ?int
    {
        if (preg_match('/' . preg_quote($key, '/') . ':\s*(\d+)\s*kB/', $mem, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    private function getDiskUsage(): ?array
    {
        $root = PHP_OS_FAMILY === 'Linux' ? '/' : base_path();
        $total = @disk_total_space($root);
        $free = @disk_free_space($root);
        if ($total === false || $free === false || $total <= 0) {
            return null;
        }
        $used = $total - $free;
        return [
            'percent' => round(100 * $used / $total, 1),
            'used_gb' => round($used / (1024 ** 3), 1),
            'total_gb' => round($total / (1024 ** 3), 1),
        ];
    }

    private function getUptime(): ?string
    {
        if (is_readable('/proc/uptime')) {
            $s = @file_get_contents('/proc/uptime');
            if ($s === false) {
                return null;
            }
            $seconds = (float) explode(' ', $s)[0];
            return $this->formatUptime((int) $seconds);
        }
        $out = [];
        @exec('uptime -s 2>/dev/null', $out);
        if (!empty($out)) {
            return $out[0];
        }
        return null;
    }

    private function formatUptime(int $seconds): string
    {
        $d = (int) ($seconds / 86400);
        $h = (int) (($seconds % 86400) / 3600);
        $m = (int) (($seconds % 3600) / 60);
        $parts = [];
        if ($d > 0) {
            $parts[] = $d . 'g';
        }
        $parts[] = $h . 's';
        $parts[] = $m . 'dk';
        return implode(' ', $parts);
    }

    private function getLoadAverage(): ?string
    {
        if (is_readable('/proc/loadavg')) {
            $s = @file_get_contents('/proc/loadavg');
            if ($s === false) {
                return null;
            }
            $parts = preg_split('/\s+/', trim($s), 0, PREG_SPLIT_NO_EMPTY);
            if (count($parts) >= 3) {
                return $parts[0] . ' / ' . $parts[1] . ' / ' . $parts[2];
            }
            return $parts[0] ?? null;
        }
        $out = [];
        @exec('uptime 2>/dev/null', $out);
        if (!empty($out) && preg_match('/load average[s]?:\s*([\d.,\s]+)/', $out[0], $m)) {
            $nums = array_map('trim', explode(',', $m[1]));
            if (count($nums) >= 3) {
                return $nums[0] . ' / ' . $nums[1] . ' / ' . $nums[2];
            }
            return trim($m[1]);
        }
        return null;
    }

    private function getServiceStatus(string $service): string
    {
        $out = [];
        @exec('systemctl is-active ' . escapeshellarg($service) . ' 2>/dev/null', $out);
        $status = trim($out[0] ?? '');
        return $status === 'active' ? 'active' : ($status ?: 'inactive');
    }

    private function getMysqlStatus(): string
    {
        foreach (['mysql', 'mysqld', 'mariadb'] as $service) {
            $s = $this->getServiceStatus($service);
            if ($s === 'active') {
                return 'active';
            }
        }
        return 'inactive';
    }

    private function getPhpFpmStatus(): string
    {
        $versions = ['8.4', '8.3', '8.2', '8.1', '8.0'];
        foreach ($versions as $v) {
            if ($this->getServiceStatus("php{$v}-fpm") === 'active') {
                return 'active';
            }
        }
        return 'inactive';
    }

    private function getLastDeployTime(): ?string
    {
        $path = base_path('deploy_last_at.txt');
        if (!is_readable($path)) {
            return null;
        }
        $content = trim((string) @file_get_contents($path));
        return $content !== '' ? $content : null;
    }

    private function getFailedJobsCount(): int
    {
        try {
            return (int) DB::table('failed_jobs')->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
