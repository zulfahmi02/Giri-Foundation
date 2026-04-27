<?php

namespace App\Support;

use App\Models\Content;
use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoData
{
    /**
     * @param  list<array<string, mixed>>  $structuredData
     * @param  list<array{label: string, url: string}>  $breadcrumbs
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $canonicalUrl,
        public string $robots,
        public string $openGraphType,
        public string $twitterCard,
        public string $siteName,
        public ?string $imageUrl,
        public ?string $siteVerification,
        public array $structuredData,
        public array $breadcrumbs,
    ) {
    }

    /**
     * @param  array<string, mixed>  $viewData
     */
    public static function fromViewData(array $viewData, Request $request): self
    {
        $siteProfile = $viewData['siteProfile'] ?? null;
        $siteName = $siteProfile?->name ?? config('app.name', 'GIRI Foundation');
        $siteSummary = (string) ($viewData['siteSummary'] ?? $siteProfile?->short_description ?? 'GIRI Foundation Indonesia');
        $page = $viewData['page'] ?? null;
        $story = $request->routeIs('stories.show') && ($viewData['story'] ?? null) instanceof Content
            ? $viewData['story']
            : null;
        $program = $request->routeIs('programs.show') && ($viewData['program'] ?? null) instanceof Program
            ? $viewData['program']
            : null;

        $title = static::resolveTitle($viewData, $siteName, $page, $story, $program);
        $description = static::resolveDescription($viewData, $siteSummary, $page, $story, $program);
        $canonicalUrl = static::resolveCanonicalUrl($request);
        $robots = static::resolveRobots($request);
        $breadcrumbs = static::resolveBreadcrumbs($request, $page, $story, $program);
        $imageUrl = static::resolveImageUrl(
            $story instanceof Content
                ? $story->featured_image_url
                : ($program instanceof Program ? $program->featured_image_url : ($siteProfile?->logo_url ?: 'image/logo.png')),
            $request,
        );
        $openGraphType = $story instanceof Content ? 'article' : 'website';

        return new self(
            title: $title,
            description: $description,
            canonicalUrl: $canonicalUrl,
            robots: $robots,
            openGraphType: $openGraphType,
            twitterCard: $imageUrl ? 'summary_large_image' : 'summary',
            siteName: $siteName,
            imageUrl: $imageUrl,
            siteVerification: config('seo.google_site_verification'),
            structuredData: static::structuredData(
                siteProfile: $siteProfile,
                siteName: $siteName,
                siteSummary: $siteSummary,
                title: $title,
                description: $description,
                canonicalUrl: $canonicalUrl,
                imageUrl: $imageUrl,
                story: $story,
                breadcrumbs: $breadcrumbs,
                request: $request,
            ),
            breadcrumbs: $breadcrumbs,
        );
    }

    private static function resolveTitle(
        array $viewData,
        string $siteName,
        ?Page $page,
        ?Content $story,
        ?Program $program,
    ): string {
        if ($story instanceof Content) {
            return static::appendSiteName(
                $story->displaySeoTitle() ?: $story->displayTitle(),
                $siteName,
            );
        }

        if ($program instanceof Program) {
            return static::appendSiteName($program->title, $siteName);
        }

        if ($page instanceof Page) {
            return (string) ($page->seo_title ?: $page->title ?: $siteName);
        }

        return (string) ($viewData['title'] ?? $siteName);
    }

    private static function resolveDescription(
        array $viewData,
        string $siteSummary,
        ?Page $page,
        ?Content $story,
        ?Program $program,
    ): string {
        if ($story instanceof Content) {
            return (string) ($story->displaySeoDescription() ?: $story->displayExcerpt() ?: $siteSummary);
        }

        if ($program instanceof Program) {
            return (string) ($program->excerpt ?: Str::limit(strip_tags((string) $program->description), 160) ?: $siteSummary);
        }

        if ($page instanceof Page) {
            return (string) ($page->seo_description ?: $siteSummary);
        }

        return (string) ($viewData['metaDescription'] ?? $siteSummary);
    }

    private static function resolveCanonicalUrl(Request $request): string
    {
        $canonicalUrl = $request->fullUrlWithoutQuery(config('seo.canonical_ignored_query_parameters', []));

        if ($request->routeIs('resources.index') && filled($request->string('search')->toString())) {
            return route('resources.index');
        }

        return $canonicalUrl;
    }

    private static function resolveRobots(Request $request): string
    {
        if ($request->routeIs('resources.index') && filled($request->string('search')->toString())) {
            return 'noindex,follow';
        }

        return 'index,follow';
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    private static function resolveBreadcrumbs(
        Request $request,
        ?Page $page,
        ?Content $story,
        ?Program $program,
    ): array {
        $breadcrumbs = [
            [
                'label' => 'Beranda',
                'url' => route('home'),
            ],
        ];

        if ($request->routeIs('about')) {
            $breadcrumbs[] = ['label' => 'Tentang', 'url' => route('about')];
        } elseif ($request->routeIs('programs.index')) {
            $breadcrumbs[] = ['label' => 'Program', 'url' => route('programs.index')];
        } elseif ($request->routeIs('programs.show') && $program instanceof Program) {
            $breadcrumbs[] = ['label' => 'Program', 'url' => route('programs.index')];
            $breadcrumbs[] = ['label' => $program->title, 'url' => route('programs.show', $program)];
        } elseif ($request->routeIs('media.index')) {
            $breadcrumbs[] = ['label' => 'Media', 'url' => route('media.index')];
        } elseif ($request->routeIs('publications.index')) {
            $breadcrumbs[] = ['label' => 'Publikasi', 'url' => route('publications.index')];
        } elseif ($request->routeIs('stories.index')) {
            $breadcrumbs[] = ['label' => 'Cerita', 'url' => route('stories.index')];
        } elseif ($request->routeIs('stories.show') && $story instanceof Content) {
            $breadcrumbs[] = ['label' => 'Cerita', 'url' => route('stories.index')];
            $breadcrumbs[] = ['label' => $story->displayTitle(), 'url' => route('stories.show', $story)];
        } elseif ($request->routeIs('contact.show')) {
            $breadcrumbs[] = ['label' => 'Kontak', 'url' => route('contact.show')];
        } elseif ($request->routeIs('donate.show')) {
            $breadcrumbs[] = ['label' => 'Donasi', 'url' => route('donate.show')];
        } elseif ($request->routeIs('resources.index')) {
            $breadcrumbs[] = ['label' => 'Dokumen', 'url' => route('resources.index')];
        } elseif ($request->routeIs('partners.index')) {
            $breadcrumbs[] = ['label' => 'Kemitraan', 'url' => route('partners.index')];
        } elseif ($page instanceof Page) {
            $breadcrumbs[] = [
                'label' => $page->title ?: $page->seo_title ?: $request->route()?->getName() ?: 'Halaman',
                'url' => $request->url(),
            ];
        }

        return $breadcrumbs;
    }

    /**
     * @param  list<array{label: string, url: string}>  $breadcrumbs
     * @return list<array<string, mixed>>
     */
    private static function structuredData(
        ?OrganizationProfile $siteProfile,
        string $siteName,
        string $siteSummary,
        string $title,
        string $description,
        string $canonicalUrl,
        ?string $imageUrl,
        ?Content $story,
        array $breadcrumbs,
        Request $request,
    ): array {
        $logoUrl = static::resolveImageUrl($siteProfile?->logo_url ?: 'image/logo.png', $request);

        $schemas = [
            static::filterSchema([
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $siteName,
                'url' => route('home'),
                'description' => $siteSummary,
                'logo' => $logoUrl,
                'email' => $siteProfile?->email,
                'telephone' => $siteProfile?->phone,
                'address' => $siteProfile?->address,
                'foundingDate' => $siteProfile?->founded_date?->toDateString(),
            ]),
            static::filterSchema([
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => route('home'),
                'description' => $siteSummary,
                'inLanguage' => str_replace('_', '-', config('app.locale', 'id')),
            ]),
            static::filterSchema([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $title,
                'url' => $canonicalUrl,
                'description' => $description,
                'isPartOf' => [
                    '@type' => 'WebSite',
                    'name' => $siteName,
                    'url' => route('home'),
                ],
            ]),
        ];

        if (count($breadcrumbs) > 1) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($breadcrumbs)
                    ->values()
                    ->map(fn (array $breadcrumb, int $index): array => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $breadcrumb['label'],
                        'item' => $breadcrumb['url'],
                    ])
                    ->all(),
            ];
        }

        if ($story instanceof Content) {
            $schemas[] = static::filterSchema([
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $story->displayTitle(),
                'description' => $description,
                'url' => $canonicalUrl,
                'image' => $imageUrl ? [$imageUrl] : null,
                'datePublished' => $story->published_at?->toIso8601String(),
                'dateModified' => $story->updated_at?->toIso8601String(),
                'mainEntityOfPage' => $canonicalUrl,
                'author' => [
                    '@type' => 'Person',
                    'name' => $story->displayAuthorName(),
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'logo' => $logoUrl ? [
                        '@type' => 'ImageObject',
                        'url' => $logoUrl,
                    ] : null,
                ],
            ]);
        }

        return $schemas;
    }

    private static function appendSiteName(string $title, string $siteName): string
    {
        if (Str::contains(Str::lower($title), Str::lower($siteName))) {
            return $title;
        }

        return "{$title} | {$siteName}";
    }

    private static function resolveImageUrl(?string $path, Request $request): ?string
    {
        if (blank($path) || $path === '#') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, '//')) {
            return "{$request->getScheme()}:{$path}";
        }

        return asset(ltrim($path, '/'));
    }

    /**
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    private static function filterSchema(array $schema): array
    {
        $filtered = [];

        foreach ($schema as $key => $value) {
            if (is_array($value)) {
                $value = static::filterArray($value);
            }

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    /**
     * @param  array<int|string, mixed>  $value
     * @return array<int|string, mixed>
     */
    private static function filterArray(array $value): array
    {
        $filtered = [];

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $item = static::filterArray($item);
            }

            if ($item === null || $item === '' || $item === []) {
                continue;
            }

            $filtered[$key] = $item;
        }

        return $filtered;
    }
}
