<?php

namespace App\Filament\Resources\DonationUpdates\Pages;

use App\Filament\Resources\DonationUpdates\DonationUpdateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDonationUpdate extends ViewRecord
{
    protected static string $resource = DonationUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
