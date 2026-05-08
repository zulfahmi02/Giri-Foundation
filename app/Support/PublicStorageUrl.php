<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicStorageUrl
{
    public static function resolve(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $path = trim($value);

        if ($path === '#') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
