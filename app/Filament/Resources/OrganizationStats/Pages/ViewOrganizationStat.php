<?php

namespace App\Filament\Resources\OrganizationStats\Pages;

use App\Filament\Resources\OrganizationStats\OrganizationStatResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganizationStat extends ViewRecord
{
    protected static string $resource = OrganizationStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
