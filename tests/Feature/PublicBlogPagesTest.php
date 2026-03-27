<?php

namespace Tests\Feature;

use Modules\Analytics\Http\Middleware\LogPageHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\Blog\Models\Post;

class PublicBlogPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_locale_page_renders_latest_blog_snippet(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'user')->create([
            'published' => true,
            'published_at' => now(),
        ]);

        $post->translations()->create([
            'locale' => 'tr',
            'title' => 'Ana Sayfa Blog Yazisi',
            'slug' => 'ana-sayfa-blog-yazisi',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $response = $this->withSession(['app_locale' => 'tr'])
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('home', ['locale' => 'tr']));

        $response->assertOk();
        $response->assertSee('Ana Sayfa Blog Yazisi');
    }

    public function test_blog_index_shows_only_published_posts(): void
    {
        $user = User::factory()->create();

        $published = Post::factory()->for($user, 'user')->create([
            'published' => true,
            'published_at' => now(),
        ]);

        $draft = Post::factory()->for($user, 'user')->draft()->create();

        $published->translations()->create([
            'locale' => 'tr',
            'title' => 'Yayinda Baslik',
            'slug' => 'yayinda-baslik',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $draft->translations()->create([
            'locale' => 'tr',
            'title' => 'Taslak Baslik',
            'slug' => 'taslak-baslik',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $response = $this->withSession(['app_locale' => 'tr'])
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Yayinda Baslik');
        $response->assertDontSee('Taslak Baslik');
    }

    public function test_blog_show_renders_post_by_locale_slug(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'user')->create([
            'published' => true,
            'published_at' => now(),
        ]);

        $post->translations()->create([
            'locale' => 'tr',
            'title' => 'Detay Baslik',
            'slug' => 'detay-baslik',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $post->translations()->create([
            'locale' => 'en',
            'title' => 'Detail Title',
            'slug' => 'detail-title',
            'excerpt' => 'Summary',
            'content' => 'Content',
        ]);

        $response = $this->withSession(['app_locale' => 'tr'])
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('blog.show', ['slug' => 'detay-baslik']));

        $response->assertOk();
        $response->assertSee('Detay Baslik');
        $response->assertDontSee('Detail Title');
    }
}
