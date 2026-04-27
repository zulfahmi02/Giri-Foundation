<?php

namespace App\Filament\Resources\Donations\Schemas;

use App\Support\AdminStateOptions;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DonationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('campaign.title')
                    ->label('Campaign')
                    ->placeholder('-'),
                TextEntry::make('donor.id')
                    ->label('Donor')
                    ->placeholder('-'),
                TextEntry::make('invoice_number'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('payment_method'),
                TextEntry::make('payment_channel')
                    ->placeholder('-'),
                TextEntry::make('payment_status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(AdminStateOptions::donationPaymentStatuses(), $state)),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('proof_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('external_transaction_id')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
