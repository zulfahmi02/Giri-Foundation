@php
    $items = $breadcrumbs ?? ($seo->breadcrumbs ?? []);
@endphp

@if ($items !== [] && count($items) > 1)
    <nav aria-label="Breadcrumb" class="{{ $class ?? '' }}">
        <ol class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--ink-muted)]">
            @foreach ($items as $breadcrumb)
                <li class="flex items-center gap-2">
                    @if (! $loop->first)
                        <span aria-hidden="true">/</span>
                    @endif

                    @if ($loop->last)
                        <span class="text-[var(--primary)]">{{ $breadcrumb['label'] }}</span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}" class="transition hover:text-[var(--primary)]">
                            {{ $breadcrumb['label'] }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
