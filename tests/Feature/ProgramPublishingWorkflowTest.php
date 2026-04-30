<?php

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;

test('published programs created from the admin form receive a publication timestamp automatically', function () {
    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten.'],
    );

    $editor = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $editor->roles()->sync([$editorRole->id]);

    $category = ProgramCategory::query()->create([
        'name' => 'Program Pemberdayaan',
        'slug' => 'program-pemberdayaan',
        'description' => 'Kategori uji terbit otomatis.',
    ]);

    $this->actingAs($editor);

    Livewire::test(CreateProgram::class)
        ->fillForm([
            'title' => 'Program Otomatis Terbit',
            'slug' => 'program-otomatis-terbit',
            'description' => 'Program ini dibuat lewat form admin dan harus langsung tampil di website.',
            'category_id' => $category->id,
            'status' => 'published',
            'phase' => 'active',
            'beneficiaries_count' => 120,
            'created_by' => $editor->id,
            'published_at' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $program = Program::query()
        ->where('slug', 'program-otomatis-terbit')
        ->firstOrFail();

    expect($program->published_at)->not->toBeNull()
        ->and($program->status)->toBe('published');

    $this->get('/programs')
        ->assertSuccessful()
        ->assertSee('Program Otomatis Terbit');
});
