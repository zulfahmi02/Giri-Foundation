<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Support\TeamMemberStructureSlots;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeamMemberInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('structure_slot')
                    ->label('Slot Struktur')
                    ->formatStateUsing(fn (?string $state): string => TeamMemberStructureSlots::label($state) ?? '-'),
                TextEntry::make('position'),
                TextEntry::make('parent.name')
                    ->label('Atasan')
                    ->placeholder('-'),
                TextEntry::make('divisionRecord.name')
                    ->label('Divisi')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('photo_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('linkedin_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort_order')
                    ->numeric(),
                IconEntry::make('is_structural')
                    ->label('Struktur')
                    ->boolean(),
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
