<?php

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use App\Models\Role;
use App\Models\TeamMember;
use App\Models\User;
use App\Support\TeamMemberStructureSlots;
use Database\Seeders\GiriFoundationSeeder;
use Illuminate\Support\Facades\Storage;

test('structural slot update replaces the existing occupant instead of creating a duplicate record', function () {
    $this->seed(GiriFoundationSeeder::class);

    $existingPembina = TeamMember::query()
        ->where('structure_slot', TeamMemberStructureSlots::TrusteePrimary)
        ->firstOrFail();

    $updatedPembina = TeamMemberStructureSlots::upsert([
        'name' => 'Pembina Baru',
        'structure_slot' => TeamMemberStructureSlots::TrusteePrimary,
        'bio' => 'Pembina utama pengganti.',
        'photo_url' => null,
        'email' => 'pembina@example.com',
        'linkedin_url' => null,
        'is_structural' => true,
        'is_active' => true,
    ]);

    expect($updatedPembina->id)->toBe($existingPembina->id)
        ->and($updatedPembina->name)->toBe('Pembina Baru')
        ->and($updatedPembina->position)->toBe('Ketua Dewan Pembina')
        ->and($updatedPembina->parent?->structure_slot)->toBe(TeamMemberStructureSlots::Advisor)
        ->and(TeamMember::query()->where('structure_slot', TeamMemberStructureSlots::TrusteePrimary)->count())->toBe(1);
});

test('structural slots automatically fill hierarchy metadata', function () {
    $this->seed(GiriFoundationSeeder::class);

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

test('team member exposes a public photo url for uploaded and legacy image paths', function () {
    $uploadedPhotoMember = TeamMember::factory()->create([
        'photo_url' => 'team-members/anggota-tim.jpg',
    ]);

    $legacyPhotoMember = TeamMember::factory()->create([
        'photo_url' => '/image/logo.png',
    ]);

    expect($uploadedPhotoMember->public_photo_url)->toBe(Storage::disk('public')->url('team-members/anggota-tim.jpg'))
        ->and($legacyPhotoMember->public_photo_url)->toBe('/image/logo.png');
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
