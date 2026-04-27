<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $siteName = $siteProfile?->name ?? 'GIRI FOUNDATION';
            $logoUrl = $siteProfile?->logo_url ?: asset('image/logo.png');
            $faviconUrl = $siteProfile?->favicon_url ?: $logoUrl;
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
    </head>
    <body class="bg-[var(--surface)] text-[var(--ink)]">
        <header class="sticky top-0 z-50 border-b border-[color:rgba(190,201,195,0.25)] bg-[color:rgba(252,249,248,0.84)] backdrop-blur-xl">
            <nav class="mx-auto grid w-full grid-cols-[auto_1fr] items-center gap-8 px-6 py-5 sm:w-[88vw] lg:gap-10 lg:px-10 xl:px-12">
                <a href="{{ route('home') }}" class="flex items-center gap-4 justify-self-start">
                    <span class="overflow-hidden rounded-xl shadow-sm ring-1 ring-black/5">
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-14 w-14 object-cover sm:h-16 sm:w-16">
                    </span>
                    <span class="font-editorial text-2xl font-bold italic tracking-tight">
                        {{ $siteName }}
                    </span>
                </a>

                <div class="hidden min-w-0 md:flex md:justify-center">
                    <div class="flex items-center justify-center gap-8 lg:gap-9 xl:gap-10">
                        <a
                            href="{{ route('home') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('home'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('home'),
                            ])
                        >
                            Beranda
                        </a>
                        <a
                            href="{{ route('programs.index') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('programs.*'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('programs.*'),
                            ])
                        >
                            Program
                        </a>
                        <a
                            href="{{ route('media.index') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('media.*'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('media.*'),
                            ])
                        >
                            Media
                        </a>
                        <a
                            href="{{ route('publications.index') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('publications.*'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('publications.*'),
                            ])
                        >
                            Publikasi
                        </a>
                        <a
                            href="{{ route('about') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('about'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('about'),
                            ])
                        >
                            Tentang
                        </a>
                        <a
                            href="{{ route('contact.show') }}"
                            @class([
                                'font-editorial text-lg transition-colors hover:text-[var(--primary)]',
                                'text-[var(--primary)]' => request()->routeIs('contact.*'),
                                'text-[color:rgba(28,27,27,0.68)]' => ! request()->routeIs('contact.*'),
                            ])
                        >
                            Kontak
                        </a>
                    </div>
                </div>
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
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                        <span class="overflow-hidden rounded-2xl shadow-sm ring-1 ring-black/5">
                            <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-16 w-16 object-cover sm:h-[4.5rem] sm:w-[4.5rem]">
                        </span>
                        <span class="font-editorial text-2xl">{{ $siteName }}</span>
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
                        <a href="{{ route('donate.show') }}" class="block transition hover:text-[var(--primary)]">Donasi</a>
                    </div>
                </div>

                <div>
                    <p class="section-label mb-6">Hubungi Kami</p>
                    <div class="space-y-4 text-sm text-[var(--ink-muted)]">
                        <p>{{ $siteProfile->email ?? 'hello@giri.foundation' }}</p>
                        <p>{{ $siteProfile->phone ?? '+62 000 0000 000' }}</p>
                        <p>{{ $siteProfile->address ?? 'Bali, Indonesia' }}</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
