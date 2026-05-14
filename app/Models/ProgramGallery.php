<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\ProgramGalleryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramGallery extends Model
{
    /** @use HasFactory<ProgramGalleryFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'program_id',
        'file_url',
        'caption',
        'sort_order',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function resolvedFileUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->file_url, verifyPublicDisk: true)
            ?? PublicStorageUrl::fallbackImagePath();
    }
}
