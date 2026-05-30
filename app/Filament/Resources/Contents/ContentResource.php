<?php

namespace App\Filament\Resources\Contents;

use App\Filament\Clusters\Publications\PublicationsCluster;
use App\Filament\Resources\Contents\Pages\CreateContent;
use App\Filament\Resources\Contents\Pages\EditContent;
use App\Filament\Resources\Contents\Pages\ListContents;
use App\Filament\Resources\Contents\Pages\ViewContent;
use App\Filament\Resources\Contents\Schemas\ContentForm;
use App\Filament\Resources\Contents\Schemas\ContentInfolist;
use App\Filament\Resources\Contents\Tables\ContentsTable;
use App\Models\Content;
use Illuminate\Support\Facades\Cache;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $cluster = PublicationsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Cerita & Artikel';

    protected static ?string $modelLabel = 'cerita';

    protected static ?string $pluralModelLabel = 'Cerita & Artikel';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ContentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $draftContents = Cache::remember('nav_badge_contents_draft', 60, fn () => Content::query()
            ->where('status', 'draft')
            ->count());

        return $draftContents > 0 ? (string) $draftContents : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContents::route('/'),
            'create' => CreateContent::route('/create'),
            'view' => ViewContent::route('/{record}'),
            'edit' => EditContent::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
