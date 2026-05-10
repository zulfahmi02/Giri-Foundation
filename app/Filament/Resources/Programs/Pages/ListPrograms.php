<?php

namespace App\Filament\Resources\Programs\Pages;

use App\Filament\Resources\Programs\ProgramResource;
use App\Models\Program;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPrograms extends ListRecords
{
    protected static string $resource = ProgramResource::class;

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
                ->badge(Program::query()->count()),
            'draft' => Tab::make('Draft')
                ->badge(Program::query()->where('status', 'draft')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft')),
            'active' => Tab::make('Aktif')
                ->badge(Program::query()->where('status', 'published')->where('phase', 'active')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published')->where('phase', 'active')),
            'upcoming' => Tab::make('Mendatang')
                ->badge(Program::query()->where('status', 'published')->where('phase', 'upcoming')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'published')->where('phase', 'upcoming')),
            'archived' => Tab::make('Arsip')
                ->badge(Program::query()->where(function (Builder $query): void {
                    $query
                        ->where('phase', 'archived')
                        ->orWhereIn('status', ['completed', 'archived']);
                })->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function (Builder $archivedQuery): void {
                    $archivedQuery
                        ->where('phase', 'archived')
                        ->orWhereIn('status', ['completed', 'archived']);
                })),
        ];
    }
}
