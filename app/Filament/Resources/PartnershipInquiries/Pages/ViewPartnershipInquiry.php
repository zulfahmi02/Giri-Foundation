<?php

namespace App\Filament\Resources\PartnershipInquiries\Pages;

use App\Filament\Resources\PartnershipInquiries\PartnershipInquiryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnershipInquiry extends ViewRecord
{
    protected static string $resource = PartnershipInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
