<?php

namespace App\Filament\Resources\Consultations;

use App\Filament\Resources\Consultations\Pages\CreateConsultation;
use App\Filament\Resources\Consultations\Pages\EditConsultation;
use App\Filament\Resources\Consultations\Pages\ListConsultations;
use App\Filament\Resources\Consultations\Pages\ViewConsultation;
use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Filament\Resources\Consultations\Schemas\ConsultationInfolist;
use App\Filament\Resources\Consultations\Tables\ConsultationsTable;
use App\Models\Consultation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static ?string $cluster = \App\Filament\Clusters\Inbox\InboxCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Inbox';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Konsultasi';

    protected static ?string $modelLabel = 'konsultasi';

    protected static ?string $pluralModelLabel = 'Konsultasi';

    protected static ?string $recordTitleAttribute = 'subject';

    public static function form(Schema $schema): Schema
    {
        return ConsultationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationsTable::configure($table);
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
        $newConsultations = Consultation::query()
            ->where('status', 'new')
            ->count();

        return $newConsultations > 0 ? (string) $newConsultations : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConsultations::route('/'),
            'create' => CreateConsultation::route('/create'),
            'view' => ViewConsultation::route('/{record}'),
            'edit' => EditConsultation::route('/{record}/edit'),
        ];
    }
}
