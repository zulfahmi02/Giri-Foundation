<?php

use App\Models\Page;
use Database\Seeders\GiriFoundationSeeder;

test('public pages render page managed content', function (string $slug, string $uri, array $updates, array $expectedTexts) {
    $this->seed(GiriFoundationSeeder::class);

    $page = Page::query()->where('slug', $slug)->firstOrFail();
    $page->update($updates);

    $response = $this->get($uri)->assertSuccessful();

    foreach ($expectedTexts as $expectedText) {
        $response->assertSee($expectedText);
    }
})->with([
    'home' => [
        'home',
        '/',
        [
            'hero_data' => ['title_prefix' => 'Menumbuhkan arsip harapan'],
            'section_data' => [
                'programs' => ['title' => 'Program yang menggerakkan perubahan.'],
                'closing' => ['title' => 'Mari buka percakapan yang lebih jauh.'],
            ],
        ],
        ['Menumbuhkan arsip harapan', 'Program yang menggerakkan perubahan.', 'Mari buka percakapan yang lebih jauh.'],
    ],
    'about' => [
        'about',
        '/about',
        [
            'section_data' => [
                'brand' => ['subtitle' => 'Lembaga komunitas dengan kerja sosial, budaya, dan keberlanjutan yang terarsip rapi.'],
                'personnel' => ['title' => 'Orang-orang inti yang bekerja di garis depan.'],
            ],
        ],
        ['Lembaga komunitas dengan kerja sosial, budaya, dan keberlanjutan yang terarsip rapi.', 'Orang-orang inti yang bekerja di garis depan.'],
    ],
    'programs' => [
        'programs',
        '/programs',
        [
            'section_data' => [
                'partnership' => ['title' => 'Program aktif bersama mitra pilihan.'],
                'upcoming' => ['title' => 'Agenda perluasan pemberdayaan berikutnya.'],
            ],
        ],
        ['Program aktif bersama mitra pilihan.', 'Agenda perluasan pemberdayaan berikutnya.'],
    ],
    'media' => [
        'media',
        '/media',
        [
            'hero_data' => ['title_prefix' => 'Jejak'],
            'section_data' => ['videos' => ['title' => 'Video terbaru dari kanal resmi kami.']],
        ],
        ['Jejak', 'Video terbaru dari kanal resmi kami.'],
    ],
    'publikasi' => [
        'publikasi',
        '/publikasi',
        [
            'hero_data' => ['title_prefix' => 'Pustaka, arsip, dan'],
            'section_data' => ['opinions' => ['title' => 'Ruang refleksi dan sudut pandang editorial.']],
        ],
        ['Pustaka, arsip, dan', 'Ruang refleksi dan sudut pandang editorial.'],
    ],
    'stories' => [
        'stories',
        '/stories',
        [
            'hero_data' => ['kicker' => 'Sorotan Editorial'],
            'section_data' => ['newsletter' => ['title' => 'Dapatkan catatan lapangan terbaru.']],
        ],
        ['Sorotan Editorial', 'Dapatkan catatan lapangan terbaru.'],
    ],
    'contact' => [
        'contact',
        '/contact',
        [
            'hero_data' => ['body' => 'Tim kami siap membahas kunjungan, media, dan kolaborasi lintas program.'],
            'section_data' => ['form' => ['title' => 'Sampaikan pesan langsung kepada tim inti kami.']],
        ],
        ['Tim kami siap membahas kunjungan, media, dan kolaborasi lintas program.', 'Sampaikan pesan langsung kepada tim inti kami.'],
    ],
    'donate' => [
        'donate',
        '/donate',
        [
            'section_data' => ['documents' => ['title' => 'Referensi Program Pendanaan']],
        ],
        ['Referensi Program Pendanaan'],
    ],
    'resources' => [
        'resources',
        '/resources',
        [
            'hero_data' => ['title_prefix' => 'Pustaka &'],
            'section_data' => ['filters' => ['submit_label' => 'Temukan Dokumen']],
        ],
        ['Pustaka &', 'Temukan Dokumen'],
    ],
    'partners' => [
        'partners',
        '/partners',
        [
            'section_data' => [
                'highlight' => ['label' => 'Jaringan Kolaborator'],
                'inquiry' => ['title' => 'Bangun kemitraan yang bergerak bersama komunitas.'],
            ],
        ],
        ['Jaringan Kolaborator', 'Bangun kemitraan yang bergerak bersama komunitas.'],
    ],
]);
