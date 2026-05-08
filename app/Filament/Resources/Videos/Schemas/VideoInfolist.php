<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\Video;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('summary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('youtube_url')
                    ->label('YouTube URL')
                    ->columnSpanFull(),
                ImageEntry::make('thumbnail_url')
                    ->getStateUsing(fn (Video $record): ?string => $record->resolvedThumbnailUrl())
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('creator.name')
                    ->placeholder('-'),
            ]);
    }
}
