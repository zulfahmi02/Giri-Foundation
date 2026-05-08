<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('name')
                        ->required(),
                ),
                FilamentSlugGenerator::field('name'),
                FilamentImageUpload::make('logo_url', 'partners', 'Logo partner'),
                Textarea::make('website_url')
                    ->columnSpanFull(),
                TextInput::make('type')
                    ->required()
                    ->default('ngo'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
