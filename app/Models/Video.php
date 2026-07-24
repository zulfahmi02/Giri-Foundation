<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use App\Support\YouTubeVideo;
use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    /** @use HasFactory<VideoFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'youtube_url',
        'thumbnail_url',
        'sort_order',
        'status',
        'published_at',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
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

    public function youtubeVideoId(): ?string
    {
        return YouTubeVideo::videoId($this->youtube_url);
    }

    public function embedUrl(): ?string
    {
        return YouTubeVideo::embedUrl($this->youtube_url);
    }

    public function resolvedThumbnailUrl(): ?string
    {
        if (! $this->shouldUseYoutubeThumbnail()) {
            return PublicStorageUrl::resolve($this->thumbnail_url);
        }

        return YouTubeVideo::thumbnailUrl($this->youtube_url);
    }

    private function shouldUseYoutubeThumbnail(): bool
    {
        $thumbnailReference = trim((string) $this->thumbnail_url);

        return $thumbnailReference === ''
            || in_array($thumbnailReference, ['#', 'image/logo.png', '/image/logo.png'], true);
    }
}
