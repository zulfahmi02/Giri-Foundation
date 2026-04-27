<?php

namespace App\Filament\Resources\PartnershipInquiries\Pages;

use App\Filament\Resources\PartnershipInquiries\PartnershipInquiryResource;
use App\Models\PartnershipInquiry;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPartnershipInquiries extends ListRecords
{
    protected static string $resource = PartnershipInquiryResource::class;

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
                ->badge(PartnershipInquiry::query()->count()),
            'new' => Tab::make('Baru')
                ->badge(PartnershipInquiry::query()->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),
            'in_review' => Tab::make('Dalam Tinjauan')
                ->badge(PartnershipInquiry::query()->where('status', 'in_review')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_review')),
            'resolved' => Tab::make('Selesai')
                ->badge(PartnershipInquiry::query()->where('status', 'resolved')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'resolved')),
            'closed' => Tab::make('Ditutup')
                ->badge(PartnershipInquiry::query()->where('status', 'closed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'closed')),
        ];
    }
}
