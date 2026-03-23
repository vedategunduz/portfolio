<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Throwable;

class ImageOptimizationService
{
    private const COVER_WIDTH = 1200;

    private const COVER_HEIGHT = 630;

    private const GALLERY_MAX_WIDTH = 800;

    private const GALLERY_MAX_HEIGHT = 600;

    private const WEBP_QUALITY = 82;

    private const JPEG_FALLBACK_QUALITY = 85;

    public function __construct(private ImageManager $imageManager) {}

    /**
     * Blog cover: tam 1200×630 (merkez crop), Open Graph / paylaşım görselleri için.
     */
    public function optimizeCoverImage(UploadedFile $file, string $disk = 'public'): string
    {
        return $this->processImage(
            $file,
            'blog/covers',
            self::COVER_WIDTH,
            self::COVER_HEIGHT,
            $disk,
            exactCoverDimensions: true,
        );
    }

    /**
     * Galeri: en-boy oranı korunur, 800×600 kutusuna sığacak şekilde küçültülür.
     */
    public function optimizeGalleryImage(UploadedFile $file, string $disk = 'public'): string
    {
        return $this->processImage(
            $file,
            'blog/gallery',
            self::GALLERY_MAX_WIDTH,
            self::GALLERY_MAX_HEIGHT,
            $disk,
            exactCoverDimensions: false,
        );
    }

    /**
     * @param  bool  $exactCoverDimensions  true: sabit boyut + crop (cover), false: oran koruyarak scaleDown
     *
     * Başarısız olursa orijinal dosya kaydedilir (admin yüklemesi kesilmez). Sıkı mod için burada
     * hatayı yeniden fırlatabilirsiniz.
     */
    private function processImage(
        UploadedFile $file,
        string $path,
        int $width,
        int $height,
        string $disk,
        bool $exactCoverDimensions,
    ): string {
        try {
            $image = $this->readUploadedFile($file);
            $this->applyResize($image, $width, $height, $exactCoverDimensions);

            [$encoded, $extension] = $this->encodeWithWebpPreference($image);

            $fullPath = "{$path}/".$this->generateFilename($file, $extension);

            Storage::disk($disk)->put($fullPath, (string) $encoded);

            return $fullPath;
        } catch (Throwable $e) {
            Log::warning('Image optimization failed', [
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
                'mode' => $exactCoverDimensions ? 'cover_fixed' : 'scale_down_max',
            ]);

            return $file->store($path, $disk);
        }
    }

    private function readUploadedFile(UploadedFile $file): ImageInterface
    {
        $pathname = $file->getPathname();

        if ($pathname !== '' && is_file($pathname) && is_readable($pathname)) {
            return $this->imageManager->read($pathname);
        }

        return $this->imageManager->read($file->getStream());
    }

    private function applyResize(ImageInterface $image, int $width, int $height, bool $exactCoverDimensions): void
    {
        if ($exactCoverDimensions) {
            $image->cover($width, $height, position: 'center');

            return;
        }

        $image->scaleDown(width: $width, height: $height);
    }

    /**
     * @return array{0: EncodedImageInterface, 1: string}
     */
    private function encodeWithWebpPreference(ImageInterface $image): array
    {
        try {
            return [$image->toWebp(self::WEBP_QUALITY), 'webp'];
        } catch (Throwable) {
            return [$image->toJpeg(quality: self::JPEG_FALLBACK_QUALITY), 'jpg'];
        }
    }

    private function generateFilename(UploadedFile $file, string $extension): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = $this->sanitizeFilename($name);

        return "{$name}-".Str::uuid().".{$extension}";
    }

    private function sanitizeFilename(string $filename): string
    {
        $name = preg_replace('/[^a-z0-9-]/i', '-', $filename) ?? 'image';
        $name = preg_replace('/-+/', '-', $name);
        $name = trim($name, '-');

        return $name !== '' ? $name : 'image';
    }
}
