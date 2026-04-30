<?php

use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\Program;
use App\Models\Role;
use Database\Seeders\DatabaseSeeder;

test('database seeder only bootstraps panel roles without injecting demo website data', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Role::query()->pluck('name')->all())
        ->toContain('Admin', 'Editor')
        ->and(OrganizationProfile::query()->count())->toBe(0)
        ->and(Page::query()->count())->toBe(0)
        ->and(Program::query()->count())->toBe(0);
});
