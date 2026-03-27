<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Blog\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'published' => true,
            'published_at' => $this->faker->dateTimeThisYear(),
            'is_featured' => false,
            'cover_image' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'published' => false,
                'published_at' => null,
            ];
        });
    }

    public function featured(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_featured' => true,
            ];
        });
    }
}
