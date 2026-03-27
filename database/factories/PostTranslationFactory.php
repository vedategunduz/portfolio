<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\PostTranslation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Blog\Models\PostTranslation>
 */
class PostTranslationFactory extends Factory
{
    protected $model = PostTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
                    'locale' => config('app.locale', 'tr'),
                    'title' => $this->faker->sentence(6),
                    'excerpt' => $this->faker->paragraph(2),
                    'content' => $this->faker->paragraphs(5, true),
                    'meta_title' => null,
                    'meta_description' => null,
        ];
    }

    public function asLocales(array $locales): static
    {
        return $this->sequence(...array_map(fn ($locale) => [
            'locale' => $locale,
        ], $locales));
    }
}
