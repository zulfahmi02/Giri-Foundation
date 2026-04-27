@extends('layouts.site')

@section('content')
    @if ($featuredStory)
        <section class="mx-auto max-w-7xl px-6 pt-10 pb-16 lg:px-10 lg:pt-12 lg:pb-20">
            <div class="grid gap-10 lg:grid-cols-12 lg:items-start">
                <div class="overflow-hidden rounded-[2rem] lg:col-span-7">
                    <img src="{{ $featuredStory->featured_image_url }}" alt="Sampul cerita {{ $featuredStory->displayTitle() }}" class="h-[28rem] w-full object-cover md:h-[34rem] lg:h-[36rem] xl:h-[38rem]" decoding="async" fetchpriority="high">
                </div>
                <div class="lg:col-span-5 lg:pt-6">
                    <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Cerita Pilihan') }}</p>
                    <h1 class="font-editorial text-5xl leading-[1.02] text-[var(--primary)] md:text-6xl">
                        {{ $featuredStory->displayTitle() }}
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-[var(--ink-muted)]">{{ $featuredStory->displayExcerpt() }}</p>
                    <div class="mt-7">
                        <p class="text-sm font-semibold">{{ $featuredStory->displayAuthorName() }}</p>
                        <p class="mt-1 text-xs font-bold uppercase tracking-[0.18em] text-[var(--ink-muted)]">{{ optional($featuredStory->published_at)->translatedFormat('d F Y') }}</p>
                    </div>
                    <a href="{{ route('stories.show', $featuredStory) }}" class="mt-7 inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                        {{ $page->heroValue('primary_cta_label', 'Baca Arsip Lengkap') }}
                        <span class="material-symbols-outlined">arrow_right_alt</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl space-y-20 px-6 pb-20 lg:px-10">
        @foreach ($secondaryStories as $story)
            <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
                <div class="{{ $loop->odd ? 'lg:col-span-5' : 'lg:col-span-7 lg:order-2' }}">
                    <div class="surface-card rounded-[2rem] p-8 lg:p-12">
                        <p class="section-label mb-4">{{ $story->category?->name }}</p>
                        <h2 class="font-editorial text-4xl leading-tight md:text-5xl">{{ $story->displayTitle() }}</h2>
                        <p class="mt-6 text-base leading-8 text-[var(--ink-muted)]">{{ $story->displayExcerpt() }}</p>
                        <a href="{{ route('stories.show', $story) }}" class="mt-8 inline-block border-b border-[var(--primary)] pb-1 text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">
                            Lanjut Membaca
                        </a>
                    </div>
                </div>
                <div class="{{ $loop->odd ? 'lg:col-span-7' : 'lg:col-span-5 lg:order-1' }}">
                    <img src="{{ $story->featured_image_url }}" alt="Sampul cerita {{ $story->displayTitle() }}" class="w-full rounded-[2rem] object-cover {{ $loop->odd ? 'aspect-[16/10]' : 'aspect-[4/5]' }}" loading="lazy" decoding="async">
                </div>
            </div>
        @endforeach
    </section>

    <section class="bg-white py-20">
        <div class="mx-auto max-w-4xl px-6 text-center lg:px-10">
            <h2 class="font-editorial text-4xl italic text-[var(--primary)] md:text-5xl">
                {{ $page->sectionValue('newsletter.title', 'Tetap terhubung dengan cerita yang penting.') }}
            </h2>
            <p class="mt-6 text-lg leading-8 text-[var(--ink-muted)]">
                {{ $page->sectionValue('newsletter.body', 'Ikuti ringkasan editorial bulanan kami untuk pembahasan mendalam tentang dampak, budaya, dan daya hidup manusia.') }}
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <p class="section-label mb-8 text-[var(--ink-muted)]">{{ $page->sectionValue('archive.kicker', 'Lebih Banyak Dari Arsip') }}</p>
        <div class="grid gap-8 md:grid-cols-3">
            @foreach ($archiveStories as $story)
                <article class="group">
                    <div class="mb-6 overflow-hidden rounded-[1.5rem]">
                        <img src="{{ $story->featured_image_url }}" alt="Sampul cerita {{ $story->displayTitle() }}" class="aspect-[3/2] w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                    </div>
                    <p class="section-label mb-3">{{ $story->category?->name }}</p>
                    <h3 class="font-editorial text-3xl leading-tight transition group-hover:text-[var(--primary)]">
                        <a href="{{ route('stories.show', $story) }}">{{ $story->displayTitle() }}</a>
                    </h3>
                    <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $story->displayExcerpt() }}</p>
                </article>
            @endforeach
        </div>

        @if ($archiveStories->hasPages())
            <div class="mt-10">
                {{ $archiveStories->links() }}
            </div>
        @endif
    </section>
@endsection
