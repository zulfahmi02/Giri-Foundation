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
                --surface: #f7f4f2;
                --surface-card: #ffffff;
                --ink: #1c1b1b;
                --ink-muted: #5e6863;
                --outline: rgba(190, 201, 195, 0.6);
                --primary: #00604c;
                --primary-light: #e6f2ee;
            }

            *, *::before, *::after {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            html, body {
                min-height: 100%;
            }

            body {
                min-height: 100vh;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                background: var(--surface);
                color: var(--ink);
                background-image:
                    radial-gradient(ellipse 80% 50% at 15% 10%, rgba(200, 234, 220, 0.5) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 40% at 85% 90%, rgba(200, 234, 220, 0.25) 0%, transparent 50%);
            }

            .page {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                padding: clamp(1.5rem, 4vw, 3rem) clamp(1.25rem, 5vw, 4rem);
            }

            /* ── Brand ─────────────────────────────────────── */
            .brand {
                display: flex;
                align-items: center;
                gap: 0.875rem;
            }

            .brand__logo {
                flex-shrink: 0;
                width: 3.25rem;
                height: 3.25rem;
                border-radius: 0.75rem;
                background: #fff;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .brand__logo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .brand__name {
                font-size: 1.1rem;
                font-weight: 700;
                font-style: italic;
                font-family: Georgia, "Times New Roman", serif;
                line-height: 1.2;
            }

            .brand__caption {
                margin-top: 0.15rem;
                font-size: 0.78rem;
                color: var(--ink-muted);
            }

            /* ── Main content ───────────────────────────────── */
            .content {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: clamp(2.5rem, 6vw, 5rem) 0;
            }

            .inner {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: clamp(2rem, 5vw, 5rem);
                align-items: center;
                width: min(100%, 72rem);
            }

            /* ── Left column ────────────────────────────────── */
            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.5rem;
                padding: 0.35rem 0.85rem;
                border-radius: 999px;
                background: var(--primary-light);
                color: var(--primary);
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
            }

            .eyebrow::before {
                content: '';
                display: block;
                width: 0.45rem;
                height: 0.45rem;
                border-radius: 50%;
                background: var(--primary);
                animation: pulse 2s ease-in-out infinite;
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.4; transform: scale(0.7); }
            }

            h1 {
                font-family: Georgia, "Times New Roman", serif;
                font-size: clamp(2.5rem, 6vw, 5rem);
                font-weight: 500;
                line-height: 1.08;
                letter-spacing: -0.01em;
                color: var(--ink);
            }

            .summary {
                margin-top: 1.5rem;
                color: var(--ink-muted);
                font-size: clamp(0.95rem, 1.5vw, 1.1rem);
                line-height: 1.8;
                max-width: 38rem;
            }

            /* ── Right card ─────────────────────────────────── */
            .card {
                background: var(--surface-card);
                border: 1px solid var(--outline);
                border-radius: 1.5rem;
                padding: clamp(1.75rem, 3.5vw, 2.5rem);
                box-shadow:
                    0 1px 3px rgba(0, 0, 0, 0.04),
                    0 12px 40px rgba(0, 0, 0, 0.07);
            }

            .card__status-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding-bottom: 1.25rem;
                border-bottom: 1px solid var(--outline);
                margin-bottom: 1.5rem;
            }

            .card__label {
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--ink-muted);
            }

            .card__badge {
                padding: 0.3rem 0.7rem;
                border-radius: 999px;
                background: #fef3cd;
                color: #92621a;
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            }

            .card__code {
                font-family: Georgia, "Times New Roman", serif;
                font-size: clamp(3rem, 6vw, 4.5rem);
                font-weight: 500;
                line-height: 1;
                color: var(--ink);
                margin-bottom: 0.5rem;
            }

            .card__title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--ink);
                margin-bottom: 0.75rem;
            }

            .card__copy {
                color: var(--ink-muted);
                font-size: 0.9rem;
                line-height: 1.75;
            }

            .card__divider {
                height: 1px;
                background: var(--outline);
                margin: 1.5rem 0;
            }

            .actions {
                display: flex;
                flex-direction: column;
                gap: 0.65rem;
            }

            .button {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 3rem;
                border-radius: 0.75rem;
                padding: 0 1.25rem;
                font-size: 0.85rem;
                font-weight: 600;
                text-decoration: none;
                letter-spacing: 0.01em;
                transition: opacity 0.15s ease;
            }

            .button:hover {
                opacity: 0.85;
            }

            .button--primary {
                background: var(--primary);
                color: #fff;
            }

            .button--ghost {
                border: 1px solid var(--outline);
                color: var(--primary);
                background: transparent;
            }

            /* ── Footer ─────────────────────────────────────── */
            .footer {
                padding-top: 1rem;
                font-size: 0.8rem;
                color: var(--ink-muted);
            }

            /* ── Responsive ─────────────────────────────────── */
            @media (max-width: 740px) {
                .page {
                    padding: 1.25rem;
                }

                .inner {
                    grid-template-columns: 1fr;
                    gap: 2.5rem;
                }

                h1 {
                    font-size: clamp(2.25rem, 10vw, 3rem);
                }
            }
        </style>
    </head>
    <body>
        <main class="page">

            <header class="brand" aria-label="{{ $siteName }}">
                <span class="brand__logo">
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
                </span>
                <span>
                    <p class="brand__name">{{ $siteName }}</p>
                    <p class="brand__caption">{{ $alternateName }}</p>
                </span>
            </header>

            <div class="content">
                <div class="inner">

                    <div>
                        <span class="eyebrow">Pemeliharaan Situs</span>
                        <h1 id="maintenance-title">Kami sedang merapikan sistem.</h1>
                        <p class="summary">
                            Website sedang dalam pemeliharaan singkat agar layanan dan
                            informasi yayasan tetap stabil. Silakan kembali beberapa saat lagi.
                        </p>
                    </div>

                    <aside class="card" aria-labelledby="maintenance-title">
                        <div class="card__status-row">
                            <p class="card__label">Kode Status</p>
                            <span class="card__badge">Sementara</span>
                        </div>

                        <p class="card__code">503</p>
                        <p class="card__title">Service Unavailable</p>
                        <p class="card__copy">
                            Permintaan sementara belum dapat diproses.
                            Jika ada kebutuhan mendesak, silakan hubungi
                            pengelola yayasan melalui email.
                        </p>

                        <div class="card__divider"></div>

                        <div class="actions">
                            <a class="button button--primary" href="/">Coba Lagi</a>
                            <a class="button button--ghost" href="mailto:girinusantarasejahtera@gmail.com">Hubungi Kami</a>
                        </div>
                    </aside>

                </div>
            </div>

            <footer class="footer">
                Terima kasih atas pengertiannya &mdash; {{ $siteName }}.
            </footer>

        </main>
    </body>
</html>
