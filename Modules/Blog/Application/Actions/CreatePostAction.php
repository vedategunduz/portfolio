<?php

namespace Modules\Blog\Application\Actions;

use Modules\Blog\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Application\Services\PostMediaService;
use Modules\Blog\Application\Services\PostTranslationService;

class CreatePostAction
{
    public function __construct(
        private PostMediaService $postMediaService,
        private PostTranslationService $postTranslationService
    ) {}

    public function execute(array $validated, int $userId, UploadedFile|array|null $uploadedGalleryImages = null, ?UploadedFile $coverImage = null): Post
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        $translationsPayload = Arr::get($validated, 'translations', []);

        if (empty($validated['published'])) {
            $validated['published_at'] = null;
        }

        $coverImagePath = $coverImage
            ? $this->postMediaService->optimizeCoverImage($coverImage)
            : null;

        /** @var Post $post */
        $post = DB::transaction(function () use ($validated, $translationsPayload, $locales, $coverImagePath, $userId): Post {
            $createdPost = Post::create([
                'user_id' => $userId,
                'published' => (bool) ($validated['published'] ?? false),
                'published_at' => ! empty($validated['published_at']) ? $validated['published_at'] : null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'cover_image' => $coverImagePath,
            ]);

            $this->postTranslationService->upsertTranslations($createdPost, $translationsPayload, $locales);

            return $createdPost;
        });

        $this->postMediaService->appendUploadedGalleryImages($post, $uploadedGalleryImages);

        return $post;
    }
}
