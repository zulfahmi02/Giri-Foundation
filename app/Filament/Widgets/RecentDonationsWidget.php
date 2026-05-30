<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Donations\DonationResource;
use App\Models\Donation;
use App\Support\AdminStateOptions;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentDonationsWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Donasi Terbaru')
            ->description('5 donasi terakhir yang masuk. Klik "Lihat" untuk melihat detail dan memverifikasi pembayaran.')
            ->query(
                Donation::query()
                    ->with(['donor', 'campaign'])
                    ->latest()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('donor.full_name')
                    ->label('Nama Donatur')
                    ->formatStateUsing(function (?string $state, Donation $record): string {
                        return $record->is_anonymous ? '(Anonim)' : ($state ?? '-');
                    })
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->formatStateUsing(fn (mixed $state): string => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('campaign.title')
                    ->label('Kampanye')
                    ->placeholder('Tanpa kampanye')
                    ->limit(35),

                TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->formatStateUsing(fn (?string $state): string => ucwords(str_replace('_', ' ', (string) $state))),

                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(
                        AdminStateOptions::donationPaymentStatuses(),
                        $state,
                    ))
                    ->color(fn (?string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn (Donation $record): string => DonationResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateIcon(Heroicon::OutlinedBanknotes)
            ->emptyStateHeading('Belum ada donasi')
            ->emptyStateDescription('Donasi yang masuk dari formulir website akan muncul di sini.');
    }
}
