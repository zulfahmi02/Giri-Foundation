<?php

use App\Filament\Resources\Divisions\DivisionResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Activity;
use App\Models\OrganizationProfile;
use App\Models\Program;
use App\Models\Role;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use App\Support\FrontendCache;
use Database\Seeders\GiriFoundationSeeder;

test('home page navigation uses the refreshed information architecture', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeInOrder(['Beranda', 'Program', 'Media', 'Publikasi', 'Tentang', 'Kontak', 'Donasi'])
        ->assertSee(route('donate.show'), false)
        ->assertSee('data-desktop-nav-primary', false)
        ->assertSee('data-desktop-nav-secondary', false)
        ->assertSee('data-nav-donate-link', false)
        ->assertSee('shadow-[0_18px_38px_rgba(0,96,76,0.22)]', false)
        ->assertSee('Buka menu navigasi')
        ->assertSee('data-mobile-nav', false)
        ->assertDontSee('Cerita');
});

test('program page splits records by phase and partnership data', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/programs')
        ->assertSuccessful()
        ->assertSeeInOrder([
            'Program Aktif',
            'Sistem Lembaga yang Mandiri, Transparan, dan Akuntabel',
            'Program Kerja Sama',
            'Publikasi dan Penyebaran Informasi',
            'Program Mendatang',
            'Pelestarian Lingkungan dan Kebudayaan',
        ]);
});

test('program pages render mobile-oriented layout classes', function () {
    $this->seed(GiriFoundationSeeder::class);

    $program = Program::query()
        ->published()
        ->firstOrFail();

    $this->get('/programs')
        ->assertSuccessful()
        ->assertSee('h-52 w-full object-cover sm:h-64', false)
        ->assertSee('font-editorial text-2xl leading-tight sm:text-3xl', false);

    $this->get(route('programs.show', $program))
        ->assertSuccessful()
        ->assertSee('min-h-[24rem]', false)
        ->assertSee('sm:grid-cols-3', false)
        ->assertSee('sm:grid-cols-2 md:grid-cols-4', false);
});

test('media page renders activities before videos and hides empty sections', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/media')
        ->assertSuccessful()
        ->assertSeeInOrder(['Aktivitas', 'Video']);

    Activity::query()->get()->each(function (Activity $activity): void {
        $activity->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    });

    Video::query()->get()->each(function (Video $video): void {
        $video->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    });

    $this->get('/media')
        ->assertSuccessful()
        ->assertDontSee('Dokumentasi kegiatan yayasan.')
        ->assertDontSee('Video pengantar kelembagaan dan program.');
});

test('publikasi page mixes editorial content and archive documents', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/publikasi')
        ->assertSuccessful()
        ->assertSee('Cerita dari lapangan dan perjalanan yayasan.')
        ->assertSee('Yayasan Independen untuk Pemberdayaan Masyarakat')
        ->assertSee(route('stories.show', 'yayasan-independen-pemberdayaan-masyarakat'), false)
        ->assertSee('Anggaran Dasar Yayasan Giri Nusantara Sejahtera')
        ->assertSee('Anggaran Dasar dan Anggaran Rumah Tangga GNS')
        ->assertSee('Anggaran Rumah Tangga Yayasan Giri Nusantara Sejahtera')
        ->assertSee('Kode Etik Kekayaan dan Donasi');
});

test('about page renders personnel structure', function () {
    $this->seed(GiriFoundationSeeder::class);

    $pembina = TeamMember::query()
        ->where('slug', 'm-suaeb-abdullah')
        ->firstOrFail();

    $direktur = TeamMember::query()
        ->where('slug', 'direktur-yayasan')
        ->firstOrFail();

    $ketuaYayasan = TeamMember::query()
        ->where('slug', 'rian-adi-kurniawan')
        ->firstOrFail();

    expect($direktur->parent_id)->toBe($pembina->id)
        ->and($ketuaYayasan->is_structural)->toBeFalse();

    $this->get('/about')
        ->assertSuccessful()
        ->assertSee('GIRI FOUNDATION')
        ->assertSee('Personil')
        ->assertSee('Penasihat')
        ->assertSee('Profil Personil')
        ->assertSee('Ketua')
        ->assertSee('Anggota')
        ->assertSee('Direktur')
        ->assertSee('data-team-structure="advisor-chair-branch"', false)
        ->assertSee('data-team-structure="trustee-companion-branch"', false)
        ->assertSee('data-team-structure="trustee-director-branch"', false)
        ->assertSee('Sekretaris')
        ->assertSee('Bendahara')
        ->assertSee('Bidang Riset dan Digitalisasi')
        ->assertSee('Menjalankan kewenangan Dewan Pembina sesuai Anggaran Dasar')
        ->assertSee('Nama personil untuk slot ini masih dalam proses penetapan.')
        ->assertSee('data-team-member-dialog-trigger=', false)
        ->assertSee('data-team-member-dialog=', false)
        ->assertDontSee('Pembina 1')
        ->assertDontSee('Pembina 2')
        ->assertDontSee('Pembina 3')
        ->assertDontSee('Ketua Yayasan')
        ->assertDontSee('Nama menyusul')
        ->assertSee('Ketua Dewan Pembina')
        ->assertSee('Anggota Dewan Pembina')
        ->assertSee('M. Suaeb Abdullah')
        ->assertSee('Dewan Pembina');
});

test('about page remains available when personnel data is removed', function () {
    $this->seed(GiriFoundationSeeder::class);

    TeamMember::query()->delete();

    $this->get('/about')
        ->assertSuccessful()
        ->assertSee('GIRI FOUNDATION')
        ->assertDontSee('Personil');
});

test('legacy public routes remain reachable', function (string $uri) {
    $this->seed(GiriFoundationSeeder::class);

    $this->get($uri)
        ->assertSuccessful();
})->with([
    '/stories',
    '/resources',
    '/partners',
    '/consultation',
    '/donate',
]);

test('contact page shows the configured organization contact information', function () {
    $this->seed(GiriFoundationSeeder::class);

    $organizationProfile = OrganizationProfile::query()->firstOrFail();

    $organizationProfile->update([
        'email' => 'kontak@giri.foundation',
        'phone' => '+62 851 7777 8888',
        'whatsapp_number' => '+62 811 2222 3333',
        'address' => 'Jl. Test Integrasi No. 5, Bojonegoro',
    ]);
    FrontendCache::bump(FrontendCache::SiteShell);

    $this->get('/contact')
        ->assertSuccessful()
        ->assertSee('kontak@giri.foundation')
        ->assertSee('+62 851 7777 8888')
        ->assertSee('+62 811 2222 3333')
        ->assertSee('Jl. Test Integrasi No. 5, Bojonegoro')
        ->assertDontSee('hello@giri.foundation')
        ->assertDontSee('+62 812 0000 0000')
        ->assertDontSee('+62 000 0000 000')
        ->assertDontSee('Bali, Indonesia');
});

test('consultation page reuses the configured organization contact information', function () {
    $this->seed(GiriFoundationSeeder::class);

    $organizationProfile = OrganizationProfile::query()->firstOrFail();

    $organizationProfile->update([
        'email' => 'konsultasi@giri.foundation',
        'phone' => '+62 852 1111 4444',
        'whatsapp_number' => '+62 813 9999 0000',
    ]);
    FrontendCache::bump(FrontendCache::SiteShell);

    $this->get('/consultation')
        ->assertSuccessful()
        ->assertSee('konsultasi@giri.foundation')
        ->assertSee('+62 852 1111 4444')
        ->assertSee('+62 813 9999 0000')
        ->assertDontSee('hello@giri.foundation')
        ->assertDontSee('+62 812 0000 0000')
        ->assertDontSee('+62 000 0000 000');
});

test('contact page converts plain location text into a valid google maps embed url', function () {
    $this->seed(GiriFoundationSeeder::class);

    $organizationProfile = OrganizationProfile::query()->firstOrFail();

    $organizationProfile->update([
        'address' => 'Bojonegoro, Provinsi Jawa Timur, Indonesia',
        'google_maps_embed' => 'Bojonegoro, Provinsi Jawa Timur, Indonesia',
    ]);
    FrontendCache::bump(FrontendCache::SiteShell);

    $this->get('/contact')
        ->assertSuccessful()
        ->assertSee(
            'https://www.google.com/maps?q=Bojonegoro%2C%20Provinsi%20Jawa%20Timur%2C%20Indonesia&amp;output=embed',
            false,
        )
        ->assertDontSee('src="Bojonegoro, Provinsi Jawa Timur, Indonesia"', false)
        ->assertSee('break-all', false);
});

test('editor can access the new video and division resources', function () {
    $this->seed(GiriFoundationSeeder::class);

    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten.'],
    );

    $user = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$editorRole->id]);

    $this->actingAs($user)
        ->get((string) parse_url(VideoResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful();

    $this->actingAs($user)
        ->get((string) parse_url(DivisionResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful();
});
