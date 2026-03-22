<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTranslationAutomationTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_is_auto_generated_and_deconflicted_per_locale(): void
    {
        $postA = Post::factory()->create();
        $postB = Post::factory()->create();

        $first = PostTranslation::create([
            'post_id' => $postA->id,
            'locale' => 'tr',
            'title' => 'Merhaba Dunya',
            'excerpt' => 'Kisa ozet',
            'content' => 'Icerik',
        ]);

        $second = PostTranslation::create([
            'post_id' => $postB->id,
            'locale' => 'tr',
            'title' => 'Merhaba Dunya',
            'excerpt' => 'Baska ozet',
            'content' => 'Baska icerik',
        ]);

        $this->assertSame('merhaba-dunya', $first->slug);
        $this->assertSame('merhaba-dunya-1', $second->slug);
    }

    public function test_meta_description_is_auto_generated_on_create_and_update(): void
    {
        $post = Post::factory()->create();

        $excerpt = '<p>' . str_repeat('A', 200) . '</p>';

        $translation = PostTranslation::create([
            'post_id' => $post->id,
            'locale' => 'tr',
            'title' => 'Meta test',
            'excerpt' => $excerpt,
            'content' => 'Icerik',
            'meta_description' => null,
        ]);

        $this->assertSame(substr(str_repeat('A', 200), 0, 160), $translation->meta_description);

        $translation->update([
            'excerpt' => '<p>' . str_repeat('B', 200) . '</p>',
            'meta_description' => '',
        ]);

        $translation->refresh();

        $this->assertSame(substr(str_repeat('B', 200), 0, 160), $translation->meta_description);
    }
}
