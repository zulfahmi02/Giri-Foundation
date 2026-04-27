@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-16 lg:px-10 lg:pt-10 lg:pb-20">
        <div class="grid gap-10 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-5">
                <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Kontak') }}</p>
                <h1 class="font-editorial text-4xl leading-[0.95] md:text-6xl">
                    {{ $page->heroValue('title_prefix', 'Mari membentuk') }}
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'masa depan') }}</span>
                    {{ $page->heroValue('title_suffix', 'bersama.') }}
                </h1>
                <p class="mt-6 max-w-lg text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', 'Hubungi kami untuk kolaborasi, permintaan editorial, kunjungan lapangan, atau pertanyaan umum.') }}
                </p>

                <div class="mt-10 space-y-4">
                    <p class="section-label">{{ $page->sectionValue('details.kicker', 'Informasi Kontak') }}</p>
                    <h2 class="font-editorial text-3xl">{{ $page->sectionValue('details.title', 'Beberapa cara untuk menghubungi kami.') }}</h2>
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-8 lg:col-span-7 lg:p-10">
                <h2 class="font-editorial text-3xl">{{ $page->sectionValue('form.title', 'Kirim pesan langsung kepada tim kami.') }}</h2>

                <form method="POST" action="{{ route('contact.store') }}" class="mt-8 grid gap-6 md:grid-cols-2">
                    @csrf
                    <div>
                        <label for="name" class="section-label mb-3 block">Nama</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div>
                        <label for="email" class="section-label mb-3 block">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div>
                        <label for="phone" class="section-label mb-3 block">Telepon</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]">
                    </div>
                    <div>
                        <label for="subject" class="section-label mb-3 block">Subjek</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="message" class="section-label mb-3 block">Pesan</label>
                        <textarea id="message" name="message" rows="6" class="w-full rounded-2xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]" required>{{ old('message') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button class="rounded-xl bg-[var(--primary)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.14em] text-white">
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-3 lg:px-10">
            <article class="surface-card rounded-[1.75rem] p-8">
                <p class="section-label mb-4">WhatsApp</p>
                <p class="font-editorial text-3xl">{{ ($siteProfile ?? null)?->whatsapp_number ?? '+62 812 0000 0000' }}</p>
            </article>
            <article class="surface-card rounded-[1.75rem] p-8">
                <p class="section-label mb-4">Email</p>
                <p class="font-editorial text-3xl">{{ ($siteProfile ?? null)?->email ?? 'hello@giri.foundation' }}</p>
            </article>
            <article class="surface-card rounded-[1.75rem] p-8">
                <p class="section-label mb-4">Telepon</p>
                <p class="font-editorial text-3xl">{{ ($siteProfile ?? null)?->phone ?? '+62 000 0000 000' }}</p>
            </article>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="mb-8">
            <p class="section-label mb-4">{{ $page->sectionValue('location.kicker', 'Lokasi') }}</p>
            <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('location.title', 'Alamat dan titik temu lembaga.') }}</h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
            <article class="surface-card rounded-[1.75rem] p-8">
                <p class="text-sm leading-8 text-[var(--ink-muted)]">{{ ($siteProfile ?? null)?->address ?? 'Bali, Indonesia' }}</p>
            </article>

            @if (filled(($siteProfile ?? null)?->google_maps_embed))
                <div class="overflow-hidden rounded-[1.75rem] border border-[color:rgba(190,201,195,0.28)]">
                    <iframe src="{{ ($siteProfile ?? null)?->google_maps_embed }}" class="h-80 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            @endif
        </div>
    </section>
@endsection
