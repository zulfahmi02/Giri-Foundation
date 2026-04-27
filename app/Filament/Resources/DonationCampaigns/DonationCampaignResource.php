<?php

namespace App\Filament\Resources\DonationCampaigns;

use App\Filament\Resources\DonationCampaigns\Pages\CreateDonationCampaign;
use App\Filament\Resources\DonationCampaigns\Pages\EditDonationCampaign;
use App\Filament\Resources\DonationCampaigns\Pages\ListDonationCampaigns;
use App\Filament\Resources\DonationCampaigns\Pages\ViewDonationCampaign;
use App\Filament\Resources\DonationCampaigns\Schemas\DonationCampaignForm;
use App\Filament\Resources\DonationCampaigns\Schemas\DonationCampaignInfolist;
use App\Filament\Resources\DonationCampaigns\Tables\DonationCampaignsTable;
use App\Models\DonationCampaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DonationCampaignResource extends Resource
{
    protected static ?string $model = DonationCampaign::class;

    protected static ?string $cluster = \App\Filament\Clusters\Donations\DonationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Donasi';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Kampanye Donasi';

    protected static ?string $modelLabel = 'kampanye donasi';

    protected static ?string $pluralModelLabel = 'Kampanye Donasi';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DonationCampaignForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DonationCampaignInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationCampaignsTable::configure($table);
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
            'index' => ListDonationCampaigns::route('/'),
            'create' => CreateDonationCampaign::route('/create'),
            'view' => ViewDonationCampaign::route('/{record}'),
            'edit' => EditDonationCampaign::route('/{record}/edit'),
        ];
    }
}
