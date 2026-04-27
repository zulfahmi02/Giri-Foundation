@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-12 lg:px-10 lg:pt-10 lg:pb-16">
        <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Arsip Dokumen') }}</p>
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-7">
                <h1 class="font-editorial text-4xl leading-[1.02] md:text-6xl">
                    {{ $page->heroValue('title_prefix', 'Dokumen &') }}
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'Wawasan') }}</span>{{ $page->heroValue('title_suffix', '') }}
                </h1>
            </div>
            <div class="lg:col-span-5 lg:pt-2">
                <p class="text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', 'Telusuri laporan, kerangka kebijakan, arsip riset, dan rencana strategis yang mendukung kerja publik yayasan.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-16 lg:px-10 lg:pb-20">
        <form method="GET" class="surface-card rounded-[2rem] p-8">
            <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
                <div>
                    <label for="search" class="section-label mb-3 block">{{ $page->sectionValue('filters.search_label', 'Cari') }}</label>
                    <input id="search" name="search" value="{{ $search }}" placeholder="{{ $page->sectionValue('filters.search_placeholder', 'Cari berdasarkan judul atau deskripsi...') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]">
                </div>
                <div>
                    <label for="category" class="section-label mb-3 block">{{ $page->sectionValue('filters.category_label', 'Kategori') }}</label>
                    <select id="category" name="category" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]">
                        <option value="">Semua Berkas</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="mt-6 rounded-xl bg-[var(--primary)] px-6 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-white">
                {{ $page->sectionValue('filters.submit_label', 'Saring Arsip') }}
            </button>
        </form>

        <div class="mt-10 space-y-6">
            @foreach ($documents as $document)
                <article class="surface-card rounded-[1.75rem] p-8">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                        <div class="max-w-3xl">
                            <p class="section-label mb-4">{{ $document->category }}</p>
                            <h2 class="font-editorial text-3xl">{{ $document->title }}</h2>
                            <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $document->description }}</p>
                        </div>
                        <div class="text-sm text-[var(--ink-muted)] md:text-right">
                            <p class="font-semibold text-[var(--primary)]">{{ $document->file_type }}</p>
                            <p class="mt-1">{{ number_format((int) $document->download_count, 0, ',', '.') }} unduhan</p>
                            <p class="mt-1">{{ optional($document->published_at)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($documents->hasPages())
            <div class="mt-10">
                {{ $documents->links() }}
            </div>
        @endif
    </section>
@endsection
