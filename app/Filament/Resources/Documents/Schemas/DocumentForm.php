<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Support\FilamentImageUpload;
use App\Support\FilamentSlugGenerator;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentSlugGenerator::source(
                    TextInput::make('title')
                        ->label('Judul dokumen')
                        ->required(),
                ),
                FilamentSlugGenerator::field(),
                TextInput::make('category')
                    ->label('Kategori')
                    ->helperText('Contoh: Dokumen Organisasi, Laporan, Profil Yayasan, Tata Kelola.'),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->helperText('Ringkasan singkat yang akan tampil di kartu dokumen.')
                    ->columnSpanFull(),
                FileUpload::make('file_url')
                    ->label('Berkas dokumen')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'text/csv',
                    ])
                    ->disk('public')
                    ->directory('documents')
                    ->visibility('public')
                    ->openable()
                    ->downloadable()
                    ->requiredWithout('external_file_url')
                    ->helperText('Upload PDF, Word, Excel, PowerPoint, atau CSV agar pengunjung bisa mengunduh file dari website.')
                    ->columnSpanFull(),
                TextInput::make('external_file_url')
                    ->label('URL eksternal dokumen')
                    ->url()
                    ->requiredWithout('file_url')
                    ->helperText('Isi hanya jika dokumen berada di luar server, misalnya Google Drive atau website lain.')
                    ->columnSpanFull(),
                FilamentImageUpload::make('thumbnail_url', 'documents/thumbnails', 'Gambar thumbnail')
                    ->helperText('Opsional. Dipakai sebagai gambar pendukung dokumen, bukan file utama yang diunduh.'),
                TextInput::make('file_type')
                    ->label('Tipe file')
                    ->helperText('Opsional. Jika kosong, sistem akan mengambil dari ekstensi file, misalnya PDF atau DOCX.'),
                TextInput::make('file_size')
                    ->label('Ukuran file')
                    ->numeric()
                    ->helperText('Opsional. Untuk upload langsung, ukuran file akan dihitung otomatis.'),
                TextInput::make('download_count')
                    ->label('Jumlah unduhan')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Dihitung otomatis setiap kali dokumen diunduh.'),
                Toggle::make('is_public')
                    ->label('Tampilkan di website')
                    ->required()
                    ->default(true)
                    ->helperText('Matikan jika dokumen hanya disimpan sebagai draft/internal.'),
                DateTimePicker::make('published_at')
                    ->label('Tanggal tampil di website')
                    ->default(now())
                    ->helperText('Wajib terisi agar dokumen muncul di halaman publik. Kosongkan hanya jika belum siap tampil.'),
                Select::make('created_by')
                    ->label('Dibuat oleh')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
