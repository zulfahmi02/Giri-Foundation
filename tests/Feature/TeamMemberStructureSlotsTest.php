<?php

use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Resources\TeamMembers\Pages\EditTeamMember;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\Role;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\TeamMemberStructureSlots;
use Database\Seeders\GiriFoundationSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

test('structural slot creation is rejected when the slot is already occupied', function () {
    $this->seed(GiriFoundationSeeder::class);

    $existingPembina = TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::TrusteePrimary)
        ->firstOrFail();

    expect(fn () => TeamMemberStructureSlots::upsert([
        'name' => 'Pembina Baru',
        'structure_slot' => TeamMemberStructureSlots::TrusteePrimary,
        'bio' => 'Pembina utama pengganti.',
        'photo_url' => null,
        'email' => 'pembina@example.com',
        'linkedin_url' => null,
        'is_structural' => true,
        'is_active' => true,
    ]))->toThrow(ValidationException::class);

    expect($existingPembina->fresh()->name)->toBe('M. Suaeb Abdullah')
        ->and(TeamMember::query()->where('structure_slot', TeamMemberStructureSlots::TrusteePrimary)->count())->toBe(1);
});

test('structural slots automatically fill hierarchy metadata', function () {
    $this->seed(GiriFoundationSeeder::class);

    TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::Director)
        ->firstOrFail()
        ->update([
            'structure_slot' => null,
            'is_structural' => false,
            'slug' => 'direktur-sebelumnya',
        ]);

    $director = TeamMemberStructureSlots::upsert([
        'name' => 'Direktur Operasional',
        'structure_slot' => TeamMemberStructureSlots::Director,
        'bio' => 'Memimpin pelaksanaan program yayasan.',
        'photo_url' => null,
        'email' => 'direktur@example.com',
        'linkedin_url' => null,
        'is_structural' => true,
        'is_active' => true,
    ]);

    expect($director->position)->toBe('Direktur')
        ->and($director->divisionRecord?->slug)->toBe('dewan-pengurus')
        ->and($director->parent?->structure_slot)->toBe(TeamMemberStructureSlots::TrusteePrimary)
        ->and($director->sort_order)->toBe(50);
});

test('team member create form prevents overwriting an occupied structural slot', function () {
    $this->seed(GiriFoundationSeeder::class);

    $adminRole = Role::query()->firstOrCreate(
        ['name' => 'Admin'],
        ['description' => 'Mengelola akses panel.'],
    );

    $user = User::factory()->create([
        'status' => 'active',
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$adminRole->id]);

    $this->actingAs($user);

    $existingDirector = TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::Director)
        ->firstOrFail();

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'is_structural' => true,
            'name' => 'Direktur Baru',
            'structure_slot' => TeamMemberStructureSlots::Director,
            'bio' => 'Calon direktur baru.',
            'email' => 'direktur-baru@example.com',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasFormErrors(['structure_slot']);

    expect($existingDirector->fresh()->name)->not->toBe('Direktur Baru')
        ->and(TeamMember::query()->where('structure_slot', TeamMemberStructureSlots::Director)->count())->toBe(1);
});

test('team member create form shows structural slot selector for admins', function () {
    $this->seed(GiriFoundationSeeder::class);

    $adminRole = Role::query()->firstOrCreate(
        ['name' => 'Admin'],
        ['description' => 'Mengelola akses panel.'],
    );

    $user = User::factory()->create([
        'status' => 'active',
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$adminRole->id]);

    $this->actingAs($user)
        ->get((string) parse_url(TeamMemberResource::getUrl('create', panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Masuk struktur organisasi')
        ->assertSee('Data Personil')
        ->assertSee('Slot Struktur')
        ->assertSee('Foto personil')
        ->assertSee('Peran pada struktur organisasi')
        ->assertSee('Ketua Pembina')
        ->assertSee('Anggota Pembina (kiri)')
        ->assertSee('Anggota Pembina (kanan)')
        ->assertSee('Direktur')
        ->assertDontSee('URL foto')
        ->assertDontSee('Pembina 1')
        ->assertDontSee('Pembina 2')
        ->assertDontSee('Pembina 3')
        ->assertDontSee('Pengaturan Manual')
        ->assertDontSee('Atasan langsung')
        ->assertDontSee('Urutan tampil');
});

test('admins can free an occupied structural slot by editing the existing team member', function () {
    $this->seed(GiriFoundationSeeder::class);

    $adminRole = Role::query()->firstOrCreate(
        ['name' => 'Admin'],
        ['description' => 'Mengelola akses panel.'],
    );

    $user = User::factory()->create([
        'status' => 'active',
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$adminRole->id]);

    $this->actingAs($user);

    $director = TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::Director)
        ->firstOrFail();

    Livewire::test(EditTeamMember::class, ['record' => $director->getRouteKey()])
        ->fillForm([
            'is_structural' => false,
            'name' => 'Direktur Alumni',
            'position' => 'Direktur Alumni',
            'sort_order' => 99,
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($director->fresh()->structure_slot)->toBeNull()
        ->and($director->fresh()->is_structural)->toBeFalse();

    $replacementDirector = TeamMemberStructureSlots::upsert([
        'name' => 'Direktur Pengganti',
        'structure_slot' => TeamMemberStructureSlots::Director,
        'bio' => 'Memimpin pelaksanaan program yayasan.',
        'photo_url' => null,
        'email' => 'direktur-pengganti@example.com',
        'linkedin_url' => null,
        'is_structural' => true,
        'is_active' => true,
    ]);

    expect($replacementDirector->id)->not->toBe($director->id)
        ->and($replacementDirector->structure_slot)->toBe(TeamMemberStructureSlots::Director);
});

test('team member exposes a public photo url for uploaded and legacy image paths', function () {
    Storage::fake('public');
    Storage::disk('public')->put('team-members/anggota-tim.jpg', 'photo');

    $uploadedPhotoMember = TeamMember::factory()->create([
        'photo_url' => 'team-members/anggota-tim.jpg',
    ]);

    $legacyPhotoMember = TeamMember::factory()->create([
        'photo_url' => '/image/logo.png',
    ]);

    expect($uploadedPhotoMember->public_photo_url)->toBe('/storage/team-members/anggota-tim.jpg')
        ->and($legacyPhotoMember->public_photo_url)->toBe('/image/logo.png');
});

test('about page renders uploaded team member photos from public storage', function () {
    Storage::fake('public');
    Storage::disk('public')->put('team-members/direktur.jpg', 'photo');

    $this->seed(GiriFoundationSeeder::class);

    TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::Director)
        ->firstOrFail()
        ->update([
            'photo_url' => 'team-members/direktur.jpg',
        ]);

    $this->get(route('about'))
        ->assertSuccessful()
        ->assertSee('src="/storage/team-members/direktur.jpg"', false)
        ->assertSee('Profil Personil');
});

test('admins can upload a team member photo from the create form', function () {
    Storage::fake('public');

    $adminRole = Role::query()->firstOrCreate(
        ['name' => 'Admin'],
        ['description' => 'Mengelola akses panel.'],
    );

    $user = User::factory()->create([
        'status' => 'active',
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$adminRole->id]);

    $this->actingAs($user);

    $photo = UploadedFile::fake()->image('tim-admin.jpg');

    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'is_structural' => false,
            'name' => 'Tim Lapangan',
            'slug' => 'tim-lapangan',
            'position' => 'Koordinator Lapangan',
            'sort_order' => 1,
            'is_active' => true,
            'photo_url' => $photo,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $teamMember = TeamMember::query()
        ->where('slug', 'tim-lapangan')
        ->firstOrFail();

    expect($teamMember->photo_url)->toStartWith('team-members/')
        ->and($teamMember->public_photo_url)->toStartWith('/storage/team-members/');

    Storage::disk('public')->assertExists($teamMember->photo_url);
});

test('team member structure slots expose distinct admin and public labels for trustees', function () {
    expect(TeamMemberStructureSlots::label(TeamMemberStructureSlots::TrusteePrimary))->toBe('Ketua Pembina')
        ->and(TeamMemberStructureSlots::label(TeamMemberStructureSlots::TrusteeLeft))->toBe('Anggota Pembina (kiri)')
        ->and(TeamMemberStructureSlots::label(TeamMemberStructureSlots::TrusteeRight))->toBe('Anggota Pembina (kanan)')
        ->and(TeamMemberStructureSlots::displayLabel(TeamMemberStructureSlots::TrusteePrimary))->toBe('Ketua')
        ->and(TeamMemberStructureSlots::displayLabel(TeamMemberStructureSlots::TrusteeLeft))->toBe('Anggota')
        ->and(TeamMemberStructureSlots::displayLabel(TeamMemberStructureSlots::TrusteeRight))->toBe('Anggota')
        ->and(TeamMemberStructureSlots::usesLateralLayout(new TeamMember([
            'structure_slot' => TeamMemberStructureSlots::Advisor,
        ])))->toBeTrue();
});

test('giri foundation seeder can rerun after a structural team member slug changes', function () {
    $this->seed(GiriFoundationSeeder::class);

    TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::Advisor)
        ->firstOrFail()
        ->update([
            'slug' => 'penasihat-kustom',
        ]);

    $this->seed(GiriFoundationSeeder::class);

    expect(TeamMember::query()->where('structure_slot', TeamMemberStructureSlots::Advisor)->count())->toBe(1)
        ->and(
            TeamMember::query()
                ->where('structure_slot', TeamMemberStructureSlots::Advisor)
                ->value('slug'),
        )->toBe('penasihat-yayasan');
});
