<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Models\Program;
use App\Support\AdminStateOptions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('excerpt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('category.name')
                    ->label('Category')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(AdminStateOptions::programStatuses(), $state)),
                TextEntry::make('phase')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(AdminStateOptions::programPhases(), $state)),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('location_name')
                    ->placeholder('-'),
                TextEntry::make('province')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->placeholder('-'),
                TextEntry::make('target_beneficiaries')
                    ->placeholder('-'),
                TextEntry::make('beneficiaries_count')
                    ->numeric(),
                TextEntry::make('budget_amount')
                    ->numeric()
                    ->placeholder('-'),
                ImageEntry::make('featured_image_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('partners.name')
                    ->badge()
                    ->separator(',')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Program $record): bool => $record->trashed()),
            ]);
    }
}
