<?php

namespace App\Filament\Resources\MediaLibraries;

use App\Filament\Resources\MediaLibraries\Pages\CreateMediaLibrary;
use App\Filament\Resources\MediaLibraries\Pages\EditMediaLibrary;
use App\Filament\Resources\MediaLibraries\Pages\ListMediaLibraries;
use App\Filament\Resources\MediaLibraries\Pages\ViewMediaLibrary;
use App\Filament\Resources\MediaLibraries\Schemas\MediaLibraryForm;
use App\Filament\Resources\MediaLibraries\Schemas\MediaLibraryInfolist;
use App\Filament\Resources\MediaLibraries\Tables\MediaLibrariesTable;
use App\Models\MediaLibrary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MediaLibraryResource extends Resource
{
    protected static ?string $model = MediaLibrary::class;

    protected static ?string $cluster = \App\Filament\Clusters\Website\WebsiteCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Media';

    protected static ?string $modelLabel = 'media';

    protected static ?string $pluralModelLabel = 'Media';

    protected static ?string $recordTitleAttribute = 'file_name';

    public static function form(Schema $schema): Schema
    {
        return MediaLibraryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MediaLibraryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediaLibrariesTable::configure($table);
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
            'index' => ListMediaLibraries::route('/'),
            'create' => CreateMediaLibrary::route('/create'),
            'view' => ViewMediaLibrary::route('/{record}'),
            'edit' => EditMediaLibrary::route('/{record}/edit'),
        ];
    }
}
