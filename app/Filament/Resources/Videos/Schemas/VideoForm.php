<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoForm
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
                Textarea::make('summary')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(6)
                    ->columnSpanFull(),
                TextInput::make('youtube_url')
                    ->label('YouTube URL')
                    ->url()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('thumbnail_url')
                    ->url()
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                        'archived' => 'Arsip',
                    ])
                    ->default('draft'),
                DateTimePicker::make('published_at'),
                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
