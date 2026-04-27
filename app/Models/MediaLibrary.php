<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    /** @use HasFactory<\Database\Factories\MediaLibraryFactory> */
    use HasFactory;

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
