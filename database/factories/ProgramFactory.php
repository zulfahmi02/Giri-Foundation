<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $title = $this->faker->unique()->sentence(4),
            'slug' => str($title)->slug(),
            'excerpt' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'category_id' => null,
            'status' => 'draft',
            'phase' => 'active',
            'start_date' => null,
            'end_date' => null,
            'location_name' => $this->faker->city(),
            'province' => null,
            'city' => null,
            'target_beneficiaries' => null,
            'beneficiaries_count' => 0,
            'budget_amount' => null,
            'featured_image_url' => null,
            'is_featured' => false,
            'published_at' => null,
            'created_by' => null,
        ];
    }
}
