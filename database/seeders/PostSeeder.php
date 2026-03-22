<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first() ?? User::first();

        for ($i = 0; $i < 5; $i++) {
            $post = Post::factory()
                ->for($admin, 'user')
                ->create();

            \App\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'tr'])
                ->create();

            \App\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'en'])
                ->create();
        }

        for ($i = 0; $i < 2; $i++) {
            $post = Post::factory()
                ->draft()
                ->for($admin, 'user')
                ->create();

            \App\Models\PostTranslation::factory()
                ->for($post)
                ->state(['locale' => 'tr'])
                ->create();
        }
    }
}
