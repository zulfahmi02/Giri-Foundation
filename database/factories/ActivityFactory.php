<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'title' => $title = $this->faker->sentence(4),
            'slug' => str($title)->slug(),
            'summary' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'activity_date' => $this->faker->date(),
            'location_name' => $this->faker->city(),
            'featured_image_url' => null,
            'status' => 'draft',
            'published_at' => null,
            'created_by' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function withoutProgram(): static
    {
        return $this->state(fn (): array => [
            'program_id' => null,
        ]);
    }
}
