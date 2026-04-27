<?php

namespace App\Support;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FrontendCache
{
    public const SiteShell = 'site-shell';

    public const FrontendPages = 'frontend-pages';

    public const HomePage = 'home-page';

    public const AboutPage = 'about-page';

    public const ProgramsPage = 'programs-page';

    public const MediaPage = 'media-page';

    public const PublicationsPage = 'publications-page';

    public const PartnersPage = 'partners-page';

    public const DonatePage = 'donate-page';

    public const ResourcesPage = 'resources-page';

    public const Sitemap = 'sitemap';

    /**
     * @param  list<string>  $segments
     */
    public static function remember(
        string $key,
        Closure $callback,
        array $segments = [],
        DateTimeInterface | DateInterval | int | null $ttl = null,
    ): mixed {
        return Cache::remember(
            static::buildKey($key, $segments),
            $ttl ?? now()->addMinutes(10),
            $callback,
        );
    }

    /**
     * @param  string|list<string>  $segments
     */
    public static function bump(string|array $segments): void
    {
        foreach (Arr::wrap($segments) as $segment) {
            $versionKey = static::versionKey($segment);

            Cache::add($versionKey, 1, now()->addDay());
            Cache::increment($versionKey);
        }
    }

    /**
     * @param  list<string>  $segments
     */
    protected static function buildKey(string $key, array $segments): string
    {
        $versionSuffix = collect($segments)
            ->unique()
            ->values()
            ->map(fn (string $segment): string => Str::slug($segment) . '-v' . static::version($segment))
            ->implode(':');

        return filled($versionSuffix)
            ? "frontend:{$key}:{$versionSuffix}"
            : "frontend:{$key}";
    }

    protected static function version(string $segment): int
    {
        return (int) Cache::rememberForever(
            static::versionKey($segment),
            fn (): int => 1,
        );
    }

    protected static function versionKey(string $segment): string
    {
        return 'frontend:version:' . Str::slug($segment);
    }
}
