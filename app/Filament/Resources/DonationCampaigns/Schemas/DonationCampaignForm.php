<?php

namespace App\Filament\Resources\DonationCampaigns\Schemas;

use App\Support\AdminStateOptions;
use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationCampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                Textarea::make('short_description')
                    ->label('Ringkasan singkat')
                    ->helperText('Deskripsi pendek yang muncul di kartu kampanye. Maksimal 2-3 kalimat.')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi lengkap')
                    ->helperText('Penjelasan lengkap kampanye yang tampil di halaman donasi.')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('target_amount')
                    ->label('Target donasi (Rp)')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Jumlah dana yang ingin dikumpulkan dari kampanye ini.'),
                TextInput::make('collected_amount')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Dihitung otomatis dari donasi yang sudah terkonfirmasi (status: paid).'),
                DatePicker::make('start_date')
                    ->label('Tanggal mulai kampanye'),
                DatePicker::make('end_date')
                    ->label('Tanggal berakhir kampanye'),
                FilamentImageUpload::make('banner_image_url', 'donation-campaigns', 'Gambar banner'),
                Select::make('status')
                    ->required()
                    ->options(AdminStateOptions::donationCampaignStatuses())
                    ->default('draft'),
                Toggle::make('is_featured')
                    ->required()
                    ->default(false)
                    ->helperText('Kampanye publik yang ditandai unggulan akan menggantikan unggulan sebelumnya secara otomatis.'),
                Select::make('published_by')
                    ->label('Diterbitkan oleh')
                    ->relationship('publisher', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
