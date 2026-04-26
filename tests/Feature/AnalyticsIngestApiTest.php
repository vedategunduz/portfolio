<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Modules\Analytics\Http\Middleware\LogPageHistory;
use Modules\Blog\Models\Post;
use Tests\TestCase;

class AnalyticsIngestApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_ingest_route_uses_named_throttle_middleware(): void
    {
        $route = app('router')->getRoutes()->match(
            Request::create('/api/analytics/pageview/start', 'POST')
        );

        $this->assertContains('throttle:analytics-ingest', $route->gatherMiddleware());
    }

    public function test_analytics_ingest_rejects_mismatched_post_slug(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->for($author, 'user')->create([
            'published' => true,
            'published_at' => now()->subMinute(),
        ]);

        $post->translations()->create([
            'locale' => 'tr',
            'title' => 'Analytics Test',
            'slug' => 'analytics-test',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
        ]);

        $response = $this->withoutMiddleware(LogPageHistory::class)
            ->postJson('/api/analytics/pageview/start', [
                'visitor_uuid' => (string) str()->uuid(),
                'session_id' => 'sess-analytics-test',
                'view_uuid' => (string) str()->uuid(),
                'post_id' => $post->id,
                'post_slug' => 'wrong-slug',
                'url' => 'https://example.com/blog/wrong-slug',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['post_slug']);
    }
}
