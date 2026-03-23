<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Services\ImageOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private ImageOptimizationService $imageOptimizer) {}

    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $search = $request->query('search', '');
        $status = $request->string('status')->toString();

        $query = Post::query()
            ->with(['user', 'translations', 'galleryImages']);

        // Search by title or slug
        if ($search !== '') {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status === 'published') {
            $query->where('published', true)->whereNotNull('published_at');
        } elseif ($status === 'draft') {
            $query->where(function ($q) {
                $q->where('published', false)->orWhereNull('published_at');
            });
        } elseif ($status === 'featured') {
            $query->where('is_featured', true);
        }

        $posts = $query->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'locale'));
    }

    public function create(): View
    {
        $locales = config('app.supported_locales', ['tr', 'en']);

        return view('admin.posts.create', compact('locales'));
    }

    public function autosaveStore(Request $request): JsonResponse
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        $validated = $this->validateAutosaveRequest($request, $locales);
        $coverImagePath = null;

        if (! empty($validated['remove_cover_image'])) {
            $coverImagePath = null;
        } elseif ($request->hasFile('cover_image')) {
            $coverImagePath = $this->imageOptimizer->optimizeCoverImage($request->file('cover_image'));
        }

        /** @var Post $post */
        $post = DB::transaction(function () use ($request, $validated, $locales, $coverImagePath): Post {
            $createdPost = Post::create([
                'user_id' => (int) $request->user()->id,
                'published' => false,
                'published_at' => null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'cover_image' => $coverImagePath,
            ]);

            $this->upsertTranslations($createdPost, Arr::get($validated, 'translations', []), $locales);

            return $createdPost;
        });

        $this->appendUploadedGalleryImages($post, $request->file('gallery_images', []));

        return response()->json([
            'ok' => true,
            'post_id' => $post->id,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    public function autosaveUpdate(Request $request, Post $post): JsonResponse
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);
        $validated = $this->validateAutosaveRequest($request, $locales, $post);
        $coverImagePath = $post->cover_image;

        if (! empty($validated['remove_cover_image'])) {
            $this->deleteStoredFile($coverImagePath);
            $coverImagePath = null;
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteStoredFile($coverImagePath);
            $coverImagePath = $this->imageOptimizer->optimizeCoverImage($request->file('cover_image'));
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
                    $this->deleteStoredFile($image->image_path);
                    $image->delete();
                }
            }

            $this->upsertTranslations($post, Arr::get($validated, 'translations', []), $locales);
        });

        $this->appendUploadedGalleryImages($post, $request->file('gallery_images', []));

        return response()->json([
            'ok' => true,
            'post_id' => $post->id,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        $validated = $this->validateRequest($request, $locales);

        $translationsPayload = Arr::get($validated, 'translations', []);
        $coverImagePath = $request->hasFile('cover_image')
            ? $this->imageOptimizer->optimizeCoverImage($request->file('cover_image'))
            : null;

        /** @var Post $post */
        $post = DB::transaction(function () use ($request, $validated, $translationsPayload, $locales, $coverImagePath): Post {
            $createdPost = Post::create([
                'user_id' => (int) $request->user()->id,
                'published' => (bool) ($validated['published'] ?? false),
                'published_at' => !empty($validated['published_at']) ? $validated['published_at'] : null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'cover_image' => $coverImagePath,
            ]);

            foreach ($locales as $locale) {
                $payload = $translationsPayload[$locale] ?? [];

                if (! $this->hasAnyTranslationValue($payload)) {
                    continue;
                }

                $createdPost->translations()->create([
                    'locale' => $locale,
                    'title' => $payload['title'] ?? '',
                    'slug' => ($payload['slug'] ?? null) ?: null,
                    'excerpt' => $payload['excerpt'] ?? '',
                    'content' => $payload['content'] ?? '',
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                ]);
            }

            return $createdPost;
        });

        $this->appendUploadedGalleryImages($post, $request->file('gallery_images', []));

        return redirect()->route('admin.posts.index')->with('success', __('messages.blog_admin.created'));
    }

    public function edit(Post $post): View
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);

        return view('admin.posts.edit', compact('post', 'locales'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);
        $validated = $this->validateRequest($request, $locales, $post);

        $translationsPayload = Arr::get($validated, 'translations', []);
        $coverImagePath = $post->cover_image;

        if (! empty($validated['remove_cover_image'])) {
            $this->deleteStoredFile($coverImagePath);
            $coverImagePath = null;
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteStoredFile($coverImagePath);
            $coverImagePath = $this->imageOptimizer->optimizeCoverImage($request->file('cover_image'));
        }

        $removedGalleryPaths = collect($validated['remove_gallery_images'] ?? [])
            ->filter(fn ($path) => is_string($path) && trim($path) !== '')
            ->values();

        DB::transaction(function () use ($post, $validated, $translationsPayload, $locales, $coverImagePath, $removedGalleryPaths): void {
            $post->update([
                'published' => (bool) ($validated['published'] ?? false),
                'published_at' => !empty($validated['published_at']) ? $validated['published_at'] : null,
                'is_featured' => (bool) ($validated['is_featured'] ?? false),
                'cover_image' => $coverImagePath,
            ]);

            if ($removedGalleryPaths->isNotEmpty()) {
                $imagesToRemove = $post->galleryImages()
                    ->whereIn('image_path', $removedGalleryPaths->all())
                    ->get();

                foreach ($imagesToRemove as $image) {
                    $this->deleteStoredFile($image->image_path);
                    $image->delete();
                }
            }

            foreach ($locales as $locale) {
                $payload = $translationsPayload[$locale] ?? [];
                $translation = $post->translations->firstWhere('locale', $locale);

                if (! $this->hasAnyTranslationValue($payload)) {
                    if ($translation) {
                        $translation->delete();
                    }
                    continue;
                }

                if ($translation) {
                    $slug = array_key_exists('slug', $payload)
                        ? (($payload['slug'] ?? null) ?: null)
                        : ($translation->slug ?? null);

                    $translation->update([
                        'title' => $payload['title'] ?? '',
                        'slug' => $slug,
                        'excerpt' => $payload['excerpt'] ?? '',
                        'content' => $payload['content'] ?? '',
                        'meta_title' => $payload['meta_title'] ?? null,
                        'meta_description' => $payload['meta_description'] ?? null,
                    ]);
                } else {
                    $post->translations()->create([
                        'locale' => $locale,
                        'title' => $payload['title'] ?? '',
                        'slug' => ($payload['slug'] ?? null) ?: null,
                        'excerpt' => $payload['excerpt'] ?? '',
                        'content' => $payload['content'] ?? '',
                        'meta_title' => $payload['meta_title'] ?? null,
                        'meta_description' => $payload['meta_description'] ?? null,
                    ]);
                }
            }
        });

        $this->appendUploadedGalleryImages($post, $request->file('gallery_images', []));

        return redirect()->route('admin.posts.index')->with('success', __('messages.blog_admin.updated'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->load('galleryImages');

        $this->deleteStoredFile($post->cover_image);
        foreach ($post->galleryImages as $image) {
            $this->deleteStoredFile($image->image_path);
        }

        $post->delete();

        return back()->with('success', __('messages.blog_admin.deleted'));
    }

    /**
     * @param array<int, string> $locales
     */
    private function validateRequest(Request $request, array $locales, ?Post $post = null): array
    {
        $rules = [
            'published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'cover_image_existing' => ['nullable', 'string', 'max:2048'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'gallery_images_existing' => ['nullable', 'array'],
            'gallery_images_existing.*' => ['nullable', 'string', 'max:2048'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['nullable', 'string', 'max:2048'],
            'translations' => ['required', 'array'],
        ];

        foreach ($locales as $locale) {
            /** @var PostTranslation|null $existing */
            $existing = $post?->translations->firstWhere('locale', $locale);

            $slugRule = Rule::unique('post_translations', 'slug')
                ->where(fn ($query) => $query->where('locale', $locale));

            if ($existing) {
                $slugRule = $slugRule->ignore($existing->id);
            }

            $rules["translations.$locale.title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$locale.slug"] = ['nullable', 'string', 'max:255', $slugRule];
            $rules["translations.$locale.excerpt"] = ['nullable', 'string'];
            $rules["translations.$locale.content"] = ['nullable', 'string'];
            $rules["translations.$locale.meta_title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$locale.meta_description"] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        if (empty($validated['published'])) {
            $validated['published_at'] = null;
        }

        $defaultLocale = config('app.fallback_locale', 'tr');
        $defaultTranslation = Arr::get($validated, "translations.$defaultLocale", []);

        if (! $this->hasRequiredTranslationValue($defaultTranslation)) {
            throw ValidationException::withMessages([
                "translations.$defaultLocale.title" => [__('messages.blog_admin.default_locale_required', ['locale' => strtoupper($defaultLocale)])],
            ]);
        }

        return $validated;
    }

    /**
     * @param array<int, string> $locales
     */
    private function validateAutosaveRequest(Request $request, array $locales, ?Post $post = null): array
    {
        $rules = [
            'is_featured' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'max:5120'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['nullable', 'string', 'max:2048'],
            'translations' => ['nullable', 'array'],
        ];

        foreach ($locales as $locale) {
            /** @var PostTranslation|null $existing */
            $existing = $post?->translations->firstWhere('locale', $locale);

            $slugRule = Rule::unique('post_translations', 'slug')
                ->where(fn ($query) => $query->where('locale', $locale));

            if ($existing) {
                $slugRule = $slugRule->ignore($existing->id);
            }

            $rules["translations.$locale.title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$locale.slug"] = ['nullable', 'string', 'max:255', $slugRule];
            $rules["translations.$locale.excerpt"] = ['nullable', 'string'];
            $rules["translations.$locale.content"] = ['nullable', 'string'];
            $rules["translations.$locale.meta_title"] = ['nullable', 'string', 'max:255'];
            $rules["translations.$locale.meta_description"] = ['nullable', 'string', 'max:255'];
        }

        return $request->validate($rules);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function hasAnyTranslationValue(array $payload): bool
    {
        return filled($payload['title'] ?? null)
            || filled($payload['excerpt'] ?? null)
            || filled($payload['content'] ?? null)
            || filled($payload['meta_title'] ?? null)
            || filled($payload['meta_description'] ?? null);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function hasRequiredTranslationValue(array $payload): bool
    {
        return filled($payload['title'] ?? null) && filled($payload['content'] ?? null);
    }

    /**
     * @param array<string, mixed> $translationsPayload
     * @param array<int, string> $locales
     */
    private function upsertTranslations(Post $post, array $translationsPayload, array $locales): void
    {
        $post->loadMissing('translations');

        foreach ($locales as $locale) {
            $payload = is_array($translationsPayload[$locale] ?? null)
                ? $translationsPayload[$locale]
                : [];

            if (! $this->hasAnyTranslationValue($payload)) {
                continue;
            }

            $translation = $post->translations->firstWhere('locale', $locale);

            $slug = array_key_exists('slug', $payload)
                ? (($payload['slug'] ?? null) ?: null)
                : ($translation?->slug ?? null);

            $attributes = [
                'title' => $payload['title'] ?? '',
                'slug' => $slug,
                'excerpt' => $payload['excerpt'] ?? '',
                'content' => $payload['content'] ?? '',
                'meta_title' => $payload['meta_title'] ?? null,
                'meta_description' => $payload['meta_description'] ?? null,
            ];

            if ($translation) {
                $translation->update($attributes);
            } else {
                $post->translations()->create(['locale' => $locale] + $attributes);
            }
        }

        $post->unsetRelation('translations');
    }

    /**
     * @param array<int, UploadedFile>|UploadedFile|null $uploadedFiles
     */
    private function appendUploadedGalleryImages(Post $post, UploadedFile|array|null $uploadedFiles): void
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

    private function deleteStoredFile(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
