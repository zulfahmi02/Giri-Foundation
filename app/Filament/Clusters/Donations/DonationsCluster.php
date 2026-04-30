<?php

namespace App\Filament\Clusters\Donations;

use App\Models\Donation;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class DonationsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Donasi';

    protected static ?int $navigationSort = 70;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        $pendingDonations = Donation::query()
            ->where('payment_status', 'pending')
            ->count();

        return $pendingDonations > 0 ? (string) $pendingDonations : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
