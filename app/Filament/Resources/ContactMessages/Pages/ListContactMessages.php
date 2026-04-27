<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

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
                ->badge(ContactMessage::query()->count()),
            'new' => Tab::make('Baru')
                ->badge(ContactMessage::query()->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),
            'in_review' => Tab::make('Dalam Tinjauan')
                ->badge(ContactMessage::query()->where('status', 'in_review')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_review')),
            'resolved' => Tab::make('Selesai')
                ->badge(ContactMessage::query()->where('status', 'resolved')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'resolved')),
            'closed' => Tab::make('Ditutup')
                ->badge(ContactMessage::query()->where('status', 'closed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'closed')),
        ];
    }
}
