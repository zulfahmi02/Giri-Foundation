<?php

namespace App\Filament\Resources\PartnershipInquiries\Pages;

use App\Filament\Resources\PartnershipInquiries\PartnershipInquiryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartnershipInquiry extends EditRecord
{
    protected static string $resource = PartnershipInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
