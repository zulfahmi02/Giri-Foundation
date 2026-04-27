@extends('layouts.site')

@section('content')
    <section class="mx-auto grid w-full items-start gap-10 px-6 pt-10 pb-16 sm:w-[88vw] lg:grid-cols-12 lg:gap-14 lg:px-10 lg:pt-12 lg:pb-24 xl:px-12 2xl:gap-16">
        <div class="lg:col-span-6 xl:col-span-7">
            <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Arsip Hidup') }}</p>
            <h1 class="font-editorial max-w-5xl text-5xl leading-[0.95] tracking-tight sm:text-6xl lg:text-7xl 2xl:text-8xl">
                <span class="block">{{ $page->heroValue('title_prefix', 'Memberdayakan komunitas melalui') }}</span>
                <span class="mt-1 block">
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'tindakan') }}</span>
                    {{ $page->heroValue('title_suffix', 'yang autentik.') }}
                </span>
            </h1>
            <p class="mt-10 max-w-2xl text-base leading-8 text-[var(--ink-muted)] md:text-lg">
                {{ $page->heroValue('body', $heroSummary) }}
            </p>
            <div class="mt-12 flex flex-wrap items-center gap-5">
                <a href="{{ $page->heroValue('primary_cta_url', route('contact.show')) }}" class="rounded-xl bg-[var(--primary-soft)] px-7 py-4 text-sm font-semibold uppercase tracking-[0.12em] text-white">
                    {{ $page->heroValue('primary_cta_label', 'Hubungi Kami') }}
                </a>
                <a href="{{ $page->heroValue('secondary_cta_url', route('programs.index')) }}" class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.12em] text-[var(--primary)]">
                    {{ $page->heroValue('secondary_cta_label', 'Jelajahi Program') }}
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>

        @if ($featuredProgram)
            <div class="lg:col-span-6 xl:col-span-5">
                <div class="surface-card overflow-hidden rounded-[2rem] border border-[color:rgba(190,201,195,0.2)] bg-[var(--surface-muted)]">
                    <img src="{{ $featuredProgram->featured_image_url }}" alt="Visual program unggulan {{ $featuredProgram->title }}" class="h-[26rem] w-full object-contain p-8 md:h-[30rem] lg:h-[36rem] xl:p-10" decoding="async" fetchpriority="high">
                </div>
            </div>
        @endif
    </section>

    @if ($featuredProgram)
        <section class="bg-[var(--surface-muted)] py-20 lg:py-24">
            <div class="mx-auto w-full px-6 sm:w-[88vw] lg:px-10 xl:px-12">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="section-label mb-4">{{ $page->sectionValue('programs.kicker', 'Program') }}</p>
                        <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('programs.title', 'Arah kerja yang sedang berjalan.') }}</h2>
                    </div>
                    <a href="{{ route('programs.index') }}" class="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                        {{ $page->sectionValue('programs.cta_label', 'Lihat Semua Program') }}
                    </a>
                </div>

                <article class="surface-card overflow-hidden rounded-[2rem] lg:grid lg:grid-cols-[minmax(0,1fr)_24rem]">
                    <div class="p-8 lg:p-12">
                        <div class="mb-5 flex flex-wrap items-center gap-3">
                            <span class="rounded-lg bg-[var(--secondary-soft)] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--secondary-ink)]">
                                {{ $featuredProgram->category?->name ?? 'Program' }}
                            </span>
                            <span class="rounded-lg bg-[var(--surface-muted)] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--primary)]">
                                {{ $featuredProgram->phase === 'upcoming' ? 'Mendatang' : ($featuredProgram->phase === 'archived' ? 'Arsip' : 'Aktif') }}
                            </span>
                        </div>
                        <h3 class="font-editorial text-4xl leading-tight">{{ $featuredProgram->title }}</h3>
                        <p class="mt-6 max-w-3xl text-base leading-8 text-[var(--ink-muted)]">{{ $featuredProgram->excerpt }}</p>

                        @if ($featuredProgram->partners->isNotEmpty())
                            <div class="mt-8 flex flex-wrap gap-3">
                                @foreach ($featuredProgram->partners as $partner)
                                    <span class="rounded-full border border-[color:rgba(0,96,76,0.18)] px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                                        {{ $partner->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <a href="{{ route('programs.show', $featuredProgram) }}" class="mt-10 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                            Lihat Detail Program
                            <span class="material-symbols-outlined">north_east</span>
                        </a>
                    </div>
                    <img src="{{ $featuredProgram->featured_image_url }}" alt="Dokumentasi program {{ $featuredProgram->title }}" class="h-full min-h-72 w-full object-cover" loading="lazy" decoding="async">
                </article>
            </div>
        </section>
    @endif

    @if ($latestActivities->isNotEmpty() || $latestVideos->isNotEmpty())
        <section class="mx-auto w-full px-6 py-20 sm:w-[88vw] lg:px-10 lg:py-24 xl:px-12">
            <div class="mb-10 flex flex-wrap items-end justify-between gap-6">
                <div>
                    <p class="section-label mb-4">{{ $page->sectionValue('media.kicker', 'Media') }}</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('media.title', 'Aktivitas dan video terbaru dari lapangan.') }}</h2>
                </div>
                <a href="{{ route('media.index') }}" class="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                    {{ $page->sectionValue('media.cta_label', 'Jelajahi Media') }}
                </a>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                @if ($latestActivities->isNotEmpty())
                    <div>
                        <p class="section-label mb-4">{{ $page->sectionValue('media.activities_label', 'Aktivitas') }}</p>
                        <div class="space-y-5">
                            @foreach ($latestActivities as $activity)
                                <article class="surface-card rounded-[1.75rem] p-5">
                                    <div class="flex gap-5">
                                        <img src="{{ $activity->featured_image_url }}" alt="Dokumentasi aktivitas {{ $activity->title }}" class="h-24 w-24 rounded-2xl object-cover" loading="lazy" decoding="async">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-[var(--primary)]">
                                                {{ optional($activity->activity_date)->translatedFormat('d F Y') }}
                                            </p>
                                            <h3 class="mt-2 font-editorial text-2xl leading-tight">{{ $activity->title }}</h3>
                                            <p class="mt-3 text-sm leading-7 text-[var(--ink-muted)]">{{ $activity->summary }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($latestVideos->isNotEmpty())
                    <div>
                        <p class="section-label mb-4">{{ $page->sectionValue('media.videos_label', 'Video') }}</p>
                        <div class="space-y-5">
                            @foreach ($latestVideos as $video)
                                <article class="surface-card rounded-[1.75rem] overflow-hidden">
                                    <img src="{{ $video->resolvedThumbnailUrl() }}" alt="Thumbnail video {{ $video->title }}" class="aspect-video w-full object-cover" loading="lazy" decoding="async">
                                    <div class="p-6">
                                        <h3 class="font-editorial text-2xl leading-tight">{{ $video->title }}</h3>
                                        <p class="mt-3 text-sm leading-7 text-[var(--ink-muted)]">{{ $video->summary }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($latestEditorialPublications->isNotEmpty() || $latestArchiveDocuments->isNotEmpty())
        <section class="bg-[var(--surface-muted)] py-20 lg:py-24">
            <div class="mx-auto w-full px-6 sm:w-[88vw] lg:px-10 xl:px-12">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="section-label mb-4">{{ $page->sectionValue('publications.kicker', 'Publikasi') }}</p>
                        <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('publications.title', 'Jurnal, arsip, berita, artikel, dan opini.') }}</h2>
                    </div>
                    <a href="{{ route('publications.index') }}" class="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                        {{ $page->sectionValue('publications.cta_label', 'Lihat Publikasi') }}
                    </a>
                </div>

                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
                    @if ($latestEditorialPublications->isNotEmpty())
                        <div>
                            <p class="section-label mb-4">{{ $page->sectionValue('publications.editorial_label', 'Editorial') }}</p>
                            <div class="space-y-5">
                                @foreach ($latestEditorialPublications as $publication)
                                    <article class="surface-card rounded-[1.75rem] p-6">
                                        <p class="section-label mb-3">{{ $publication->category?->name ?? strtoupper($publication->type) }}</p>
                                        <h3 class="font-editorial text-2xl leading-tight">{{ $publication->title }}</h3>
                                        <p class="mt-3 text-sm leading-7 text-[var(--ink-muted)]">{{ $publication->excerpt }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($latestArchiveDocuments->isNotEmpty())
                        <div>
                            <p class="section-label mb-4">{{ $page->sectionValue('publications.archive_label', 'Arsip') }}</p>
                            <div class="space-y-5">
                                @foreach ($latestArchiveDocuments as $document)
                                    <article class="surface-card rounded-[1.75rem] p-6">
                                        <p class="section-label mb-3">{{ $document->category }}</p>
                                        <h3 class="font-editorial text-2xl leading-tight">{{ $document->title }}</h3>
                                        <p class="mt-3 text-sm leading-7 text-[var(--ink-muted)]">{{ $document->description }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-5xl px-6 py-20 lg:px-10">
        <div class="surface-card rounded-[2rem] border border-[color:rgba(190,201,195,0.2)] px-8 py-12 text-center md:px-14 md:py-16">
            <p class="section-label mb-5">{{ $page->sectionValue('closing.kicker', 'Terhubung Dengan Kami') }}</p>
            <h2 class="font-editorial text-3xl md:text-5xl">{{ $page->sectionValue('closing.title', 'Butuh informasi lebih lanjut atau ingin berkolaborasi?') }}</h2>
            <p class="mx-auto mt-6 max-w-2xl text-base leading-8 text-[var(--ink-muted)]">
                {{ $page->sectionValue('closing.body', 'Kami terbuka untuk percakapan seputar program, dokumentasi, publikasi, dan kolaborasi lintas sektor.') }}
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ $page->sectionValue('closing.primary_cta_url', route('contact.show')) }}" class="rounded-xl bg-[var(--primary-soft)] px-7 py-4 text-sm font-semibold uppercase tracking-[0.12em] text-white">
                    {{ $page->sectionValue('closing.primary_cta_label', 'Hubungi Kami') }}
                </a>
                <a href="{{ $page->sectionValue('closing.secondary_cta_url', route('media.index')) }}" class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.12em] text-[var(--primary)]">
                    {{ $page->sectionValue('closing.secondary_cta_label', 'Lihat Media') }}
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>
@endsection
