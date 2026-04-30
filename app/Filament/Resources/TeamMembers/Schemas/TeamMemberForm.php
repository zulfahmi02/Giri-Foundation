<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Models\Division;
use App\Models\TeamMember;
use App\Support\FilamentSlugGenerator;
use App\Support\TeamMemberStructureSlots;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_structural')
                    ->label('Masuk struktur organisasi')
                    ->default(true)
                    ->disabled(fn (?TeamMember $record): bool => filled($record?->structure_slot))
                    ->live()
                    ->afterStateUpdated(function (bool $state, callable $set): void {
                        if ($state) {
                            return;
                        }

                        $set('structure_slot', null);
                    })
                    ->required()
                    ->columnSpanFull(),
                Section::make('Data Personil')
                    ->description('Isi informasi utama personil yang akan ditampilkan di panel dan halaman publik.')
                    ->schema([
                        FilamentSlugGenerator::source(
                            TextInput::make('name')
                                ->label('Nama personil')
                                ->required(),
                            shouldGenerate: fn (Get $get): bool => ! $get('is_structural'),
                        ),
                        TextInput::make('email')
                            ->label('Email')
                            ->email(),
                        Textarea::make('bio')
                            ->label('Bio singkat')
                            ->rows(4)
                            ->columnSpanFull(),
                        FileUpload::make('photo_url')
                            ->label('Foto personil')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('team-members')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Unggah foto JPG, PNG, atau WebP dari perangkat. Maksimal 2 MB.')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                        Textarea::make('linkedin_url')
                            ->label('URL LinkedIn')
                            ->rows(2)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Slot Struktur')
                    ->description('Pilih slot sesuai bagan organisasi. Sistem akan mengatur jabatan, divisi, atasan, dan urutan tampil secara otomatis.')
                    ->visible(fn (Get $get): bool => (bool) $get('is_structural'))
                    ->schema([
                        Select::make('structure_slot')
                            ->label('Peran pada struktur organisasi')
                            ->options(TeamMemberStructureSlots::options())
                            ->required(fn (Get $get): bool => (bool) $get('is_structural'))
                            ->disabled(fn (?TeamMember $record): bool => filled($record?->structure_slot))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->helperText(fn (Get $get): string => TeamMemberStructureSlots::selectionHelperText($get('structure_slot')))
                            ->afterStateUpdated(function (?string $state, callable $set): void {
                                foreach (TeamMemberStructureSlots::previewAttributes($state) as $field => $value) {
                                    $set($field, $value);
                                }
                            }),
                    ]),
                Section::make('Pengaturan Manual')
                    ->description('Bagian ini hanya dipakai jika personil tidak masuk ke struktur organisasi utama.')
                    ->visible(fn (Get $get): bool => ! $get('is_structural'))
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(fn (Get $get): bool => ! $get('is_structural')),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->required(fn (Get $get): bool => ! $get('is_structural')),
                        Select::make('division_id')
                            ->label('Divisi')
                            ->relationship('divisionRecord', 'name')
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function ($state, callable $set): void {
                                $set('division', Division::query()->find($state)?->name);
                            }),
                        Select::make('parent_id')
                            ->label('Atasan langsung')
                            ->options(function (?TeamMember $record): array {
                                return TeamMember::query()
                                    ->where('is_active', true)
                                    ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn (TeamMember $teamMember): array => [
                                        $teamMember->id => "{$teamMember->position} - {$teamMember->name}",
                                    ])
                                    ->all();
                            })
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort_order')
                            ->label('Urutan tampil')
                            ->required(fn (Get $get): bool => ! $get('is_structural'))
                            ->numeric()
                            ->default(1),
                    ])
                    ->columns(2),
            ]);
    }
}
