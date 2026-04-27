<?php

namespace App\Filament\Resources\OrganizationStats\Pages;

use App\Filament\Resources\OrganizationStats\OrganizationStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationStats extends ListRecords
{
    protected static string $resource = OrganizationStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
