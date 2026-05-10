<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Closure;
use Filament\Forms\Components\DateTimePicker;
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
                TextInput::make('file_url')
                    ->required()
                    ->helperText('Gunakan URL lengkap atau path file publik seperti documents/arsip-dokumen.pdf.')
                    ->rules([
                        static function (string $attribute, mixed $value, Closure $fail): void {
                            $fileReference = trim((string) $value);

                            if ($fileReference === '' || $fileReference === '#') {
                                $fail('Masukkan URL lengkap atau path file publik yang valid.');

                                return;
                            }

                            if (filter_var($fileReference, FILTER_VALIDATE_URL) !== false) {
                                return;
                            }

                            if (preg_match('/^\/?(?:storage\/)?[A-Za-z0-9][A-Za-z0-9_\/.\-]*$/', $fileReference) === 1) {
                                return;
                            }

                            $fail('Gunakan URL lengkap atau path file publik seperti documents/arsip-dokumen.pdf.');
                        },
                    ])
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
