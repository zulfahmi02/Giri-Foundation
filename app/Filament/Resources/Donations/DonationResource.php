<?php

namespace App\Filament\Resources\Donations;

use App\Filament\Resources\Donations\Pages\CreateDonation;
use App\Filament\Resources\Donations\Pages\EditDonation;
use App\Filament\Resources\Donations\Pages\ListDonations;
use App\Filament\Resources\Donations\Pages\ViewDonation;
use App\Filament\Resources\Donations\Schemas\DonationForm;
use App\Filament\Resources\Donations\Schemas\DonationInfolist;
use App\Filament\Resources\Donations\Tables\DonationsTable;
use App\Models\Donation;
use Illuminate\Support\Facades\Cache;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $cluster = \App\Filament\Clusters\Donations\DonationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Transaksi Donasi';

    protected static ?string $modelLabel = 'transaksi donasi';

    protected static ?string $pluralModelLabel = 'Transaksi Donasi';

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function form(Schema $schema): Schema
    {
        return DonationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DonationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingDonations = Cache::remember('nav_badge_donations_pending', 60, fn () => Donation::query()
            ->where('payment_status', 'pending')
            ->count());

        return $pendingDonations > 0 ? (string) $pendingDonations : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDonations::route('/'),
            'create' => CreateDonation::route('/create'),
            'view' => ViewDonation::route('/{record}'),
            'edit' => EditDonation::route('/{record}/edit'),
        ];
    }
}
