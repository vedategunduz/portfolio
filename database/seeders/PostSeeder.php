<?php

namespace Database\Seeders;

use Modules\Blog\Models\Post;
use Modules\Blog\Models\PostTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first() ?? User::first();

        if (! $admin) {
            return;
        }

        if (app()->environment('local')) {
            $this->seedLocalDemoPost($admin);

            return;
        }

        $this->seedFactoryPosts($admin);
    }

    private function seedLocalDemoPost(User $admin): void
    {
        $demoSlugTr = 'bu-projeyi-neden-yaptim';
        $demoSlugEn = 'why-i-built-this-project';

        if (PostTranslation::query()->where('locale', 'tr')->where('slug', $demoSlugTr)->exists()) {
            $this->command?->info('Local demo blog yazısı zaten var (slug: '.$demoSlugTr.').');

            return;
        }

        $post = Post::create([
            'user_id' => $admin->id,
            'published' => true,
            'published_at' => now()->subDay(),
            'is_featured' => true,
            'cover_image' => null,
        ]);

        PostTranslation::create([
            'post_id' => $post->id,
            'locale' => 'tr',
            'title' => 'Bu projeyi neden yaptım?',
            'slug' => $demoSlugTr,
            'excerpt' => 'Kişisel portfolyo ve blog altyapısını Laravel ile kurma nedenlerim ve hedeflerim.',
            'content' => <<<'HTML'
<p>Bu siteyi hem iş örneklerimi göstermek hem de teknik notları paylaşmak için geliştirdim.</p>
<p>Laravel, çok dilli içerik, yönetim paneli ve SEO dostu yapı önceliklerim arasındaydı. Taslak otomatik kayıt, görsel optimizasyonu ve sitemap gibi parçalar günlük kullanımda zaman kazandırıyor.</p>
<p>İleride etiketler, okuma süresi veya analitik özeti gibi alanlar eklenebilir; şimdilik tek yazar için net bir editör deneyimi hedefledim.</p>
HTML,
            'meta_title' => 'Bu projeyi neden yaptım? | Blog',
            'meta_description' => 'Portfolyo ve blog projesinin amacı: Laravel ile çok dilli içerik, admin deneyimi ve sürdürülebilir yapı.',
        ]);

        PostTranslation::create([
            'post_id' => $post->id,
            'locale' => 'en',
            'title' => 'Why I built this project',
            'slug' => $demoSlugEn,
            'excerpt' => 'Why I chose Laravel for my portfolio and blog, and what I optimized for day-to-day writing.',
            'content' => <<<'HTML'
<p>I built this site to showcase my work and share technical notes in a structured way.</p>
<p>Priorities included Laravel, multilingual content, an admin workflow, and SEO-friendly URLs. Draft autosave, image optimization, and sitemaps save time every week.</p>
<p>Tags, reading time, or analytics summaries could come next; for now the focus is a clear editor experience for a single author.</p>
HTML,
            'meta_title' => 'Why I built this project | Blog',
            'meta_description' => 'Goals behind the portfolio/blog stack: Laravel, multilingual posts, admin UX, and maintainability.',
        ]);

        $this->command?->info('Local demo blog yazısı eklendi: /blog/'.$demoSlugTr.' (TR) · /blog/'.$demoSlugEn.' (EN)');
    }

    private function seedFactoryPosts(User $admin): void
    {
        for ($i = 0; $i < 5; $i++) {
            $post = Post::factory()
                ->for($admin, 'user')
                ->create();

            \Modules\Blog\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'tr'])
                ->create();

            \Modules\Blog\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'en'])
                ->create();
        }

        for ($i = 0; $i < 2; $i++) {
            $post = Post::factory()
                ->draft()
                ->for($admin, 'user')
                ->create();

            \Modules\Blog\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'tr'])
                ->create();
        }
    }
}
