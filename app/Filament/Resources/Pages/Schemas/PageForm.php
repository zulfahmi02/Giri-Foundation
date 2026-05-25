<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use App\Support\PageContentDefaults;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        $pageOptions = collect(PageContentDefaults::pages())
            ->mapWithKeys(fn (array $page, string $slug): array => [$slug => $page['title']])
            ->all();

        $templateOptions = collect(PageContentDefaults::pages())
            ->mapWithKeys(fn (array $page): array => [$page['template'] => $page['title']])
            ->all();

        return $schema
            ->components([
                Tabs::make('Konten Halaman')
                    ->tabs([
                        Tab::make('Informasi Halaman')
                            ->schema([
                                Section::make('Identitas Halaman')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Judul halaman')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('slug')
                                            ->options($pageOptions)
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disabled()
                                            ->dehydrated()
                                            ->hidden(),
                                        Select::make('template')
                                            ->options($templateOptions)
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->disabled()
                                            ->dehydrated()
                                            ->hidden(),
                                        Textarea::make('content')
                                            ->label('Konten halaman')
                                            ->helperText('Teks utama yang ditampilkan di badan halaman. Tidak semua halaman menggunakan field ini.')
                                            ->rows(6)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Status & Optimasi Mesin Pencari (SEO)')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Status')
                                            ->required()
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Terbit',
                                                'archived' => 'Arsip',
                                            ])
                                            ->default('draft'),
                                        DateTimePicker::make('published_at')
                                            ->label('Tanggal terbit'),
                                        Select::make('created_by')
                                            ->label('Pengelola halaman')
                                            ->relationship('creator', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(fn (?Page $record): ?int => $record?->created_by ?? auth()->id()),
                                        TextInput::make('seo_title')
                                            ->label('Judul SEO')
                                            ->helperText('Muncul di tab browser dan hasil pencarian Google. Kosongkan untuk menggunakan judul halaman.')
                                            ->maxLength(255),
                                        Textarea::make('seo_description')
                                            ->label('Deskripsi SEO')
                                            ->helperText('Ringkasan singkat yang muncul di bawah judul pada hasil pencarian Google (maks. 160 karakter).')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Bagian Pembuka (Banner)')
                            ->schema([
                                Section::make('Konten Banner Utama')
                                    ->description('Teks yang tampil di bagian paling atas (banner) setiap halaman. Semua field bersifat opsional — kosongkan jika tidak ingin ditampilkan.')
                                    ->schema([
                                        TextInput::make('hero_data.kicker')
                                            ->label('Label kecil di atas judul')
                                            ->helperText('Teks pendek yang muncul di atas judul utama. Contoh: "Tentang Kami" atau "Program Unggulan".'),
                                        TextInput::make('hero_data.title_prefix')
                                            ->label('Judul (bagian awal)')
                                            ->helperText('Bagian pertama judul utama, sebelum kata yang ditonjolkan.')
                                            ->columnSpanFull(),
                                        TextInput::make('hero_data.highlight')
                                            ->label('Kata yang ditonjolkan')
                                            ->helperText('Kata atau frasa yang diberi warna/gaya berbeda di tengah judul.'),
                                        TextInput::make('hero_data.title_suffix')
                                            ->label('Judul (bagian akhir)')
                                            ->helperText('Sambungan judul setelah kata yang ditonjolkan. Kosongkan jika tidak ada.')
                                            ->columnSpanFull(),
                                        Textarea::make('hero_data.body')
                                            ->label('Teks deskripsi pembuka')
                                            ->helperText('Paragraf singkat di bawah judul yang menjelaskan isi halaman kepada pengunjung.')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                        TextInput::make('hero_data.primary_cta_label')
                                            ->label('Teks tombol utama')
                                            ->helperText('Contoh: "Pelajari Lebih Lanjut" atau "Donasi Sekarang".'),
                                        TextInput::make('hero_data.primary_cta_url')
                                            ->label('Tautan tombol utama')
                                            ->helperText('Halaman tujuan saat tombol diklik. Gunakan path seperti /program atau /donasi.'),
                                        TextInput::make('hero_data.secondary_cta_label')
                                            ->label('Teks tombol kedua')
                                            ->helperText('Tombol tambahan di samping tombol utama. Kosongkan jika tidak diperlukan.'),
                                        TextInput::make('hero_data.secondary_cta_url')
                                            ->label('Tautan tombol kedua'),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Teks Per Bagian')
                            ->schema([
                                Section::make('Beranda')
                                    ->description('Judul dan label untuk setiap blok yang tampil di halaman Beranda.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'home')
                                    ->schema([
                                        TextInput::make('section_data.programs.kicker')
                                            ->label('Label kecil bagian Program')
                                            ->helperText('Muncul di atas judul blok Program di halaman Beranda.'),
                                        TextInput::make('section_data.programs.title')
                                            ->label('Judul bagian Program')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.programs.cta_label')
                                            ->label('Teks tombol "lihat semua program"'),
                                        TextInput::make('section_data.media.kicker')
                                            ->label('Label kecil bagian Media'),
                                        TextInput::make('section_data.media.title')
                                            ->label('Judul bagian Media')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.media.activities_label')
                                            ->label('Teks tab Aktivitas'),
                                        TextInput::make('section_data.media.videos_label')
                                            ->label('Teks tab Video'),
                                        TextInput::make('section_data.media.cta_label')
                                            ->label('Teks tombol "lihat semua media"'),
                                        TextInput::make('section_data.publications.kicker')
                                            ->label('Label kecil bagian Publikasi'),
                                        TextInput::make('section_data.publications.title')
                                            ->label('Judul bagian Publikasi')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.publications.editorial_label')
                                            ->label('Teks tab Editorial'),
                                        TextInput::make('section_data.publications.archive_label')
                                            ->label('Teks tab Arsip'),
                                        TextInput::make('section_data.publications.cta_label')
                                            ->label('Teks tombol "lihat semua publikasi"'),
                                        TextInput::make('section_data.closing.kicker')
                                            ->label('Label kecil bagian Penutup'),
                                        TextInput::make('section_data.closing.title')
                                            ->label('Judul bagian Penutup')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.closing.body')
                                            ->label('Teks paragraf Penutup')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.closing.primary_cta_label')
                                            ->label('Teks tombol utama Penutup'),
                                        TextInput::make('section_data.closing.primary_cta_url')
                                            ->label('Tautan tombol utama Penutup'),
                                        TextInput::make('section_data.closing.secondary_cta_label')
                                            ->label('Teks tombol kedua Penutup'),
                                        TextInput::make('section_data.closing.secondary_cta_url')
                                            ->label('Tautan tombol kedua Penutup'),
                                    ])
                                    ->columns(2),
                                Section::make('Tentang Kami')
                                    ->description('Judul dan teks untuk setiap bagian di halaman Tentang Kami.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'about')
                                    ->schema([
                                        TextInput::make('section_data.brand.kicker')
                                            ->label('Label kecil bagian Identitas'),
                                        TextInput::make('section_data.brand.title')
                                            ->label('Nama lembaga (tampilan besar)')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.brand.subtitle')
                                            ->label('Tagline / kalimat identitas')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.brand.note')
                                            ->label('Catatan di bawah identitas')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.profile.kicker')
                                            ->label('Label kecil bagian Profil'),
                                        TextInput::make('section_data.profile.title')
                                            ->label('Judul bagian Profil')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.values.kicker')
                                            ->label('Label kecil bagian Nilai'),
                                        TextInput::make('section_data.values.title')
                                            ->label('Judul bagian Nilai')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.values.description_template')
                                            ->label('Template keterangan nilai')
                                            ->helperText('Gunakan :value untuk menyisipkan nama nilai. Contoh: "Kami percaya pada :value sebagai fondasi lembaga."')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.personnel.kicker')
                                            ->label('Label kecil bagian Pengurus'),
                                        TextInput::make('section_data.personnel.title')
                                            ->label('Judul bagian Pengurus')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Program')
                                    ->description('Label untuk setiap kelompok program di halaman Program.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'programs')
                                    ->schema([
                                        TextInput::make('section_data.active.kicker')
                                            ->label('Label kecil Program Aktif'),
                                        TextInput::make('section_data.active.title')
                                            ->label('Judul Program Aktif')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.partnership.kicker')
                                            ->label('Label kecil Program Kerja Sama'),
                                        TextInput::make('section_data.partnership.title')
                                            ->label('Judul Program Kerja Sama')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.upcoming.kicker')
                                            ->label('Label kecil Program Mendatang'),
                                        TextInput::make('section_data.upcoming.title')
                                            ->label('Judul Program Mendatang')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archive.kicker')
                                            ->label('Label kecil Arsip Program'),
                                        TextInput::make('section_data.archive.title')
                                            ->label('Judul Arsip Program')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Media')
                                    ->description('Teks judul untuk bagian-bagian di halaman Media.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'media')
                                    ->schema([
                                        TextInput::make('section_data.activities.kicker')
                                            ->label('Label kecil bagian Aktivitas'),
                                        TextInput::make('section_data.activities.title')
                                            ->label('Judul bagian Aktivitas')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.videos.kicker')
                                            ->label('Label kecil bagian Video'),
                                        TextInput::make('section_data.videos.title')
                                            ->label('Judul bagian Video')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Publikasi')
                                    ->description('Teks judul untuk setiap jenis konten di halaman Publikasi.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'publikasi')
                                    ->schema([
                                        TextInput::make('section_data.journals.kicker')
                                            ->label('Label kecil bagian Jurnal'),
                                        TextInput::make('section_data.journals.title')
                                            ->label('Judul bagian Jurnal')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archives.kicker')
                                            ->label('Label kecil bagian Arsip'),
                                        TextInput::make('section_data.archives.title')
                                            ->label('Judul bagian Arsip')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archives.cta_label')
                                            ->label('Teks tombol unduh arsip dokumen'),
                                        TextInput::make('section_data.news.kicker')
                                            ->label('Label kecil bagian Berita'),
                                        TextInput::make('section_data.news.title')
                                            ->label('Judul bagian Berita')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.articles.kicker')
                                            ->label('Label kecil bagian Artikel'),
                                        TextInput::make('section_data.articles.title')
                                            ->label('Judul bagian Artikel')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.opinions.kicker')
                                            ->label('Label kecil bagian Opini'),
                                        TextInput::make('section_data.opinions.title')
                                            ->label('Judul bagian Opini')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Cerita')
                                    ->description('Teks untuk bagian-bagian di halaman Cerita.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'stories')
                                    ->schema([
                                        TextInput::make('section_data.newsletter.title')
                                            ->label('Judul blok langganan berita')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.newsletter.body')
                                            ->label('Teks ajakan langganan berita')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archive.kicker')
                                            ->label('Label kecil bagian Arsip Cerita'),
                                    ])
                                    ->columns(2),
                                Section::make('Donasi')
                                    ->description('Teks untuk bagian-bagian di halaman Donasi.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'donate')
                                    ->schema([
                                        TextInput::make('section_data.documents.kicker')
                                            ->label('Label kecil bagian Dokumen'),
                                        TextInput::make('section_data.documents.title')
                                            ->label('Judul bagian Dokumen')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.documents.link_label')
                                            ->label('Teks tautan "lihat semua dokumen"'),
                                    ])
                                    ->columns(2),
                                Section::make('Dokumen & Wawasan')
                                    ->description('Teks label untuk filter pencarian di halaman Dokumen & Wawasan.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'resources')
                                    ->schema([
                                        TextInput::make('section_data.filters.search_label')
                                            ->label('Label kolom pencarian'),
                                        TextInput::make('section_data.filters.search_placeholder')
                                            ->label('Teks contoh dalam kolom pencarian')
                                            ->helperText('Teks abu-abu yang muncul di dalam kolom kosong sebelum diisi.')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.filters.category_label')
                                            ->label('Label filter kategori'),
                                        TextInput::make('section_data.filters.submit_label')
                                            ->label('Teks tombol cari / filter'),
                                    ])
                                    ->columns(2),
                                Section::make('Kemitraan')
                                    ->description('Teks untuk setiap bagian di halaman Kemitraan.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'partners')
                                    ->schema([
                                        TextInput::make('section_data.highlight.value')
                                            ->label('Angka statistik unggulan')
                                            ->helperText('Contoh: "120+" atau "5.000".'),
                                        TextInput::make('section_data.highlight.label')
                                            ->label('Keterangan angka statistik')
                                            ->helperText('Contoh: "Mitra aktif di seluruh Indonesia".'),
                                        Textarea::make('section_data.highlight.body')
                                            ->label('Paragraf statistik unggulan')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.collaborators.kicker')
                                            ->label('Label kecil bagian Kolaborator'),
                                        TextInput::make('section_data.collaborators.title')
                                            ->label('Judul bagian Kolaborator')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.programs.kicker')
                                            ->label('Label kecil bagian Program Mitra'),
                                        TextInput::make('section_data.programs.title')
                                            ->label('Judul bagian Program Mitra')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.inquiry.kicker')
                                            ->label('Label kecil formulir Kemitraan'),
                                        TextInput::make('section_data.inquiry.title')
                                            ->label('Judul formulir Kemitraan')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.inquiry.submit_label')
                                            ->label('Teks tombol kirim formulir'),
                                    ])
                                    ->columns(2),
                                Section::make('Kontak')
                                    ->description('Teks untuk setiap bagian di halaman Kontak.')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'contact')
                                    ->schema([
                                        TextInput::make('section_data.details.kicker')
                                            ->label('Label kecil bagian Info Kontak'),
                                        TextInput::make('section_data.details.title')
                                            ->label('Judul bagian Info Kontak')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.form.title')
                                            ->label('Judul formulir pesan')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.location.kicker')
                                            ->label('Label kecil bagian Lokasi'),
                                        TextInput::make('section_data.location.title')
                                            ->label('Judul bagian Lokasi')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
