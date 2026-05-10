<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                TextInput::make('category'),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('file_url')
                    ->label('Berkas dokumen')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'text/csv',
                    ])
                    ->disk('public')
                    ->directory('documents')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->requiredWithout('external_file_url')
                    ->helperText('Unggah dokumen dari perangkat bila berkas disimpan di server ini.')
                    ->columnSpanFull(),
                TextInput::make('external_file_url')
                    ->label('URL eksternal dokumen')
                    ->url()
                    ->requiredWithout('file_url')
                    ->helperText('Opsional. Isi jika dokumen berada di luar server ini.')
                    ->columnSpanFull(),
                FilamentImageUpload::make('thumbnail_url', 'documents/thumbnails', 'Gambar thumbnail'),
                TextInput::make('file_type'),
                TextInput::make('file_size')
                    ->numeric(),
                TextInput::make('download_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_public')
                    ->required()
                    ->default(true),
                DateTimePicker::make('published_at'),
                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
