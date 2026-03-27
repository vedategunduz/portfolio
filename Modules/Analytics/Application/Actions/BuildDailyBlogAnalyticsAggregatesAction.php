<?php

namespace Modules\Analytics\Application\Actions;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Analytics\Models\AnalyticsDeviceDailyAggregate;
use Modules\Analytics\Models\AnalyticsPostDailyAggregate;
use Modules\Analytics\Models\AnalyticsSourceDailyAggregate;

class BuildDailyBlogAnalyticsAggregatesAction
{
    public function handle(CarbonInterface|string|null $date = null): array
    {
        $targetDate = $date instanceof CarbonInterface ? $date : Carbon::parse($date ?: now()->toDateString());
        $dayStart = $targetDate->copy()->startOfDay();
        $dayEnd = $targetDate->copy()->endOfDay();

        $returningByPost = DB::table('analytics_post_views as apv')
            ->whereBetween('apv.view_started_at', [$dayStart, $dayEnd])
            ->whereExists(function ($query) use ($dayStart) {
                $query->selectRaw('1')
                    ->from('analytics_post_views as prev')
                    ->whereColumn('prev.visitor_id', 'apv.visitor_id')
                    ->whereColumn('prev.post_id', 'apv.post_id')
                    ->where('prev.view_started_at', '<', $dayStart);
            })
            ->groupBy('apv.post_id')
            ->selectRaw('apv.post_id, COUNT(DISTINCT apv.visitor_id) as returning_visitors')
            ->pluck('returning_visitors', 'post_id');

        $postRows = DB::table('analytics_post_views')
            ->whereBetween('view_started_at', [$dayStart, $dayEnd])
            ->selectRaw('
                DATE(view_started_at) as date,
                post_id,
                COUNT(*) as total_views,
                COUNT(DISTINCT visitor_id) as unique_visitors,
                COALESCE(AVG(total_time_seconds), 0) as avg_total_time_seconds,
                COALESCE(AVG(active_time_seconds), 0) as avg_active_time_seconds,
                COALESCE(AVG(max_scroll_percent), 0) as avg_scroll_percent,
                SUM(CASE WHEN completed_read = 1 THEN 1 ELSE 0 END) as completed_read_count,
                SUM(CASE WHEN engaged_read = 1 THEN 1 ELSE 0 END) as engaged_read_count,
                SUM(CASE WHEN active_time_seconds < 10 THEN 1 ELSE 0 END) as bounce_count,
                SUM(CASE WHEN is_bot = 1 THEN 1 ELSE 0 END) as bot_views,
                SUM(CASE WHEN is_suspicious = 1 THEN 1 ELSE 0 END) as suspicious_views
            ')
            ->groupByRaw('DATE(view_started_at), post_id')
            ->get();

        foreach ($postRows as $row) {
            AnalyticsPostDailyAggregate::query()->updateOrCreate(
                ['date' => $row->date, 'post_id' => $row->post_id],
                [
                    'total_views' => (int) $row->total_views,
                    'unique_visitors' => (int) $row->unique_visitors,
                    'avg_total_time_seconds' => (int) round((float) $row->avg_total_time_seconds),
                    'avg_active_time_seconds' => (int) round((float) $row->avg_active_time_seconds),
                    'avg_scroll_percent' => (int) round((float) $row->avg_scroll_percent),
                    'completed_read_count' => (int) $row->completed_read_count,
                    'engaged_read_count' => (int) $row->engaged_read_count,
                    'bounce_count' => (int) $row->bounce_count,
                    'returning_visitor_count' => (int) ($returningByPost[$row->post_id] ?? 0),
                    'bot_views' => (int) $row->bot_views,
                    'suspicious_views' => (int) $row->suspicious_views,
                ]
            );
        }

        $sourceRows = DB::table('analytics_post_views as pv')
            ->join('analytics_sessions as s', 's.id', '=', 'pv.session_ref_id')
            ->whereBetween('pv.view_started_at', [$dayStart, $dayEnd])
            ->selectRaw('
                DATE(pv.view_started_at) as date,
                s.utm_source as utm_source,
                s.utm_medium as utm_medium,
                s.utm_campaign as utm_campaign,
                SUBSTRING_INDEX(REPLACE(REPLACE(s.referrer, "https://", ""), "http://", ""), "/", 1) as referrer_domain,
                COUNT(*) as views,
                COUNT(DISTINCT pv.visitor_id) as unique_visitors,
                SUM(CASE WHEN pv.engaged_read = 1 THEN 1 ELSE 0 END) as engaged_reads
            ')
            ->groupByRaw('DATE(pv.view_started_at), s.utm_source, s.utm_medium, s.utm_campaign, referrer_domain')
            ->get();

        foreach ($sourceRows as $row) {
            AnalyticsSourceDailyAggregate::query()->updateOrCreate(
                [
                    'date' => $row->date,
                    'utm_source' => $row->utm_source,
                    'utm_medium' => $row->utm_medium,
                    'utm_campaign' => $row->utm_campaign,
                    'referrer_domain' => $row->referrer_domain,
                ],
                [
                    'views' => (int) $row->views,
                    'unique_visitors' => (int) $row->unique_visitors,
                    'engaged_reads' => (int) $row->engaged_reads,
                ]
            );
        }

        $deviceRows = DB::table('analytics_post_views as pv')
            ->join('analytics_sessions as s', 's.id', '=', 'pv.session_ref_id')
            ->whereBetween('pv.view_started_at', [$dayStart, $dayEnd])
            ->selectRaw('
                DATE(pv.view_started_at) as date,
                s.device_type as device_type,
                s.browser as browser,
                s.os as os,
                COUNT(*) as views,
                COUNT(DISTINCT pv.visitor_id) as unique_visitors
            ')
            ->groupByRaw('DATE(pv.view_started_at), s.device_type, s.browser, s.os')
            ->get();

        foreach ($deviceRows as $row) {
            AnalyticsDeviceDailyAggregate::query()->updateOrCreate(
                [
                    'date' => $row->date,
                    'device_type' => $row->device_type,
                    'browser' => $row->browser,
                    'os' => $row->os,
                ],
                [
                    'views' => (int) $row->views,
                    'unique_visitors' => (int) $row->unique_visitors,
                ]
            );
        }

        return [
            'date' => $targetDate->toDateString(),
            'post_rows' => $postRows->count(),
            'source_rows' => $sourceRows->count(),
            'device_rows' => $deviceRows->count(),
        ];
    }
}
