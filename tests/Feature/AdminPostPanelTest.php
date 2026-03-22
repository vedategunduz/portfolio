<?php

namespace Tests\Feature;

use App\Http\Middleware\LogPageHistory;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_autosave_store_creates_draft_post_with_partial_translation(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware(LogPageHistory::class)
            ->postJson(route('admin.posts.autosave.store'), [
                'is_featured' => true,
                'translations' => [
                    'tr' => [
                        'title' => 'Autosave Baslik',
                        'excerpt' => 'Kisa ozet',
                        'content' => '',
                    ],
                ],
            ]);

        $response->assertOk()->assertJsonPath('ok', true);

        $postId = (int) $response->json('post_id');
        $this->assertNotSame(0, $postId);

        $this->assertDatabaseHas('posts', [
            'id' => $postId,
            'user_id' => $user->id,
            'published' => 0,
            'is_featured' => 1,
        ]);

        $this->assertDatabaseHas('post_translations', [
            'post_id' => $postId,
            'locale' => 'tr',
            'title' => 'Autosave Baslik',
        ]);
    }

    public function test_autosave_update_updates_existing_translation(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user, 'user')->draft()->create();

        $post->translations()->create([
            'locale' => 'tr',
            'title' => 'Ilk Baslik',
            'slug' => 'ilk-baslik',
            'excerpt' => 'Ilk ozet',
            'content' => 'Ilk icerik',
        ]);

        $response = $this->actingAs($user)
            ->withoutMiddleware(LogPageHistory::class)
            ->putJson(route('admin.posts.autosave.update', $post), [
                'translations' => [
                    'tr' => [
                        'title' => 'Guncel Baslik',
                        'excerpt' => 'Guncel ozet',
                        'content' => 'Guncel icerik',
                    ],
                ],
            ]);

        $response->assertOk()->assertJsonPath('ok', true);

        $this->assertDatabaseHas('post_translations', [
            'post_id' => $post->id,
            'locale' => 'tr',
            'title' => 'Guncel Baslik',
            'excerpt' => 'Guncel ozet',
            'content' => 'Guncel icerik',
        ]);
    }

    public function test_create_form_contains_quill_editor_hooks(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('admin.posts.create'));

        $response->assertOk();
        $response->assertSee('data-editor-content', false);
        $response->assertSee('data-quill-target', false);
        $response->assertSee('window.initAllEditors', false);
    }

    public function test_index_applies_search_and_status_filters(): void
    {
        $user = User::factory()->create();

        $published = Post::factory()->for($user, 'user')->create([
            'published' => true,
            'published_at' => now(),
        ]);

        $draft = Post::factory()->for($user, 'user')->draft()->create();

        $published->translations()->create([
            'locale' => 'tr',
            'title' => 'Needle Baslik',
            'slug' => 'needle-baslik',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $draft->translations()->create([
            'locale' => 'tr',
            'title' => 'Haystack Baslik',
            'slug' => 'haystack-baslik',
            'excerpt' => 'Ozet',
            'content' => 'Icerik',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['app_locale' => 'tr'])
            ->withoutMiddleware(LogPageHistory::class)
            ->get(route('admin.posts.index', ['search' => 'Needle', 'status' => 'published']));

        $response->assertOk();
        $response->assertSee('Needle Baslik');
        $response->assertDontSee('Haystack Baslik');
    }
}
