<?php

use App\Filament\Resources\Documents\Pages\CreateDocument;
use App\Models\Document;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

function createDocumentEditor(): User
{
    $editorRole = Role::query()->firstOrCreate(
        ['name' => 'Editor'],
        ['description' => 'Mengelola konten publik yayasan.'],
    );

    $user = User::factory()->create([
        'status' => 'active',
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$editorRole->id]);

    return $user;
}

test('editors can create documents by uploading a file from the admin form', function () {
    Storage::fake('public');

    $editor = createDocumentEditor();

    $this->actingAs($editor);

    $documentFile = UploadedFile::fake()->create(
        'anggaran-dasar.pdf',
        240,
        'application/pdf',
    );

    Livewire::test(CreateDocument::class)
        ->fillForm([
            'title' => 'Anggaran Dasar Yayasan',
            'slug' => 'anggaran-dasar-yayasan',
            'category' => 'Dokumen Organisasi',
            'description' => 'Dokumen resmi anggaran dasar yang diunggah langsung dari panel admin.',
            'file_url' => $documentFile,
            'is_public' => true,
            'published_at' => now(),
            'created_by' => $editor->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $document = Document::query()
        ->where('slug', 'anggaran-dasar-yayasan')
        ->firstOrFail();

    expect($document->file_url)->toStartWith('documents/')
        ->and($document->file_type)->toBe('PDF')
        ->and($document->file_size)->not->toBeNull();

    Storage::disk('public')->assertExists($document->file_url);
});

test('editors can create documents that point to external urls from the admin form', function () {
    $editor = createDocumentEditor();

    $this->actingAs($editor);

    Livewire::test(CreateDocument::class)
        ->fillForm([
            'title' => 'Laporan Mitra Regional',
            'slug' => 'laporan-mitra-regional',
            'category' => 'Laporan',
            'description' => 'Dokumen ini disimpan di repositori eksternal organisasi mitra.',
            'external_file_url' => 'https://example.org/documents/laporan-mitra-regional.pdf',
            'is_public' => true,
            'published_at' => now(),
            'created_by' => $editor->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $document = Document::query()
        ->where('slug', 'laporan-mitra-regional')
        ->firstOrFail();

    expect($document->file_url)->toBe('https://example.org/documents/laporan-mitra-regional.pdf')
        ->and($document->file_type)->toBe('PDF')
        ->and($document->file_size)->toBeNull();
});
