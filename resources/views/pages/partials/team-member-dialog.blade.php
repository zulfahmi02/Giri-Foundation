@php
    use App\Support\TeamMemberStructureSlots;

    $dialogId = "team-member-dialog-{$member->id}";
    $displayRoleLabel = TeamMemberStructureSlots::displayRoleLabel($member);
    $displayName = $member->name === 'Belum ditentukan' ? null : $member->name;
    $photoUrl = $member->public_photo_url;
    $bioParagraphs = collect(preg_split("/\r\n|\n|\r/", (string) $member->bio))
        ->filter(fn (string $paragraph): bool => filled($paragraph))
        ->values();
@endphp

<dialog
    id="{{ $dialogId }}"
    data-team-member-dialog="{{ $member->id }}"
    aria-labelledby="{{ $dialogId }}-title"
    class="team-member-dialog max-h-[min(92vh,48rem)] w-[min(92vw,54rem)] overflow-hidden rounded-[2rem] border border-[color:rgba(190,201,195,0.45)] bg-[var(--surface-card)] p-0 text-left text-[var(--ink)] shadow-[0_40px_120px_-48px_rgba(16,56,43,0.58)]"
>
    <div class="flex items-center justify-between border-b border-[color:rgba(190,201,195,0.28)] px-5 py-4 md:px-7">
        <p class="section-label">Profil Personil</p>
        <button
            type="button"
            data-team-member-dialog-close
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[color:rgba(190,201,195,0.45)] text-[var(--ink-muted)] transition hover:border-[var(--primary)] hover:text-[var(--primary)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)]"
            aria-label="Tutup detail personil"
        >
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <div class="grid max-h-[calc(92vh-4.5rem)] gap-0 overflow-y-auto md:grid-cols-[15rem_minmax(0,1fr)]">
        <div class="border-b border-[color:rgba(190,201,195,0.22)] bg-[linear-gradient(180deg,rgba(231,240,236,0.9),rgba(248,245,240,0.95))] p-6 md:min-h-full md:border-b-0 md:border-r md:p-8">
            @if (filled($photoUrl))
                <img
                    src="{{ $photoUrl }}"
                    alt="Foto {{ $member->name }}"
                    class="h-56 w-full rounded-[1.75rem] border border-[color:rgba(190,201,195,0.38)] object-cover shadow-[0_22px_54px_-34px_rgba(16,56,43,0.46)]"
                    loading="lazy"
                    decoding="async"
                >
            @else
                <div class="flex h-56 items-center justify-center rounded-[1.75rem] border border-[color:rgba(190,201,195,0.38)] bg-white/85 px-6 text-center shadow-[0_22px_54px_-34px_rgba(16,56,43,0.46)]">
                    <span class="font-editorial text-3xl italic leading-tight text-[var(--primary)] md:text-4xl">
                        {{ $displayRoleLabel }}
                    </span>
                </div>
            @endif

            @if ($member->divisionRecord?->name)
                <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-[var(--primary)]">
                    {{ $member->divisionRecord->name }}
                </p>
            @endif

            @if (filled($member->email) || filled($member->linkedin_url))
                <div class="mt-5 space-y-3 text-sm leading-6 text-[var(--ink-muted)]">
                    @if (filled($member->email))
                        <a href="mailto:{{ $member->email }}" class="block transition hover:text-[var(--primary)]">
                            {{ $member->email }}
                        </a>
                    @endif

                    @if (filled($member->linkedin_url))
                        <a
                            href="{{ $member->linkedin_url }}"
                            class="block transition hover:text-[var(--primary)]"
                            target="_blank"
                            rel="noreferrer"
                        >
                            LinkedIn
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-6 md:p-8">
            <p class="section-label mb-3">{{ $displayRoleLabel }}</p>

            @if (filled($displayName))
                <h3 id="{{ $dialogId }}-title" class="font-editorial text-4xl leading-none tracking-tight md:text-5xl">
                    {{ $displayName }}
                </h3>
            @else
                <h3 id="{{ $dialogId }}-title" class="font-editorial text-4xl leading-none tracking-tight md:text-5xl">
                    {{ $displayRoleLabel }}
                </h3>
            @endif

            @if (filled($member->position) && $member->position !== $displayRoleLabel)
                <p class="mt-3 text-sm uppercase tracking-[0.18em] text-[var(--ink-muted)]">
                    {{ $member->position }}
                </p>
            @endif

            @if (filled($displayName))
                <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">
                    {{ $page->sectionValue('personnel.dialog_prompt', 'Klik struktur untuk mengenal lebih dekat peran dan kontribusi setiap personil.') }}
                </p>
            @else
                <p class="mt-4 text-sm leading-7 text-[var(--ink-muted)]">
                    Nama personil untuk slot ini masih dalam proses penetapan.
                </p>
            @endif

            @if ($bioParagraphs->isNotEmpty())
                <div class="editorial-prose mt-6 max-w-none text-base leading-8 text-[var(--ink-muted)]">
                    @foreach ($bioParagraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            @else
                <p class="mt-6 text-base leading-8 text-[var(--ink-muted)]">
                    Detail personil ini sedang diperbarui.
                </p>
            @endif
        </div>
    </div>
</dialog>
