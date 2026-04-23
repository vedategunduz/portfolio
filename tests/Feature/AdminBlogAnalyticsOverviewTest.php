<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Analytics\Http\Middleware\LogPageHistory;
use Modules\Analytics\Models\AnalyticsPostView;
use Modules\Analytics\Models\AnalyticsSession;
use Modules\Analytics\Models\AnalyticsVisitor;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class AdminBlogAnalyticsOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_overview_page_renders_for_authenticated_admin(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('admin.analytics.overview'));

        $response->assertOk();
        $response->assertSee('blog-analytics-overview', false);
    }

    public function test_analytics_overview_data_excludes_bots_by_default_and_returns_expected_metrics(): void
    {
        $user = User::factory()->create();
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'user')->create([
            'published' => true,
            'published_at' => now()->subDay(),
        ]);

        $post->translations()->create([
            'locale' => 'tr',
            'title' => 'Analytics Test',
            'slug' => 'analytics-test',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
        ]);

        $visitor = AnalyticsVisitor::create([
            'visitor_uuid' => (string) str()->uuid(),
            'first_seen_at' => now()->subDay(),
            'last_seen_at' => now(),
            'is_bot' => false,
            'is_suspicious' => false,
        ]);

        $session = AnalyticsSession::create([
            'session_id' => 'sess-human',
            'visitor_id' => $visitor->id,
            'started_at' => now()->subHour(),
            'referrer' => 'https://google.com/search?q=portfolio',
            'utm_source' => 'google',
            'utm_medium' => 'organic',
            'utm_campaign' => 'spring',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'Windows',
            'is_bot' => false,
            'is_suspicious' => false,
        ]);

        AnalyticsPostView::create([
            'view_uuid' => (string) str()->uuid(),
            'session_ref_id' => $session->id,
            'visitor_id' => $visitor->id,
            'post_id' => $post->id,
            'post_slug' => 'analytics-test',
            'view_started_at' => now()->subMinutes(50),
            'view_ended_at' => now()->subMinutes(49),
            'total_time_seconds' => 80,
            'active_time_seconds' => 50,
            'max_scroll_percent' => 95,
            'reading_progress_percent' => 95,
            'completed_read' => true,
            'engaged_read' => true,
            'is_bot' => false,
            'is_suspicious' => false,
        ]);

        AnalyticsPostView::create([
            'view_uuid' => (string) str()->uuid(),
            'session_ref_id' => $session->id,
            'visitor_id' => $visitor->id,
            'post_id' => $post->id,
            'post_slug' => 'analytics-test',
            'view_started_at' => now()->subMinutes(20),
            'view_ended_at' => now()->subMinutes(19),
            'total_time_seconds' => 8,
            'active_time_seconds' => 5,
            'max_scroll_percent' => 20,
            'reading_progress_percent' => 20,
            'completed_read' => false,
            'engaged_read' => false,
            'is_bot' => false,
            'is_suspicious' => false,
        ]);

        $botVisitor = AnalyticsVisitor::create([
            'visitor_uuid' => (string) str()->uuid(),
            'first_seen_at' => now()->subDay(),
            'last_seen_at' => now(),
            'is_bot' => true,
            'is_suspicious' => false,
        ]);

        $botSession = AnalyticsSession::create([
            'session_id' => 'sess-bot',
            'visitor_id' => $botVisitor->id,
            'started_at' => now()->subHour(),
            'referrer' => 'https://crawler.example/bot',
            'device_type' => 'bot',
            'browser' => 'Crawler',
            'os' => 'Linux',
            'is_bot' => true,
            'is_suspicious' => false,
        ]);

        AnalyticsPostView::create([
            'view_uuid' => (string) str()->uuid(),
            'session_ref_id' => $botSession->id,
            'visitor_id' => $botVisitor->id,
            'post_id' => $post->id,
            'post_slug' => 'analytics-test',
            'view_started_at' => now()->subMinutes(10),
            'view_ended_at' => now()->subMinutes(9),
            'total_time_seconds' => 12,
            'active_time_seconds' => 0,
            'max_scroll_percent' => 0,
            'reading_progress_percent' => 0,
            'completed_read' => false,
            'engaged_read' => false,
            'is_bot' => true,
            'is_suspicious' => false,
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware(LogPageHistory::class)
            ->getJson(route('admin.analytics.overview.data', [
                'date_from' => now()->subDays(2)->toDateString(),
                'date_to' => now()->toDateString(),
                'post_id' => $post->id,
            ]));

        $response->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('totals.total_views', 2)
            ->assertJsonPath('totals.bot_views', 1)
            ->assertJsonPath('totals.unique_visitors', 1)
            ->assertJsonPath('totals.avg_active_time_seconds', 28)
            ->assertJsonPath('totals.avg_total_time_seconds', 44)
            ->assertJsonPath('totals.avg_scroll_percent', 58)
            ->assertJsonPath('totals.completed_read_rate', 50)
            ->assertJsonPath('totals.engaged_read_rate', 50)
            ->assertJsonPath('totals.bounce_rate', 50)
            ->assertJsonPath('totals.returning_visitor_rate', 50)
            ->assertJsonPath('reconciliation.kpi_total_views', 2)
            ->assertJsonPath('reconciliation.sources_total_views', 2)
            ->assertJsonPath('reconciliation.devices_total_views', 2)
            ->assertJsonFragment([
                'label' => 'google / organic / spring / google.com',
                'views' => 2,
                'unique_visitors' => 1,
                'engaged_reads' => 1,
            ])
            ->assertJsonFragment([
                'label' => 'desktop / Chrome / Windows',
                'views' => 2,
                'unique_visitors' => 1,
            ]);
    }
}
