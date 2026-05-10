<?php

use App\Models\Document;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;

test('resources page shows a download action for public documents', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/panduan-program.pdf', 'arsip dokumen');

    Page::query()->create([
        'title' => 'Arsip Dokumen',
        'slug' => 'resources',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $document = Document::query()->create([
        'title' => 'Panduan Program',
        'slug' => 'panduan-program',
        'category' => 'Pedoman',
        'description' => 'Dokumen panduan program.',
        'file_url' => 'documents/panduan-program.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('resources.index'))
        ->assertSuccessful()
        ->assertSee(route('resources.download', $document), false)
        ->assertSee('Unduh Dokumen');
});

test('resources page only lists public categories and hides unavailable download actions', function () {
    Page::query()->create([
        'title' => 'Arsip Dokumen',
        'slug' => 'resources',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $unavailablePublicDocument = Document::query()->create([
        'title' => 'Dokumen Menunggu Upload',
        'slug' => 'dokumen-menunggu-upload',
        'category' => 'Pedoman',
        'description' => 'Masih menunggu berkas final.',
        'file_url' => '#',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    Document::query()->create([
        'title' => 'Dokumen Internal',
        'slug' => 'dokumen-internal',
        'category' => 'Rahasia Internal',
        'description' => 'Dokumen ini tidak boleh tampil di publik.',
        'file_url' => 'https://example.com/dokumen-internal.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => false,
        'published_at' => now(),
    ]);

    $this->get(route('resources.index'))
        ->assertSuccessful()
        ->assertSee('Pedoman')
        ->assertDontSee('Rahasia Internal')
        ->assertSee('Berkas segera tersedia')
        ->assertDontSee(route('resources.download', $unavailablePublicDocument), false);
});

test('public documents stored on the public disk can be downloaded', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/panduan-program.pdf', 'arsip dokumen');

    $document = Document::query()->create([
        'title' => 'Panduan Program',
        'slug' => 'panduan-program',
        'category' => 'Pedoman',
        'description' => 'Dokumen panduan program.',
        'file_url' => 'documents/panduan-program.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('resources.download', $document))
        ->assertDownload('panduan-program.pdf');

    expect($document->fresh()->download_count)->toBe(1);
});

test('public documents can redirect to external download urls', function () {
    $document = Document::query()->create([
        'title' => 'Arsip Eksternal',
        'slug' => 'arsip-eksternal',
        'category' => 'Pedoman',
        'description' => 'Dokumen dari tautan eksternal.',
        'file_url' => 'https://example.com/arsip-eksternal.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('resources.download', $document))
        ->assertRedirect('https://example.com/arsip-eksternal.pdf');

    expect($document->fresh()->download_count)->toBe(1);
});

test('missing public files return not found without increasing the download counter', function () {
    $document = Document::query()->create([
        'title' => 'Dokumen Hilang',
        'slug' => 'dokumen-hilang',
        'category' => 'Pedoman',
        'description' => 'Berkas publik yang belum tersedia di storage.',
        'file_url' => 'documents/dokumen-hilang.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('resources.download', $document))
        ->assertNotFound();

    expect($document->fresh()->download_count)->toBe(0);
});
