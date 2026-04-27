<?php

namespace App\Filament\Clusters\Editorial;

use App\Models\Content;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class EditorialCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Editorial';

    protected static ?int $navigationSort = 30;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        $draftContents = Content::query()
            ->where('status', 'draft')
            ->count();

        return $draftContents > 0 ? (string) $draftContents : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
