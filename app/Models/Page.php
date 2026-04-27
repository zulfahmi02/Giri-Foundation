<?php

namespace App\Models;

use App\Support\PageContentDefaults;
use App\Support\FrontendCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'hero_data',
        'section_data',
        'template',
        'status',
        'seo_title',
        'seo_description',
        'published_at',
        'created_by',
    ];

    /**
     * @var array<string, string>
     */
    protected $attributes = [
        'hero_data' => '[]',
        'section_data' => '[]',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hero_data' => 'array',
            'section_data' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public static function forFrontend(string $slug): self
    {
        return FrontendCache::remember(
            "pages:{$slug}",
            function () use ($slug): self {
                $page = static::query()
                    ->published()
                    ->where('slug', $slug)
                    ->first();

                if ($page) {
                    return $page;
                }

                $definition = PageContentDefaults::definition($slug);

                return new static([
                    'slug' => $slug,
                    'title' => $definition['title'] ?? Str::headline($slug),
                    'template' => $definition['template'] ?? $slug,
                    'seo_title' => $definition['seo_title'] ?? null,
                    'seo_description' => $definition['seo_description'] ?? null,
                    'hero_data' => $definition['hero_data'] ?? [],
                    'section_data' => $definition['section_data'] ?? [],
                ]);
            },
            [FrontendCache::FrontendPages],
        );
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<string, mixed>
     */
    public function heroData(): array
    {
        return array_replace_recursive(
            $this->defaultDefinition()['hero_data'] ?? [],
            $this->hero_data ?? [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function sectionData(): array
    {
        return array_replace_recursive(
            $this->defaultDefinition()['section_data'] ?? [],
            $this->section_data ?? [],
        );
    }

    public function heroValue(string $path, mixed $default = null): mixed
    {
        return data_get($this->heroData(), $path, $default);
    }

    public function sectionValue(string $path, mixed $default = null): mixed
    {
        return data_get($this->sectionData(), $path, $default);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultDefinition(): array
    {
        return PageContentDefaults::definition($this->slug ?? '');
    }
}
