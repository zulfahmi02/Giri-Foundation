<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $siteName = $siteProfile?->name ?? 'GIRI FOUNDATION';
            $logoUrl = $siteProfile?->resolvedLogoUrl() ?: asset('image/logo.png');
            $faviconUrl = $siteProfile?->resolvedFaviconUrl() ?: $logoUrl;
        @endphp

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $seo->title ?? ($title ?? (isset($page) && $page ? $page->seo_title : 'GIRI Foundation Indonesia')) }}</title>
        <meta name="description" content="{{ $seo->description ?? ($metaDescription ?? (isset($page) && $page ? $page->seo_description : ($siteSummary ?? 'GIRI Foundation Indonesia'))) }}">
        <meta name="robots" content="{{ $seo->robots ?? 'index,follow' }}">
        <link rel="canonical" href="{{ $seo->canonicalUrl ?? url()->current() }}">
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta property="og:site_name" content="{{ $seo->siteName ?? $siteName }}">
        <meta property="og:type" content="{{ $seo->openGraphType ?? 'website' }}">
        <meta property="og:title" content="{{ $seo->title ?? $siteName }}">
        <meta property="og:description" content="{{ $seo->description ?? ($siteSummary ?? 'GIRI Foundation Indonesia') }}">
        <meta property="og:url" content="{{ $seo->canonicalUrl ?? url()->current() }}">
        @if (filled($seo->imageUrl ?? null))
            <meta property="og:image" content="{{ $seo->imageUrl }}">
        @endif
        <meta name="twitter:card" content="{{ $seo->twitterCard ?? 'summary' }}">
        <meta name="twitter:title" content="{{ $seo->title ?? $siteName }}">
        <meta name="twitter:description" content="{{ $seo->description ?? ($siteSummary ?? 'GIRI Foundation Indonesia') }}">
        @if (filled($seo->imageUrl ?? null))
            <meta name="twitter:image" content="{{ $seo->imageUrl }}">
        @endif
        @if (filled($seo->siteVerification ?? null))
            <meta name="google-site-verification" content="{{ $seo->siteVerification }}">
        @endif
        <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
        @foreach (($seo->structuredData ?? []) as $structuredData)
            <script type="application/ld+json">{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE) !!}</script>
        @endforeach
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,300..800;1,300..800&family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                :root {
                    --surface: #fcf9f8;
                    --surface-muted: #f6f3f2;
                    --surface-card: #ffffff;
                    --ink: #1c1b1b;
                    --ink-muted: #5e6863;
                    --outline: #bec9c3;
                    --primary: #00604c;
                    --primary-soft: #1f7a63;
                    --secondary-soft: #c8eadc;
                    --secondary-ink: #34554a;
                    --tertiary: #854036;
                }

                body {
                    font-family: 'Public Sans', sans-serif;
                    background: var(--surface);
                    color: var(--ink);
                }

                .font-editorial {
                    font-family: 'Newsreader', serif;
                }

                .material-symbols-outlined {
                    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                }

                .section-label {
                    letter-spacing: 0.18em;
                    text-transform: uppercase;
                    font-size: 0.72rem;
                    font-weight: 700;
                    color: var(--primary);
                }

                .surface-card {
                    background: var(--surface-card);
                    box-shadow: 0 12px 40px rgba(15, 15, 15, 0.04);
                }

                .editorial-prose p + p {
                    margin-top: 1.5rem;
                }
            </style>
        @endif
        <style>
            [data-mobile-nav] > summary {
                list-style: none;
            }

            [data-mobile-nav] > summary::-webkit-details-marker {
                display: none;
            }
        </style>
    </head>
        <body class="bg-[var(--surface)] text-[var(--ink)]">
            @php
                $primaryNavigationLinks = [
                    ['label' => 'Beranda', 'route' => 'home', 'pattern' => 'home'],
                    ['label' => 'Program', 'route' => 'programs.index', 'pattern' => 'programs.*'],
                    ['label' => 'Media', 'route' => 'media.index', 'pattern' => 'media.*'],
                    ['label' => 'Publikasi', 'route' => 'publications.index', 'pattern' => ['publications.*', 'stories.*']],
                    ['label' => 'Tentang', 'route' => 'about', 'pattern' => 'about'],
                    ['label' => 'Kontak', 'route' => 'contact.show', 'pattern' => ['contact.*', 'consultation.*', 'partners.*']],
                ];
            @endphp
            <header class="sticky top-0 z-50 border-b border-[color:rgba(190,201,195,0.25)] bg-[color:rgba(252,249,248,0.84)] backdrop-blur-xl">
                <nav class="mx-auto grid w-full max-w-[92rem] grid-cols-[minmax(0,1fr)_auto] items-center gap-4 px-5 py-4 sm:w-[92vw] sm:px-6 sm:py-5 xl:grid-cols-[minmax(0,18rem)_minmax(0,1fr)_auto] xl:gap-6 xl:px-8 2xl:grid-cols-[minmax(0,28rem)_minmax(0,1fr)_auto] 2xl:gap-10 2xl:px-12">
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 justify-self-start sm:gap-4">
                    <span class="overflow-hidden rounded-xl shadow-sm ring-1 ring-black/5">
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-12 w-12 object-cover sm:h-14 sm:w-14 2xl:h-16 2xl:w-16">
                    </span>
                    <span class="max-w-[12rem] truncate font-editorial text-xl font-bold italic tracking-tight sm:max-w-[24rem] sm:text-2xl xl:max-w-[13rem] xl:text-xl 2xl:max-w-[28rem] 2xl:text-2xl">
                        {{ $siteName }}
                    </span>
                </a>

                <div class="hidden min-w-0 xl:flex xl:justify-center" data-desktop-nav-primary>
                    <div class="flex items-center justify-center gap-5 2xl:gap-10">
                        @foreach ($primaryNavigationLinks as $navigationLink)
                            <a
                                href="{{ route($navigationLink['route']) }}"
                                @class([
                                    'whitespace-nowrap font-editorial text-[1.05rem] transition-colors hover:text-[var(--primary)] 2xl:text-lg',
                                    'text-[var(--primary)]' => request()->routeIs($navigationLink['pattern']),
                                    'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs($navigationLink['pattern']),
                                ])
                            >
                                {{ $navigationLink['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="hidden items-center justify-self-end xl:flex xl:gap-3" data-desktop-nav-secondary>
                    <a
                        href="{{ route('donate.show') }}"
                        data-nav-donate-link
                        @class([
                            'inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 text-sm font-bold uppercase tracking-[0.12em] ring-1 shadow-[0_18px_38px_rgba(0,96,76,0.22)] transition hover:-translate-y-0.5 2xl:px-6 2xl:tracking-[0.14em]',
                            'bg-[var(--primary)] text-white ring-[color:rgba(0,96,76,0.12)] hover:bg-[var(--primary-soft)] hover:shadow-[0_22px_44px_rgba(0,96,76,0.26)]' => ! request()->routeIs('donate.*'),
                            'bg-[var(--secondary-soft)] text-[var(--primary)] ring-[color:rgba(0,96,76,0.18)] hover:bg-[color:rgba(200,234,220,0.9)]' => request()->routeIs('donate.*'),
                        ])
                    >
                        <span>Donasi</span>
                        <span class="material-symbols-outlined text-lg">volunteer_activism</span>
                    </a>
                </div>

                <details class="relative justify-self-end xl:hidden" data-mobile-nav>
                    <summary class="flex h-12 w-12 cursor-pointer items-center justify-center rounded-2xl border border-[color:rgba(190,201,195,0.4)] bg-white/90 text-[var(--ink)] shadow-sm transition hover:border-[var(--primary)] hover:text-[var(--primary)]">
                        <span class="sr-only">Buka menu navigasi</span>
                        <span class="material-symbols-outlined text-[2rem]">menu</span>
                    </summary>

                    <div class="absolute right-0 top-[calc(100%+1rem)] w-[min(20rem,calc(100vw-3rem))] overflow-hidden rounded-[1.75rem] border border-[color:rgba(190,201,195,0.45)] bg-white shadow-[0_24px_60px_rgba(17,24,39,0.14)]">
                        <div class="border-b border-[color:rgba(190,201,195,0.35)] px-5 py-4">
                            <p class="section-label">Navigasi</p>
                            <p class="mt-2 font-editorial text-2xl text-[var(--ink)]">{{ $siteName }}</p>
                        </div>

                        <div class="px-3 py-3">
                            @foreach ($primaryNavigationLinks as $navigationLink)
                                <a
                                    href="{{ route($navigationLink['route']) }}"
                                    @class([
                                        'flex items-center justify-between rounded-2xl px-4 py-3 font-editorial text-xl transition-colors',
                                        'bg-[var(--secondary-soft)] text-[var(--primary)]' => request()->routeIs($navigationLink['pattern']),
                                        'text-[color:rgba(28,27,27,0.78)] hover:bg-[var(--surface-muted)] hover:text-[var(--primary)]' => ! request()->routeIs($navigationLink['pattern']),
                                    ])
                                >
                                    <span>{{ $navigationLink['label'] }}</span>
                                    <span class="material-symbols-outlined text-xl">north_east</span>
                                </a>
                            @endforeach

                            <a
                                href="{{ route('donate.show') }}"
                                @class([
                                    'mt-3 flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-bold uppercase tracking-[0.14em] transition-colors',
                                    'bg-[var(--primary)] text-white' => ! request()->routeIs('donate.*'),
                                    'bg-[var(--secondary-soft)] text-[var(--primary)]' => request()->routeIs('donate.*'),
                                ])
                            >
                                <span>Donasi</span>
                                <span class="material-symbols-outlined text-xl">volunteer_activism</span>
                            </a>
                        </div>
                    </div>
                </details>
            </nav>
        </header>

        @if (session('status'))
            <div class="mx-auto mt-6 w-full px-6 sm:w-[88vw] lg:px-10 xl:px-12">
                <div class="rounded-2xl bg-[var(--secondary-soft)] px-5 py-4 text-sm font-medium text-[var(--secondary-ink)]">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main>
            @yield('content')
        </main>

        <footer class="mt-24 border-t border-[color:rgba(190,201,195,0.18)] bg-[var(--surface-muted)]">
            <div class="mx-auto grid w-full gap-16 px-6 py-20 sm:w-[88vw] lg:grid-cols-4 lg:px-10 xl:px-12">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex min-w-0 items-center gap-4">
                        <span class="overflow-hidden rounded-2xl shadow-sm ring-1 ring-black/5">
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-16 w-16 object-cover sm:h-[4.5rem] sm:w-[4.5rem]">
                        </span>
                        <span class="max-w-[15rem] truncate font-editorial text-2xl sm:max-w-none">{{ $siteName }}</span>
                    </a>
                    <p class="mt-6 max-w-sm text-sm leading-7 text-[var(--ink-muted)]">
                        {{ $siteSummary ?? 'Membangun dunia di mana ketangguhan komunitas menjadi dasar bagi kemajuan jangka panjang.' }}
                    </p>
                </div>

                <div>
                    <p class="section-label mb-6">Jelajahi</p>
                    <div class="space-y-4 text-sm text-[var(--ink-muted)]">
                        <a href="{{ route('programs.index') }}" class="block transition hover:text-[var(--primary)]">Program</a>
                        <a href="{{ route('media.index') }}" class="block transition hover:text-[var(--primary)]">Media</a>
                        <a href="{{ route('publications.index') }}" class="block transition hover:text-[var(--primary)]">Publikasi</a>
                    </div>
                </div>

                <div>
                    <p class="section-label mb-6">Yayasan</p>
                    <div class="space-y-4 text-sm text-[var(--ink-muted)]">
                        <a href="{{ route('about') }}" class="block transition hover:text-[var(--primary)]">Tentang</a>
                        <a href="{{ route('contact.show') }}" class="block transition hover:text-[var(--primary)]">Kontak</a>
                        <a href="{{ route('consultation.show') }}" class="block transition hover:text-[var(--primary)]">Konsultasi</a>
                        <a href="{{ route('partners.index') }}" class="block transition hover:text-[var(--primary)]">Kemitraan</a>
                        <a href="{{ route('donate.show') }}" class="block transition hover:text-[var(--primary)]">Donasi</a>
                    </div>
                </div>

                <div>
                    <p class="section-label mb-6">Hubungi Kami</p>
                    <div class="space-y-4 text-sm text-[var(--ink-muted)]">
                        <p>{{ $organizationContact['email'] ?? 'Belum diatur' }}</p>
                        <p>{{ $organizationContact['phone'] ?? 'Belum diatur' }}</p>
                        <p>{{ $organizationContact['address'] ?? 'Belum diatur' }}</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
