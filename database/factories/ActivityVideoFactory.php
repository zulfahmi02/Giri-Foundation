<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityVideo>
 */
class ActivityVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'title' => $this->faker->sentence(4),
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'sort_order' => 1,
        ];
    }
}
