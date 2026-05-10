<?php

namespace Database\Factories;

use App\Models\OrganizationProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganizationProfile>
 */
class OrganizationProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foundationName = 'GIRI Foundation '.fake()->unique()->word();

        return [
            'name' => $foundationName,
            'slug' => fake()->unique()->slug(),
            'short_description' => 'Yayasan independen yang fokus pada pemberdayaan masyarakat.',
            'full_description' => 'Profil organisasi untuk kebutuhan pengujian resource admin dan konsistensi frontend.',
            'vision' => 'Mewujudkan lembaga yang tangguh dan bermanfaat bagi masyarakat.',
            'mission' => 'Mendukung kolaborasi, riset, dan program pemberdayaan yang terukur.',
            'values' => 'Integritas, kolaborasi, keberlanjutan.',
        ];
    }
}
