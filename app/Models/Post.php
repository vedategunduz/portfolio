<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
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
        return $query->where('published', true)->whereNotNull('published_at');
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->orderBy('published_at', 'desc');
    }

    public function translation(?string $locale = null): ?PostTranslation
    {
        $locale = $locale ?? app()->getLocale();

        $translations = $this->relationLoaded('translations')
            ? $this->getRelation('translations')
            : $this->translations()->get();

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

        return $translations->first();
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
