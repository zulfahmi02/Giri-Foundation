<?php

namespace App\Filament\Resources\OrganizationProfiles\Schemas;

use App\Models\OrganizationProfile;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrganizationProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('short_description')
                    ->columnSpanFull(),
                TextEntry::make('full_description')
                    ->columnSpanFull(),
                TextEntry::make('vision')
                    ->columnSpanFull(),
                TextEntry::make('mission')
                    ->columnSpanFull(),
                TextEntry::make('values')
                    ->columnSpanFull(),
                TextEntry::make('founded_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('whatsapp_number')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('google_maps_embed')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('logo_url')
                    ->getStateUsing(fn (OrganizationProfile $record): ?string => $record->resolvedLogoUrl())
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('favicon_url')
                    ->getStateUsing(fn (OrganizationProfile $record): ?string => $record->resolvedFaviconUrl())
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
