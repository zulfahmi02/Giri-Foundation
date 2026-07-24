<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\ActivityGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityGallery>
 */
class ActivityGalleryFactory extends Factory
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
            'file_url' => 'activities/gallery/'.$this->faker->uuid().'.jpg',
            'caption' => $this->faker->sentence(),
            'sort_order' => 1,
        ];
    }
}
