@extends('layouts.site')

@section('content')
    <section class="mx-auto max-w-7xl px-6 pt-8 pb-16 lg:px-10 lg:pt-10 lg:pb-20">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-5">
                <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Donasi') }}</p>
                <h1 class="font-editorial text-4xl leading-[0.95] md:text-6xl">
                    {{ $page->heroValue('title_prefix', 'Danai') }}
                    <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'infrastruktur') }}</span>
                    {{ $page->heroValue('title_suffix', 'yang berkelanjutan, bukan gestur sesaat.') }}
                </h1>
                <p class="mt-6 text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                    {{ $page->heroValue('body', $campaign->displayShortDescription()) }}
                </p>
                <div class="mt-8 overflow-hidden rounded-[2rem]">
                    <img src="{{ $campaign->banner_image_url }}" alt="{{ $campaign->displayTitle() }}" class="h-[20rem] w-full object-cover md:h-[22rem]">
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="surface-card rounded-[2rem] p-8 lg:p-10">
                    <div class="mb-8">
                        <p class="section-label mb-4">Kampanye Saat Ini</p>
                        <h2 class="font-editorial text-4xl">{{ $campaign->displayTitle() }}</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-[var(--ink-muted)]">{{ $campaign->displayDescription() }}</p>
                    </div>

                    @php
                        $progress = $campaign->target_amount > 0 ? min(100, (int) round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
                    @endphp

                    <div class="mb-8 rounded-[1.5rem] bg-[var(--surface-muted)] p-5">
                        <div class="mb-3 flex justify-between text-sm font-semibold">
                            <span class="text-[var(--primary)]">Rp{{ number_format((float) $campaign->collected_amount, 0, ',', '.') }} terkumpul</span>
                            <span class="text-[var(--ink-muted)]">Target: Rp{{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-[var(--outline)]/40">
                            <div class="h-full rounded-full bg-[var(--primary)]" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('donate.store') }}" class="grid gap-5 md:grid-cols-2">
                        @csrf
                        <div>
                            <label for="full_name" class="section-label mb-3 block">Nama Lengkap</label>
                            <input id="full_name" name="full_name" value="{{ old('full_name') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]" required>
                        </div>
                        <div>
                            <label for="email" class="section-label mb-3 block">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]" required>
                        </div>
                        <div>
                            <label for="phone" class="section-label mb-3 block">Telepon</label>
                            <input id="phone" name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]">
                        </div>
                        <div>
                            <label for="amount" class="section-label mb-3 block">Jumlah Donasi</label>
                            <input id="amount" name="amount" type="number" min="5" step="0.01" value="{{ old('amount') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]" required>
                        </div>
                        <div>
                            <label for="payment_method" class="section-label mb-3 block">Metode</label>
                            <select id="payment_method" name="payment_method" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]">
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="corporate_gift">Donasi Korporat</option>
                                <option value="major_gift">Donasi Besar</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_channel" class="section-label mb-3 block">Kanal</label>
                            <input id="payment_channel" name="payment_channel" value="{{ old('payment_channel', 'manual') }}" class="w-full rounded-xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]">
                        </div>
                        <div class="md:col-span-2">
                            <label for="message" class="section-label mb-3 block">Pesan</label>
                            <textarea id="message" name="message" rows="4" class="w-full rounded-2xl border border-[color:rgba(190,201,195,0.45)] bg-transparent px-4 py-3.5 outline-none transition focus:border-[var(--primary)]">{{ old('message') }}</textarea>
                        </div>
                        <label class="md:col-span-2 inline-flex items-center gap-3 text-sm text-[var(--ink-muted)]">
                            <input type="checkbox" name="is_anonymous" value="1" class="h-4 w-4 rounded border-[var(--outline)] text-[var(--primary)]">
                            Donasi secara anonim
                        </label>
                        <div class="md:col-span-2">
                            <button class="rounded-xl bg-[var(--primary)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.14em] text-white">
                                Catat Niat Donasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[var(--surface-muted)] py-20">
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mb-12 flex items-end justify-between gap-8">
                <div>
                    <p class="section-label mb-4">{{ $page->sectionValue('documents.kicker', 'Dokumen') }}</p>
                    <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('documents.title', 'Dokumen pendukung') }}</h2>
                </div>
                <a href="{{ route('resources.index') }}" class="text-sm font-semibold uppercase tracking-[0.14em] text-[var(--primary)]">{{ $page->sectionValue('documents.link_label', 'Buka arsip') }}</a>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($documents as $document)
                    <article class="surface-card rounded-[1.75rem] p-8">
                        <p class="section-label mb-4">{{ $document->category }}</p>
                        <h3 class="font-editorial text-3xl">{{ $document->title }}</h3>
                        <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">{{ $document->description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
