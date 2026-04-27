<?php

namespace Database\Factories;

use App\Models\Division;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->unique()->name()),
            'structure_slot' => null,
            'position' => fake()->jobTitle(),
            'division' => null,
            'division_id' => Division::factory(),
            'parent_id' => null,
            'bio' => fake()->sentence(12),
            'photo_url' => fake()->imageUrl(400, 400, 'people'),
            'email' => fake()->safeEmail(),
            'linkedin_url' => fake()->url(),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_structural' => true,
            'is_active' => true,
        ];
    }
}
