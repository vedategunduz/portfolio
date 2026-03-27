<?php

namespace Modules\Blog\Application\ViewModels;

use Modules\Blog\Models\Post;
use Modules\Blog\Models\PostTranslation;

class AdminPostFormInsightsViewModel
{
    /**
     * @param  array<int, string>  $locales
     * @return array{
     *     adminTranslationWarnings: array<int, string>,
     *     slugSuffixLocales: array<int, string>,
     *     publishChecklistNotes: array<int, string>
     * }
     */
    public function build(?Post $post, array $locales): array
    {
        if (! $post) {
            return [
                'adminTranslationWarnings' => [],
                'slugSuffixLocales' => [],
                'publishChecklistNotes' => [],
            ];
        }

        $adminTranslationWarnings = [];
        $slugSuffixLocales = [];
        $publishChecklistNotes = [];

        $requiredLocale = config('app.fallback_locale', 'en');

        foreach ($locales as $loc) {
            $t = $post->translations->firstWhere('locale', $loc);
            $slug = $t?->slug ?? '';
            if ($slug !== '' && preg_match('/-\d+$/', $slug)) {
                $slugSuffixLocales[] = $loc;
            }
        }

        $requiredTranslation = $post->translations->firstWhere('locale', $requiredLocale);
        $requiredReady = $this->translationIsComplete($requiredTranslation);

        if (! $post->published && $requiredReady) {
            foreach ($locales as $loc) {
                if ($loc === $requiredLocale) {
                    continue;
                }
                $t = $post->translations->firstWhere('locale', $loc);
                if ($this->translationHasStarted($t) && ! $this->translationIsComplete($t)) {
                    $adminTranslationWarnings[] = __('messages.blog_admin.warning_translation_partial', [
                        'locale' => strtoupper($loc),
                    ]);
                }
            }
        }

        if ($post->published) {
            foreach ($locales as $loc) {
                $t = $post->translations->firstWhere('locale', $loc);
                if (! $this->translationIsComplete($t)) {
                    $adminTranslationWarnings[] = __('messages.blog_admin.warning_published_incomplete', [
                        'locale' => strtoupper($loc),
                    ]);
                }
            }
        }

        if ($post->published && ! $post->cover_image) {
            $publishChecklistNotes[] = __('messages.blog_admin.checklist_no_cover');
        }

        if ($post->published) {
            foreach ($locales as $loc) {
                $t = $post->translations->firstWhere('locale', $loc);
                if ($this->translationIsComplete($t) && ! filled(trim(strip_tags($t->excerpt ?? '')))) {
                    $publishChecklistNotes[] = __('messages.blog_admin.checklist_no_excerpt', ['locale' => strtoupper($loc)]);
                }
            }
        }

        return [
            'adminTranslationWarnings' => array_values(array_unique($adminTranslationWarnings)),
            'slugSuffixLocales' => array_values(array_unique($slugSuffixLocales)),
            'publishChecklistNotes' => array_values(array_unique($publishChecklistNotes)),
        ];
    }

    private function translationIsComplete(?PostTranslation $translation): bool
    {
        if (! $translation) {
            return false;
        }

        return filled($translation->title)
            && filled(trim(strip_tags($translation->content ?? '')));
    }

    private function translationHasStarted(?PostTranslation $translation): bool
    {
        if (! $translation) {
            return false;
        }

        return filled($translation->title)
            || filled(trim(strip_tags($translation->content ?? '')))
            || filled(trim(strip_tags($translation->excerpt ?? '')));
    }
}
