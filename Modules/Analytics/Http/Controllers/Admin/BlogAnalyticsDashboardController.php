<?php

namespace Modules\Analytics\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Analytics\Application\Actions\GetBlogAnalyticsOverviewDataAction;

class BlogAnalyticsDashboardController extends Controller
{
    public function overview(): View
    {
        return view('admin.analytics.overview');
    }

    public function overviewData(Request $request, GetBlogAnalyticsOverviewDataAction $getBlogAnalyticsOverviewDataAction): JsonResponse
    {
        $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
            'include_bots' => ['nullable', 'boolean'],
        ]);

        $from = $request->date('date_from')?->startOfDay() ?: now()->subDays(29)->startOfDay();
        $to = $request->date('date_to')?->endOfDay() ?: now()->endOfDay();
        $includeBots = (bool) $request->boolean('include_bots', false);
        $postId = $request->filled('post_id') ? (int) $request->integer('post_id') : null;

        return response()->json(
            $getBlogAnalyticsOverviewDataAction->execute($from, $to, $includeBots, $postId)
        );
    }
}
