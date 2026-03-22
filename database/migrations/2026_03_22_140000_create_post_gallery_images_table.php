<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('image_path', 2048);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['post_id', 'sort_order']);
        });

        if (Schema::hasColumn('posts', 'gallery_images')) {
            $posts = DB::table('posts')->select(['id', 'gallery_images'])->get();

            foreach ($posts as $post) {
                if (! $post->gallery_images) {
                    continue;
                }

                $images = json_decode((string) $post->gallery_images, true);

                if (! is_array($images)) {
                    continue;
                }

                $rows = [];
                foreach (array_values($images) as $index => $imagePath) {
                    if (! is_string($imagePath) || trim($imagePath) === '') {
                        continue;
                    }

                    $rows[] = [
                        'post_id' => $post->id,
                        'image_path' => trim($imagePath),
                        'sort_order' => $index,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (count($rows) > 0) {
                    DB::table('post_gallery_images')->insert($rows);
                }
            }

            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('gallery_images');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'gallery_images')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->json('gallery_images')->nullable()->after('cover_image');
            });
        }

        $posts = DB::table('posts')->select('id')->get();
        foreach ($posts as $post) {
            $images = DB::table('post_gallery_images')
                ->where('post_id', $post->id)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->pluck('image_path')
                ->filter()
                ->values()
                ->all();

            DB::table('posts')->where('id', $post->id)->update([
                'gallery_images' => count($images) > 0 ? json_encode($images, JSON_UNESCAPED_UNICODE) : null,
            ]);
        }

        Schema::dropIfExists('post_gallery_images');
    }
};
