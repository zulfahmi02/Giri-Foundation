<?php

namespace App\Filament\Resources\ProgramCategories\Schemas;

use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProgramCategoryForm
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
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
