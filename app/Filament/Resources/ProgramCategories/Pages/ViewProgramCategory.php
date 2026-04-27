<?php

namespace App\Filament\Resources\ProgramCategories\Pages;

use App\Filament\Resources\ProgramCategories\ProgramCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgramCategory extends ViewRecord
{
    protected static string $resource = ProgramCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
