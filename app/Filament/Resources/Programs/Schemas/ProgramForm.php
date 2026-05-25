<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Support\AdminStateOptions;
use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->label('Nama program')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                Textarea::make('excerpt')
                    ->label('Ringkasan singkat')
                    ->helperText('Deskripsi pendek yang muncul di kartu program. Maksimal 2-3 kalimat.')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi lengkap')
                    ->helperText('Penjelasan lengkap program yang tampil di halaman detail.')
                    ->required()
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Kategori program')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'draft' => AdminStateOptions::programStatuses()['draft'],
                        'published' => AdminStateOptions::programStatuses()['published'],
                    ])
                    ->default('draft')
                    ->helperText('Program tampil di website hanya saat status Terbit.'),
                Select::make('phase')
                    ->label('Fase program')
                    ->required()
                    ->options(AdminStateOptions::programPhases())
                    ->default('active')
                    ->helperText('Menentukan di kelompok mana program ini ditampilkan: Aktif, Mendatang, Kerja Sama, atau Arsip.'),
                DatePicker::make('start_date')
                    ->label('Tanggal mulai'),
                DatePicker::make('end_date')
                    ->label('Tanggal selesai'),
                TextInput::make('location_name')
                    ->label('Nama lokasi')
                    ->helperText('Nama desa, kecamatan, atau wilayah cakupan program.'),
                TextInput::make('province')
                    ->label('Provinsi'),
                TextInput::make('city')
                    ->label('Kota / Kabupaten'),
                TextInput::make('target_beneficiaries')
                    ->label('Target penerima manfaat')
                    ->helperText('Deskripsi kelompok sasaran. Contoh: Ibu hamil, Remaja putus sekolah.'),
                TextInput::make('beneficiaries_count')
                    ->label('Jumlah penerima manfaat')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Jumlah orang yang sudah menerima manfaat dari program ini.'),
                TextInput::make('budget_amount')
                    ->label('Anggaran program (Rp)')
                    ->numeric()
                    ->helperText('Opsional. Digunakan untuk keperluan pelaporan internal.'),
                FilamentImageUpload::make('featured_image_url', 'programs', 'Gambar program')
                    ->helperText('Foto utama yang muncul sebagai thumbnail program.'),
                Toggle::make('is_featured')
                    ->label('Tampilkan sebagai program unggulan')
                    ->required()
                    ->default(false)
                    ->helperText('Program unggulan ditampilkan lebih menonjol di halaman Beranda dan Program.'),
                DateTimePicker::make('published_at')
                    ->label('Tanggal publikasi')
                    ->helperText('Isi tanggal ini saat program siap ditampilkan di website.'),
                Select::make('partners')
                    ->label('Mitra program')
                    ->relationship(
                        name: 'partners',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query
                            ->where('is_active', true)
                            ->orderBy('name'),
                    )
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Pilih mitra yang terlibat dalam pelaksanaan program ini.')
                    ->columnSpanFull(),
                Select::make('created_by')
                    ->label('Pengelola program')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
