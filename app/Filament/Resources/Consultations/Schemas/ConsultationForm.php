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
                    ->label('Nama pemohon')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor telepon')
                    ->tel(),
                TextInput::make('subject')
                    ->label('Topik konsultasi')
                    ->required(),
                Textarea::make('message')
                    ->label('Pertanyaan / uraian')
                    ->required()
                    ->columnSpanFull(),
                Select::make('preferred_contact_channel')
                    ->label('Preferensi kontak balik')
                    ->required()
                    ->options([
                        'email' => 'Email',
                        'phone' => 'Telepon',
                        'whatsapp' => 'WhatsApp',
                    ])
                    ->default('email')
                    ->helperText('Cara yang diinginkan pemohon untuk dihubungi kembali.'),
                Select::make('status')
                    ->label('Status penanganan')
                    ->required()
                    ->options(AdminStateOptions::consultationStatuses())
                    ->default('new')
                    ->helperText('Ubah status setelah konsultasi ditindaklanjuti.'),
                Select::make('assigned_to')
                    ->label('Ditangani oleh')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
