<?php

namespace Modules\Analytics\Application\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Analytics\Models\AnalyticsEvent;
use Modules\Analytics\Models\AnalyticsPostView;
use Modules\Analytics\Models\AnalyticsSession;
use Modules\Analytics\Models\AnalyticsVisitor;

class BlogAnalyticsIngestService
{
    private const MIN_ACTIVE_SECONDS_FOR_ENGAGED = 30;

    private const MIN_SCROLL_PERCENT_FOR_ENGAGED = 50;

    private const MIN_ACTIVE_SECONDS_FOR_COMPLETED = 45;

    private const MIN_SCROLL_PERCENT_FOR_COMPLETED = 90;

    public function __construct(
        private readonly AnalyticsFingerprintService $fingerprintService,
        private readonly BlogTrafficClassifierService $trafficClassifierService,
    ) {}

    public function startView(Request $request): AnalyticsPostView
    {
        return DB::transaction(function () use ($request) {
            $resolved = $this->resolveVisitorAndSession($request);
            $now = now();

            $view = AnalyticsPostView::create([
                'view_uuid' => (string) ($request->string('view_uuid')->toString() ?: Str::uuid()),
                'session_ref_id' => $resolved['session']->id,
                'visitor_id' => $resolved['visitor']->id,
                'user_id' => $resolved['visitor']->user_id,
                'post_id' => (int) $request->integer('post_id'),
                'post_slug' => (string) $request->string('post_slug'),
                'view_started_at' => Carbon::parse((string) ($request->input('view_started_at') ?: $now->toISOString())),
                'max_scroll_percent' => max(0, min(100, (int) $request->integer('max_scroll_percent'))),
                'reading_progress_percent' => max(0, min(100, (int) $request->integer('reading_progress_percent'))),
                'is_bot' => $resolved['flags']['is_bot'],
                'is_suspicious' => $resolved['flags']['is_suspicious'],
            ]);

            $this->appendEvent($request, $resolved, $view, 'page_view_started');

            return $view;
        });
    }

    public function heartbeat(Request $request): ?AnalyticsPostView
    {
        return DB::transaction(function () use ($request) {
            $resolved = $this->resolveVisitorAndSession($request);
            $view = AnalyticsPostView::query()
                ->where('view_uuid', $request->string('view_uuid'))
                ->where('session_ref_id', $resolved['session']->id)
                ->first();

            if (! $view) {
                return null;
            }

            $activeDelta = max(0, min(30, (int) $request->integer('active_time_delta')));
            $progress = max(0, min(100, (int) $request->integer('reading_progress_percent')));
            $scroll = max(0, min(100, (int) $request->integer('max_scroll_percent')));

            $view->active_time_seconds += $activeDelta;
            $view->heartbeat_count += 1;
            $view->max_scroll_percent = max($view->max_scroll_percent, $scroll);
            $view->reading_progress_percent = max($view->reading_progress_percent, $progress);
            $view->first_scroll_at = $view->first_scroll_at ?: ($scroll > 0 ? now() : null);
            $view->reached_25_percent = $view->reached_25_percent || $scroll >= 25;
            $view->reached_50_percent = $view->reached_50_percent || $scroll >= 50;
            $view->reached_75_percent = $view->reached_75_percent || $scroll >= 75;
            $view->reached_90_percent = $view->reached_90_percent || $scroll >= 90;
            $view->is_bot = $resolved['flags']['is_bot'] || $view->is_bot;
            $view->is_suspicious = $resolved['flags']['is_suspicious'] || $view->is_suspicious;
            $view->save();

            $this->appendEvent($request, $resolved, $view, 'heartbeat');

            return $view;
        });
    }

    public function interaction(Request $request): ?AnalyticsPostView
    {
        return DB::transaction(function () use ($request) {
            $resolved = $this->resolveVisitorAndSession($request);
            $view = AnalyticsPostView::query()
                ->where('view_uuid', $request->string('view_uuid'))
                ->where('session_ref_id', $resolved['session']->id)
                ->first();

            if (! $view) {
                return null;
            }

            $interactionType = (string) $request->string('interaction_type');
            if ($interactionType === 'toc_click') {
                $view->toc_click_count++;
            } elseif ($interactionType === 'internal_link_click') {
                $view->internal_link_click_count++;
            } elseif ($interactionType === 'external_link_click') {
                $view->external_link_click_count++;
            } elseif ($interactionType === 'copy') {
                $view->copy_count++;
            } elseif ($interactionType === 'share_click') {
                $view->share_click_count++;
            }

            $view->is_bot = $resolved['flags']['is_bot'] || $view->is_bot;
            $view->is_suspicious = $resolved['flags']['is_suspicious'] || $view->is_suspicious;
            $view->save();

            $this->appendEvent($request, $resolved, $view, 'interaction');

            return $view;
        });
    }

    public function endView(Request $request): ?AnalyticsPostView
    {
        return DB::transaction(function () use ($request) {
            $resolved = $this->resolveVisitorAndSession($request);
            $view = AnalyticsPostView::query()
                ->where('view_uuid', $request->string('view_uuid'))
                ->where('session_ref_id', $resolved['session']->id)
                ->first();

            if (! $view) {
                return null;
            }

            $endedAt = Carbon::parse((string) ($request->input('view_ended_at') ?: now()->toISOString()));
            $totalTime = max(
                (int) $request->integer('total_time_seconds'),
                max(0, $view->view_started_at?->diffInSeconds($endedAt) ?? 0)
            );
            $activeTime = max($view->active_time_seconds, (int) $request->integer('active_time_seconds'));
            $progress = max($view->reading_progress_percent, min(100, (int) $request->integer('reading_progress_percent')));
            $scroll = max($view->max_scroll_percent, $progress);
            $activeTime = min($activeTime, $totalTime);
            $engagedRead = $activeTime >= self::MIN_ACTIVE_SECONDS_FOR_ENGAGED
                && $scroll >= self::MIN_SCROLL_PERCENT_FOR_ENGAGED;
            $completedRead = $activeTime >= self::MIN_ACTIVE_SECONDS_FOR_COMPLETED
                && $scroll >= self::MIN_SCROLL_PERCENT_FOR_COMPLETED;

            $view->view_ended_at = $endedAt;
            $view->total_time_seconds = $totalTime;
            $view->active_time_seconds = $activeTime;
            $view->reading_progress_percent = $progress;
            $view->max_scroll_percent = $scroll;
            $view->completed_read = $completedRead;
            $view->engaged_read = $engagedRead;
            $view->is_bot = $resolved['flags']['is_bot'] || $view->is_bot;
            $view->is_suspicious = $resolved['flags']['is_suspicious'] || $view->is_suspicious;
            $view->save();

            $this->appendEvent($request, $resolved, $view, 'page_view_ended');

            return $view;
        });
    }

    private function resolveVisitorAndSession(Request $request): array
    {
        $visitorUuid = (string) $request->string('visitor_uuid');
        $sessionId = (string) $request->string('session_id');
        $userAgent = (string) $request->userAgent();

        $flags = $this->trafficClassifierService->classify($userAgent, [
            'visitor_uuid' => $visitorUuid,
            'session_id' => $sessionId,
            'active_time_delta' => (int) $request->integer('active_time_delta'),
            'event_type' => (string) $request->input('event_type', ''),
            'max_scroll_percent' => (int) $request->integer('max_scroll_percent'),
        ]);

        $visitor = AnalyticsVisitor::query()->firstOrCreate(
            ['visitor_uuid' => $visitorUuid],
            [
                'user_id' => $request->user()?->id,
                'first_seen_at' => now(),
                'last_seen_at' => now(),
                'is_bot' => $flags['is_bot'],
                'is_suspicious' => $flags['is_suspicious'],
            ]
        );

        $visitor->forceFill([
            'user_id' => $request->user()?->id ?: $visitor->user_id,
            'last_seen_at' => now(),
            'is_bot' => $flags['is_bot'] || $visitor->is_bot,
            'is_suspicious' => $flags['is_suspicious'] || $visitor->is_suspicious,
        ])->save();

        $uaData = $this->fingerprintService->parseUserAgent($userAgent);
        $session = AnalyticsSession::query()->firstOrCreate(
            ['session_id' => $sessionId],
            [
                'visitor_id' => $visitor->id,
                'user_id' => $request->user()?->id,
                'started_at' => now(),
                'landing_url' => (string) $request->string('landing_url'),
                'referrer' => $request->input('referrer'),
                'utm_source' => $request->input('utm_source'),
                'utm_medium' => $request->input('utm_medium'),
                'utm_campaign' => $request->input('utm_campaign'),
                'utm_term' => $request->input('utm_term'),
                'utm_content' => $request->input('utm_content'),
                'ip_hash' => $this->fingerprintService->hashIp($request->ip()),
                'user_agent' => $userAgent,
                'device_type' => $uaData['device_type'],
                'browser' => $uaData['browser'],
                'os' => $uaData['os'],
                'country' => $request->header('CF-IPCountry'),
                'city' => $request->header('CF-IPCity'),
                'screen_width' => $request->integer('screen_width') ?: null,
                'screen_height' => $request->integer('screen_height') ?: null,
                'viewport_width' => $request->integer('viewport_width') ?: null,
                'viewport_height' => $request->integer('viewport_height') ?: null,
                'load_time_ms' => $request->integer('load_time_ms') ?: null,
                'dom_ready_ms' => $request->integer('dom_ready_ms') ?: null,
                'time_to_first_interaction_ms' => $request->integer('time_to_first_interaction_ms') ?: null,
                'is_bot' => $flags['is_bot'],
                'is_suspicious' => $flags['is_suspicious'],
            ]
        );

        $session->forceFill([
            'visitor_id' => $visitor->id,
            'user_id' => $request->user()?->id ?: $session->user_id,
            'referrer' => $session->referrer ?: $request->input('referrer'),
            'utm_source' => $session->utm_source ?: $request->input('utm_source'),
            'utm_medium' => $session->utm_medium ?: $request->input('utm_medium'),
            'utm_campaign' => $session->utm_campaign ?: $request->input('utm_campaign'),
            'utm_term' => $session->utm_term ?: $request->input('utm_term'),
            'utm_content' => $session->utm_content ?: $request->input('utm_content'),
            'is_bot' => $flags['is_bot'] || $session->is_bot,
            'is_suspicious' => $flags['is_suspicious'] || $session->is_suspicious,
        ])->save();

        return compact('visitor', 'session', 'flags');
    }

    private function appendEvent(
        Request $request,
        array $resolved,
        ?AnalyticsPostView $view,
        string $eventType
    ): AnalyticsEvent {
        $eventUuid = (string) ($request->input('event_uuid') ?: Str::uuid());

        return AnalyticsEvent::query()->firstOrCreate([
            'event_uuid' => $eventUuid,
        ], [
            'event_type' => $eventType,
            'occurred_at' => Carbon::parse((string) ($request->input('occurred_at') ?: now()->toISOString())),
            'received_at' => now(),
            'session_id' => (string) $request->string('session_id'),
            'visitor_id' => $resolved['visitor']->id,
            'user_id' => $resolved['visitor']->user_id,
            'post_id' => $view?->post_id ?? $request->integer('post_id'),
            'post_view_id' => $view?->id,
            'url' => (string) $request->string('url'),
            'referrer' => $request->input('referrer'),
            'payload_json' => Arr::except($request->all(), ['user_id']),
            'is_bot' => $resolved['flags']['is_bot'],
            'is_suspicious' => $resolved['flags']['is_suspicious'],
            'ip_hash' => $this->fingerprintService->hashIp($request->ip()),
            'user_agent' => (string) $request->userAgent(),
            'country' => $request->header('CF-IPCountry'),
            'city' => $request->header('CF-IPCity'),
        ]);
    }
}
