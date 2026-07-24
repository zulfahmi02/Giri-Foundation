@extends('layouts.site')

@section('content')
    <header class="relative mb-12 min-h-[30rem] overflow-hidden bg-[var(--ink)] sm:mb-16 sm:min-h-[36rem] lg:min-h-[40rem]">
        <img src="{{ $activity->resolvedFeaturedImageUrl() }}" alt="Dokumentasi {{ $activity->title }}" class="absolute inset-0 h-full w-full object-cover object-center sm:object-[center_42%]" decoding="async" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/55 to-black/15"></div>
        <div class="absolute inset-x-0 bottom-0 mx-auto max-w-7xl px-6 pb-8 text-white sm:pb-12 lg:px-10 lg:pb-14">
            @include('pages.partials.breadcrumbs', [
                'class' => 'mb-6 text-white/80 [&_a]:text-white/80 [&_a:hover]:text-white [&_span]:text-white/70',
                'breadcrumbs' => [
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Media', 'url' => route('media.index')],
                    ['label' => $activity->title, 'url' => route('activities.show', $activity)],
                ],
            ])
            <p class="section-label mb-4 !text-white/80 drop-shadow-sm">{{ $activity->program?->title ?? 'Aktivitas Umum' }}</p>
            <h1 class="font-editorial max-w-5xl break-words text-[clamp(2.5rem,7vw,4.75rem)] italic leading-[0.95] tracking-[-0.02em] text-white drop-shadow-md [text-wrap:balance]">{{ $activity->title }}</h1>
            <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm font-semibold leading-6 text-white/90 drop-shadow-sm sm:gap-x-8">
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
        <div class="mb-10 flex flex-wrap items-center gap-3 border-b border-[var(--line)] pb-8">
            <span class="mr-2 text-xs font-bold uppercase tracking-[0.16em] text-[var(--ink-muted)]">Bagikan</span>
            <a
                href="{{ $shareUrls['whatsapp'] }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Bagikan {{ $activity->title }} melalui WhatsApp"
                class="inline-flex items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#1fba59] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#25D366]"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                    <path d="M12.04 2a9.84 9.84 0 0 0-8.5 14.78L2 22l5.36-1.5A9.95 9.95 0 1 0 12.04 2Zm0 17.9a8.05 8.05 0 0 1-4.1-1.12l-.3-.18-3.18.88.85-3.1-.2-.32a7.95 7.95 0 1 1 6.93 3.84Zm4.38-5.96c-.24-.12-1.42-.7-1.64-.78-.22-.08-.38-.12-.54.12-.16.24-.62.78-.76.94-.14.16-.28.18-.52.06-.24-.12-1.01-.37-1.93-1.19a7.2 7.2 0 0 1-1.34-1.66c-.14-.24-.01-.37.1-.49.11-.11.24-.28.36-.42.12-.14.16-.24.24-.4.08-.16.04-.3-.02-.42-.06-.12-.54-1.3-.74-1.78-.2-.47-.4-.4-.54-.41h-.46c-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2s.86 2.32.98 2.48c.12.16 1.69 2.58 4.1 3.62.57.25 1.02.39 1.37.5.58.18 1.1.16 1.51.1.46-.07 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28Z"/>
                </svg>
                WhatsApp
            </a>
            <a
                href="{{ $shareUrls['facebook'] }}"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Bagikan {{ $activity->title }} melalui Facebook"
                class="inline-flex items-center gap-2 rounded-full bg-[#1877F2] px-5 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#1268d8] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1877F2]"
            >
                <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                    <path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.53-4.7 1.31 0 2.69.24 2.69.24v2.97h-1.52c-1.49 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z"/>
                </svg>
                Facebook
            </a>
        </div>

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
