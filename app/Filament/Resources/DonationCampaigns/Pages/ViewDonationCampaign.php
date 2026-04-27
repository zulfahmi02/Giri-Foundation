<?php

namespace App\Filament\Resources\DonationCampaigns\Pages;

use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDonationCampaign extends ViewRecord
{
    protected static string $resource = DonationCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
