<?php

namespace App\Filament\Resources\Activities\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'title')
                    ->searchable()
                    ->preload()
                    ->placeholder('Tidak terkait program')
                    ->helperText('Opsional. Pilih program jika aktivitas ini merupakan bagian dari program tertentu.'),
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->label('Judul kegiatan')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                Textarea::make('summary')
                    ->label('Ringkasan')
                    ->helperText('Ringkasan singkat yang muncul di kartu kegiatan dan daftar media.')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi lengkap')
                    ->helperText('Narasi lengkap kegiatan yang tampil di halaman detail.')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('activity_date')
                    ->label('Tanggal kegiatan'),
                TextInput::make('location_name')
                    ->label('Nama lokasi')
                    ->helperText('Contoh: Aula Desa Sukamaju, Kecamatan Cibogo.'),
                FilamentImageUpload::make('featured_image_url', 'activities', 'Foto kegiatan')
                    ->helperText('Foto utama yang muncul sebagai thumbnail kegiatan.'),
                Repeater::make('galleries')
                    ->label('Galeri foto')
                    ->relationship()
                    ->schema([
                        FilamentImageUpload::make('file_url', 'activities/gallery', 'Foto')
                            ->required(),
                        TextInput::make('caption')
                            ->label('Keterangan foto')
                            ->maxLength(255),
                    ])
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): string => $state['caption'] ?? 'Foto aktivitas')
                    ->addActionLabel('Tambah foto')
                    ->helperText('Tambahkan beberapa foto dan atur urutannya dengan menarik setiap item.')
                    ->columnSpanFull(),
                Repeater::make('videos')
                    ->label('Video aktivitas')
                    ->relationship()
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul video')
                            ->maxLength(255),
                        TextInput::make('youtube_url')
                            ->label('URL YouTube')
                            ->url()
                            ->required()
                            ->helperText('Gunakan URL video YouTube, YouTube Shorts, atau YouTube Live.')
                            ->columnSpanFull(),
                    ])
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): string => $state['title'] ?? 'Video aktivitas')
                    ->addActionLabel('Tambah video')
                    ->helperText('Tambahkan beberapa video YouTube dan atur urutannya.')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                        'archived' => 'Arsip',
                    ])
                    ->default('draft')
                    ->helperText('Kegiatan tampil di website hanya saat status Terbit dan tanggal publikasi terisi.'),
                DateTimePicker::make('published_at')
                    ->label('Tanggal publikasi')
                    ->helperText('Isi tanggal ini saat kegiatan siap ditampilkan di website.'),
                Select::make('created_by')
                    ->label('Pengelola')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
