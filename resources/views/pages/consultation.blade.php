@extends('layouts.site')

@section('content')
    @php($organizationProfile = $siteProfile ?? null)
    @php($contactDetails = $organizationContact ?? ['whatsapp' => 'Belum diatur', 'email' => 'Belum diatur', 'phone' => 'Belum diatur', 'address' => 'Belum diatur'])

    <section class="mx-auto max-w-7xl px-6 pt-8 pb-16 lg:px-10 lg:pt-10 lg:pb-20">
        <div class="max-w-3xl">
            <p class="section-label mb-5">{{ $page->heroValue('kicker', 'Konsultasi') }}</p>
            <h1 class="font-editorial text-4xl leading-[0.95] md:text-6xl">
                {{ $page->heroValue('title_prefix', 'Ajukan') }}
                <span class="italic text-[var(--primary)]">{{ $page->heroValue('highlight', 'konsultasi') }}</span>
                {{ $page->heroValue('title_suffix', 'yang lebih terarah.') }}
            </h1>
            <p class="mt-6 max-w-2xl text-base leading-7 text-[var(--ink-muted)] md:text-lg">
                {{ $page->heroValue('body', 'Gunakan jalur ini untuk konsultasi program, pendampingan, diskusi awal, atau kebutuhan yang memerlukan percakapan lebih mendalam dengan tim yayasan.') }}
            </p>
        </div>

        <div class="mt-12">
            @include('pages.partials.inquiry-pathways', ['currentPathway' => 'consultation'])
        </div>

        <div class="mt-12 grid gap-10 lg:grid-cols-12 lg:items-start">
            <div class="lg:col-span-5">
                <div class="space-y-4">
                    <p class="section-label">{{ $page->sectionValue('details.kicker', 'Kapan Menggunakan Jalur Ini') }}</p>
                    <h2 class="font-editorial text-3xl">{{ $page->sectionValue('details.title', 'Konsultasi cocok untuk percakapan yang butuh konteks dan tindak lanjut.') }}</h2>
                </div>

                <div class="mt-8 space-y-4 text-sm leading-7 text-[var(--ink-muted)]">
                    <article class="surface-card rounded-[1.5rem] p-6">
                        <p class="font-semibold text-[var(--ink)]">Pendampingan atau penajaman gagasan</p>
                        <p class="mt-2">Gunakan konsultasi untuk membahas kebutuhan program, pendekatan lapangan, atau rencana kegiatan yang masih perlu diarahkan.</p>
                    </article>
                    <article class="surface-card rounded-[1.5rem] p-6">
                        <p class="font-semibold text-[var(--ink)]">Percakapan yang memerlukan konteks</p>
                        <p class="mt-2">Jika pesan Anda butuh penjelasan lebih panjang daripada kontak umum, jalur ini akan lebih mudah ditindaklanjuti tim.</p>
                    </article>
                    <article class="surface-card rounded-[1.5rem] p-6">
                        <p class="font-semibold text-[var(--ink)]">Bukan untuk proposal kemitraan formal</p>
                        <p class="mt-2">Jika Anda mewakili organisasi dan ingin membahas kerja sama, gunakan halaman kemitraan agar masuk ke inbox yang sesuai.</p>
                    </article>
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-8 lg:col-span-7 lg:p-10">
                <h2 class="font-editorial text-3xl">{{ $page->sectionValue('form.title', 'Ajukan konsultasi kepada tim kami.') }}</h2>

                <form method="POST" action="{{ route('consultation.store') }}" class="mt-8 grid gap-6 md:grid-cols-2" data-submit-feedback-form>
                    @csrf
                    @if ($errors->any())
                        <div class="md:col-span-2 rounded-2xl border border-[color:rgba(133,64,54,0.18)] bg-[color:rgba(133,64,54,0.08)] px-5 py-4 text-sm leading-7 text-[var(--tertiary)]">
                            <p class="font-semibold">Mohon periksa kembali form konsultasi Anda.</p>
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
                        <label for="preferred_contact_channel" class="section-label mb-3 block">Preferensi Kontak</label>
                        <select id="preferred_contact_channel" name="preferred_contact_channel" @class([
                            'w-full rounded-xl border bg-transparent px-4 py-4 outline-none transition focus:border-[var(--primary)]',
                            'border-[color:rgba(190,201,195,0.45)]' => ! $errors->has('preferred_contact_channel'),
                            'border-[var(--tertiary)]' => $errors->has('preferred_contact_channel'),
                        ]) required>
                            <option value="email" @selected(old('preferred_contact_channel', 'email') === 'email')>Email</option>
                            <option value="phone" @selected(old('preferred_contact_channel') === 'phone')>Telepon</option>
                            <option value="whatsapp" @selected(old('preferred_contact_channel') === 'whatsapp')>WhatsApp</option>
                        </select>
                        @error('preferred_contact_channel')
                            <p class="mt-2 text-sm text-[var(--tertiary)]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
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
                        <label for="message" class="section-label mb-3 block">Uraian Kebutuhan</label>
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
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[var(--primary)] px-8 py-4 text-sm font-semibold uppercase tracking-[0.14em] text-white transition disabled:cursor-wait disabled:opacity-75" data-submit-feedback-button>
                            <span data-submit-idle>Ajukan Konsultasi</span>
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
        <div class="mx-auto max-w-7xl px-6 lg:px-10">
            <div class="mb-8">
                <p class="section-label mb-4">{{ $page->sectionValue('channels.kicker', 'Kanal Respons') }}</p>
                <h2 class="font-editorial text-4xl md:text-5xl">{{ $page->sectionValue('channels.title', 'Pilih cara kami menghubungi Anda kembali.') }}</h2>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                <article class="surface-card rounded-[1.75rem] p-8">
                    <p class="section-label mb-4">Email</p>
                    <p class="font-editorial text-3xl">{{ $contactDetails['email'] }}</p>
                </article>
                <article class="surface-card rounded-[1.75rem] p-8">
                    <p class="section-label mb-4">WhatsApp</p>
                    <p class="font-editorial text-3xl">{{ $contactDetails['whatsapp'] }}</p>
                </article>
                <article class="surface-card rounded-[1.75rem] p-8">
                    <p class="section-label mb-4">Telepon</p>
                    <p class="font-editorial text-3xl">{{ $contactDetails['phone'] }}</p>
                </article>
            </div>
        </div>
    </section>
@endsection
