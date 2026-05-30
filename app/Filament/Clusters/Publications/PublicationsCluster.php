<?php

namespace App\Filament\Clusters\Publications;

use App\Models\Content;
use BackedEnum;
use Illuminate\Support\Facades\Cache;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class PublicationsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Publikasi';

    protected static ?int $navigationSort = 40;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        $draftContents = Cache::remember('nav_badge_contents_draft', 60, fn () => Content::query()
            ->where('status', 'draft')
            ->count());

        return $draftContents > 0 ? (string) $draftContents : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
