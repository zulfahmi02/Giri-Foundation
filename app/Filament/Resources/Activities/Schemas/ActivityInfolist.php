<?php

namespace App\Filament\Resources\Activities\Schemas;

use App\Models\Activity;
use App\Models\ActivityGallery;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('program.title')
                    ->label('Program')
                    ->placeholder('Tidak terkait program'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('summary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('activity_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('location_name')
                    ->placeholder('-'),
                ImageEntry::make('featured_image_url')
                    ->getStateUsing(fn (Activity $record): ?string => $record->resolvedFeaturedImageUrl())
                    ->placeholder('-')
                    ->columnSpanFull(),
                RepeatableEntry::make('galleries')
                    ->label('Galeri foto')
                    ->schema([
                        ImageEntry::make('file_url')
                            ->label('Foto')
                            ->getStateUsing(fn (ActivityGallery $record): string => $record->resolvedFileUrl()),
                        TextEntry::make('caption')
                            ->label('Keterangan')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                RepeatableEntry::make('videos')
                    ->label('Video')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Judul')
                            ->placeholder('-'),
                        TextEntry::make('youtube_url')
                            ->label('URL YouTube')
                            ->url(fn (string $state): string => $state)
                            ->openUrlInNewTab(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                TextEntry::make('status'),
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
            ]);
    }
}
