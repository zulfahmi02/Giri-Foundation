@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-12 lg:px-10 lg:pt-10 lg:pb-16">
        <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Publikasi') }}</p>
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-7">
                <h1 class="font-editorial text-4xl leading-[0.95] tracking-tight md:text-6xl">
                    {{ trim($page->heroValue('title_prefix', 'Gagasan, arsip, dan')) }}
                    <span class="italic text-[var(--primary)]"> {{ trim($page->heroValue('highlight', 'narasi')) }} </span>
                    {{ trim($page->heroValue('title_suffix', 'yang kami terbitkan.')) }}
                </h1>
            </div>
            <div class="lg:col-span-5 lg:pt-2">
                <p class="text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', 'Halaman ini merangkum publikasi editorial dan arsip dokumen yang membentuk pengetahuan publik lembaga.') }}
                </p>
            </div>
        </div>
    </section>

    @php
        $sections = [
            [
                'collection' => $journals,
                'kicker' => $page->sectionValue('journals.kicker', 'Jurnal'),
                'title' => $page->sectionValue('journals.title', 'Kajian dan tulisan mendalam.'),
                'kind' => 'content',
            ],
            [
                'collection' => $archives,
                'kicker' => $page->sectionValue('archives.kicker', 'Arsip'),
                'title' => $page->sectionValue('archives.title', 'Dokumen dan rekam publikasi formal.'),
                'kind' => 'document',
            ],
            [
                'collection' => $newsItems,
                'kicker' => $page->sectionValue('news.kicker', 'Berita'),
                'title' => $page->sectionValue('news.title', 'Kabar terbaru dari kegiatan dan organisasi.'),
                'kind' => 'content',
            ],
            [
                'collection' => $articles,
                'kicker' => $page->sectionValue('articles.kicker', 'Artikel'),
                'title' => $page->sectionValue('articles.title', 'Tulisan informatif dan pembahasan tematik.'),
                'kind' => 'content',
            ],
            [
                'collection' => $opinions,
                'kicker' => $page->sectionValue('opinions.kicker', 'Opini'),
                'title' => $page->sectionValue('opinions.title', 'Refleksi dan sudut pandang editorial.'),
                'kind' => 'content',
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

                <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($section['collection'] as $item)
                        <article class="surface-card rounded-[1.75rem] p-8">
                            <p class="section-label mb-3">
                                {{ $section['kind'] === 'document' ? $item->category : ($item->category?->name ?? strtoupper($item->type)) }}
                            </p>
                            <h3 class="font-editorial text-3xl leading-tight">{{ $item->title }}</h3>
                            <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">
                                {{ $section['kind'] === 'document' ? $item->description : $item->excerpt }}
                            </p>
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
