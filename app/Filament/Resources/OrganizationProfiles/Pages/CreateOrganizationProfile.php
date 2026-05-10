<?php

namespace App\Filament\Resources\OrganizationProfiles\Pages;

use App\Filament\Resources\OrganizationProfiles\OrganizationProfileResource;
use App\Models\OrganizationProfile;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationProfile extends CreateRecord
{
    protected static string $resource = OrganizationProfileResource::class;

    public function mount(): void
    {
        $existingProfile = OrganizationProfile::query()
            ->oldest('id')
            ->first();

        if ($existingProfile) {
            $this->redirect(
                OrganizationProfileResource::getUrl('edit', ['record' => $existingProfile]),
            );

            return;
        }

        parent::mount();
    }
}
