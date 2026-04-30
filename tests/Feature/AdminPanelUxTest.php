<?php

use App\Filament\Clusters\About\AboutCluster;
use App\Filament\Clusters\Contact\ContactCluster;
use App\Filament\Clusters\Donations\DonationsCluster;
use App\Filament\Clusters\Home\HomeCluster;
use App\Filament\Clusters\Media\MediaCluster;
use App\Filament\Clusters\Programs\ProgramsCluster;
use App\Filament\Clusters\Publications\PublicationsCluster;
use App\Filament\Clusters\System\SystemCluster;
use App\Filament\Resources\Activities\ActivityResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\OrganizationProfiles\OrganizationProfileResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;

function createPanelUserForUxTest(string $roleName): User
{
    $role = Role::query()->firstOrCreate(
        ['name' => $roleName],
        ['description' => "{$roleName} access."],
    );

    $user = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$role->id]);

    return $user;
}

test('admin panel groups resources by website navbar clusters', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getClusters())
        ->toEqualCanonicalizing([
            HomeCluster::class,
            ProgramsCluster::class,
            MediaCluster::class,
            PublicationsCluster::class,
            AboutCluster::class,
            ContactCluster::class,
            DonationsCluster::class,
            SystemCluster::class,
        ]);

    expect(PageResource::getCluster())->toBe(HomeCluster::class)
        ->and(ProgramResource::getCluster())->toBe(ProgramsCluster::class)
        ->and(ActivityResource::getCluster())->toBe(MediaCluster::class)
        ->and(ContentResource::getCluster())->toBe(PublicationsCluster::class)
        ->and(OrganizationProfileResource::getCluster())->toBe(AboutCluster::class)
        ->and(ContactMessageResource::getCluster())->toBe(ContactCluster::class)
        ->and(DonationCampaignResource::getCluster())->toBe(DonationsCluster::class)
        ->and(UserResource::getCluster())->toBe(SystemCluster::class);
});

test('dashboard highlights actionable admin work', function () {
    $user = createPanelUserForUxTest('Admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSee('Ringkasan Operasional')
        ->assertSee('Program Aktif')
        ->assertSee('Draft Editorial')
        ->assertSee('Inbox Baru')
        ->assertSee('Donasi Menunggu')
        ->assertSee('Kampanye Aktif');
});

test('workflow tabs are visible on the main operational resources', function () {
    $user = createPanelUserForUxTest('Admin');

    $this->actingAs($user)
        ->get((string) parse_url(ContactMessageResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Dalam Tinjauan')
        ->assertSee('Ditutup');

    $this->actingAs($user)
        ->get((string) parse_url(ProgramResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Mendatang')
        ->assertSee('Arsip');

    $this->actingAs($user)
        ->get((string) parse_url(ContentResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Draft')
        ->assertSee('Publikasi');

    $this->actingAs($user)
        ->get((string) parse_url(DonationResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Menunggu')
        ->assertSee('Dikembalikan');

    $this->actingAs($user)
        ->get((string) parse_url(DonationCampaignResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Draft')
        ->assertSee('Aktif');
});
