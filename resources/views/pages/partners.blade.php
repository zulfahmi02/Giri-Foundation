@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-7">
                <p class="section-label mb-6">{{ $page->heroValue('kicker', 'Kemitraan') }}</p>
                <h1 class="font-editorial text-5xl leading-[0.95] md:text-7xl">
                    {{ $page->heroValue('title_prefix', 'Merancang') }}
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'perubahan') }}</span>
                    {{ $page->heroValue('title_suffix', 'bersama.') }}
                </h1>
                <p class="mt-8 max-w-2xl text-lg leading-8 text-[var(--ink-muted)]">
                    {{ $page->heroValue('body', 'Kami membangun relasi jangka panjang dengan institusi, komunitas, dan pendukung yang peduli pada sistem yang bertahan lama, bukan perhatian sesaat.') }}
                </p>
            </div>
            <div class="lg:col-span-5">
                <div class="surface-card rounded-[2rem] p-8">
                    <p class="font-editorial text-6xl text-[var(--primary)]">{{ $page->sectionValue('highlight.value', number_format($partners->count(), 0, ',', '.')) }}</p>
                    <p class="mt-3 text-xs font-bold uppercase tracking-[0.18em] text-[var(--ink-muted)]">{{ $page->sectionValue('highlight.label', 'Mitra Aktif') }}</p>
                    <p class="mt-6 text-sm leading-7 text-[var(--ink-muted)]">
                        {{ $page->sectionValue('highlight.body', 'Kolaborasi mencakup filantropi, jaringan komunitas, implementasi strategis, dan akuntabilitas naratif.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mb-12">
                <p class="section-label mb-4">{{ $page->sectionValue('collaborators.kicker', 'Kolaborator Utama') }}</p>
                <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('collaborators.title', 'Arsip kolaborasi dengan institusi yang sejalan.') }}</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($partners as $partner)
                    <article class="surface-card rounded-[1.5rem] p-8">
                        <p class="font-editorial text-3xl">{{ $partner->name }}</p>
                        <p class="mt-3 text-xs font-bold uppercase tracking-[0.18em] text-[var(--primary)]">{{ match ($partner->type) { 'foundation' => 'YAYASAN', 'community' => 'KOMUNITAS', 'corporate' => 'KORPORASI', default => strtoupper($partner->type) } }}</p>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $partner->description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="mb-10">
            <p class="section-label mb-4">{{ $page->sectionValue('programs.kicker', 'Program Bersama Mitra') }}</p>
            <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('programs.title', 'Tempat kolaborasi menjadi aksi.') }}</h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            @foreach ($partnerPrograms as $program)
                <article class="overflow-hidden rounded-[1.75rem] bg-white shadow-[0_12px_40px_rgba(15,15,15,0.04)]">
                    <img src="{{ $program->featured_image_url }}" alt="{{ $program->title }}" class="h-64 w-full object-cover">
                    <div class="p-8">
                        <p class="section-label mb-4">{{ $program->category?->name }}</p>
                        <h3 class="font-editorial text-3xl">{{ $program->title }}</h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $program->excerpt }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto max-w-4xl px-6 lg:px-10">
            <div class="surface-card rounded-[2rem] p-8 lg:p-12">
                <p class="section-label mb-4">{{ $page->sectionValue('inquiry.kicker', 'Kembangkan Arsip Bersama Kami') }}</p>
                <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('inquiry.title', 'Mulai percakapan kemitraan.') }}</h2>

                <form method="POST" action="{{ route('partners.store') }}" class="mt-10 grid gap-6 md:grid-cols-2">
                    @csrf
                    <div>
                        <label for="organization_name" class="section-label mb-3 block">Organisasi</label>
                        <input id="organization_name" name="organization_name" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div>
                        <label for="contact_person" class="section-label mb-3 block">Narahubung</label>
                        <input id="contact_person" name="contact_person" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div>
                        <label for="email" class="section-label mb-3 block">Email</label>
                        <input id="email" name="email" type="email" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div>
                        <label for="phone" class="section-label mb-3 block">Telepon</label>
                        <input id="phone" name="phone" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]">
                    </div>
                    <div class="md:col-span-2">
                        <label for="inquiry_type" class="section-label mb-3 block">Jenis Pertanyaan</label>
                        <input id="inquiry_type" name="inquiry_type" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" placeholder="Pendanaan, implementasi, media, riset..." required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="message" class="section-label mb-3 block">Pesan</label>
                        <textarea id="message" name="message" rows="5" class="w-full rounded-2xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button class="rounded-xl bg-[var(--primary)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.14em] text-white">
                            {{ $page->sectionValue('inquiry.submit_label', 'Kirim Permintaan') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
