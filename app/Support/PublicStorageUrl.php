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

        $path = str_replace('\\', '/', trim($value));

        if ($path === '#') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, '/')) {
            return $path;
        }

        $path = self::normalizeStoragePath($path);

        if (Str::startsWith($path, ['storage/', 'image/'])) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }

    private static function normalizeStoragePath(string $path): string
    {
        foreach (['public/', 'storage/app/public/', 'app/public/'] as $prefix) {
            if (Str::startsWith($path, $prefix)) {
                return Str::after($path, $prefix);
            }
        }

        return $path;
    }
}
