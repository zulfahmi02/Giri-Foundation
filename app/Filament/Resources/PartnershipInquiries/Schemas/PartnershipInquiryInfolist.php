<?php

namespace App\Filament\Resources\PartnershipInquiries\Schemas;

use App\Support\AdminStateOptions;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PartnershipInquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('organization_name'),
                TextEntry::make('contact_person'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('inquiry_type'),
                TextEntry::make('message')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(AdminStateOptions::partnershipInquiryStatuses(), $state)),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
