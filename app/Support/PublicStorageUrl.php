<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicStorageUrl
{
    public static function resolve(?string $value, bool $verifyPublicDisk = false): ?string
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
            if ($verifyPublicDisk && Str::startsWith($path, '/storage/')) {
                $publicDiskPath = Str::after($path, '/storage/');

                return self::publicDiskFileExists($publicDiskPath) ? $path : null;
            }

            return $path;
        }

        $path = self::normalizeStoragePath($path);

        if (Str::startsWith($path, 'image/')) {
            return '/'.ltrim($path, '/');
        }

        $publicDiskPath = Str::startsWith($path, 'storage/')
            ? Str::after($path, 'storage/')
            : ltrim($path, '/');

        if ($verifyPublicDisk && ! self::publicDiskFileExists($publicDiskPath)) {
            return null;
        }

        return '/storage/'.ltrim($publicDiskPath, '/');
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

    public static function fallbackImagePath(): string
    {
        return '/image/logo.png';
    }

    private static function publicDiskFileExists(string $path): bool
    {
        return Storage::disk('public')->exists(ltrim($path, '/'));
    }
}
