<?php

namespace App\Filament\Resources\Consultations\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Consultation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListConsultations extends ListRecords
{
    protected static string $resource = ConsultationResource::class;

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
                ->badge(Consultation::query()->count()),
            'new' => Tab::make('Baru')
                ->badge(Consultation::query()->where('status', 'new')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'new')),
            'in_review' => Tab::make('Dalam Tinjauan')
                ->badge(Consultation::query()->where('status', 'in_review')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_review')),
            'scheduled' => Tab::make('Terjadwal')
                ->badge(Consultation::query()->where('status', 'scheduled')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'scheduled')),
            'resolved' => Tab::make('Selesai')
                ->badge(Consultation::query()->where('status', 'resolved')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'resolved')),
            'closed' => Tab::make('Ditutup')
                ->badge(Consultation::query()->where('status', 'closed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'closed')),
        ];
    }
}
