<?php

namespace App\Filament\Resources\PartnershipInquiries;

use App\Filament\Clusters\Contact\ContactCluster;
use App\Filament\Resources\PartnershipInquiries\Pages\CreatePartnershipInquiry;
use App\Filament\Resources\PartnershipInquiries\Pages\EditPartnershipInquiry;
use App\Filament\Resources\PartnershipInquiries\Pages\ListPartnershipInquiries;
use App\Filament\Resources\PartnershipInquiries\Pages\ViewPartnershipInquiry;
use App\Filament\Resources\PartnershipInquiries\Schemas\PartnershipInquiryForm;
use App\Filament\Resources\PartnershipInquiries\Schemas\PartnershipInquiryInfolist;
use App\Filament\Resources\PartnershipInquiries\Tables\PartnershipInquiriesTable;
use App\Models\PartnershipInquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnershipInquiryResource extends Resource
{
    protected static ?string $model = PartnershipInquiry::class;

    protected static ?string $cluster = ContactCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Kontak';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Inquiry Kemitraan';

    protected static ?string $modelLabel = 'inquiry kemitraan';

    protected static ?string $pluralModelLabel = 'Inquiry Kemitraan';

    protected static ?string $recordTitleAttribute = 'organization_name';

    public static function form(Schema $schema): Schema
    {
        return PartnershipInquiryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartnershipInquiryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnershipInquiriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $newInquiries = PartnershipInquiry::query()
            ->where('status', 'new')
            ->count();

        return $newInquiries > 0 ? (string) $newInquiries : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartnershipInquiries::route('/'),
            'create' => CreatePartnershipInquiry::route('/create'),
            'view' => ViewPartnershipInquiry::route('/{record}'),
            'edit' => EditPartnershipInquiry::route('/{record}/edit'),
        ];
    }
}
