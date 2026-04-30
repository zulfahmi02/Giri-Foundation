<?php

use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function (): void {
    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten.'],
    );

    $editor = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $editor->roles()->sync([$editorRole->id]);

    $this->actingAs($editor);
});

test('program form auto-fills the slug from the title', function () {
    Livewire::test(CreateProgram::class)
        ->fillForm([
            'title' => 'Program Pemberdayaan Warga',
        ])
        ->assertFormSet([
            'slug' => 'program-pemberdayaan-warga',
        ]);
});

test('partner form auto-fills the slug from the name', function () {
    Livewire::test(CreatePartner::class)
        ->fillForm([
            'name' => 'Mitra Kolaborasi Desa',
        ])
        ->assertFormSet([
            'slug' => 'mitra-kolaborasi-desa',
        ]);
});

test('manually customized slugs are preserved when the source field changes', function () {
    Livewire::test(CreateProgram::class)
        ->fillForm([
            'title' => 'Judul Awal Program',
        ])
        ->assertFormSet([
            'slug' => 'judul-awal-program',
        ])
        ->fillForm([
            'slug' => 'slug-khusus-program',
        ])
        ->fillForm([
            'title' => 'Judul Program Revisi',
        ])
        ->assertFormSet([
            'slug' => 'slug-khusus-program',
        ]);
});

test('team member form auto-fills the slug in non-structural mode', function () {
    Livewire::test(CreateTeamMember::class)
        ->fillForm([
            'is_structural' => false,
        ])
        ->fillForm([
            'name' => 'Koordinator Relawan',
        ])
        ->assertFormSet([
            'slug' => 'koordinator-relawan',
        ]);
});
