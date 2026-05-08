<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Page;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Buka Halaman')
                ->url(fn (Page $record): ?string => $record->frontendUrl(), shouldOpenInNewTab: true)
                ->visible(fn (Page $record): bool => filled($record->frontendUrl())),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
