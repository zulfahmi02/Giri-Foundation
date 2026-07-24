<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\ActivityGalleryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityGallery extends Model
{
    /** @use HasFactory<ActivityGalleryFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'activity_id',
        'file_url',
        'caption',
        'sort_order',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function resolvedFileUrl(): string
    {
        return PublicStorageUrl::resolve($this->file_url, verifyPublicDisk: true)
            ?? PublicStorageUrl::fallbackImagePath();
    }
}
