<?php

namespace App\Models;

use Database\Factories\MediaLibraryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    /** @use HasFactory<MediaLibraryFactory> */
    use HasFactory;

    protected $table = 'media_library';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'file_name',
        'file_path',
        'file_url',
        'mime_type',
        'file_size',
        'uploaded_by',
        'alt_text',
    ];
}
