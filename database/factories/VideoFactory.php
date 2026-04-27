<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'slug' => Str::slug(fake()->unique()->sentence(3)),
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => fake()->imageUrl(640, 360),
            'sort_order' => fake()->numberBetween(1, 10),
            'status' => 'published',
            'published_at' => now()->subDay(),
        ];
    }
}
