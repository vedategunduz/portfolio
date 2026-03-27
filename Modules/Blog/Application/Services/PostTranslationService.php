<?php

namespace Modules\Blog\Application\Services;

use Modules\Blog\Models\Post;

class PostTranslationService
{
    /**
     * @param array<string, mixed> $translationsPayload
     * @param array<int, string> $locales
     */
    public function upsertTranslations(Post $post, array $translationsPayload, array $locales, bool $deleteWhenEmpty = false): void
    {
        $post->loadMissing('translations');

        foreach ($locales as $locale) {
            $payload = is_array($translationsPayload[$locale] ?? null)
                ? $translationsPayload[$locale]
                : [];

            if (! $this->hasAnyTranslationValue($payload)) {
                if ($deleteWhenEmpty) {
                    $translation = $post->translations->firstWhere('locale', $locale);
                    if ($translation) {
                        $translation->delete();
                    }
                }

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
}
