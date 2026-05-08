<?php

namespace App\Filament\Resources\DonationUpdates\Schemas;

use App\Support\FilamentImageUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DonationUpdateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->relationship('campaign', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                FilamentImageUpload::make('image_url', 'donation-updates', 'Gambar update'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
