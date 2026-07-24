<?php

namespace App\Models;

use App\Support\YouTubeVideo;
use Database\Factories\ActivityVideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityVideo extends Model
{
    /** @use HasFactory<ActivityVideoFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'activity_id',
        'title',
        'youtube_url',
        'sort_order',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function embedUrl(): ?string
    {
        return YouTubeVideo::embedUrl($this->youtube_url);
    }
}
