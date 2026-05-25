<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\ContentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    /** @use HasFactory<ContentFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'type',
        'category_id',
        'excerpt',
        'body',
        'featured_image_url',
        'author_id',
        'status',
        'is_featured',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeStories(Builder $query): void
    {
        $query->where('type', 'story');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeEditorialPublications(Builder $query): void
    {
        $query->whereIn('type', ['journal', 'news', 'article', 'opinion']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContentFile::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function displayTitle(): string
    {
        return (string) ($this->title ?? '');
    }

    public function displayExcerpt(): string
    {
        return (string) ($this->excerpt ?? '');
    }

    public function displayBody(): string
    {
        return (string) ($this->body ?? '');
    }

    public function displaySeoTitle(): string
    {
        return (string) ($this->seo_title ?? '');
    }

    public function displaySeoDescription(): string
    {
        return (string) ($this->seo_description ?? '');
    }

    public function resolvedFeaturedImageUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->featured_image_url, verifyPublicDisk: true)
            ?? PublicStorageUrl::fallbackImagePath();
    }

    public function displayAuthorName(): string
    {
        $authorName = $this->author?->name;

        if ($authorName === null) {
            return app()->isLocale('id') ? 'Meja Editorial' : 'Editorial Desk';
        }

        if (app()->isLocale('id') && $authorName === 'GIRI Editorial Desk') {
            return 'Meja Editorial GIRI';
        }

        return $authorName;
    }

}
