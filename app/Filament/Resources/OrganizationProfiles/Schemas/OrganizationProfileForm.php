<?php

namespace App\Filament\Resources\OrganizationProfiles\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrganizationProfileForm
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
                    ->label('Telepon')
                    ->tel(),
                TextInput::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->tel(),
                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
                Textarea::make('google_maps_embed')
                    ->label('URL / Embed Google Maps')
                    ->helperText('Tempel URL Google Maps, kode iframe embed, atau alamat lengkap. Field ini dipakai untuk peta lokasi di website.')
                    ->columnSpanFull(),
                FilamentImageUpload::make('logo_url', 'organization', 'Logo organisasi'),
                FilamentImageUpload::make('favicon_url', 'organization', 'Favicon'),
            ]);
    }
}
