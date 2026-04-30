<?php

use App\Filament\Resources\Divisions\DivisionResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Activity;
use App\Models\Role;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\GiriFoundationSeeder;

test('home page navigation uses the refreshed information architecture', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSeeInOrder(['Beranda', 'Program', 'Media', 'Publikasi', 'Tentang', 'Kontak'])
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
    '/donate',
]);

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
