<?php

namespace Tests\Feature;

use App\Http\Middleware\LogPageHistory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_locale_home_and_blog_urls(): void
    {
        $response = $this->withoutMiddleware(LogPageHistory::class)
            ->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee(url('/tr'), false);
        $response->assertSee(url('/en'), false);
        $response->assertSee(url('/blog'), false);
    }

    public function test_sitemap_contains_only_published_post_slugs_for_available_locales(): void
    {
        $user = User::factory()->create();

        $published = Post::factory()->for($user, 'user')->create([
            'published' => true,
            'published_at' => now(),
        ]);

        $draft = Post::factory()->for($user, 'user')->draft()->create();

        $published->translations()->create([
            'locale' => 'tr',
            'title' => 'Sitemap Tr Yazi',
            'slug' => 'sitemap-tr-yazi',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $published->translations()->create([
            'locale' => 'en',
            'title' => 'Sitemap En Post',
            'slug' => 'sitemap-en-post',
            'excerpt' => 'Summary',
            'content' => 'Content',
        ]);

        $draft->translations()->create([
            'locale' => 'tr',
            'title' => 'Taslak Sitemap',
            'slug' => 'taslak-sitemap',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $response = $this->withoutMiddleware(LogPageHistory::class)
            ->get(route('sitemap'));

        $response->assertOk();
        $response->assertSee(url('/blog/sitemap-tr-yazi'), false);
        $response->assertSee(url('/blog/sitemap-en-post'), false);
        $response->assertDontSee(url('/blog/taslak-sitemap'), false);
    }
}

