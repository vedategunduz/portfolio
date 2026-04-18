<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Analytics\Http\Middleware\LogPageHistory;
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
}
