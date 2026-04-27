<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
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
        if (preg_match('~(?:v=|youtu\.be/|embed/)([\w-]{11})~', $this->youtube_url, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    public function embedUrl(): ?string
    {
        $videoId = $this->youtubeVideoId();

        return $videoId ? "https://www.youtube-nocookie.com/embed/{$videoId}" : null;
    }

    public function resolvedThumbnailUrl(): ?string
    {
        if (filled($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        $videoId = $this->youtubeVideoId();

        return $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : null;
    }
}
