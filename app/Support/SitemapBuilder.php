<?php

namespace App\Support;

use App\Models\Content;
use App\Models\Page;
use App\Models\Program;

class SitemapBuilder
{
    public function toXml(): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($this->build() as $url) {
            $lines[] = '    <url>';
            $lines[] = '        <loc>'.htmlspecialchars($url['loc'], ENT_XML1).'</loc>';

            if ($url['lastmod']) {
                $lines[] = '        <lastmod>'.htmlspecialchars($url['lastmod'], ENT_XML1).'</lastmod>';
            }

            $lines[] = '    </url>';
        }

        $lines[] = '</urlset>';

        return implode("\n", $lines);
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    public function build(): array
    {
        return FrontendCache::remember(
            'seo:sitemap',
            fn (): array => [
                ...$this->staticPages(),
                ...$this->programPages(),
                ...$this->storyPages(),
            ],
            [FrontendCache::Sitemap],
            now()->addMinutes(30),
        );
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    private function staticPages(): array
    {
        $definitions = [
            ['route' => 'home', 'slug' => 'home'],
            ['route' => 'about', 'slug' => 'about'],
            ['route' => 'programs.index', 'slug' => 'programs'],
            ['route' => 'media.index', 'slug' => 'media'],
            ['route' => 'publications.index', 'slug' => 'publikasi'],
            ['route' => 'stories.index', 'slug' => 'stories'],
            ['route' => 'contact.show', 'slug' => 'contact'],
            ['route' => 'donate.show', 'slug' => 'donate'],
            ['route' => 'resources.index', 'slug' => 'resources'],
            ['route' => 'partners.index', 'slug' => 'partners'],
        ];

        $pages = Page::query()
            ->published()
            ->whereIn('slug', collect($definitions)->pluck('slug')->all())
            ->get()
            ->keyBy('slug');

        return collect($definitions)
            ->map(function (array $definition) use ($pages): array {
                /** @var Page|null $page */
                $page = $pages->get($definition['slug']);

                return [
                    'loc' => route($definition['route']),
                    'lastmod' => $page?->updated_at?->toAtomString(),
                ];
            })
            ->all();
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    private function programPages(): array
    {
        return Program::query()
            ->published()
            ->select(['id', 'slug', 'updated_at'])
            ->latest('published_at')
            ->get()
            ->map(fn (Program $program): array => [
                'loc' => route('programs.show', $program),
                'lastmod' => $program->updated_at?->toAtomString(),
            ])
            ->all();
    }

    /**
     * @return list<array{loc: string, lastmod: string|null}>
     */
    private function storyPages(): array
    {
        return Content::query()
            ->published()
            ->stories()
            ->select(['id', 'slug', 'updated_at'])
            ->latest('published_at')
            ->get()
            ->map(fn (Content $story): array => [
                'loc' => route('stories.show', $story),
                'lastmod' => $story->updated_at?->toAtomString(),
            ])
            ->all();
    }
}
