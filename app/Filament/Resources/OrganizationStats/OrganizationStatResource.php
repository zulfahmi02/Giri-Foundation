<?php

namespace App\Filament\Resources\OrganizationStats;

use App\Filament\Resources\OrganizationStats\Pages\CreateOrganizationStat;
use App\Filament\Resources\OrganizationStats\Pages\EditOrganizationStat;
use App\Filament\Resources\OrganizationStats\Pages\ListOrganizationStats;
use App\Filament\Resources\OrganizationStats\Pages\ViewOrganizationStat;
use App\Filament\Resources\OrganizationStats\Schemas\OrganizationStatForm;
use App\Filament\Resources\OrganizationStats\Schemas\OrganizationStatInfolist;
use App\Filament\Resources\OrganizationStats\Tables\OrganizationStatsTable;
use App\Models\OrganizationStat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrganizationStatResource extends Resource
{
    protected static ?string $model = OrganizationStat::class;

    protected static ?string $cluster = \App\Filament\Clusters\Website\WebsiteCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Statistik Dampak';

    protected static ?string $modelLabel = 'statistik dampak';

    protected static ?string $pluralModelLabel = 'Statistik Dampak';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return OrganizationStatForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrganizationStatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationStatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizationStats::route('/'),
            'create' => CreateOrganizationStat::route('/create'),
            'view' => ViewOrganizationStat::route('/{record}'),
            'edit' => EditOrganizationStat::route('/{record}/edit'),
        ];
    }
}
