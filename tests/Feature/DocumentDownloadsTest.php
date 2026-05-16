<?php

use App\Models\Document;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;

test('resources page shows a download action for public documents', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/panduan-program.pdf', 'arsip dokumen');
    Storage::disk('public')->put('documents/thumbnails/panduan-program.jpg', 'thumbnail dokumen');

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
        'thumbnail_url' => 'documents/thumbnails/panduan-program.jpg',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('resources.index'))
        ->assertSuccessful()
        ->assertSee('/storage/documents/thumbnails/panduan-program.jpg', false)
        ->assertSee('Thumbnail Panduan Program')
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

    Document::query()->create([
        'title' => 'Dokumen Belum Dijadwalkan',
        'slug' => 'dokumen-belum-dijadwalkan',
        'category' => 'Draft',
        'description' => 'Dokumen ini belum punya tanggal tampil.',
        'file_url' => 'https://example.com/dokumen-belum-dijadwalkan.pdf',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => null,
    ]);

    $this->get(route('resources.index'))
        ->assertSuccessful()
        ->assertSee('Pedoman')
        ->assertDontSee('Rahasia Internal')
        ->assertDontSee('Draft')
        ->assertDontSee('Dokumen Belum Dijadwalkan')
        ->assertSee('Berkas segera tersedia')
        ->assertDontSee(route('resources.download', $unavailablePublicDocument), false);
});

test('publication archive cards expose document download metadata and action', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/laporan-publik.pdf', 'laporan publik');
    Storage::disk('public')->put('documents/thumbnails/laporan-publik.jpg', 'thumbnail laporan publik');

    Page::query()->create([
        'title' => 'Publikasi',
        'slug' => 'publikasi',
        'status' => 'published',
        'published_at' => now(),
    ]);

    Page::query()->create([
        'title' => 'Arsip Dokumen',
        'slug' => 'resources',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $document = Document::query()->create([
        'title' => 'Laporan Publik Tahunan',
        'slug' => 'laporan-publik-tahunan',
        'category' => 'Laporan',
        'description' => 'Laporan resmi yang dapat diunduh oleh pengunjung.',
        'file_url' => 'documents/laporan-publik.pdf',
        'thumbnail_url' => 'documents/thumbnails/laporan-publik.jpg',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('publications.index'))
        ->assertSuccessful()
        ->assertSee('Laporan Publik Tahunan')
        ->assertSee('/storage/documents/thumbnails/laporan-publik.jpg', false)
        ->assertSee('Thumbnail Laporan Publik Tahunan')
        ->assertSee('PDF')
        ->assertSee('0 unduhan')
        ->assertSee('mt-auto pt-8 lg:pt-10', false)
        ->assertSee('Unduh Dokumen')
        ->assertSee(route('resources.download', $document), false)
        ->assertSee('Lihat Semua Dokumen')
        ->assertSee(route('resources.index'), false);
});

test('home page publication archive cards expose document thumbnails and download actions', function () {
    Storage::fake('public');
    Storage::disk('public')->put('documents/ringkasan-yayasan.pdf', 'ringkasan yayasan');
    Storage::disk('public')->put('documents/thumbnails/ringkasan-yayasan.jpg', 'thumbnail ringkasan yayasan');

    $document = Document::query()->create([
        'title' => 'Ringkasan Yayasan',
        'slug' => 'ringkasan-yayasan',
        'category' => 'Profil Yayasan',
        'description' => 'Dokumen ringkas profil yayasan yang dapat diunduh pengunjung.',
        'file_url' => 'documents/ringkasan-yayasan.pdf',
        'thumbnail_url' => 'documents/thumbnails/ringkasan-yayasan.jpg',
        'file_type' => 'PDF',
        'download_count' => 0,
        'is_public' => true,
        'published_at' => now(),
    ]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('Ringkasan Yayasan')
        ->assertSee('/storage/documents/thumbnails/ringkasan-yayasan.jpg', false)
        ->assertSee('Thumbnail Ringkasan Yayasan')
        ->assertSee('sm:grid-cols-[7rem,1fr]', false)
        ->assertSee('object-contain', false)
        ->assertSee('text-xs font-semibold uppercase leading-none tracking-[0.08em]', false)
        ->assertSee('px-3.5 py-2', false)
        ->assertSee('mt-8 inline-flex', false)
        ->assertSee('PDF')
        ->assertSee('0 unduhan')
        ->assertSee('Unduh Dokumen')
        ->assertSee(route('resources.download', $document), false);
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
