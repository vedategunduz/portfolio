<?php

namespace Modules\Blog\Application\Actions;

use Modules\Blog\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Application\Services\PostMediaService;
use Modules\Blog\Application\Services\PostTranslationService;

class UpdatePostAction
{
    public function __construct(
        private PostMediaService $postMediaService,
        private PostTranslationService $postTranslationService
    ) {}

    public function execute(Post $post, array $validated, UploadedFile|array|null $uploadedGalleryImages = null, ?UploadedFile $coverImage = null): Post
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);
        $translationsPayload = Arr::get($validated, 'translations', []);
        $coverImagePath = $post->cover_image;

        if (empty($validated['published'])) {
            $validated['published_at'] = null;
        }

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

        DB::transaction(function () use ($post, $validated, $translationsPayload, $locales, $coverImagePath, $removedGalleryPaths): void {
            $post->update([
                'published' => (bool) ($validated['published'] ?? false),
                'published_at' => ! empty($validated['published_at']) ? $validated['published_at'] : null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
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

            $this->postTranslationService->upsertTranslations($post, $translationsPayload, $locales, true);
        });

        $this->postMediaService->appendUploadedGalleryImages($post, $uploadedGalleryImages);

        return $post;
    }
}
