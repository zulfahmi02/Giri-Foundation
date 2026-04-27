<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key_name')
                    ->required(),
                Textarea::make('value_text')
                    ->columnSpanFull(),
                Select::make('value_type')
                    ->required()
                    ->options([
                        'text' => 'Teks',
                        'number' => 'Angka',
                        'boolean' => 'Boolean',
                        'json' => 'JSON',
                    ])
                    ->default('text'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
