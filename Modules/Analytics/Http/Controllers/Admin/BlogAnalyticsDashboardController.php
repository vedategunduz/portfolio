<?php

namespace Modules\Analytics\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BlogAnalyticsDashboardController extends Controller
{
    public function overview(): View
    {
        return view('admin.analytics.overview');
    }

    public function overviewData(Request $request): JsonResponse
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
            'include_bots' => ['nullable', 'boolean'],
        ]);

        $from = $request->date('date_from') ?: now()->subDays(29)->startOfDay();
        $to = $request->date('date_to') ?: now()->endOfDay();
        $includeBots = (bool) $request->boolean('include_bots', false);
        $postId = $request->filled('post_id') ? (int) $request->integer('post_id') : null;

        $baseViews = DB::table('analytics_post_views as pv')
            ->join('analytics_sessions as s', 's.id', '=', 'pv.session_ref_id')
            ->whereBetween('pv.view_started_at', [$from, $to]);

        if ($postId) {
            $baseViews->where('pv.post_id', $postId);
        }

        if (! $includeBots) {
            $baseViews->where('pv.is_bot', false);
        }

        $overview = (clone $baseViews)->selectRaw('
            COUNT(*) as total_views,
            COUNT(DISTINCT pv.visitor_id) as unique_visitors,
            COALESCE(AVG(pv.active_time_seconds), 0) as avg_active_time_seconds,
            COALESCE(AVG(pv.total_time_seconds), 0) as avg_total_time_seconds,
            COALESCE(AVG(pv.max_scroll_percent), 0) as avg_scroll_percent,
            SUM(CASE WHEN pv.completed_read = 1 THEN 1 ELSE 0 END) as completed_reads,
            SUM(CASE WHEN pv.engaged_read = 1 THEN 1 ELSE 0 END) as engaged_reads,
            SUM(CASE WHEN pv.active_time_seconds < 10 THEN 1 ELSE 0 END) as bounce_views
        ')->first();
        $rawBotViews = (int) DB::table('analytics_post_views as pv')
            ->whereBetween('pv.view_started_at', [$from, $to])
            ->where('pv.is_bot', true)
            ->when($postId, fn ($q) => $q->where('pv.post_id', $postId))
            ->count();

        $totalViews = (int) ($overview->total_views ?? 0);
        $completed = (int) ($overview->completed_reads ?? 0);
        $engaged = (int) ($overview->engaged_reads ?? 0);
        $bounce = (int) ($overview->bounce_views ?? 0);

        $returning = (int) (clone $baseViews)
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('analytics_post_views as prev')
                    ->whereColumn('prev.visitor_id', 'pv.visitor_id')
                    ->whereColumn('prev.post_id', 'pv.post_id')
                    ->whereColumn('prev.view_started_at', '<', 'pv.view_started_at');
            })
            ->distinct('pv.visitor_id')
            ->count('pv.visitor_id');

        $trendRows = (clone $baseViews)
            ->selectRaw('
                DATE(pv.view_started_at) as date,
                COUNT(*) as views,
                SUM(CASE WHEN pv.engaged_read = 1 THEN 1 ELSE 0 END) as engaged,
                SUM(CASE WHEN pv.completed_read = 1 THEN 1 ELSE 0 END) as completed
            ')
            ->groupByRaw('DATE(pv.view_started_at)')
            ->orderBy('date')
            ->get();

        $sourceBase = (clone $baseViews)
            ->selectRaw("
                pv.visitor_id as visitor_id,
                pv.engaged_read as engaged_read,
                COALESCE(NULLIF(s.utm_source, ''), 'direct') as utm_source,
                COALESCE(NULLIF(s.utm_medium, ''), 'none') as utm_medium,
                COALESCE(NULLIF(s.utm_campaign, ''), 'none') as utm_campaign,
                COALESCE(
                    NULLIF(SUBSTRING_INDEX(REPLACE(REPLACE(s.referrer, 'https://', ''), 'http://', ''), '/', 1), ''),
                    'direct'
                ) as referrer_domain
            ");

        $sourceAllRows = DB::query()
            ->fromSub($sourceBase, 'src')
            ->selectRaw('
                src.utm_source,
                src.utm_medium,
                src.utm_campaign,
                src.referrer_domain,
                COUNT(*) as views,
                COUNT(DISTINCT src.visitor_id) as unique_visitors,
                SUM(CASE WHEN src.engaged_read = 1 THEN 1 ELSE 0 END) as engaged_reads
            ')
            ->groupBy('src.utm_source', 'src.utm_medium', 'src.utm_campaign', 'src.referrer_domain')
            ->orderByDesc('views')
            ->get();

        $deviceBase = (clone $baseViews)
            ->selectRaw("
                pv.visitor_id as visitor_id,
                COALESCE(NULLIF(s.device_type, ''), 'unknown') as device_type,
                COALESCE(NULLIF(s.browser, ''), 'unknown') as browser,
                COALESCE(NULLIF(s.os, ''), 'unknown') as os
            ");

        $deviceAllRows = DB::query()
            ->fromSub($deviceBase, 'dev')
            ->selectRaw('
                dev.device_type,
                dev.browser,
                dev.os,
                COUNT(*) as views,
                COUNT(DISTINCT dev.visitor_id) as unique_visitors
            ')
            ->groupBy('dev.device_type', 'dev.browser', 'dev.os')
            ->orderByDesc('views')
            ->get();

        $sourceRows = $this->buildReconciledTopRows($sourceAllRows, $totalViews);
        $deviceRows = $this->buildReconciledTopRows($deviceAllRows, $totalViews, false);

        return response()->json([
            'ok' => true,
            'date_from' => $from->toDateString(),
            'date_to' => $to->toDateString(),
            'totals' => [
                'total_views' => $totalViews,
                'total_views_raw' => $totalViews,
                'bot_views' => $rawBotViews,
                'unique_visitors' => (int) ($overview->unique_visitors ?? 0),
                'avg_active_time_seconds' => (int) round((float) ($overview->avg_active_time_seconds ?? 0)),
                'avg_total_time_seconds' => (int) round((float) ($overview->avg_total_time_seconds ?? 0)),
                'avg_scroll_percent' => (int) round((float) ($overview->avg_scroll_percent ?? 0)),
                'completed_read_rate' => $totalViews > 0 ? round(($completed / $totalViews) * 100, 2) : 0,
                'engaged_read_rate' => $totalViews > 0 ? round(($engaged / $totalViews) * 100, 2) : 0,
                'bounce_rate' => $totalViews > 0 ? round(($bounce / $totalViews) * 100, 2) : 0,
                'returning_visitor_rate' => $totalViews > 0 ? round(($returning / $totalViews) * 100, 2) : 0,
            ],
            'trend' => $trendRows,
            'sources' => $sourceRows->map(fn ($row) => [
                'label' => (string) ($row->label ?? trim(implode(' / ', array_filter([
                    $row->utm_source ?: null,
                    $row->utm_medium ?: null,
                    $row->utm_campaign ?: null,
                    $row->referrer_domain ?: null,
                ]))) ?: 'direct'),
                'views' => (int) $row->views,
                'unique_visitors' => (int) $row->unique_visitors,
                'engaged_reads' => (int) ($row->engaged_reads ?? 0),
            ]),
            'devices' => $deviceRows->map(fn ($row) => [
                'label' => (string) ($row->label ?? trim(implode(' / ', array_filter([
                    $row->device_type ?: null,
                    $row->browser ?: null,
                    $row->os ?: null,
                ]))) ?: 'unknown'),
                'views' => (int) $row->views,
                'unique_visitors' => (int) $row->unique_visitors,
            ]),
            'reconciliation' => [
                'kpi_total_views' => $totalViews,
                'sources_total_views' => (int) $sourceRows->sum('views'),
                'devices_total_views' => (int) $deviceRows->sum('views'),
            ],
        ]);
    }

    private function buildReconciledTopRows($rows, int $expectedTotalViews, bool $hasEngagedReads = true)
    {
        $topRows = $rows->take(8)->values();
        $shownViews = (int) $topRows->sum('views');
        $otherViews = max(0, $expectedTotalViews - $shownViews);

        if ($otherViews <= 0) {
            return $topRows;
        }

        $payload = (object) [
            'label' => 'other',
            'views' => $otherViews,
            'unique_visitors' => 0,
        ];

        if ($hasEngagedReads) {
            $payload->engaged_reads = 0;
        }

        return $topRows->push($payload);
    }
}
