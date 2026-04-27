<?php

namespace App\Filament\Resources\Consultations\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ConsultationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('subject')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                Select::make('preferred_contact_channel')
                    ->required()
                    ->options([
                        'email' => 'Email',
                        'phone' => 'Telepon',
                        'whatsapp' => 'WhatsApp',
                    ])
                    ->default('email'),
                Select::make('status')
                    ->required()
                    ->options(AdminStateOptions::consultationStatuses())
                    ->default('new'),
                Select::make('assigned_to')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
