<?php

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Select;
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

test('program form only shows active partners and can attach them to a program', function () {
    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten.'],
    );

    $editor = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $editor->roles()->sync([$editorRole->id]);

    $category = ProgramCategory::query()->create([
        'name' => 'Program Kolaborasi',
        'slug' => 'program-kolaborasi',
        'description' => 'Kategori uji mitra aktif.',
    ]);

    $activePartnerA = Partner::query()->create([
        'name' => 'Komunitas Akar',
        'slug' => 'komunitas-akar',
        'type' => 'community',
        'description' => 'Mitra aktif pertama.',
        'is_active' => true,
    ]);

    $activePartnerB = Partner::query()->create([
        'name' => 'Yayasan Bina Desa',
        'slug' => 'yayasan-bina-desa',
        'type' => 'ngo',
        'description' => 'Mitra aktif kedua.',
        'is_active' => true,
    ]);

    $inactivePartner = Partner::query()->create([
        'name' => 'Mitra Arsip',
        'slug' => 'mitra-arsip',
        'type' => 'media',
        'description' => 'Mitra nonaktif.',
        'is_active' => false,
    ]);

    $this->actingAs($editor);

    Livewire::test(CreateProgram::class)
        ->assertFormFieldExists('partners', function (Select $field) use ($activePartnerA, $activePartnerB, $inactivePartner): bool {
            $optionLabels = array_values($field->getOptions());

            return in_array($activePartnerA->name, $optionLabels, true)
                && in_array($activePartnerB->name, $optionLabels, true)
                && ! in_array($inactivePartner->name, $optionLabels, true);
        })
        ->fillForm([
            'title' => 'Program Dengan Mitra',
            'slug' => 'program-dengan-mitra',
            'description' => 'Program ini harus bisa memilih dan menyimpan mitra aktif.',
            'category_id' => $category->id,
            'status' => 'draft',
            'phase' => 'active',
            'beneficiaries_count' => 50,
            'created_by' => $editor->id,
            'partners' => [$activePartnerA->id, $activePartnerB->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $program = Program::query()
        ->where('slug', 'program-dengan-mitra')
        ->firstOrFail();

    expect($program->partners()->pluck('partners.id')->all())
        ->toEqualCanonicalizing([$activePartnerA->id, $activePartnerB->id]);
});

test('program form only offers draft and published status options', function () {
    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten.'],
    );

    $editor = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $editor->roles()->sync([$editorRole->id]);

    $this->actingAs($editor);

    Livewire::test(CreateProgram::class)
        ->assertFormFieldExists('status', function (Select $field): bool {
            return $field->getOptions() === [
                'draft' => 'Draft',
                'published' => 'Terbit',
            ];
        });
});

test('legacy completed programs are normalized into archived public records when saved', function () {
    $program = Program::query()->create([
        'title' => 'Program Legacy Selesai',
        'slug' => 'program-legacy-selesai',
        'description' => 'Program lama yang masih memakai status selesai.',
        'status' => 'completed',
        'phase' => 'active',
        'beneficiaries_count' => 10,
    ]);

    expect($program->fresh()->phase)->toBe('archived')
        ->and($program->fresh()->published_at)->not->toBeNull()
        ->and($program->fresh()->isVisibleOnFrontend())->toBeTrue();
});

test('program listing stays stable for published programs without partners', function () {
    $category = ProgramCategory::query()->create([
        'name' => 'Program Mandiri',
        'slug' => 'program-mandiri',
        'description' => 'Kategori untuk program tanpa mitra.',
    ]);

    Program::query()->create([
        'title' => 'Program Tanpa Mitra',
        'slug' => 'program-tanpa-mitra',
        'description' => 'Program ini diterbitkan tanpa mitra dan tidak boleh memicu lazy loading error.',
        'category_id' => $category->id,
        'status' => 'published',
        'phase' => 'active',
        'beneficiaries_count' => 40,
        'published_at' => now(),
    ]);

    $this->get('/programs')
        ->assertSuccessful()
        ->assertSee('Program Tanpa Mitra');
});
