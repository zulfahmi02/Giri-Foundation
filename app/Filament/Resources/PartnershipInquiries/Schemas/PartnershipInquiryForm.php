<?php

namespace App\Filament\Resources\PartnershipInquiries\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PartnershipInquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('organization_name')
                    ->label('Nama organisasi / lembaga')
                    ->required(),
                TextInput::make('contact_person')
                    ->label('Nama penanggung jawab')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor telepon')
                    ->tel(),
                TextInput::make('inquiry_type')
                    ->label('Jenis kemitraan yang diminati')
                    ->required(),
                Textarea::make('message')
                    ->label('Pesan / deskripsi kebutuhan')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status penanganan')
                    ->required()
                    ->options(AdminStateOptions::partnershipInquiryStatuses())
                    ->default('new')
                    ->helperText('Ubah status setelah permintaan kemitraan ditindaklanjuti.'),
            ]);
    }
}
