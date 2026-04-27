<?php

namespace App\Filament\Resources\Donors;

use App\Filament\Resources\Donors\Pages\CreateDonor;
use App\Filament\Resources\Donors\Pages\EditDonor;
use App\Filament\Resources\Donors\Pages\ListDonors;
use App\Filament\Resources\Donors\Pages\ViewDonor;
use App\Filament\Resources\Donors\Schemas\DonorForm;
use App\Filament\Resources\Donors\Schemas\DonorInfolist;
use App\Filament\Resources\Donors\Tables\DonorsTable;
use App\Models\Donor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DonorResource extends Resource
{
    protected static ?string $model = Donor::class;

    protected static ?string $cluster = \App\Filament\Clusters\Donations\DonationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Donasi';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Donatur';

    protected static ?string $modelLabel = 'donatur';

    protected static ?string $pluralModelLabel = 'Donatur';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return DonorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DonorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonorsTable::configure($table);
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
            'index' => ListDonors::route('/'),
            'create' => CreateDonor::route('/create'),
            'view' => ViewDonor::route('/{record}'),
            'edit' => EditDonor::route('/{record}/edit'),
        ];
    }
}
