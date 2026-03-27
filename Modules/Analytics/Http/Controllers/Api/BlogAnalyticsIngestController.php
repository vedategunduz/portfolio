<?php

namespace Modules\Analytics\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Analytics\Application\Services\BlogAnalyticsIngestService;

class BlogAnalyticsIngestController extends Controller
{
    public function __construct(private readonly BlogAnalyticsIngestService $ingestService) {}

    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'event_uuid' => ['nullable', 'uuid'],
            'occurred_at' => ['nullable', 'date'],
            'visitor_uuid' => ['required', 'uuid'],
            'session_id' => ['required', 'string', 'max:100'],
            'view_uuid' => ['required', 'uuid'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'post_slug' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
            'landing_url' => ['nullable', 'url', 'max:2048'],
            'referrer' => ['nullable', 'url', 'max:2048'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_term' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
            'screen_width' => ['nullable', 'integer', 'min:0'],
            'screen_height' => ['nullable', 'integer', 'min:0'],
            'viewport_width' => ['nullable', 'integer', 'min:0'],
            'viewport_height' => ['nullable', 'integer', 'min:0'],
            'load_time_ms' => ['nullable', 'integer', 'min:0'],
            'dom_ready_ms' => ['nullable', 'integer', 'min:0'],
            'time_to_first_interaction_ms' => ['nullable', 'integer', 'min:0'],
            'view_started_at' => ['nullable', 'date'],
            'max_scroll_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'reading_progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $view = $this->ingestService->startView($request);

        return response()->json([
            'ok' => true,
            'view_uuid' => $view->view_uuid,
            'post_view_id' => $view->id,
        ]);
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $request->validate([
            'event_uuid' => ['nullable', 'uuid'],
            'occurred_at' => ['nullable', 'date'],
            'visitor_uuid' => ['required', 'uuid'],
            'session_id' => ['required', 'string', 'max:100'],
            'view_uuid' => ['required', 'uuid'],
            'url' => ['required', 'url', 'max:2048'],
            'referrer' => ['nullable', 'url', 'max:2048'],
            'active_time_delta' => ['nullable', 'integer', 'min:0', 'max:60'],
            'max_scroll_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'reading_progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $view = $this->ingestService->heartbeat($request);
        if (! $view) {
            return response()->json(['ok' => false, 'message' => 'View not found'], 404);
        }

        return response()->json(['ok' => true]);
    }

    public function interaction(Request $request): JsonResponse
    {
        $request->validate([
            'event_uuid' => ['nullable', 'uuid'],
            'occurred_at' => ['nullable', 'date'],
            'visitor_uuid' => ['required', 'uuid'],
            'session_id' => ['required', 'string', 'max:100'],
            'view_uuid' => ['required', 'uuid'],
            'url' => ['required', 'url', 'max:2048'],
            'referrer' => ['nullable', 'url', 'max:2048'],
            'interaction_type' => [
                'required',
                'string',
                Rule::in(['toc_click', 'internal_link_click', 'external_link_click', 'copy', 'share_click']),
            ],
        ]);

        $view = $this->ingestService->interaction($request);
        if (! $view) {
            return response()->json(['ok' => false, 'message' => 'View not found'], 404);
        }

        return response()->json(['ok' => true]);
    }

    public function end(Request $request): JsonResponse
    {
        $request->validate([
            'event_uuid' => ['nullable', 'uuid'],
            'occurred_at' => ['nullable', 'date'],
            'visitor_uuid' => ['required', 'uuid'],
            'session_id' => ['required', 'string', 'max:100'],
            'view_uuid' => ['required', 'uuid'],
            'url' => ['required', 'url', 'max:2048'],
            'referrer' => ['nullable', 'url', 'max:2048'],
            'view_ended_at' => ['nullable', 'date'],
            'total_time_seconds' => ['nullable', 'integer', 'min:0'],
            'active_time_seconds' => ['nullable', 'integer', 'min:0'],
            'reading_progress_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $view = $this->ingestService->endView($request);
        if (! $view) {
            return response()->json(['ok' => false, 'message' => 'View not found'], 404);
        }

        return response()->json([
            'ok' => true,
            'completed_read' => $view->completed_read,
            'engaged_read' => $view->engaged_read,
        ]);
    }
}
