<?php

namespace App\Filament\Resources\DonationCampaigns\Schemas;

use App\Support\AdminStateOptions;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->required(),
                ),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('short_description')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('target_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('collected_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                TextInput::make('banner_image_url')
                    ->url()
                    ->columnSpanFull(),
                Select::make('status')
                    ->required()
                    ->options(AdminStateOptions::donationCampaignStatuses())
                    ->default('draft'),
                Toggle::make('is_featured')
                    ->required()
                    ->default(false),
                Select::make('published_by')
                    ->relationship('publisher', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
