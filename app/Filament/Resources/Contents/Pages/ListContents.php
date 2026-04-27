<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Models\Content;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Content::query()->count()),
            'draft' => Tab::make('Draft')
                ->badge(Content::query()->where('status', 'draft')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft')),
            'published' => Tab::make('Terbit')
                ->badge(Content::query()->where('status', 'published')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published')),
            'stories' => Tab::make('Cerita')
                ->badge(Content::query()->where('type', 'story')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'story')),
            'publications' => Tab::make('Publikasi')
                ->badge(Content::query()->whereIn('type', ['journal', 'news', 'article', 'opinion'])->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('type', ['journal', 'news', 'article', 'opinion'])),
        ];
    }
}
