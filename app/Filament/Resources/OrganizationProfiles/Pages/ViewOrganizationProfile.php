<?php

namespace App\Filament\Resources\OrganizationProfiles\Pages;

use App\Filament\Resources\OrganizationProfiles\OrganizationProfileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganizationProfile extends ViewRecord
{
    protected static string $resource = OrganizationProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
