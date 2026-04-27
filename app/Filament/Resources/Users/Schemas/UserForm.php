<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
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
                    ->required()
                    ->unique(ignoreRecord: true),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->autocomplete('new-password'),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('avatar_url')
                    ->columnSpanFull(),
                Select::make('status')
                    ->required()
                    ->options(AdminStateOptions::userStatuses())
                    ->default('active'),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
