<?php

namespace App\Filament\Resources\ContentCategories;

use App\Filament\Clusters\Publications\PublicationsCluster;
use App\Filament\Resources\ContentCategories\Pages\CreateContentCategory;
use App\Filament\Resources\ContentCategories\Pages\EditContentCategory;
use App\Filament\Resources\ContentCategories\Pages\ListContentCategories;
use App\Filament\Resources\ContentCategories\Pages\ViewContentCategory;
use App\Filament\Resources\ContentCategories\Schemas\ContentCategoryForm;
use App\Filament\Resources\ContentCategories\Schemas\ContentCategoryInfolist;
use App\Filament\Resources\ContentCategories\Tables\ContentCategoriesTable;
use App\Models\ContentCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentCategoryResource extends Resource
{
    protected static ?string $model = ContentCategory::class;

    protected static ?string $cluster = PublicationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Publikasi';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Kategori Publikasi';

    protected static ?string $modelLabel = 'kategori cerita';

    protected static ?string $pluralModelLabel = 'Kategori Cerita';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ContentCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContentCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentCategoriesTable::configure($table);
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
            'index' => ListContentCategories::route('/'),
            'create' => CreateContentCategory::route('/create'),
            'view' => ViewContentCategory::route('/{record}'),
            'edit' => EditContentCategory::route('/{record}/edit'),
        ];
    }
}
