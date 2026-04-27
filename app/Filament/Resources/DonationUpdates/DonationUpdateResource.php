<?php

namespace App\Filament\Resources\DonationUpdates;

use App\Filament\Resources\DonationUpdates\Pages\CreateDonationUpdate;
use App\Filament\Resources\DonationUpdates\Pages\EditDonationUpdate;
use App\Filament\Resources\DonationUpdates\Pages\ListDonationUpdates;
use App\Filament\Resources\DonationUpdates\Pages\ViewDonationUpdate;
use App\Filament\Resources\DonationUpdates\Schemas\DonationUpdateForm;
use App\Filament\Resources\DonationUpdates\Schemas\DonationUpdateInfolist;
use App\Filament\Resources\DonationUpdates\Tables\DonationUpdatesTable;
use App\Models\DonationUpdate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DonationUpdateResource extends Resource
{
    protected static ?string $model = DonationUpdate::class;

    protected static ?string $cluster = \App\Filament\Clusters\Donations\DonationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Donasi';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Update Kampanye';

    protected static ?string $modelLabel = 'update kampanye';

    protected static ?string $pluralModelLabel = 'Update Kampanye';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DonationUpdateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DonationUpdateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationUpdatesTable::configure($table);
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
            'index' => ListDonationUpdates::route('/'),
            'create' => CreateDonationUpdate::route('/create'),
            'view' => ViewDonationUpdate::route('/{record}'),
            'edit' => EditDonationUpdate::route('/{record}/edit'),
        ];
    }
}
