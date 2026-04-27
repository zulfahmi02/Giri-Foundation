<?php

namespace App\Filament\Resources\OrganizationStats\Pages;

use App\Filament\Resources\OrganizationStats\OrganizationStatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationStat extends EditRecord
{
    protected static string $resource = OrganizationStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
