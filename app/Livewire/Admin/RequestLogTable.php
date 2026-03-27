<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Analytics\Models\RawRequestLog;

class RequestLogTable extends Component
{
    use WithPagination;

    public string $date_from = '';

    public string $date_to = '';

    public string $ip = '';

    public string $method = '';

    public string $path = '';

    public string $user_agent = '';

    public string $status_code = '';

    public ?RawRequestLog $selectedLog = null;

    protected $queryString = [
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
        'ip' => ['except' => ''],
        'method' => ['except' => ''],
        'path' => ['except' => ''],
        'user_agent' => ['except' => ''],
        'status_code' => ['except' => ''],
    ];

    /** Framework/internal path prefixes to exclude from "top endpoint" stat (real user endpoints only). */
    protected static array $internalPathPatterns = [
        '/livewire',
        '/_ignition',
        '/_debugbar',
        '/telescope',
        '/horizon',
        '/_fcgi',
        '/sanctum',
    ];

    public function getExportUrlProperty(): string
    {
        $params = array_filter([
            'date_from' => $this->date_from ?: null,
            'date_to' => $this->date_to ?: null,
            'ip' => $this->ip ?: null,
            'method' => $this->method ?: null,
            'path' => $this->path ?: null,
            'user_agent' => $this->user_agent ?: null,
            'status_code' => $this->status_code ?: null,
        ]);

        return route('admin.page-history.raw.export', $params);
    }

    protected function applyFilters($query): void
    {
        if ($this->ip !== '') {
            $query->where('ip_address', $this->ip);
        }
        if ($this->method !== '') {
            $query->where('method', $this->method);
        }
        if ($this->path !== '') {
            $query->where('path', 'like', '%'.$this->path.'%');
        }
        if ($this->user_agent !== '') {
            $query->where('user_agent', 'like', '%'.$this->user_agent.'%');
        }
        if ($this->status_code !== '') {
            $query->where('status_code', $this->status_code);
        }
        if ($this->date_from !== '') {
            $query->whereDate('visited_at', '>=', $this->date_from);
        }
        if ($this->date_to !== '') {
            $query->whereDate('visited_at', '<=', $this->date_to);
        }
    }

    protected function rawStats($query): array
    {
        $total = $query->count();
        $avgTime = $total > 0 ? (float) $query->avg('response_time_ms') : null;
        $errorCount = (clone $query)->where(function ($q) {
            $q->where('status_code', '>=', 400)->orWhereNull('status_code');
        })->count();
        $errorRate = $total > 0 ? round(($errorCount / $total) * 100, 1) : 0;

        $topQuery = clone $query;
        $likeEscape = fn (string $s): string => str_replace(['_', '%'], ['\\_', '\\%'], $s).'%';
        foreach (self::$internalPathPatterns as $prefix) {
            $trimmed = ltrim($prefix, '/');
            $topQuery->where('path', 'not like', $likeEscape($prefix))->where('path', 'not like', $likeEscape($trimmed));
        }
        $topEndpoint = $topQuery->select('path')
            ->selectRaw('count(*) as c')
            ->groupBy('path')
            ->reorder()
            ->orderByDesc('c')
            ->first();

        return [
            'total_requests' => $total,
            'avg_response_ms' => $avgTime,
            'error_rate_percent' => $errorRate,
            'top_endpoint' => $topEndpoint,
        ];
    }

    public function clearFilters(): void
    {
        $this->reset(['date_from', 'date_to', 'ip', 'method', 'path', 'user_agent', 'status_code']);
        $this->resetPage();
    }

    public function openLogDetail(int $id): void
    {
        $this->selectedLog = RawRequestLog::find($id);
    }

    public function closeLogDetail(): void
    {
        $this->selectedLog = null;
    }

    public function getLogsProperty()
    {
        $query = RawRequestLog::query()->orderBy('visited_at', 'desc');
        $this->applyFilters($query);

        return $query->paginate(50);
    }

    public function getStatsProperty(): array
    {
        $query = RawRequestLog::query()->orderBy('visited_at', 'desc');
        $this->applyFilters($query);

        return $this->rawStats($query);
    }

    public function render()
    {
        return view('livewire.admin.request-log-table');
    }
}
