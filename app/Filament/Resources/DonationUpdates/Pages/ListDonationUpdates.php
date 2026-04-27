<?php

namespace App\Filament\Resources\DonationUpdates\Pages;

use App\Filament\Resources\DonationUpdates\DonationUpdateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDonationUpdates extends ListRecords
{
    protected static string $resource = DonationUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
