<?php

namespace App\Filament\Clusters\System;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class SystemCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Administrasi';

    protected static ?int $navigationSort = 80;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
}
