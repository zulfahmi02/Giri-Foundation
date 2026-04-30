<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Support\FilamentSlugGenerator;
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
                TextInput::make('slug')
                    ->required(),
                TextInput::make('category'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('file_url')
                    ->required()
                    ->url()
                    ->columnSpanFull(),
                TextInput::make('thumbnail_url')
                    ->url()
                    ->columnSpanFull(),
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
