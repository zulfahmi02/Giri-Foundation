<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Models\Document;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('file_type')
                    ->searchable(),
                TextColumn::make('file_size')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('download_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('website_status')
                    ->label('Status Website')
                    ->state(fn (Document $record): string => match (true) {
                        ! $record->is_public => 'Belum publik',
                        $record->published_at === null => 'Tanggal kosong',
                        default => 'Tampil di website',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tampil di website' => 'success',
                        'Tanggal kosong' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_public')
                    ->label('Publik')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tanggal tampil')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
