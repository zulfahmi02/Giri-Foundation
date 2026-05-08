<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Models\Partner;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PartnerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                ImageEntry::make('logo_url')
                    ->getStateUsing(fn (Partner $record): ?string => $record->resolvedLogoUrl())
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('website_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('type'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
