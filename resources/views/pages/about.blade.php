@extends('layouts.site')

@section('content')
    @php
        $brandLogo = $profile->logo_url ?: asset('image/logo.png');
        $brandName = $page->sectionValue('brand.title', $profile->name);
        $brandSubtitle = $page->sectionValue('brand.subtitle', 'Yayasan untuk inisiatif sosial, budaya, dan keberlanjutan komunitas.');
        $brandNote = $page->sectionValue('brand.note', 'Berakar di Bali dan bekerja bersama komunitas lintas wilayah secara jangka panjang.');
    @endphp

    <section class="mx-auto max-w-7xl px-6 pt-8 pb-12 lg:px-10 lg:pt-10 lg:pb-16">
        <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Tentang Yayasan') }}</p>
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-7">
                <h1 class="font-editorial text-4xl leading-[0.95] tracking-tight md:text-6xl">
                    {{ trim($page->heroValue('title_prefix', 'Arsip hidup tentang kepedulian,')) }}
                    <span class="italic text-[var(--primary)]"> {{ trim($page->heroValue('highlight', 'keberlanjutan')) }} </span>
                    {{ trim($page->heroValue('title_suffix', ', dan daya lokal.')) }}
                </h1>
            </div>
            <div class="lg:col-span-5 lg:pt-2">
                <p class="text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', $profile->short_description) }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-6 pb-16 lg:px-10">
        <div class="rounded-[2.5rem] border border-[color:rgba(190,201,195,0.4)] bg-[linear-gradient(180deg,rgba(255,255,255,0.98),rgba(248,245,240,0.92))] px-8 py-12 text-center shadow-[0_30px_80px_-45px_rgba(16,56,43,0.4)] md:px-12 md:py-16">
            <p class="section-label mb-6">{{ $page->sectionValue('brand.kicker', 'Identitas Lembaga') }}</p>

            <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-[1.75rem] border border-[color:rgba(190,201,195,0.45)] bg-white p-5 shadow-[0_24px_60px_-38px_rgba(16,56,43,0.38)] md:h-32 md:w-32">
                <img src="{{ $brandLogo }}" alt="Logo {{ $brandName }}" class="max-h-full max-w-full object-contain" decoding="async">
            </div>

            <div class="mx-auto mt-8 max-w-3xl">
                <h2 class="font-editorial text-4xl italic leading-none tracking-tight md:text-6xl">{{ $brandName }}</h2>
                <p class="mt-6 text-base leading-8 text-[var(--ink-muted)] md:text-lg">
                    {{ $brandSubtitle }}
                </p>
                <div class="mx-auto mt-8 h-px w-24 bg-[color:rgba(16,56,43,0.18)]"></div>
                <p class="mt-6 text-sm uppercase tracking-[0.24em] text-[var(--primary)]">
                    {{ $brandNote }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-16 lg:px-10">
        <div class="surface-card rounded-[2rem] p-8 md:p-10">
            <p class="section-label mb-4">{{ $page->sectionValue('profile.kicker', 'Profil Lembaga') }}</p>
            <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('profile.title', 'Siapa kami dan bagaimana kami bekerja.') }}</h2>
            <div class="editorial-prose mt-8 max-w-4xl text-base leading-8 text-[var(--ink-muted)]">
                @foreach (preg_split("/\r\n|\n|\r/", $profile->full_description) as $paragraph)
                    @if (filled($paragraph))
                        <p>{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="grid gap-8 lg:grid-cols-2">
                <div class="surface-card rounded-[1.75rem] p-8">
                    <p class="section-label mb-5">Visi</p>
                    <h2 class="font-editorial text-4xl leading-tight">{{ $profile->vision }}</h2>
                </div>
                <div class="surface-card rounded-[1.75rem] p-8">
                    <p class="section-label mb-5">Misi</p>
                    <p class="text-lg leading-8 text-[var(--ink-muted)]">{{ $profile->mission }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-10">
        <div class="mb-12">
            <p class="section-label mb-4">{{ $page->sectionValue('values.kicker', 'Nilai Kami') }}</p>
            <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('values.title', 'Prinsip di balik setiap keputusan.') }}</h2>
        </div>

        @php
            $valuesDescriptionTemplate = $page->sectionValue('values.description_template', ':value membentuk cara GIRI merancang program, mendokumentasikan dampak, dan menjaga akuntabilitas kepada komunitas.');
        @endphp
        <div class="grid gap-6 md:grid-cols-3">
            @foreach (preg_split("/\r\n|\n|\r/", $profile->values) as $value)
                @if (filled($value))
                    <article class="surface-card rounded-[1.75rem] p-8">
                        <h3 class="font-editorial text-3xl">{{ $value }}</h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">
                            {{ str_replace(':value', $value, $valuesDescriptionTemplate) }}
                        </p>
                    </article>
                @endif
            @endforeach
        </div>
    </section>

    @if ($teamMembers->isNotEmpty())
        <section class="bg-[var(--surface-muted)] py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <div class="mb-8">
                    <p class="section-label mb-4">{{ $page->sectionValue('personnel.kicker', 'Personil') }}</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('personnel.title', 'Struktur organisasi yang menjaga arah kerja lembaga.') }}</h2>
                </div>

                <div class="overflow-x-auto pb-4">
                    <div class="flex min-w-max justify-center">
                        @foreach ($teamMembers as $member)
                            @include('pages.partials.org-chart-node', ['member' => $member])
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
