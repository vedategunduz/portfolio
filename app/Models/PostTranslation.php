<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostTranslation extends Model
{
    use HasFactory;

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
        static::creating(function (PostTranslation $translation) {
            // Auto-generate slug from title
            if (! $translation->slug) {
                $baseSlug = str($translation->title)->slug()->toString();
                $translation->slug = $baseSlug;

                $existing = static::where('locale', $translation->locale)
                    ->where('slug', $baseSlug)
                    ->where('post_id', '!=', $translation->post_id ?? 0)
                    ->exists();

                if ($existing) {
                    $counter = 1;
                    do {
                        $newSlug = "{$baseSlug}-{$counter}";
                        $existing = static::where('locale', $translation->locale)
                            ->where('slug', $newSlug)
                            ->where('post_id', '!=', $translation->post_id ?? 0)
                            ->exists();
                        $counter++;
                    } while ($existing);

                    $translation->slug = $newSlug;
                }
            }

            // Auto-generate meta_description from excerpt or content
            if (! $translation->meta_description && $translation->excerpt) {
                $translation->meta_description = substr(strip_tags($translation->excerpt), 0, 160);
            } elseif (! $translation->meta_description && $translation->content) {
                $translation->meta_description = substr(strip_tags($translation->content), 0, 160);
            }
        });

        static::updating(function (PostTranslation $translation) {
            // Auto-fill meta_description if cleared
            if (blank($translation->meta_description) && $translation->excerpt) {
                $translation->meta_description = substr(strip_tags($translation->excerpt), 0, 160);
            } elseif (blank($translation->meta_description) && $translation->content) {
                $translation->meta_description = substr(strip_tags($translation->content), 0, 160);
            }
        });
    }
}
