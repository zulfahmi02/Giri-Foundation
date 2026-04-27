@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-12 lg:px-10 lg:pt-10 lg:pb-16">
        <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Media') }}</p>
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-7">
                <h1 class="font-editorial text-4xl leading-[0.95] tracking-tight md:text-6xl">
                    {{ trim($page->heroValue('title_prefix', 'Dokumentasi')) }}
                    <span class="italic text-[var(--primary)]"> {{ trim($page->heroValue('highlight', 'aktivitas')) }} </span>
                    {{ trim($page->heroValue('title_suffix', 'dan video dari lapangan.')) }}
                </h1>
            </div>
            <div class="lg:col-span-5 lg:pt-2">
                <p class="text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', 'Jelajahi dokumentasi visual dari kegiatan lapangan, kolaborasi, dan kanal video GIRI Foundation.') }}
                </p>
            </div>
        </div>
    </section>

    @if ($activities->count() > 0)
        <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-10">
            <div class="mb-10">
                <p class="section-label mb-4">{{ $page->sectionValue('activities.kicker', 'Aktivitas') }}</p>
                <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('activities.title', 'Dokumentasi kegiatan terbaru.') }}</h2>
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($activities as $activity)
                    <article class="surface-card overflow-hidden rounded-[1.75rem]">
                        <img src="{{ $activity->featured_image_url }}" alt="{{ $activity->title }}" class="h-72 w-full object-cover">
                        <div class="p-8">
                            <p class="section-label mb-3">{{ optional($activity->activity_date)->translatedFormat('d F Y') }}</p>
                            <h3 class="font-editorial text-3xl">{{ $activity->title }}</h3>
                            <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $activity->summary }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($activities->hasPages())
                <div class="mt-10">
                    {{ $activities->links() }}
                </div>
            @endif
        </section>
    @endif

    @if ($videos->count() > 0)
        <section class="bg-[var(--surface-muted)] py-20 lg:py-24">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <div class="mb-10">
                    <p class="section-label mb-4">{{ $page->sectionValue('videos.kicker', 'Video') }}</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('videos.title', 'Video terbaru dari kanal kami.') }}</h2>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    @foreach ($videos as $video)
                        <article class="surface-card overflow-hidden rounded-[1.75rem]">
                            @if ($video->embedUrl())
                                <iframe src="{{ $video->embedUrl() }}" title="{{ $video->title }}" class="aspect-video w-full border-0" allowfullscreen loading="lazy"></iframe>
                            @else
                                <img src="{{ $video->resolvedThumbnailUrl() }}" alt="{{ $video->title }}" class="aspect-video w-full object-cover">
                            @endif
                            <div class="p-8">
                                <h3 class="font-editorial text-3xl">{{ $video->title }}</h3>
                                <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $video->summary }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($videos->hasPages())
                    <div class="mt-10">
                        {{ $videos->links() }}
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection
