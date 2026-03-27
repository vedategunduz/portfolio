<?php

namespace Modules\Blog\Models;

use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected static function newFactory(): Factory
    {
        return PostFactory::new();
    }

    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'published',
        'published_at',
        'is_featured',
        'cover_image',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(PostGalleryImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->orderBy('published_at', 'desc');
    }

    public function translation(?string $locale = null): ?PostTranslation
    {
        $locale = $locale ?? app()->getLocale();
        if ($this->relationLoaded('translations')) {
            $translations = $this->getRelation('translations');

            $translation = $translations->firstWhere('locale', $locale);
            if ($translation) {
                return $translation;
            }

            $fallback = config('app.fallback_locale');
            if ($fallback !== $locale) {
                $translation = $translations->firstWhere('locale', $fallback);
                if ($translation) {
                    return $translation;
                }
            }

            $dbTranslation = $this->translations()->where('locale', $locale)->first();
            if ($dbTranslation) {
                return $dbTranslation;
            }

            if ($fallback !== $locale) {
                $dbTranslation = $this->translations()->where('locale', $fallback)->first();
                if ($dbTranslation) {
                    return $dbTranslation;
                }
            }

            return $this->translations()->first();
        }

        $translation = $this->translations()->where('locale', $locale)->first();
        if ($translation) {
            return $translation;
        }

        $fallback = config('app.fallback_locale');
        if ($fallback !== $locale) {
            $translation = $this->translations()->where('locale', $fallback)->first();
            if ($translation) {
                return $translation;
            }
        }

        return $this->translations()->first();
    }

    public function getTranslatedTitleAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->title;
    }

    public function getTranslatedSlugAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->slug;
    }

    public function getTranslatedExcerptAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->excerpt;
    }

    public function getTranslatedContentAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->content;
    }

    public function getTranslatedMetaTitleAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->meta_title;
    }

    public function getTranslatedMetaDescriptionAttribute(?string $locale = null): ?string
    {
        return $this->translation($locale)?->meta_description;
    }

    public function getSeoTitleAttribute(?string $locale = null): ?string
    {
        return $this->getTranslatedMetaTitleAttribute($locale)
            ?? $this->getTranslatedTitleAttribute($locale);
    }

    public function getSeoDescriptionAttribute(?string $locale = null): ?string
    {
        return $this->getTranslatedMetaDescriptionAttribute($locale)
            ?? $this->getTranslatedExcerptAttribute($locale);
    }
}
