<?php

namespace App\Filament\Resources\DonationUpdates\Pages;

use App\Filament\Resources\DonationUpdates\DonationUpdateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDonationUpdate extends EditRecord
{
    protected static string $resource = DonationUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
