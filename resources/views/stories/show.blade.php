@extends('layouts.site')

@section('content')
    <article class="mx-auto max-w-5xl px-6 py-16 lg:px-10 lg:py-24">
        @include('pages.partials.breadcrumbs', [
            'class' => 'mb-6',
            'breadcrumbs' => [
                ['label' => 'Beranda', 'url' => route('home')],
                ['label' => 'Cerita', 'url' => route('stories.index')],
                ['label' => $story->displayTitle(), 'url' => route('stories.show', $story)],
            ],
        ])
        <p class="section-label mb-6">{{ $story->category?->name }} • {{ optional($story->published_at)->translatedFormat('d F Y') }}</p>
        <h1 class="font-editorial text-5xl leading-[1.05] md:text-7xl">{{ $story->displayTitle() }}</h1>
        <p class="mt-8 max-w-3xl text-xl leading-9 text-[var(--ink-muted)]">{{ $story->displayExcerpt() }}</p>

        <div class="mt-12 overflow-hidden rounded-[2rem]">
            <img src="{{ $story->featured_image_url }}" alt="Sampul cerita {{ $story->displayTitle() }}" class="h-[34rem] w-full object-cover" decoding="async" fetchpriority="high">
        </div>

        <div class="mt-12 flex flex-wrap items-center gap-6 text-sm text-[var(--ink-muted)]">
            <span>Oleh {{ $story->displayAuthorName() }}</span>
            @foreach ($story->tags as $tag)
                <span class="rounded-full bg-[var(--secondary-soft)] px-3 py-1 text-[var(--secondary-ink)]">{{ $tag->name }}</span>
            @endforeach
        </div>

        <div class="editorial-prose mt-12 text-lg leading-9 text-[var(--ink)]">
            @foreach (preg_split("/\r\n\r\n|\n\n|\r\r/", $story->displayBody()) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mb-10 flex items-end justify-between gap-8">
                <div>
                    <p class="section-label mb-4">Bacaan Lanjutan</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">Entri arsip terkait.</h2>
                </div>
                <a href="{{ route('stories.index') }}" class="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">Lihat Semua Cerita</a>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($relatedStories as $relatedStory)
                    <article class="surface-card rounded-[1.75rem] p-8">
                        <p class="section-label mb-4">{{ $relatedStory->category?->name }}</p>
                        <h3 class="font-editorial text-3xl leading-tight">
                            <a href="{{ route('stories.show', $relatedStory) }}">{{ $relatedStory->displayTitle() }}</a>
                        </h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $relatedStory->displayExcerpt() }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
