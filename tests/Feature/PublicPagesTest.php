<?php

use App\Models\DonationCampaign;
use App\Support\FrontendCache;
use Database\Seeders\GiriFoundationSeeder;

test('public pages render successfully', function (string $uri, string $visibleText) {
    $this->seed(GiriFoundationSeeder::class);

    $this->get($uri)
        ->assertSuccessful()
        ->assertSee($visibleText);
})->with([
    ['/', 'Terhubung Dengan Kami'],
    ['/about', 'Organ yayasan sesuai Anggaran Dasar.'],
    ['/programs', 'Program Kerja Sama'],
    ['/media', 'Dokumentasi kegiatan yayasan.'],
    ['/publikasi', 'Naskah dan ringkasan kelembagaan.'],
    ['/stories', 'Lebih Banyak Dari Arsip'],
    ['/contact', 'Kirim Pesan'],
    ['/consultation', 'Preferensi Kontak'],
    ['/donate', 'Catat Niat Donasi'],
    ['/resources', 'Saring Dokumen'],
    ['/partners', 'Mulai percakapan kemitraan.'],
]);

test('site layout uses the bundled fallback logo and favicon', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('image/logo.png', false)
        ->assertSee('rel="icon"', false);
});

test('core public pages still render without seeded content', function (string $uri, string $visibleText) {
    $this->get($uri)
        ->assertSuccessful()
        ->assertSee($visibleText);
})->with([
    ['/', 'Terhubung Dengan Kami'],
    ['/about', 'Profil organisasi sedang dilengkapi.'],
    ['/donate', 'Kampanye donasi belum tersedia'],
]);

test('donate page falls back to the bundled logo when the campaign banner file is missing', function () {
    DonationCampaign::query()->create([
        'title' => 'Banner Hilang',
        'slug' => 'banner-hilang',
        'short_description' => 'Kampanye dengan banner yang hilang di storage.',
        'description' => 'Frontend harus tetap stabil saat file banner tidak ditemukan.',
        'target_amount' => 1000000,
        'collected_amount' => 125000,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDays(30),
        'banner_image_url' => 'donation-campaigns/missing-banner.jpg',
        'status' => 'active',
        'is_featured' => true,
    ]);

    FrontendCache::bump(FrontendCache::DonatePage);

    $this->get('/donate')
        ->assertSuccessful()
        ->assertSee('src="/image/logo.png"', false)
        ->assertDontSee('src="/storage/donation-campaigns/missing-banner.jpg"', false);
});
