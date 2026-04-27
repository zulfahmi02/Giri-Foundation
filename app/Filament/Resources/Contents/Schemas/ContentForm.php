<?php

namespace App\Filament\Resources\Contents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('type')
                    ->required()
                    ->options([
                        'story' => 'Cerita',
                        'journal' => 'Jurnal',
                        'news' => 'Berita',
                        'article' => 'Artikel',
                        'opinion' => 'Opini',
                        'report' => 'Laporan',
                    ])
                    ->default('story'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('featured_image_url')
                    ->columnSpanFull(),
                Select::make('author_id')
                    ->relationship('author', 'name'),
                Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Terbit',
                        'archived' => 'Arsip',
                    ])
                    ->default('draft'),
                Toggle::make('is_featured')
                    ->required()
                    ->default(false),
                DateTimePicker::make('published_at'),
                TextInput::make('seo_title'),
                Textarea::make('seo_description')
                    ->columnSpanFull(),
            ]);
    }
}
