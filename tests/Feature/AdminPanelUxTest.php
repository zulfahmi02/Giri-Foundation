<?php

use App\Filament\Clusters\Donations\DonationsCluster;
use App\Filament\Clusters\Editorial\EditorialCluster;
use App\Filament\Clusters\Inbox\InboxCluster;
use App\Filament\Clusters\Programs\ProgramsCluster;
use App\Filament\Clusters\System\SystemCluster;
use App\Filament\Clusters\Website\WebsiteCluster;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\Programs\ProgramResource;
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

test('admin panel groups resources into task based clusters', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getClusters())
        ->toEqualCanonicalizing([
            WebsiteCluster::class,
            ProgramsCluster::class,
            EditorialCluster::class,
            InboxCluster::class,
            DonationsCluster::class,
            SystemCluster::class,
        ]);
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
