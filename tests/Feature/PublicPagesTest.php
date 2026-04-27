<?php

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
