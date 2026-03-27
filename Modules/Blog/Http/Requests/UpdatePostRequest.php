<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\PostTranslation;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        /** @var Post|null $post */
        $post = $this->route('post');
        $post?->loadMissing('translations');

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
            $existing = $post?->translations?->firstWhere('locale', $locale);

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

        return $rules;
    }

    protected function passedValidation(): void
    {
        $defaultLocale = config('app.fallback_locale', 'tr');
        $defaultTranslation = Arr::get($this->validated(), "translations.$defaultLocale", []);

        if (! $this->hasRequiredTranslationValue($defaultTranslation)) {
            throw ValidationException::withMessages([
                "translations.$defaultLocale.title" => [__('messages.blog_admin.default_locale_required', ['locale' => strtoupper($defaultLocale)])],
            ]);
        }
    }

    private function hasRequiredTranslationValue(array $payload): bool
    {
        return filled($payload['title'] ?? null) && filled($payload['content'] ?? null);
    }
}
