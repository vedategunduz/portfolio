<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassifiedVisitLog;
use App\Models\ExploitSuspiciousEvent;
use App\Models\RawRequestLog;
use Illuminate\Http\Request;

/**
 * Sayfa geçmişi logları: ham istek, sınıflandırılmış trafik, şüpheli/exploit listeleri.
 */
class PageHistoryController extends Controller
{
    /**
     * Tüm ham istekler (raw_request_logs) — Request Log Livewire component ile.
     */
    public function raw()
    {
        return view('admin.page-history.raw');
    }

    /**
     * CSV export (mevcut filtrelerle).
     */
    public function rawExport(Request $request)
    {
        $query = RawRequestLog::query()->orderBy('visited_at', 'desc');
        $this->applyRawFilters($query, $request);
        $logs = $query->limit(10000)->get();

        $filename = 'request-log-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tarih', 'IP', 'Metod', 'Path', 'Status', 'Süre (ms)', 'Asset', 'User-Agent']);
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->visited_at?->format('Y-m-d H:i:s'),
                    $log->ip_address,
                    $log->method,
                    $log->path,
                    $log->status_code,
                    $log->response_time_ms,
                    $log->is_asset_request ? 'Evet' : 'Hayır',
                    $log->user_agent,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Sınıflandırılmış trafik (classified_visit_logs).
     */
    public function classified(Request $request)
    {
        $query = ClassifiedVisitLog::query()->orderBy('visited_at', 'desc');
        $this->applyClassifiedFilters($query, $request);
        $logs = $query->paginate(50)->withQueryString();

        return view('admin.page-history.classified', compact('logs'));
    }

    /**
     * Şüpheli / exploit girişimleri.
     */
    public function suspicious(Request $request)
    {
        $query = ExploitSuspiciousEvent::query()->orderBy('created_at', 'desc');
        $this->applySuspiciousFilters($query, $request);
        $logs = $query->paginate(50)->withQueryString();

        return view('admin.page-history.suspicious', compact('logs'));
    }

    protected function applyRawFilters($query, Request $request): void
    {
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        if ($request->filled('path')) {
            $query->where('path', 'like', '%' . $request->path . '%');
        }
        if ($request->filled('user_agent')) {
            $query->where('user_agent', 'like', '%' . $request->user_agent . '%');
        }
        if ($request->filled('status_code')) {
            $query->where('status_code', $request->status_code);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('visited_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visited_at', '<=', $request->date_to);
        }
        if ($request->filled('asset_only')) {
            $query->where('is_asset_request', true);
        }
    }

    protected function applyClassifiedFilters($query, Request $request): void
    {
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }
        if ($request->filled('traffic_type')) {
            $query->where('traffic_type', $request->traffic_type);
        }
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('visited_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('visited_at', '<=', $request->date_to);
        }
    }

    protected function applySuspiciousFilters($query, Request $request): void
    {
        if ($request->filled('ip')) {
            $query->where('ip_address', $request->ip);
        }
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('url')) {
            $query->where('full_url', 'like', '%' . $request->url . '%');
        }
    }
}
