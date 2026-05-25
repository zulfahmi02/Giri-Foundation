<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'file_url',
        'thumbnail_url',
        'file_type',
        'file_size',
        'is_public',
        'published_at',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePubliclyAvailable(Builder $query): void
    {
        $query->where('is_public', true)->whereNotNull('published_at');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublishedPublicly(): bool
    {
        return $this->is_public && $this->published_at !== null;
    }

    public function isExternalFile(): bool
    {
        return filter_var($this->file_url, FILTER_VALIDATE_URL) !== false;
    }

    public function hasDownloadableFile(): bool
    {
        return $this->isExternalFile() || $this->downloadablePath() !== null;
    }

    public function downloadablePath(): ?string
    {
        $fileUrl = trim($this->file_url ?? '');

        if ($fileUrl === '' || $fileUrl === '#' || $this->isExternalFile()) {
            return null;
        }

        // Normalize to a storage-disk relative path: strip leading slash and
        // any 'storage/' URL prefix left over from older upload conventions.
        $diskPath = ltrim($fileUrl, '/');
        if (str_starts_with($diskPath, 'storage/')) {
            $diskPath = substr($diskPath, 8);
        }

        if (Storage::disk('public')->exists($diskPath)) {
            return Storage::disk('public')->path($diskPath);
        }

        $publicPath = public_path($diskPath);

        return is_file($publicPath) ? $publicPath : null;
    }

    public function downloadFilename(): string
    {
        $path = parse_url($this->file_url, PHP_URL_PATH) ?: $this->file_url;
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension === '' && filled($this->file_type)) {
            $extension = Str::lower($this->file_type);
        }

        $filename = Str::slug($this->title);

        if ($filename === '') {
            $filename = 'document';
        }

        return $extension === '' ? $filename : "{$filename}.{$extension}";
    }

    public function resolvedThumbnailUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->thumbnail_url, verifyPublicDisk: true)
            ?? PublicStorageUrl::fallbackImagePath();
    }
}
