<?php

namespace App\Filament\Resources\OrganizationProfiles\Pages;

use App\Filament\Resources\OrganizationProfiles\OrganizationProfileResource;
use App\Models\OrganizationProfile;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationProfiles extends ListRecords
{
    protected static string $resource = OrganizationProfileResource::class;

    protected function getHeaderActions(): array
    {
        $existingProfile = OrganizationProfile::query()
            ->oldest('id')
            ->first();

        if ($existingProfile) {
            return [
                Action::make('editPrimaryOrganizationProfile')
                    ->label('Edit Profil Yayasan')
                    ->icon('heroicon-o-pencil-square')
                    ->url(OrganizationProfileResource::getUrl('edit', ['record' => $existingProfile])),
            ];
        }

        return [
            CreateAction::make()
                ->label('Buat Profil Yayasan'),
        ];
    }
}
