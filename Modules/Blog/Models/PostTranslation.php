<?php

namespace Modules\Blog\Models;

use Database\Factories\PostTranslationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return PostTranslationFactory::new();
    }

    protected $fillable = [
        'post_id',
        'locale',
        'title',
        'slug',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected static function booted(): void
    {
        static::saving(function (PostTranslation $translation) {
            if (blank($translation->slug) && filled($translation->title)) {
                static::assignUniqueSlugFromTitle($translation);
            }
        });

        static::creating(function (PostTranslation $translation) {
            if (! $translation->meta_description && $translation->excerpt) {
                $translation->meta_description = substr(strip_tags($translation->excerpt), 0, 160);
            } elseif (! $translation->meta_description && $translation->content) {
                $translation->meta_description = substr(strip_tags($translation->content), 0, 160);
            }
        });

        static::updating(function (PostTranslation $translation) {
            if (blank($translation->meta_description) && $translation->excerpt) {
                $translation->meta_description = substr(strip_tags($translation->excerpt), 0, 160);
            } elseif (blank($translation->meta_description) && $translation->content) {
                $translation->meta_description = substr(strip_tags($translation->content), 0, 160);
            }
        });
    }

    private static function assignUniqueSlugFromTitle(PostTranslation $translation): void
    {
        $base = str($translation->title)->slug()->toString();
        if ($base === '') {
            $base = 'post';
        }

        $candidate = $base;
        $counter = 1;
        while (static::slugTakenByAnotherRow($translation, $candidate)) {
            $candidate = "{$base}-{$counter}";
            $counter++;
        }

        $translation->slug = $candidate;
    }

    private static function slugTakenByAnotherRow(PostTranslation $translation, string $slug): bool
    {
        return static::query()
            ->where('locale', $translation->locale)
            ->where('slug', $slug)
            ->when($translation->exists, fn ($query) => $query->whereKeyNot($translation->getKey()))
            ->exists();
    }
}
