<?php

namespace App\Filament\Clusters\Programs;

use App\Models\Program;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class ProgramsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $navigationLabel = 'Program & Mitra';

    protected static ?int $navigationSort = 20;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        $activePrograms = Program::query()
            ->where('status', 'published')
            ->where('phase', 'active')
            ->count();

        return $activePrograms > 0 ? (string) $activePrograms : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
