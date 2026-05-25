<?php

namespace App\Filament\Resources\Activities\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
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
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih program yang menjadi induk kegiatan ini.'),
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
