<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Support\PageContentDefaults;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                        Tab::make('Utama')
                            ->schema([
                                Section::make('Identitas Halaman')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Judul')
                                            ->required()
                                            ->maxLength(255),
                                        Select::make('slug')
                                            ->options($pageOptions)
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Select::make('template')
                                            ->options($templateOptions)
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        Textarea::make('content')
                                            ->label('Konten panjang')
                                            ->rows(6)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Publikasi & SEO')
                                    ->schema([
                                        Select::make('status')
                                            ->required()
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Terbit',
                                                'archived' => 'Arsip',
                                            ])
                                            ->default('draft'),
                                        DateTimePicker::make('published_at')
                                            ->label('Dipublikasikan pada'),
                                        Select::make('created_by')
                                            ->relationship('creator', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->default(fn (): ?int => auth()->id()),
                                        TextInput::make('seo_title')
                                            ->label('Judul SEO')
                                            ->maxLength(255),
                                        Textarea::make('seo_description')
                                            ->label('Deskripsi SEO')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Hero')
                            ->schema([
                                Section::make('Konten Hero')
                                    ->description('Bidang umum untuk blok pembuka halaman.')
                                    ->schema([
                                        TextInput::make('hero_data.kicker')
                                            ->label('Kicker'),
                                        TextInput::make('hero_data.title_prefix')
                                            ->label('Judul sebelum highlight')
                                            ->columnSpanFull(),
                                        TextInput::make('hero_data.highlight')
                                            ->label('Kata highlight'),
                                        TextInput::make('hero_data.title_suffix')
                                            ->label('Judul sesudah highlight')
                                            ->columnSpanFull(),
                                        Textarea::make('hero_data.body')
                                            ->label('Ringkasan hero')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                        TextInput::make('hero_data.primary_cta_label')
                                            ->label('Label CTA utama'),
                                        TextInput::make('hero_data.primary_cta_url')
                                            ->label('URL / path CTA utama'),
                                        TextInput::make('hero_data.secondary_cta_label')
                                            ->label('Label CTA sekunder'),
                                        TextInput::make('hero_data.secondary_cta_url')
                                            ->label('URL / path CTA sekunder'),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Blok Konten')
                            ->schema([
                                Section::make('Beranda')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'home')
                                    ->schema([
                                        TextInput::make('section_data.programs.kicker')
                                            ->label('Label program'),
                                        TextInput::make('section_data.programs.title')
                                            ->label('Judul program')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.programs.cta_label')
                                            ->label('Label CTA program'),
                                        TextInput::make('section_data.media.kicker')
                                            ->label('Label media'),
                                        TextInput::make('section_data.media.title')
                                            ->label('Judul media')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.media.activities_label')
                                            ->label('Label aktivitas'),
                                        TextInput::make('section_data.media.videos_label')
                                            ->label('Label video'),
                                        TextInput::make('section_data.media.cta_label')
                                            ->label('Label CTA media'),
                                        TextInput::make('section_data.publications.kicker')
                                            ->label('Label publikasi'),
                                        TextInput::make('section_data.publications.title')
                                            ->label('Judul publikasi')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.publications.editorial_label')
                                            ->label('Label editorial'),
                                        TextInput::make('section_data.publications.archive_label')
                                            ->label('Label arsip'),
                                        TextInput::make('section_data.publications.cta_label')
                                            ->label('Label CTA publikasi'),
                                        TextInput::make('section_data.closing.kicker')
                                            ->label('Label penutup'),
                                        TextInput::make('section_data.closing.title')
                                            ->label('Judul penutup')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.closing.body')
                                            ->label('Isi penutup')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.closing.primary_cta_label')
                                            ->label('CTA utama penutup'),
                                        TextInput::make('section_data.closing.primary_cta_url')
                                            ->label('URL CTA utama penutup'),
                                        TextInput::make('section_data.closing.secondary_cta_label')
                                            ->label('CTA sekunder penutup'),
                                        TextInput::make('section_data.closing.secondary_cta_url')
                                            ->label('URL CTA sekunder penutup'),
                                    ])
                                    ->columns(2),
                                Section::make('Tentang')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'about')
                                    ->schema([
                                        TextInput::make('section_data.brand.kicker')
                                            ->label('Label identitas'),
                                        TextInput::make('section_data.brand.title')
                                            ->label('Nama lembaga')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.brand.subtitle')
                                            ->label('Subjudul identitas')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.brand.note')
                                            ->label('Catatan identitas')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.profile.kicker')
                                            ->label('Label profil'),
                                        TextInput::make('section_data.profile.title')
                                            ->label('Judul profil')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.values.kicker')
                                            ->label('Label nilai'),
                                        TextInput::make('section_data.values.title')
                                            ->label('Judul nilai')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.values.description_template')
                                            ->label('Template deskripsi nilai')
                                            ->helperText('Gunakan :value untuk menyisipkan nama nilai.')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.personnel.kicker')
                                            ->label('Label personil'),
                                        TextInput::make('section_data.personnel.title')
                                            ->label('Judul personil')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Program')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'programs')
                                    ->schema([
                                        TextInput::make('section_data.active.kicker')
                                            ->label('Label program aktif'),
                                        TextInput::make('section_data.active.title')
                                            ->label('Judul program aktif')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.partnership.kicker')
                                            ->label('Label program kerja sama'),
                                        TextInput::make('section_data.partnership.title')
                                            ->label('Judul program kerja sama')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.upcoming.kicker')
                                            ->label('Label program mendatang'),
                                        TextInput::make('section_data.upcoming.title')
                                            ->label('Judul program mendatang')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archive.kicker')
                                            ->label('Label arsip program'),
                                        TextInput::make('section_data.archive.title')
                                            ->label('Judul arsip program')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Media')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'media')
                                    ->schema([
                                        TextInput::make('section_data.activities.kicker')
                                            ->label('Label aktivitas'),
                                        TextInput::make('section_data.activities.title')
                                            ->label('Judul aktivitas')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.videos.kicker')
                                            ->label('Label video'),
                                        TextInput::make('section_data.videos.title')
                                            ->label('Judul video')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Publikasi')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'publikasi')
                                    ->schema([
                                        TextInput::make('section_data.journals.kicker')
                                            ->label('Label jurnal'),
                                        TextInput::make('section_data.journals.title')
                                            ->label('Judul jurnal')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archives.kicker')
                                            ->label('Label arsip'),
                                        TextInput::make('section_data.archives.title')
                                            ->label('Judul arsip')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.news.kicker')
                                            ->label('Label berita'),
                                        TextInput::make('section_data.news.title')
                                            ->label('Judul berita')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.articles.kicker')
                                            ->label('Label artikel'),
                                        TextInput::make('section_data.articles.title')
                                            ->label('Judul artikel')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.opinions.kicker')
                                            ->label('Label opini'),
                                        TextInput::make('section_data.opinions.title')
                                            ->label('Judul opini')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Cerita')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'stories')
                                    ->schema([
                                        TextInput::make('section_data.newsletter.title')
                                            ->label('Judul blok newsletter')
                                            ->columnSpanFull(),
                                        Textarea::make('section_data.newsletter.body')
                                            ->label('Isi blok newsletter')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.archive.kicker')
                                            ->label('Label blok arsip'),
                                    ])
                                    ->columns(2),
                                Section::make('Donasi')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'donate')
                                    ->schema([
                                        TextInput::make('section_data.documents.kicker')
                                            ->label('Label blok dokumen'),
                                        TextInput::make('section_data.documents.title')
                                            ->label('Judul blok dokumen')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.documents.link_label')
                                            ->label('Label link blok dokumen'),
                                    ])
                                    ->columns(2),
                                Section::make('Dokumen & Wawasan')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'resources')
                                    ->schema([
                                        TextInput::make('section_data.filters.search_label')
                                            ->label('Label pencarian'),
                                        TextInput::make('section_data.filters.search_placeholder')
                                            ->label('Placeholder pencarian')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.filters.category_label')
                                            ->label('Label kategori'),
                                        TextInput::make('section_data.filters.submit_label')
                                            ->label('Label tombol filter'),
                                    ])
                                    ->columns(2),
                                Section::make('Kemitraan')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'partners')
                                    ->schema([
                                        TextInput::make('section_data.highlight.value')
                                            ->label('Angka highlight'),
                                        TextInput::make('section_data.highlight.label')
                                            ->label('Label highlight'),
                                        Textarea::make('section_data.highlight.body')
                                            ->label('Deskripsi highlight')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.collaborators.kicker')
                                            ->label('Label kolaborator'),
                                        TextInput::make('section_data.collaborators.title')
                                            ->label('Judul kolaborator')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.programs.kicker')
                                            ->label('Label program mitra'),
                                        TextInput::make('section_data.programs.title')
                                            ->label('Judul program mitra')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.inquiry.kicker')
                                            ->label('Label form inquiry'),
                                        TextInput::make('section_data.inquiry.title')
                                            ->label('Judul form inquiry')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.inquiry.submit_label')
                                            ->label('Label tombol inquiry'),
                                    ])
                                    ->columns(2),
                                Section::make('Kontak')
                                    ->visible(fn (Get $get): bool => $get('slug') === 'contact')
                                    ->schema([
                                        TextInput::make('section_data.details.kicker')
                                            ->label('Label detail kontak'),
                                        TextInput::make('section_data.details.title')
                                            ->label('Judul detail kontak')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.form.title')
                                            ->label('Judul formulir')
                                            ->columnSpanFull(),
                                        TextInput::make('section_data.location.kicker')
                                            ->label('Label lokasi'),
                                        TextInput::make('section_data.location.title')
                                            ->label('Judul lokasi')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
