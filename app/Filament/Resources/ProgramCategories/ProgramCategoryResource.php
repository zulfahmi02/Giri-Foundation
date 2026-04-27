<?php

namespace App\Filament\Resources\ProgramCategories;

use App\Filament\Resources\ProgramCategories\Pages\CreateProgramCategory;
use App\Filament\Resources\ProgramCategories\Pages\EditProgramCategory;
use App\Filament\Resources\ProgramCategories\Pages\ListProgramCategories;
use App\Filament\Resources\ProgramCategories\Pages\ViewProgramCategory;
use App\Filament\Resources\ProgramCategories\Schemas\ProgramCategoryForm;
use App\Filament\Resources\ProgramCategories\Schemas\ProgramCategoryInfolist;
use App\Filament\Resources\ProgramCategories\Tables\ProgramCategoriesTable;
use App\Models\ProgramCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProgramCategoryResource extends Resource
{
    protected static ?string $model = ProgramCategory::class;

    protected static ?string $cluster = \App\Filament\Clusters\Programs\ProgramsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Program';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Kategori Program';

    protected static ?string $modelLabel = 'kategori program';

    protected static ?string $pluralModelLabel = 'Kategori Program';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProgramCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgramCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgramCategoriesTable::configure($table);
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
            'index' => ListProgramCategories::route('/'),
            'create' => CreateProgramCategory::route('/create'),
            'view' => ViewProgramCategory::route('/{record}'),
            'edit' => EditProgramCategory::route('/{record}/edit'),
        ];
    }
}
