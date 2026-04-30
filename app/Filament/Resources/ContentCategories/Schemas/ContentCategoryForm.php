<?php

namespace App\Filament\Resources\ContentCategories\Schemas;

use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('name')
                        ->required(),
                ),
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
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
