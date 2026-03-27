<?php

namespace Modules\Blog\Application\Services;

use Modules\Blog\Models\Post;
use App\Services\ImageOptimizationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostMediaService
{
    public function __construct(private ImageOptimizationService $imageOptimizer)
    {
    }

    public function appendUploadedGalleryImages(Post $post, UploadedFile|array|null $uploadedFiles): void
    {
        $files = $uploadedFiles instanceof UploadedFile
            ? [$uploadedFiles]
            : (is_array($uploadedFiles) ? $uploadedFiles : []);

        if (count($files) === 0) {
            return;
        }

        $nextOrder = (int) ($post->galleryImages()->max('sort_order') ?? -1) + 1;

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $storedPath = $this->imageOptimizer->optimizeGalleryImage($file);

            $post->galleryImages()->create([
                'image_path' => $storedPath,
                'sort_order' => $nextOrder,
            ]);

            $nextOrder++;
        }
    }

    public function optimizeCoverImage(UploadedFile $coverImage): string
    {
        return $this->imageOptimizer->optimizeCoverImage($coverImage);
    }

    public function deleteStoredFile(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
