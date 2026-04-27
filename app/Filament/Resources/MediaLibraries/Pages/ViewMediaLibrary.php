<?php

namespace App\Filament\Resources\MediaLibraries\Pages;

use App\Filament\Resources\MediaLibraries\MediaLibraryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMediaLibrary extends ViewRecord
{
    protected static string $resource = MediaLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
