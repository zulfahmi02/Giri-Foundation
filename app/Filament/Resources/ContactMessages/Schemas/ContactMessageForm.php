<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama pengirim')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor telepon')
                    ->tel(),
                TextInput::make('subject')
                    ->label('Subjek pesan')
                    ->required(),
                Textarea::make('message')
                    ->label('Isi pesan')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status penanganan')
                    ->required()
                    ->options(AdminStateOptions::contactMessageStatuses())
                    ->default('new')
                    ->helperText('Ubah status setelah pesan ditindaklanjuti.'),
            ]);
    }
}
