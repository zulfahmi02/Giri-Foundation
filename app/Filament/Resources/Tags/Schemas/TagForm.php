<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TagForm
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
            ]);
    }
}
