<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('content')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('hero_data')
                    ->label('Hero')
                    ->formatStateUsing(fn (mixed $state): string => self::formatStructuredState($state))
                    ->columnSpanFull(),
                TextEntry::make('section_data')
                    ->label('Seksi')
                    ->formatStateUsing(fn (mixed $state): string => self::formatStructuredState($state))
                    ->columnSpanFull(),
                TextEntry::make('template')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('seo_title')
                    ->placeholder('-'),
                TextEntry::make('seo_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->label('Dibuat oleh')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    private static function formatStructuredState(mixed $state): string
    {
        if (blank($state)) {
            return '-';
        }

        if (is_array($state)) {
            return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-';
        }

        if (is_string($state)) {
            $decodedState = json_decode($state, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedState)) {
                return json_encode($decodedState, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-';
            }

            return $state;
        }

        return (string) $state;
    }
}
