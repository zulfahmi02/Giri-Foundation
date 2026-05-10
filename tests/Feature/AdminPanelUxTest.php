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
use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\ContentCategories\ContentCategoryResource;
use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Resources\Documents\DocumentResource;
use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\MediaLibraries\MediaLibraryResource;
use App\Filament\Resources\OrganizationProfiles\OrganizationProfileResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\Partners\PartnerResource;
use App\Filament\Resources\ProgramCategories\ProgramCategoryResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Settings\SettingResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\MediaLibrary;
use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

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

    expect($panel->getResources())
        ->not->toContain('App\\Filament\\Resources\\Tags\\TagResource');

    expect(PageResource::getCluster())->toBe(HomeCluster::class)
        ->and(ProgramResource::getCluster())->toBe(ProgramsCluster::class)
        ->and(ActivityResource::getCluster())->toBe(MediaCluster::class)
        ->and(ContentResource::getCluster())->toBe(PublicationsCluster::class)
        ->and(OrganizationProfileResource::getCluster())->toBe(AboutCluster::class)
        ->and(ContactMessageResource::getCluster())->toBe(ContactCluster::class)
        ->and(DonationCampaignResource::getCluster())->toBe(DonationsCluster::class)
        ->and(UserResource::getCluster())->toBe(SystemCluster::class);
});

test('admin cluster entry points prioritize primary client workflows', function () {
    expect(ProgramResource::getNavigationSort())->toBe(10)
        ->and(ProgramCategoryResource::getNavigationSort())->toBe(20)
        ->and(PartnerResource::getNavigationSort())->toBe(30)
        ->and(ActivityResource::getNavigationSort())->toBe(10)
        ->and(VideoResource::getNavigationSort())->toBe(20)
        ->and(ContentResource::getNavigationSort())->toBe(10)
        ->and(DocumentResource::getNavigationSort())->toBe(20)
        ->and(ContentCategoryResource::getNavigationSort())->toBe(30)
        ->and(UserResource::getNavigationSort())->toBe(10)
        ->and(RoleResource::getNavigationSort())->toBe(20)
        ->and(SettingResource::getNavigationSort())->toBe(30)
        ->and(ActivityLogResource::getNavigationSort())->toBe(40);
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

test('page management only edits navbar pages and offers frontend previews', function () {
    $user = createPanelUserForUxTest('Admin');

    $page = Page::factory()->create([
        'title' => 'Tentang',
        'slug' => 'about',
        'template' => 'about',
        'status' => 'published',
        'published_at' => now(),
    ]);

    expect(PageResource::shouldRegisterNavigation())->toBeFalse();

    $this->actingAs($user)
        ->get((string) parse_url(PageResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertDontSee((string) parse_url('/admin/home/pages/create', PHP_URL_PATH))
        ->assertSee('Buka Halaman');

    $this->actingAs($user)
        ->get((string) parse_url(PageResource::getUrl('edit', ['record' => $page], panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Buka Halaman')
        ->assertSee('about')
        ->assertSee('template');
});

test('page detail handles legacy structured content stored as strings', function () {
    $user = createPanelUserForUxTest('Admin');

    DB::table('pages')->insert([
        'title' => 'Beranda',
        'slug' => 'home',
        'content' => null,
        'hero_data' => json_encode('Legacy hero text'),
        'section_data' => json_encode(['closing' => ['title' => 'Hubungi Kami']]),
        'template' => 'home',
        'status' => 'published',
        'seo_title' => null,
        'seo_description' => null,
        'published_at' => now(),
        'created_by' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $page = Page::query()->where('slug', 'home')->firstOrFail();

    $this->actingAs($user)
        ->get((string) parse_url(PageResource::getUrl('view', ['record' => $page], panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Legacy hero text')
        ->assertSee('Hubungi Kami');
});

test('technical media library resource is hidden but remains safe to access directly', function () {
    $user = createPanelUserForUxTest('Admin');

    expect(MediaLibraryResource::shouldRegisterNavigation())->toBeFalse()
        ->and((new MediaLibrary)->getTable())->toBe('media_library');

    $this->actingAs($user)
        ->get((string) parse_url(MediaLibraryResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful();
});

test('organization profile resource keeps one primary record available', function () {
    expect(OrganizationProfileResource::canCreate())->toBeTrue();

    $primaryProfile = OrganizationProfile::factory()->create();

    expect(OrganizationProfileResource::canCreate())->toBeFalse()
        ->and(OrganizationProfileResource::canDeleteAny())->toBeFalse()
        ->and(OrganizationProfileResource::canDelete($primaryProfile))->toBeFalse();

    $secondaryProfile = OrganizationProfile::factory()->create();

    expect(OrganizationProfileResource::canDeleteAny())->toBeTrue()
        ->and(OrganizationProfileResource::canDelete($secondaryProfile))->toBeTrue();
});

test('organization profile create route redirects to the primary record when it already exists', function () {
    $user = createPanelUserForUxTest('Admin');
    $primaryProfile = OrganizationProfile::factory()->create();

    $this->actingAs($user)
        ->get((string) parse_url(OrganizationProfileResource::getUrl('create', panel: 'admin'), PHP_URL_PATH))
        ->assertRedirect((string) parse_url(OrganizationProfileResource::getUrl('edit', ['record' => $primaryProfile], panel: 'admin'), PHP_URL_PATH));

    $this->actingAs($user)
        ->get((string) parse_url(OrganizationProfileResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Edit Profil Yayasan')
        ->assertDontSee((string) parse_url(OrganizationProfileResource::getUrl('create', panel: 'admin'), PHP_URL_PATH));
});
