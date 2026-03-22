<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageOptimizationService
{
    public function __construct(private ImageManager $imageManager) {}

    /**
     * Optimize cover image: resize to 1200x630 and compress
     */
    public function optimizeCoverImage(UploadedFile $file, string $disk = 'public'): string
    {
        return $this->processImage($file, 'blog/covers', 1200, 630, $disk);
    }

    /**
     * Optimize gallery image: resize to 800x600 and compress
     */
    public function optimizeGalleryImage(UploadedFile $file, string $disk = 'public'): string
    {
        return $this->processImage($file, 'blog/gallery', 800, 600, $disk);
    }

    /**
     * Process and optimize image
     *
     * @param UploadedFile $file
     * @param string $path Storage path
     * @param int $width Target width
     * @param int $height Target height
     * @param string $disk Storage disk
     * @return string Path to optimized image
     */
    private function processImage(
        UploadedFile $file,
        string $path,
        int $width,
        int $height,
        string $disk = 'public'
    ): string {
        try {
            // Read and resize image
            $image = $this->imageManager->read($file->getStream());

            // Resize with aspect ratio preservation
            $image->scaleDown(width: $width, height: $height);

            // Encode with quality optimization
            $encoded = $image->toJpeg(quality: 85);

            // Generate filename
            $filename = $this->generateFilename($file);
            $fullPath = "{$path}/{$filename}";

            // Store optimized image
            Storage::disk($disk)->put($fullPath, $encoded);

            return $fullPath;
        } catch (\Exception $e) {
            // Fallback: store original file if optimization fails
            Log::warning("Image optimization failed: {$e->getMessage()}", ['file' => $file->getClientOriginalName()]);

            return $file->store($path, $disk);
        }
    }

    /**
     * Generate unique filename with timestamp
     */
    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = $this->sanitizeFilename($name);

        return "{$name}-" . time() . ".{$extension}";
    }

    /**
     * Sanitize filename
     */
    private function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-z0-9-]/i', '-', $filename) ?? 'image';
    }
}
