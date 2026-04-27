<?php

namespace App\Support;

use App\Models\OrganizationProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AdminNotificationRecipients
{
    /**
     * @return Collection<int, User>
     */
    public static function forPublicSubmissions(): Collection
    {
        return User::query()
            ->where('status', 'active')
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['Admin', 'Editor']))
            ->get();
    }

    public static function fallbackEmail(): ?string
    {
        return OrganizationProfile::query()->value('email');
    }
}
