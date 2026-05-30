<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use App\Support\AdminStateOptions;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentInboxWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pesan Masuk Terbaru')
            ->description('Pesan kontak, konsultasi, dan permintaan kemitraan terbaru dari pengunjung website.')
            ->query(
                ContactMessage::query()->latest()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengirim')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Subjek / Keperluan')
                    ->limit(45),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => AdminStateOptions::labelFor(
                        AdminStateOptions::contactMessageStatuses(),
                        $state,
                    ))
                    ->color(fn (?string $state): string => match ($state) {
                        'new' => 'danger',
                        'in_review' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Diterima')
                    ->since()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('baca')
                    ->label('Baca')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->url(fn (ContactMessage $record): string => ContactMessageResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateIcon(Heroicon::OutlinedInbox)
            ->emptyStateHeading('Belum ada pesan masuk')
            ->emptyStateDescription('Pesan dari pengunjung website akan muncul di sini.');
    }
}
