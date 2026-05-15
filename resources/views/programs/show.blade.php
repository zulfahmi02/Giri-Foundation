@extends('layouts.site')

@section('content')
    <header class="relative mb-16 min-h-[24rem] overflow-hidden sm:mb-20 sm:min-h-[30rem] lg:min-h-[34rem]">
        <img src="{{ $program->resolvedFeaturedImageUrl() }}" alt="Sampul program {{ $program->title }}" class="h-[24rem] w-full object-cover sm:h-[30rem] lg:h-[34rem]" decoding="async" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-t from-[color:rgba(252,249,248,0.92)] via-transparent to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 mx-auto max-w-7xl px-6 pb-8 sm:pb-12 lg:px-10">
            @include('pages.partials.breadcrumbs', [
                'class' => 'mb-6',
                'breadcrumbs' => [
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Program', 'url' => route('programs.index')],
                    ['label' => $program->title, 'url' => route('programs.show', $program)],
                ],
            ])
            <span class="mb-6 inline-block rounded-full bg-[var(--secondary-soft)] px-4 py-2 text-[10px] font-bold uppercase tracking-[0.18em] text-[var(--secondary-ink)]">
                {{ $program->category?->name ?? 'Program' }}
            </span>
            <h1 class="font-editorial max-w-4xl text-4xl italic leading-none sm:text-5xl lg:text-7xl xl:text-8xl">{{ $program->title }}</h1>
            <div class="mt-8 grid gap-6 text-sm sm:grid-cols-3 sm:gap-8 md:flex md:flex-wrap md:gap-10">
                <div>
                    <p class="section-label mb-2 text-[var(--ink-muted)]">Lokasi</p>
                    <p class="font-semibold">{{ $program->location_name }}</p>
                </div>
                <div>
                    <p class="section-label mb-2 text-[var(--ink-muted)]">Penerima Manfaat</p>
                    <p class="font-semibold">{{ number_format($program->beneficiaries_count, 0, ',', '.') }}+</p>
                </div>
                <div>
                    <p class="section-label mb-2 text-[var(--ink-muted)]">Durasi</p>
                    <p class="font-semibold">
                        {{ optional($program->start_date)->format('Y') }}{{ $program->end_date ? ' - ' . $program->end_date->format('Y') : ' - Berjalan' }}
                    </p>
                </div>
            </div>
        </div>
    </header>

    <section class="mx-auto grid max-w-7xl gap-10 px-6 pb-16 lg:grid-cols-12 lg:gap-16 lg:px-10 lg:pb-20">
        <div class="lg:col-span-7">
            <p class="section-label mb-6">Misi Program</p>
            <p class="font-editorial text-2xl italic leading-relaxed sm:text-3xl md:text-4xl">{{ $program->excerpt }}</p>
            <div class="editorial-prose mt-10 text-base leading-8 text-[var(--ink-muted)]">
                @foreach (preg_split("/\r\n\r\n|\n\n|\r\r/", $program->description) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>

        <aside class="surface-card rounded-[2rem] p-6 sm:p-8 lg:col-span-5">
            <p class="section-label mb-8 text-[var(--ink-muted)]">Ringkasan</p>
            <div class="space-y-6 text-sm leading-7 text-[var(--ink-muted)]">
                <div>
                    <p class="font-semibold text-[var(--ink)]">Sasaran Penerima Manfaat</p>
                    <p>{{ $program->target_beneficiaries }}</p>
                </div>
                <div>
                    <p class="font-semibold text-[var(--ink)]">Anggaran</p>
                    <p>Rp{{ number_format((float) $program->budget_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="font-semibold text-[var(--ink)]">Kota & Provinsi</p>
                    <p>{{ $program->city }}, {{ $program->province }}</p>
                </div>
                <div>
                    <p class="font-semibold text-[var(--ink)]">Mitra</p>
                    <p>{{ $program->partners->pluck('name')->join(', ') ?: '-' }}</p>
                </div>
            </div>
            <a href="{{ route('donate.show') }}" class="mt-10 inline-block w-full rounded-xl bg-[var(--primary)] px-8 py-4 text-center text-sm font-semibold uppercase tracking-[0.14em] text-white">
                Dukung Program Ini
            </a>
        </aside>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-16 lg:px-10 lg:pb-20">
        <div class="mb-10 flex items-end justify-between gap-8">
            <div>
                <p class="section-label mb-4">Momen Dampak</p>
                <h2 class="font-editorial text-4xl md:text-5xl">Galeri lapangan</h2>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
            @foreach ($program->galleries as $gallery)
                <figure class="{{ $loop->first ? 'md:col-span-2 md:row-span-2' : '' }} overflow-hidden rounded-[1.5rem]">
                    <img
                        src="{{ $gallery->resolvedFileUrl() }}"
                        alt="{{ $gallery->caption ?: 'Dokumentasi program ' . $program->title }}"
                        class="h-full w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                </figure>
            @endforeach
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mb-10">
                <p class="section-label mb-4">Kabar Terbaru</p>
                <h2 class="font-editorial text-4xl md:text-5xl">Pembaruan terbaru dan bacaan terkait.</h2>
            </div>

            <div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
                @foreach ($program->activities->take(3) as $activity)
                    <article class="surface-card rounded-[1.75rem] p-6 sm:p-8">
                        <p class="section-label mb-4">{{ optional($activity->published_at)->translatedFormat('F Y') }}</p>
                        <h3 class="font-editorial text-3xl">{{ $activity->title }}</h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $activity->summary }}</p>
                    </article>
                @endforeach
                @foreach ($relatedStories->take(max(0, 3 - $program->activities->take(3)->count())) as $story)
                    <article class="surface-card rounded-[1.75rem] p-6 sm:p-8">
                        <p class="section-label mb-4">{{ optional($story->published_at)->translatedFormat('F Y') }}</p>
                        <h3 class="font-editorial text-3xl">
                            <a href="{{ route('stories.show', $story) }}">{{ $story->displayTitle() }}</a>
                        </h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $story->excerpt }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
