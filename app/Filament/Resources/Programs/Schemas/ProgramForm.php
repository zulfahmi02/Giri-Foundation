<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Support\AdminStateOptions;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProgramForm
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
                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->required()
                    ->options(AdminStateOptions::programStatuses())
                    ->default('draft'),
                Select::make('phase')
                    ->required()
                    ->options(AdminStateOptions::programPhases())
                    ->default('active'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                TextInput::make('location_name'),
                TextInput::make('province'),
                TextInput::make('city'),
                TextInput::make('target_beneficiaries'),
                TextInput::make('beneficiaries_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('budget_amount')
                    ->numeric(),
                TextInput::make('featured_image_url')
                    ->url()
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->required()
                    ->default(false),
                DateTimePicker::make('published_at'),
                Select::make('partners')
                    ->relationship('partners', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
