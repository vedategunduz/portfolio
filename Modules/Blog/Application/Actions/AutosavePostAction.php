<?php

namespace Modules\Blog\Application\Actions;

use Modules\Blog\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Application\Services\PostMediaService;
use Modules\Blog\Application\Services\PostTranslationService;

class AutosavePostAction
{
    public function __construct(
        private PostMediaService $postMediaService,
        private PostTranslationService $postTranslationService
    ) {
    }

    public function store(array $validated, int $userId, UploadedFile|array|null $uploadedGalleryImages = null, ?UploadedFile $coverImage = null): Post
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        $coverImagePath = null;

        if (! empty($validated['remove_cover_image'])) {
            $coverImagePath = null;
        } elseif ($coverImage) {
            $coverImagePath = $this->postMediaService->optimizeCoverImage($coverImage);
        }

        /** @var Post $post */
        $post = DB::transaction(function () use ($validated, $locales, $coverImagePath, $userId): Post {
            $createdPost = Post::create([
                'user_id' => $userId,
                'published' => false,
                'published_at' => null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'cover_image' => $coverImagePath,
            ]);

            $this->postTranslationService->upsertTranslations(
                $createdPost,
                Arr::get($validated, 'translations', []),
                $locales
            );

            return $createdPost;
        });

        $this->postMediaService->appendUploadedGalleryImages($post, $uploadedGalleryImages);

        return $post;
    }

    public function update(Post $post, array $validated, UploadedFile|array|null $uploadedGalleryImages = null, ?UploadedFile $coverImage = null): Post
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);
        $coverImagePath = $post->cover_image;

        if (! empty($validated['remove_cover_image'])) {
            $this->postMediaService->deleteStoredFile($coverImagePath);
            $coverImagePath = null;
        }

        if ($coverImage) {
            $this->postMediaService->deleteStoredFile($coverImagePath);
            $coverImagePath = $this->postMediaService->optimizeCoverImage($coverImage);
        }

        $removedGalleryPaths = collect($validated['remove_gallery_images'] ?? [])
            ->filter(fn ($path) => is_string($path) && trim($path) !== '')
            ->values();

        DB::transaction(function () use ($post, $validated, $locales, $coverImagePath, $removedGalleryPaths): void {
            $post->update([
                'is_featured' => (bool) ($validated['is_featured'] ?? $post->is_featured),
                'cover_image' => $coverImagePath,
            ]);

            if ($removedGalleryPaths->isNotEmpty()) {
                $imagesToRemove = $post->galleryImages()
                    ->whereIn('image_path', $removedGalleryPaths->all())
                    ->get();

                foreach ($imagesToRemove as $image) {
                    $this->postMediaService->deleteStoredFile($image->image_path);
                    $image->delete();
                }
            }

            $this->postTranslationService->upsertTranslations(
                $post,
                Arr::get($validated, 'translations', []),
                $locales
            );
        });

        $this->postMediaService->appendUploadedGalleryImages($post, $uploadedGalleryImages);

        return $post;
    }
}
