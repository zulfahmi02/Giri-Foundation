@extends('layouts.site')

@section('content')
    @php($organizationProfile = $siteProfile ?? null)
    @php($contactDetails = $organizationContact ?? ['whatsapp' => 'Belum diatur', 'email' => 'Belum diatur', 'phone' => 'Belum diatur', 'address' => 'Belum diatur'])
    @php($mapsEmbedUrl = $organizationProfile?->resolvedGoogleMapsEmbedUrl())

    <section class="mx-auto max-w-7xl px-6 pt-8 pb-16 lg:px-10 lg:pt-10 lg:pb-20">
        <div class="max-w-3xl">
            <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Kontak') }}</p>
            <h1 class="font-editorial text-4xl leading-[0.95] sm:text-5xl lg:text-6xl">
                {{ $page->heroValue('title_prefix', 'Mari membentuk') }}
                <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'masa depan') }}</span>
                {{ $page->heroValue('title_suffix', 'bersama.') }}
            </h1>
            <p class="mt-6 max-w-2xl text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                {{ $page->heroValue('body', 'Hubungi kami untuk kolaborasi, permintaan editorial, kunjungan lapangan, atau pertanyaan umum.') }}
            </p>
        </div>

        <div class="mt-12">
            @include('pages.partials.inquiry-pathways', ['currentPathway' => 'contact'])
        </div>

        <div class="mt-12 grid gap-10 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-5">
                <div class="space-y-4">
                    <p class="section-label">{{ $page->sectionValue('details.kicker', 'Informasi Kontak') }}</p>
                    <h2 class="font-editorial text-3xl">{{ $page->sectionValue('details.title', 'Beberapa cara untuk menghubungi kami.') }}</h2>
                </div>

                <div class="mt-8 rounded-[1.5rem] border border-[color:rgba(190,201,195,0.35)] bg-white px-6 py-5 text-sm leading-7 text-[var(--ink-muted)]">
                    Gunakan form ini untuk pertanyaan umum. Jika Anda butuh percakapan yang lebih terarah, pindah ke jalur
                    <a href="{{ route('consultation.show') }}" class="font-semibold text-[var(--primary)] transition hover:text-[var(--primary-soft)]">konsultasi</a>.
                    Jika Anda mewakili organisasi dan ingin membahas kerja sama, gunakan jalur
                    <a href="{{ route('partners.index') }}" class="font-semibold text-[var(--primary)] transition hover:text-[var(--primary-soft)]">kemitraan</a>.
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-6 sm:p-8 lg:col-span-7 lg:p-10">
                <h2 class="font-editorial text-3xl">{{ $page->sectionValue('form.title', 'Kirim pesan langsung kepada tim kami.') }}</h2>

                <form method="POST" action="{{ route('contact.store') }}" class="mt-8 grid gap-6 md:grid-cols-2" data-submit-feedback-form>
                    @csrf
                    @if ($errors->any())
                        <div class="md:col-span-2 rounded-2xl border border-[color:rgba(133,64,54,0.18)] bg-[color:rgba(133,64,54,0.08)] px-5 py-4 text-sm leading-7 text-[var(--tertiary)]">
                            <p class="font-semibold">Mohon periksa kembali form kontak Anda.</p>
                            @if ($errors->has('form'))
                                <p class="mt-1">{{ $errors->first('form') }}</p>
                            @endif
                        </div>
                    @endif

                    <div>
                        <label for="name" class="section-label mb-3 block">Nama</label>
                        <input id="name" name="name" value="{{ old('name') }}" @class([
                            'w-full rounded-xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('name'),
                            'border-[var(--tertiary)]' => $errors->has('name'),
                        ]) required>
                        @error('name')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="section-label mb-3 block">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" @class([
                            'w-full rounded-xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('email'),
                            'border-[var(--tertiary)]' => $errors->has('email'),
                        ]) required>
                        @error('email')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="section-label mb-3 block">Telepon</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" @class([
                            'w-full rounded-xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('phone'),
                            'border-[var(--tertiary)]' => $errors->has('phone'),
                        ])>
                        @error('phone')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subject" class="section-label mb-3 block">Subjek</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}" @class([
                            'w-full rounded-xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('subject'),
                            'border-[var(--tertiary)]' => $errors->has('subject'),
                        ]) required>
                        @error('subject')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="message" class="section-label mb-3 block">Pesan</label>
                        <textarea id="message" name="message" rows="6" @class([
                            'w-full rounded-2xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('message'),
                            'border-[var(--tertiary)]' => $errors->has('message'),
                        ]) required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[var(--primary)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.14em] text-white transition disabled:cursor-wait disabled:opacity-75 sm:w-auto" data-submit-feedback-button>
                            <span data-submit-idle>Kirim Pesan</span>
                            <span class="hidden" data-submit-loading>
                                <span class="inline-flex items-center gap-3">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4z"></path>
                                    </svg>
                                    Mengirim...
                                </span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto grid max-w-7xl justify-items-center gap-8 px-6 md:grid-cols-2 lg:grid-cols-3 lg:px-10">
            <article class="surface-card flex w-full max-w-sm flex-col items-center rounded-[1.75rem] p-6 text-center sm:p-8">
                <p class="section-label mb-4">WhatsApp</p>
                <p class="max-w-full break-words text-center font-editorial text-[clamp(1.45rem,2.2vw,2.15rem)] leading-[1.15]">{{ $contactDetails['whatsapp'] }}</p>
            </article>
            <article class="surface-card flex w-full max-w-sm flex-col items-center rounded-[1.75rem] p-6 text-center sm:p-8">
                <p class="section-label mb-4">Email</p>
                <p class="max-w-full break-all text-center font-editorial text-[clamp(1.15rem,1.65vw,1.8rem)] leading-[1.15]">{{ $contactDetails['email'] }}</p>
            </article>
            <article class="surface-card flex w-full max-w-sm flex-col items-center rounded-[1.75rem] p-6 text-center sm:p-8 md:col-span-2 lg:col-span-1">
                <p class="section-label mb-4">Telepon</p>
                <p class="max-w-full break-words text-center font-editorial text-[clamp(1.45rem,2.2vw,2.15rem)] leading-[1.15]">{{ $contactDetails['phone'] }}</p>
            </article>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="mb-8">
            <p class="section-label mb-4">{{ $page->sectionValue('location.kicker', 'Lokasi') }}</p>
            <h2 class="font-editorial text-4xl leading-tight lg:text-5xl">{{ $page->sectionValue('location.title', 'Alamat dan titik temu lembaga.') }}</h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
            <article class="surface-card rounded-[1.75rem] p-6 sm:p-8">
                <p class="text-sm leading-8 text-[var(--ink-muted)]">{{ $contactDetails['address'] }}</p>
            </article>

            @if (filled($mapsEmbedUrl))
                <div class="overflow-hidden rounded-[1.75rem] border border-[color:rgba(190,201,195,0.28)]">
                    <iframe src="{{ $mapsEmbedUrl }}" class="h-80 w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            @endif
        </div>
    </section>
@endsection
