@extends('layouts.site')

@section('content')
    <header class="relative mb-16 min-h-[24rem] overflow-hidden sm:min-h-[30rem]">
        <img src="{{ $activity->resolvedFeaturedImageUrl() }}" alt="Dokumentasi {{ $activity->title }}" class="h-[24rem] w-full object-cover sm:h-[30rem]" decoding="async" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-t from-[color:rgba(252,249,248,0.94)] via-transparent to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 mx-auto max-w-7xl px-6 pb-8 sm:pb-12 lg:px-10">
            @include('pages.partials.breadcrumbs', [
                'class' => 'mb-6',
                'breadcrumbs' => [
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Media', 'url' => route('media.index')],
                    ['label' => $activity->title, 'url' => route('activities.show', $activity)],
                ],
            ])
            <p class="section-label mb-4">{{ $activity->program?->title ?? 'Aktivitas Umum' }}</p>
            <h1 class="font-editorial max-w-4xl text-4xl italic leading-none sm:text-5xl lg:text-7xl">{{ $activity->title }}</h1>
            <div class="mt-6 flex flex-wrap gap-x-8 gap-y-3 text-sm font-semibold">
                @if ($activity->activity_date)
                    <span>{{ $activity->activity_date->translatedFormat('d F Y') }}</span>
                @endif
                @if ($activity->location_name)
                    <span>{{ $activity->location_name }}</span>
                @endif
            </div>
        </div>
    </header>

    <section class="mx-auto max-w-4xl px-6 pb-16 lg:px-10 lg:pb-20">
        @if ($activity->summary)
            <p class="font-editorial text-2xl italic leading-relaxed sm:text-3xl">{{ $activity->summary }}</p>
        @endif
        <div class="editorial-prose mt-10 text-base leading-8 text-[var(--ink-muted)]">
            @foreach (preg_split("/\r\n\r\n|\n\n|\r\r/", $activity->description) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </section>

    @if ($activity->galleries->isNotEmpty())
        <section class="mx-auto max-w-7xl px-6 pb-16 lg:px-10 lg:pb-20">
            <div class="mb-10">
                <p class="section-label mb-4">Galeri Foto</p>
                <h2 class="font-editorial text-4xl md:text-5xl">Momen dari kegiatan ini.</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($activity->galleries as $gallery)
                    <figure class="overflow-hidden rounded-[1.5rem] bg-[var(--surface-muted)] {{ $loop->first ? 'md:col-span-2 md:row-span-2' : '' }}">
                        <img src="{{ $gallery->resolvedFileUrl() }}" alt="{{ $gallery->caption ?: 'Dokumentasi '.$activity->title }}" class="aspect-[4/3] h-full w-full object-cover" loading="lazy" decoding="async">
                        @if ($gallery->caption)
                            <figcaption class="p-4 text-sm leading-6 text-[var(--ink-muted)]">{{ $gallery->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </section>
    @endif

    @if ($activity->videos->isNotEmpty())
        <section class="bg-[var(--surface-muted)] py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <div class="mb-10">
                    <p class="section-label mb-4">Video</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">Rekaman kegiatan.</h2>
                </div>
                <div class="grid gap-8 lg:grid-cols-2">
                    @foreach ($activity->videos as $video)
                        @if ($video->embedUrl())
                            <article class="surface-card overflow-hidden rounded-[1.75rem]">
                                <iframe src="{{ $video->embedUrl() }}" title="{{ $video->title ?: $activity->title }}" class="aspect-video w-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                @if ($video->title)
                                    <h3 class="p-6 font-editorial text-2xl">{{ $video->title }}</h3>
                                @endif
                            </article>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
