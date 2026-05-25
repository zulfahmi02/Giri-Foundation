<?php

namespace App\Filament\Resources\Contents\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->label('Judul')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                Select::make('type')
                    ->label('Jenis konten')
                    ->required()
                    ->options([
                        'story' => 'Cerita',
                        'journal' => 'Jurnal',
                        'news' => 'Berita',
                        'article' => 'Artikel',
                        'opinion' => 'Opini',
                        'report' => 'Laporan',
                    ])
                    ->default('story')
                    ->helperText('Pilih jenis tulisan. Untuk PDF atau dokumen unduhan, gunakan menu Dokumen Unduhan.'),
                Select::make('category_id')
                    ->label('Kategori konten')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Kategori membantu pengunjung memahami topik tulisan.'),
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->helperText('Ringkasan pendek yang muncul di kartu konten dan daftar publikasi.')
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->label('Isi tulisan')
                    ->required()
                    ->columnSpanFull(),
                FilamentImageUpload::make('featured_image_url', 'contents', 'Gambar sampul')
                    ->helperText('Dipakai sebagai thumbnail/kartu Cerita & Artikel. Bukan untuk upload PDF atau dokumen unduhan.'),
                Select::make('author_id')
                    ->label('Penulis')
                    ->relationship('author', 'name'),
                Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                        'archived' => 'Arsip',
                    ])
                    ->default('draft')
                    ->helperText('Konten tampil di website hanya saat status Terbit dan tanggal publikasi terisi.'),
                Toggle::make('is_featured')
                    ->label('Tampilkan sebagai unggulan')
                    ->required()
                    ->default(false),
                DateTimePicker::make('published_at')
                    ->label('Tanggal publikasi')
                    ->helperText('Isi tanggal ini saat konten siap ditampilkan di website.'),
                TextInput::make('seo_title')
                    ->label('Judul SEO')
                    ->helperText('Judul untuk Google dan preview saat link dibagikan. Boleh sama dengan judul tulisan.'),
                Textarea::make('seo_description')
                    ->label('Deskripsi SEO')
                    ->helperText('Ringkasan 1 kalimat untuk Google dan preview share. Tidak selalu tampil di halaman.')
                    ->columnSpanFull(),
            ]);
    }
}
