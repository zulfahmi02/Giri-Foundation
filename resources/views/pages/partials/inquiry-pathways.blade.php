@php
    $inquiryPathways = [
        [
            'key' => 'contact',
            'eyebrow' => 'Pertanyaan Umum',
            'title' => 'Kontak Umum',
            'description' => 'Untuk pertanyaan umum, permintaan informasi, atau pesan yang belum perlu diarahkan ke jalur khusus.',
            'route' => 'contact.show',
            'action_label' => 'Buka Kontak',
        ],
        [
            'key' => 'consultation',
            'eyebrow' => 'Diskusi Terarah',
            'title' => 'Konsultasi',
            'description' => 'Untuk pendampingan awal, konsultasi program, atau percakapan yang memerlukan konteks lebih mendalam.',
            'route' => 'consultation.show',
            'action_label' => 'Ajukan Konsultasi',
        ],
        [
            'key' => 'partners',
            'eyebrow' => 'Kerja Sama',
            'title' => 'Kemitraan',
            'description' => 'Untuk kolaborasi organisasi, dukungan kelembagaan, implementasi program, atau penjajakan pendanaan.',
            'route' => 'partners.index',
            'action_label' => 'Jelajahi Kemitraan',
        ],
    ];
@endphp

<section class="surface-card rounded-[2rem] p-8 lg:p-10" data-inquiry-pathways>
    <div class="max-w-2xl">
        <p class="section-label mb-4">Pilih Jalur Komunikasi</p>
        <h2 class="font-editorial text-3xl md:text-4xl">Supaya pesan Anda masuk ke inbox yang tepat sejak awal.</h2>
        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)] md:text-base">
            Kami memisahkan pertanyaan umum, konsultasi, dan kemitraan agar tindak lanjut tim yayasan tidak tercampur.
        </p>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-3">
        @foreach ($inquiryPathways as $pathway)
            @php($isCurrentPathway = ($currentPathway ?? null) === $pathway['key'])

            <article @class([
                'rounded-[1.5rem] border p-6 transition',
                'border-[color:rgba(0,96,76,0.18)] bg-[var(--secondary-soft)] shadow-[0_18px_38px_rgba(0,96,76,0.08)]' => $isCurrentPathway,
                'border-[color:rgba(190,201,195,0.35)] bg-white hover:border-[color:rgba(0,96,76,0.2)]' => ! $isCurrentPathway,
            ])>
                <p class="section-label mb-4">{{ $pathway['eyebrow'] }}</p>
                <h3 class="font-editorial text-2xl">{{ $pathway['title'] }}</h3>
                <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $pathway['description'] }}</p>

                <div class="mt-6">
                    @if ($isCurrentPathway)
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.14em] text-[var(--primary)] ring-1 ring-[color:rgba(0,96,76,0.12)]">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span>Halaman Ini</span>
                        </span>
                    @else
                        <a
                            href="{{ route($pathway['route']) }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--primary)] transition hover:text-[var(--primary-soft)]"
                        >
                            <span>{{ $pathway['action_label'] }}</span>
                            <span class="material-symbols-outlined text-lg">north_east</span>
                        </a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
