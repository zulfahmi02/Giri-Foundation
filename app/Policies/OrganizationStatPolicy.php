<?php

namespace App\Policies;

use App\Models\OrganizationStat;
use App\Models\User;

class OrganizationStatPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->status !== 'active') {
            return false;
        }

        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function delete(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    public function restore(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }

    public function replicate(User $user, OrganizationStat $organizationStat): bool
    {
        return false;
    }

    public function reorder(User $user): bool
    {
        return false;
    }
}
