<?php

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Filament\Resources\Divisions\DivisionResource;
use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\DonationUpdates\DonationUpdateResource;
use App\Filament\Resources\Donors\DonorResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\GiriFoundationSeeder;
use Filament\Facades\Filament;

function setFilamentAdminSeedEnvironment(?string $name, ?string $email, ?string $password): void
{
    foreach ([
        'FILAMENT_ADMIN_NAME' => $name,
        'FILAMENT_ADMIN_EMAIL' => $email,
        'FILAMENT_ADMIN_PASSWORD' => $password,
    ] as $key => $value) {
        if ($value === null) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);

            continue;
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

function createPanelUserWithRole(string $roleName, array $attributes = []): User
{
    $role = Role::query()->firstOrCreate(
        ['name' => $roleName],
        ['description' => "{$roleName} access."],
    );

    $user = User::factory()->create(array_merge([
        'app_authentication_secret' => 'totp-secret',
    ], $attributes));

    $user->roles()->sync([$role->id]);

    return $user;
}

function adminPanelPathForResource(string $resourceClass): string
{
    return (string) parse_url($resourceClass::getUrl(panel: 'admin'), PHP_URL_PATH);
}

test('guest is redirected to the admin login page', function () {
    $this->get('/admin')
        ->assertRedirect(route('filament.admin.auth.login'));
});

test('seed does not create a predictable default admin account', function () {
    setFilamentAdminSeedEnvironment(null, null, null);

    $this->seed(GiriFoundationSeeder::class);

    $this->assertDatabaseMissing('users', [
        'email' => 'admin@giri.foundation',
    ]);

    $this->assertDatabaseHas('users', [
        'email' => 'editorial@giri.foundation',
        'status' => 'inactive',
    ]);
});

test('configured bootstrap admin is required to set up multi factor authentication', function () {
    $originalName = getenv('FILAMENT_ADMIN_NAME') ?: null;
    $originalEmail = getenv('FILAMENT_ADMIN_EMAIL') ?: null;
    $originalPassword = getenv('FILAMENT_ADMIN_PASSWORD') ?: null;

    setFilamentAdminSeedEnvironment('Bootstrap Admin', 'bootstrap-admin@giri.foundation', 'StrongPassword!123');

    try {
        $this->seed(GiriFoundationSeeder::class);
    } finally {
        setFilamentAdminSeedEnvironment($originalName, $originalEmail, $originalPassword);
    }

    $user = User::query()
        ->where('email', 'bootstrap-admin@giri.foundation')
        ->firstOrFail();

    expect($user->isAdmin())->toBeTrue();

    $this->actingAs($user)
        ->get('/admin')
        ->assertRedirect(Filament::getPanel('admin')->getSetUpRequiredMultiFactorAuthenticationUrl());
});

test('editor can access content resources in the admin panel', function (string $resourceClass) {
    $user = createPanelUserWithRole('Editor');

    $this->actingAs($user)
        ->get(adminPanelPathForResource($resourceClass))
        ->assertSuccessful();
})->with([
    [VideoResource::class],
    [DivisionResource::class],
]);

test('inactive users are forbidden from the admin panel', function () {
    $user = createPanelUserWithRole('Admin', [
        'status' => 'inactive',
    ]);

    $this->actingAs($user)
        ->get(adminPanelPathForResource(UserResource::class))
        ->assertForbidden();
});

test('editors are forbidden from admin only resources', function (string $resourceClass) {
    $user = createPanelUserWithRole('Editor');

    $this->actingAs($user)
        ->get(adminPanelPathForResource($resourceClass))
        ->assertForbidden();
})->with([
    [UserResource::class],
    [RoleResource::class],
    [SettingResource::class],
    [ActivityLogResource::class],
    [DonationResource::class],
    [DonorResource::class],
    [DonationCampaignResource::class],
    [DonationUpdateResource::class],
]);

test('admins can access restricted admin resources', function (string $resourceClass) {
    $user = createPanelUserWithRole('Admin');

    $this->actingAs($user)
        ->get(adminPanelPathForResource($resourceClass))
        ->assertSuccessful();
})->with([
    [UserResource::class],
    [RoleResource::class],
    [SettingResource::class],
    [ActivityLogResource::class],
    [DonationResource::class],
    [DonorResource::class],
    [DonationCampaignResource::class],
    [DonationUpdateResource::class],
]);

test('admin panel requires multi factor authentication', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->hasMultiFactorAuthentication())->toBeTrue()
        ->and($panel->isMultiFactorAuthenticationRequired())->toBeTrue()
        ->and(collect($panel->getMultiFactorAuthenticationProviders())->map->getId()->all())
        ->toContain('app');
});

test('make filament user command creates a user that can access the local admin panel', function () {
    $originalEnvironment = app()['env'];
    app()['env'] = 'local';

    try {
        $this->artisan('make:filament-user', [
            '--panel' => 'admin',
            '--name' => 'Local Filament User',
            '--email' => 'local-filament@giri.foundation',
            '--password' => 'password123',
        ])->assertSuccessful();

        $user = User::query()
            ->where('email', 'local-filament@giri.foundation')
            ->firstOrFail();

        $user->forceFill([
            'app_authentication_secret' => 'totp-secret',
        ])->save();

        expect($user->password_hash)->not->toBeNull();

        $this->actingAs($user)
            ->get('/admin')
            ->assertSuccessful()
            ->assertSee('GIRI Foundation');
    } finally {
        app()['env'] = $originalEnvironment;
    }
});
