<?php

namespace App\Filament\Resources\Donations\Pages;

use App\Filament\Resources\Donations\DonationResource;
use App\Models\Donation;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDonations extends ListRecords
{
    protected static string $resource = DonationResource::class;

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
                ->badge(Donation::query()->count()),
            'pending' => Tab::make('Menunggu')
                ->badge(Donation::query()->where('payment_status', 'pending')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'pending')),
            'paid' => Tab::make('Lunas')
                ->badge(Donation::query()->where('payment_status', 'paid')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'paid')),
            'failed' => Tab::make('Gagal')
                ->badge(Donation::query()->where('payment_status', 'failed')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'failed')),
            'refunded' => Tab::make('Dikembalikan')
                ->badge(Donation::query()->where('payment_status', 'refunded')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'refunded')),
        ];
    }
}
