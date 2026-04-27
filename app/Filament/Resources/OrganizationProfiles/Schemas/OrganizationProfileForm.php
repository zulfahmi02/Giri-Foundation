<?php

namespace App\Filament\Resources\OrganizationProfiles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrganizationProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('short_description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('full_description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('vision')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('mission')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('values')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('founded_date'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('whatsapp_number'),
                Textarea::make('address')
                    ->columnSpanFull(),
                Textarea::make('google_maps_embed')
                    ->columnSpanFull(),
                Textarea::make('logo_url')
                    ->columnSpanFull(),
                Textarea::make('favicon_url')
                    ->columnSpanFull(),
            ]);
    }
}
