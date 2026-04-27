@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-12 lg:px-10 lg:pt-10 lg:pb-16">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-7">
                <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Program') }}</p>
                <h1 class="font-editorial text-4xl leading-[0.95] tracking-tight md:text-6xl">
                    {{ $page->heroValue('title_prefix', 'Mengarsipkan') }}
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'potensi') }}</span>{{ $page->heroValue('title_suffix', '.') }}
                </h1>
            </div>
            <div class="lg:col-span-5 lg:pt-2">
                <p class="text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', 'Jelajahi inisiatif tempat GIRI berinvestasi pada sistem yang tangguh, kapasitas lokal, dan pendampingan jangka panjang.') }}
                </p>
            </div>
        </div>
    </section>

    @php
        $sections = [
            [
                'collection' => $activePrograms,
                'kicker' => $page->sectionValue('active.kicker', 'Program Aktif'),
                'title' => $page->sectionValue('active.title', 'Inisiatif yang sedang berjalan saat ini.'),
            ],
            [
                'collection' => $partnershipPrograms,
                'kicker' => $page->sectionValue('partnership.kicker', 'Program Kerja Sama'),
                'title' => $page->sectionValue('partnership.title', 'Program aktif yang dijalankan bersama mitra.'),
            ],
            [
                'collection' => $upcomingPrograms,
                'kicker' => $page->sectionValue('upcoming.kicker', 'Program Mendatang'),
                'title' => $page->sectionValue('upcoming.title', 'Inisiatif yang sedang dipersiapkan untuk fase berikutnya.'),
            ],
            [
                'collection' => $archivedPrograms,
                'kicker' => $page->sectionValue('archive.kicker', 'Arsip Program'),
                'title' => $page->sectionValue('archive.title', 'Program yang telah lewat namun tetap menjadi bagian dari rekam jejak kerja kami.'),
            ],
        ];
    @endphp

    @foreach ($sections as $section)
        @if ($section['collection']->count() > 0)
            <section @class([
                'mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-20',
                'bg-[var(--surface-muted)]' => $loop->even,
            ])>
                <div class="mb-10">
                    <p class="section-label mb-4">{{ $section['kicker'] }}</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">{{ $section['title'] }}</h2>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    @foreach ($section['collection'] as $program)
                        <article class="surface-card overflow-hidden rounded-[1.75rem]">
                            <img src="{{ $program->featured_image_url }}" alt="Dokumentasi program {{ $program->title }}" class="h-64 w-full object-cover" loading="lazy" decoding="async">
                            <div class="p-8">
                                <div class="mb-4 flex flex-wrap items-center gap-3">
                                    <span class="rounded-lg bg-[var(--secondary-soft)] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--secondary-ink)]">
                                        {{ $program->category?->name ?? 'Program' }}
                                    </span>
                                    <span class="rounded-lg bg-[var(--surface-muted)] px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[var(--primary)]">
                                        {{ $program->phase === 'upcoming' ? 'Mendatang' : ($program->phase === 'archived' ? 'Arsip' : 'Aktif') }}
                                    </span>
                                </div>

                                <h3 class="font-editorial text-3xl leading-tight">
                                    <a href="{{ route('programs.show', $program) }}">{{ $program->title }}</a>
                                </h3>
                                <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $program->excerpt }}</p>

                                <div class="mt-6 flex flex-wrap gap-3 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--ink-muted)]">
                                    @if ($program->location_name)
                                        <span>{{ $program->location_name }}</span>
                                    @endif
                                    @if ($program->partners->isNotEmpty())
                                        <span>{{ $program->partners->count() }} mitra</span>
                                    @endif
                                </div>

                                @if ($program->partners->isNotEmpty())
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($program->partners as $partner)
                                            <span class="rounded-full border border-[color:rgba(0,96,76,0.18)] px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                                                {{ $partner->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($section['collection']->hasPages())
                    <div class="mt-10">
                        {{ $section['collection']->links() }}
                    </div>
                @endif
            </section>
        @endif
    @endforeach
@endsection
