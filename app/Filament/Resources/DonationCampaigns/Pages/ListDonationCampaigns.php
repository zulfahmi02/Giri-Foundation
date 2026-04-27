<?php

namespace App\Filament\Resources\DonationCampaigns\Pages;

use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Models\DonationCampaign;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDonationCampaigns extends ListRecords
{
    protected static string $resource = DonationCampaignResource::class;

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
                ->badge(DonationCampaign::query()->count()),
            'draft' => Tab::make('Draft')
                ->badge(DonationCampaign::query()->where('status', 'draft')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft')),
            'active' => Tab::make('Aktif')
                ->badge(DonationCampaign::query()->where('status', 'active')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active')),
            'completed' => Tab::make('Selesai')
                ->badge(DonationCampaign::query()->where('status', 'completed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
            'archived' => Tab::make('Arsip')
                ->badge(DonationCampaign::query()->where('status', 'archived')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'archived')),
        ];
    }
}
