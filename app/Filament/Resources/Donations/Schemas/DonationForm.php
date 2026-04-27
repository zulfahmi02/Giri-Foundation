<?php

namespace App\Filament\Resources\Donations\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->relationship('campaign', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('donor_id')
                    ->relationship('donor', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('invoice_number')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->required()
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'cash' => 'Tunai',
                    ]),
                TextInput::make('payment_channel'),
                Select::make('payment_status')
                    ->required()
                    ->options(AdminStateOptions::donationPaymentStatuses())
                    ->default('pending'),
                DateTimePicker::make('paid_at'),
                Textarea::make('message')
                    ->columnSpanFull(),
                TextInput::make('proof_url')
                    ->url()
                    ->columnSpanFull(),
                TextInput::make('external_transaction_id'),
            ]);
    }
}
