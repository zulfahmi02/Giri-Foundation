@php
    $siteName = config('seo.site_name', 'GIRI Foundation');
    $alternateName = config('seo.site_alternate_name', 'Yayasan Giri Nusantara Sejahtera');
    $logoUrl = asset('image/logo.png');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,follow">
        <title>Pemeliharaan Situs | {{ $siteName }}</title>
        <meta name="description" content="{{ $siteName }} sedang melakukan pemeliharaan singkat.">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" type="image/png" href="{{ $logoUrl }}">
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
                --tertiary: #854036;
            }

            * {
                box-sizing: border-box;
            }

            html {
                min-height: 100%;
                background: var(--surface);
                color: var(--ink);
                font-family: "Public Sans", Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            body {
                min-height: 100vh;
                margin: 0;
                background:
                    radial-gradient(circle at top left, rgba(200, 234, 220, 0.45), transparent 34rem),
                    linear-gradient(180deg, #fffefd 0%, var(--surface) 52%, var(--surface-muted) 100%);
            }

            .page {
                display: grid;
                min-height: 100vh;
                place-items: center;
                padding: 2rem;
            }

            .shell {
                width: min(100%, 68rem);
            }

            .brand {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .brand__logo {
                width: 4rem;
                height: 4rem;
                overflow: hidden;
                border-radius: 1rem;
                background: #fff;
                box-shadow: 0 10px 28px rgba(15, 15, 15, 0.08);
            }

            .brand__logo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .brand__name {
                margin: 0;
                font-family: Georgia, "Times New Roman", serif;
                font-size: clamp(1.45rem, 3vw, 2rem);
                font-style: italic;
                font-weight: 700;
                letter-spacing: 0;
            }

            .brand__caption {
                margin: 0.2rem 0 0;
                color: var(--ink-muted);
                font-size: 0.88rem;
                line-height: 1.5;
            }

            .panel {
                margin-top: clamp(3rem, 7vw, 5rem);
                display: grid;
                grid-template-columns: minmax(0, 1.18fr) minmax(18rem, 0.82fr);
                gap: clamp(1.5rem, 4vw, 3rem);
                align-items: end;
            }

            .eyebrow {
                margin: 0 0 1.25rem;
                color: var(--primary);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
            }

            h1 {
                max-width: 12ch;
                margin: 0;
                font-family: Georgia, "Times New Roman", serif;
                font-size: clamp(3rem, 9vw, 7.5rem);
                font-weight: 500;
                letter-spacing: 0;
                line-height: 0.95;
            }

            .summary {
                max-width: 42rem;
                margin: 1.6rem 0 0;
                color: var(--ink-muted);
                font-size: clamp(1rem, 2vw, 1.22rem);
                line-height: 1.8;
            }

            .status-card {
                border: 1px solid rgba(190, 201, 195, 0.58);
                border-radius: 1.25rem;
                background: rgba(255, 255, 255, 0.92);
                box-shadow: 0 24px 70px rgba(15, 15, 15, 0.08);
                padding: clamp(1.35rem, 4vw, 2rem);
            }

            .status-card__label {
                margin: 0;
                color: var(--primary);
                font-size: 0.72rem;
                font-weight: 800;
                letter-spacing: 0.18em;
                text-transform: uppercase;
            }

            .status-card__code {
                margin: 0.7rem 0 0;
                font-family: Georgia, "Times New Roman", serif;
                font-size: clamp(2.5rem, 7vw, 4.5rem);
                line-height: 1;
            }

            .status-card__copy {
                margin: 1rem 0 0;
                color: var(--ink-muted);
                line-height: 1.75;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.9rem;
                margin-top: 2rem;
            }

            .button {
                display: inline-flex;
                min-height: 3.35rem;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                padding: 0 1.35rem;
                font-size: 0.82rem;
                font-weight: 800;
                letter-spacing: 0.14em;
                text-decoration: none;
                text-transform: uppercase;
            }

            .button--primary {
                background: var(--primary);
                color: #fff;
                box-shadow: 0 18px 38px rgba(0, 96, 76, 0.2);
            }

            .button--ghost {
                border: 1px solid rgba(190, 201, 195, 0.8);
                color: var(--primary);
            }

            .footnote {
                margin: clamp(3rem, 7vw, 4.5rem) 0 0;
                color: var(--ink-muted);
                font-size: 0.86rem;
                line-height: 1.7;
            }

            @media (max-width: 760px) {
                .page {
                    align-items: start;
                    padding: 1.25rem;
                }

                .brand {
                    align-items: flex-start;
                }

                .brand__logo {
                    width: 3.4rem;
                    height: 3.4rem;
                    border-radius: 0.9rem;
                }

                .panel {
                    grid-template-columns: 1fr;
                }

                h1 {
                    max-width: 9ch;
                }

                .actions {
                    flex-direction: column;
                }

                .button {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="shell" aria-labelledby="maintenance-title">
                <header class="brand" aria-label="{{ $siteName }}">
                    <span class="brand__logo">
                        <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
                    </span>
                    <span>
                        <p class="brand__name">{{ $siteName }}</p>
                        <p class="brand__caption">{{ $alternateName }}</p>
                    </span>
                </header>

                <div class="panel">
                    <div>
                        <p class="eyebrow">Pemeliharaan Situs</p>
                        <h1 id="maintenance-title">Kami sedang merapikan sistem.</h1>
                        <p class="summary">
                            Website sedang dalam pemeliharaan singkat agar layanan dan informasi yayasan tetap stabil.
                            Silakan kembali beberapa saat lagi.
                        </p>
                    </div>

                    <aside class="status-card" aria-label="Status layanan">
                        <p class="status-card__label">Status</p>
                        <p class="status-card__code">503</p>
                        <p class="status-card__copy">
                            Permintaan sementara belum dapat diproses. Jika ada kebutuhan mendesak, hubungi pengelola yayasan melalui email.
                        </p>
                        <div class="actions">
                            <a class="button button--primary" href="/">Coba Lagi</a>
                            <a class="button button--ghost" href="mailto:girinusantarasejahtera@gmail.com">Hubungi Kami</a>
                        </div>
                    </aside>
                </div>

                <p class="footnote">
                    Terima kasih atas pengertiannya.
                </p>
            </section>
        </main>
    </body>
</html>
