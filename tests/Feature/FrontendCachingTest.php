<?php

use App\Models\Content;
use App\Models\OrganizationProfile;
use App\Models\Page;
use Database\Seeders\GiriFoundationSeeder;

test('page cache invalidates after page content changes', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('Terhubung Dengan Kami');

    $page = Page::query()->where('slug', 'home')->firstOrFail();
    $page->update([
        'section_data' => array_replace_recursive($page->section_data ?? [], [
            'closing' => ['title' => 'Percakapan yang sudah diperbarui.'],
        ]),
    ]);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('Percakapan yang sudah diperbarui.');
});

test('site shell cache invalidates after organization profile changes', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/about')
        ->assertSuccessful()
        ->assertSee('GIRI FOUNDATION');

    OrganizationProfile::query()->firstOrFail()->update([
        'name' => 'GIRI FOUNDATION BARU',
    ]);

    $this->get('/about')
        ->assertSuccessful()
        ->assertSee('GIRI FOUNDATION BARU');
});

test('publication cache invalidates after editorial content changes', function () {
    $this->seed(GiriFoundationSeeder::class);

    $publication = Content::query()
        ->published()
        ->editorialPublications()
        ->firstOrFail();

    $this->get('/publikasi')
        ->assertSuccessful()
        ->assertSee($publication->title);

    $publication->update([
        'title' => 'Berita kolaborasi telah diperbarui.',
    ]);

    $this->get('/publikasi')
        ->assertSuccessful()
        ->assertSee('Berita kolaborasi telah diperbarui.');
});
