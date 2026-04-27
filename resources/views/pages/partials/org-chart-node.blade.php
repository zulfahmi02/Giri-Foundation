@php
    use App\Support\TeamMemberStructureSlots;

    $depth = $depth ?? 0;
    $isCompact = $isCompact ?? false;
    $children = $member->children ?? collect();
    $companionMembers = collect($companionMembers ?? []);
    $hasPlaceholderName = $member->name === 'Belum ditentukan';
    $supportChildren = $children
        ->filter(fn ($child): bool => \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($child->position), ['sekretaris', 'bendahara']))
        ->values();
    $fieldChildren = $children
        ->filter(fn ($child): bool => \Illuminate\Support\Str::startsWith(\Illuminate\Support\Str::lower($child->position), 'bidang'))
        ->values();
    $hasFunctionalChildren = $supportChildren->isNotEmpty() || $fieldChildren->isNotEmpty();
    $displayName = $hasPlaceholderName ? null : $member->name;
    $articleWidth = $isCompact ? 'w-20 md:w-24' : 'w-24 md:w-32';
    $nodeSize = $isCompact ? 'h-14 w-14 md:h-16 md:w-16' : 'h-20 w-20 md:h-24 md:w-24';
    $nodeTextSize = $isCompact ? 'text-[7px] leading-[1.1] md:text-[8px]' : 'text-[10px] leading-tight md:text-xs';
    $nodeTextPadding = $isCompact ? 'px-1' : 'px-2';
    $connectorStyle = 'background-color: rgba(16, 56, 43, 0.3);';
    $displayRoleLabel = TeamMemberStructureSlots::displayRoleLabel($member);
    $photoUrl = $member->public_photo_url;
    $hasProfileDialog = filled($photoUrl)
        || filled($displayName)
        || filled($member->bio)
        || filled($member->email)
        || filled($member->linkedin_url)
        || filled($member->divisionRecord?->name);
    $isAdvisorNode = $member->structure_slot === TeamMemberStructureSlots::Advisor;
    $trusteeChair = $isAdvisorNode
        ? $children->first(fn ($child) => $child->structure_slot === TeamMemberStructureSlots::TrusteePrimary)
        : null;
    $advisorCompanionMembers = $isAdvisorNode
        ? $children
            ->filter(fn ($child) => in_array($child->structure_slot, [
                TeamMemberStructureSlots::TrusteeLeft,
                TeamMemberStructureSlots::TrusteeRight,
            ], true))
            ->sortBy('sort_order')
            ->values()
        : collect();
    $usesTrusteeCompanionBranch = $companionMembers->isNotEmpty();
    $lateralCenterChild = $children->first(fn ($child): bool => ($child->children ?? collect())->isNotEmpty());
    $usesLateralChildren = ! $isAdvisorNode
        && ! $usesTrusteeCompanionBranch
        && ! $hasFunctionalChildren
        && $children->count() === 3
        && $lateralCenterChild !== null;
@endphp

<div class="flex flex-col items-center px-1.5 md:px-2">
    <article class="{{ $articleWidth }} text-center">
        @if ($hasProfileDialog)
            <button
                type="button"
                data-team-member-dialog-trigger="{{ $member->id }}"
                aria-haspopup="dialog"
                aria-controls="team-member-dialog-{{ $member->id }}"
                class="group relative z-20 mx-auto flex {{ $nodeSize }} items-center justify-center overflow-hidden rounded-full border border-[color:rgba(0,96,76,0.18)] bg-[#e7f0ec] shadow-[0_18px_42px_-34px_rgba(16,56,43,0.52)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_24px_52px_-34px_rgba(16,56,43,0.58)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)] focus-visible:ring-offset-4 focus-visible:ring-offset-[var(--surface-muted)]"
            >
                @if (filled($photoUrl))
                    <img src="{{ $photoUrl }}" alt="Foto {{ $member->name }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                @else
                    <span class="{{ $nodeTextSize }} {{ $nodeTextPadding }} font-semibold uppercase tracking-[0.06em] text-[var(--ink)]">
                        {{ $displayRoleLabel }}
                    </span>
                @endif
            </button>
        @else
            <div class="relative z-20 mx-auto flex {{ $nodeSize }} items-center justify-center overflow-hidden rounded-full border border-[color:rgba(0,96,76,0.18)] bg-[#e7f0ec] shadow-[0_18px_42px_-34px_rgba(16,56,43,0.52)]">
                @if (filled($photoUrl))
                    <img src="{{ $photoUrl }}" alt="Foto {{ $member->name }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
                @else
                    <span class="{{ $nodeTextSize }} {{ $nodeTextPadding }} font-semibold uppercase tracking-[0.06em] text-[var(--ink)]">
                        {{ $displayRoleLabel }}
                    </span>
                @endif
            </div>
        @endif

        <div class="mt-2.5 space-y-0.5">
            @if (filled($photoUrl))
                <h3 class="font-editorial text-lg leading-tight md:text-xl">{{ $displayRoleLabel }}</h3>
            @endif

            @if (filled($displayName))
                <p class="text-[10px] font-semibold text-[var(--ink-muted)] md:text-xs">{{ $displayName }}</p>
            @endif

            @if (filled($displayName) && $member->divisionRecord?->name)
                <p class="text-[8px] uppercase tracking-[0.16em] text-[var(--primary)] md:text-[9px]">
                    {{ $member->divisionRecord->name }}
                </p>
            @endif
        </div>
    </article>

    @if ($hasProfileDialog)
        @include('pages.partials.team-member-dialog', ['member' => $member])
    @endif

    @if ($isAdvisorNode && $trusteeChair !== null)
        <div class="mt-4 h-7 w-px" style="{{ $connectorStyle }}"></div>

        <div class="flex justify-center" data-team-structure="advisor-chair-branch">
            @include('pages.partials.org-chart-node', [
                'member' => $trusteeChair,
                'depth' => $depth + 1,
                'companionMembers' => $advisorCompanionMembers,
            ])
        </div>
    @elseif ($usesTrusteeCompanionBranch)
        <div class="mt-4 h-7 w-px" style="{{ $connectorStyle }}"></div>

        <div class="relative w-[22rem] md:w-[28rem]" data-team-structure="trustee-companion-branch">
            @if ($companionMembers->count() > 1)
                <div
                    class="{{ $children->isNotEmpty() ? 'bottom-[-3rem] md:bottom-[-3.25rem]' : 'h-10 md:h-12' }} absolute left-1/2 top-0 w-px -translate-x-1/2"
                    style="{{ $connectorStyle }}"
                ></div>
                <div
                    class="absolute top-10 h-px md:top-12"
                    style="left: calc(50% / {{ $companionMembers->count() }}); right: calc(50% / {{ $companionMembers->count() }}); {{ $connectorStyle }}"
                ></div>
            @endif

            <div class="relative z-10 grid grid-cols-2 justify-items-center gap-10">
                @foreach ($companionMembers as $companionMember)
                    @include('pages.partials.org-chart-node', [
                        'member' => $companionMember,
                        'depth' => $depth + 1,
                        'companionMembers' => collect(),
                    ])
                @endforeach
            </div>
        </div>

        @if ($children->isNotEmpty())
            <div class="mt-5"></div>

            <div class="flex justify-center" data-team-structure="trustee-director-branch">
                @foreach ($children as $child)
                    @include('pages.partials.org-chart-node', [
                        'member' => $child,
                        'depth' => $depth + 1,
                        'companionMembers' => collect(),
                    ])
                @endforeach
            </div>
        @endif
    @elseif ($usesLateralChildren)
        <div class="mt-4 h-7 w-px" style="{{ $connectorStyle }}"></div>

        <div class="relative w-[26rem] md:w-[34rem]">
            <div
                class="absolute left-[16.666%] right-[16.666%] top-10 h-px md:top-12"
                style="{{ $connectorStyle }}"
            ></div>

            <div class="relative z-10 grid grid-cols-3 justify-items-center gap-2 md:gap-4">
                @foreach ($children as $child)
                    @include('pages.partials.org-chart-node', [
                        'member' => $child,
                        'depth' => $depth + 1,
                        'companionMembers' => collect(),
                    ])
                @endforeach
            </div>
        </div>
    @elseif ($hasFunctionalChildren)
        <div class="mt-4 h-7 w-px" style="{{ $connectorStyle }}"></div>

        @if ($supportChildren->isNotEmpty())
            <div class="relative w-[22rem] md:w-[28rem]">
                @if ($supportChildren->count() > 1)
                    <div
                        class="{{ $fieldChildren->isNotEmpty() ? 'bottom-[-3rem] md:bottom-[-3.25rem]' : 'h-10 md:h-12' }} absolute left-1/2 top-0 w-px -translate-x-1/2"
                        style="{{ $connectorStyle }}"
                    ></div>
                    <div
                        class="absolute top-10 h-px md:top-12"
                        style="left: calc(50% / {{ $supportChildren->count() }}); right: calc(50% / {{ $supportChildren->count() }}); {{ $connectorStyle }}"
                    ></div>
                @endif
                <div class="relative z-10 grid grid-cols-2 justify-items-center gap-10">
                    @foreach ($supportChildren as $child)
                        @include('pages.partials.org-chart-node', [
                            'member' => $child,
                            'depth' => $depth + 1,
                            'companionMembers' => collect(),
                        ])
                    @endforeach
                </div>
            </div>
        @endif

        @if ($fieldChildren->isNotEmpty())
            @if ($supportChildren->isEmpty())
                <div class="mt-5 h-7 w-px" style="{{ $connectorStyle }}"></div>
            @else
                <div class="mt-5"></div>
            @endif

            <div class="relative w-[44rem] md:w-[52rem]">
                @if ($fieldChildren->count() > 1)
                    <div
                        class="absolute left-1/2 top-0 h-7 w-px -translate-x-1/2 md:h-8"
                        style="{{ $connectorStyle }}"
                    ></div>
                    <div
                        class="absolute top-7 h-px md:top-8"
                        style="left: calc(50% / {{ $fieldChildren->count() }}); right: calc(50% / {{ $fieldChildren->count() }}); {{ $connectorStyle }}"
                    ></div>
                @endif
                <div class="relative z-10 grid grid-cols-7 justify-items-center gap-1">
                    @foreach ($fieldChildren as $child)
                        @include('pages.partials.org-chart-node', [
                            'member' => $child,
                            'depth' => $depth + 1,
                            'isCompact' => true,
                            'companionMembers' => collect(),
                        ])
                    @endforeach
                </div>
            </div>
        @endif
    @elseif ($children->isNotEmpty())
        <div class="mt-4 h-7 w-px" style="{{ $connectorStyle }}"></div>

        <div
            class="relative grid min-w-max justify-center gap-x-2 gap-y-7 pt-7 md:gap-x-4"
            style="grid-template-columns: repeat({{ $children->count() }}, minmax(6rem, 8rem));"
        >
            @if ($children->count() > 1)
                <div
                    class="absolute top-0 h-px"
                    style="left: calc(50% / {{ $children->count() }}); right: calc(50% / {{ $children->count() }}); {{ $connectorStyle }}"
                ></div>
            @endif

            @foreach ($children as $child)
                <div class="relative flex flex-col items-center">
                    <div class="absolute left-1/2 top-[-1.75rem] h-6 w-px -translate-x-1/2" style="{{ $connectorStyle }}"></div>
                    @include('pages.partials.org-chart-node', [
                        'member' => $child,
                        'depth' => $depth + 1,
                        'companionMembers' => collect(),
                    ])
                </div>
            @endforeach
        </div>
    @endif
</div>
